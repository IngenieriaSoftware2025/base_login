<?php

namespace Controllers;

use MVC\Router;
use Model\ActiveRecord;
use Model\Permisos;
use Exception;

class PermisosController extends ActiveRecord
{
    // 1. MOSTRAR LA PÁGINA DE PERMISOS
    public static function renderizarPagina(Router $router)
    {
        $router->render('permisos/index', []);
    }

    // 2. GUARDAR NUEVO PERMISO
    public static function guardarAPI()
    {
        getHeadersApi();

        try {
            // Verificar que lleguen los datos
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

            // FORMATEO AUTOMÁTICO - Primera letra mayúscula, resto minúscula
            $_POST['nombre_permiso'] = ucfirst(strtolower(trim($_POST['nombre_permiso'])));
            $_POST['descripcion'] = ucfirst(strtolower(trim($_POST['descripcion'])));

            // Validar que no exista un permiso con el mismo nombre
            $existeNombre = self::fetchFirst("SELECT id_permiso FROM permisos WHERE LOWER(nombre_permiso) = LOWER('{$_POST['nombre_permiso']}') AND situacion = 1");
            if ($existeNombre) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Ya existe un permiso con ese nombre'
                ]);
                return;
            }

            // Crear el permiso
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

    // 3. BUSCAR TODOS LOS PERMISOS
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

    // 4. MODIFICAR PERMISO
    public static function modificarAPI()
    {
        getHeadersApi();

        try {
            $id = $_POST['id_permiso'];

            // Verificar datos
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

            // FORMATEO AUTOMÁTICO - Primera letra mayúscula, resto minúscula
            $_POST['nombre_permiso'] = ucfirst(strtolower(trim($_POST['nombre_permiso'])));
            $_POST['descripcion'] = ucfirst(strtolower(trim($_POST['descripcion'])));

            // Validar que no exista otro permiso con el mismo nombre (excluyendo el actual)
            $existeNombre = self::fetchFirst("SELECT id_permiso FROM permisos WHERE LOWER(nombre_permiso) = LOWER('{$_POST['nombre_permiso']}') AND situacion = 1 AND id_permiso != $id");
            if ($existeNombre) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Ya existe otro permiso con ese nombre'
                ]);
                return;
            }

            // Buscar el permiso y actualizarlo
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

    // 5. ELIMINAR PERMISO
    public static function eliminarAPI()
    {
        getHeadersApi();

        try {
            $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
            
            // Verificar si el permiso está siendo usado (opcional)
            // $sql = "SELECT COUNT(*) as total FROM rol_permisos WHERE id_permiso = $id";
            // $resultado = self::fetchFirst($sql);
            // if ($resultado['total'] > 0) { ... }

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