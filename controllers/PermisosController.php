<?php

namespace Controllers;

use MVC\Router;
use Model\ActiveRecord;
use Model\Permisos;
use Exception;

class PermisosController extends ActiveRecord
{

    public static function renderizarPagina(Router $router)
    {
        $router->render('permisos/index', []);
    }


    public static function guardarAPI()
    {
        getHeadersApi();

        try {

            if (empty($_POST['nombre_permiso'])) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El nombre del permiso es obligatorio'
                ]);
                return;
            }

            if (empty($_POST['descripcion'])) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'La descripción es obligatoria'
                ]);
                return;
            }


            $_POST['nombre_permiso'] = ucfirst(strtolower(trim($_POST['nombre_permiso'])));
            $_POST['descripcion'] = ucfirst(strtolower(trim($_POST['descripcion'])));



            $permisoExistente = self::fetchFirst("SELECT id_permiso FROM permisos WHERE nombre_permiso = '" . $_POST['nombre_permiso'] . "' AND situacion = 1");

            if ($permisoExistente) {
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Ya existe un permiso con ese nombre'
                ]);
                return;
            }


            $permiso = new Permisos($_POST);
            $resultado = $permiso->crear();

            if ($resultado['resultado'] == 1) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Permiso guardado correctamente'
                ]);
            } else {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Error al guardar el permiso'
                ]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al guardar el permiso',
                'detalle' => $e->getMessage()
            ]);
        }
    }


    public static function buscarAPI()
    {
        getHeadersApi();

        try {
            $permisos = Permisos::obtenerPermisosActivos();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Permisos obtenidos correctamente',
                'data' => $permisos
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al buscar permisos',
                'detalle' => $e->getMessage()
            ]);
        }
    }


    public static function modificarAPI()
    {
        getHeadersApi();

        try {
            $id = $_POST['id_permiso'];


            if (empty($_POST['nombre_permiso'])) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El nombre del permiso es obligatorio'
                ]);
                return;
            }

            if (empty($_POST['descripcion'])) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'La descripción es obligatoria'
                ]);
                return;
            }


            $_POST['nombre_permiso'] = ucfirst(strtolower(trim($_POST['nombre_permiso'])));
            $_POST['descripcion'] = ucfirst(strtolower(trim($_POST['descripcion'])));

            $permisoExistente = self::fetchFirst("SELECT id_permiso FROM permisos WHERE nombre_permiso = '" . $_POST['nombre_permiso'] . "' AND situacion = 1");

            if ($permisoExistente) {
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Ya existe un permiso con ese nombre'
                ]);
                return;
            }

    
            $permiso = Permisos::find($id);
            $permiso->sincronizar([
                'nombre_permiso' => $_POST['nombre_permiso'],
                'descripcion' => $_POST['descripcion'],
                'situacion' => 1
            ]);

            $resultado = $permiso->actualizar();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Permiso modificado correctamente'
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al modificar el permiso',
                'detalle' => $e->getMessage()
            ]);
        }
    }


    public static function eliminarAPI()
    {
        getHeadersApi();

        try {
            $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

            Permisos::EliminarPermiso($id);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Permiso eliminado correctamente'
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al eliminar el permiso',
                'detalle' => $e->getMessage()
            ]);
        }
    }
}
