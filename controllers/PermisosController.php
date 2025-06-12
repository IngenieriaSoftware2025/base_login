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
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El nombre del permiso es obligatorio'
                ]);
                return;
            }

            if (empty($_POST['descripcion'])) {
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'La descripción es obligatoria'
                ]);
                return;
            }

            // Limpiar los datos
            $_POST['nombre_permiso'] = trim($_POST['nombre_permiso']);
            $_POST['descripcion'] = trim($_POST['descripcion']);

            // Crear el permiso
            $permiso = new Permisos($_POST);
            $resultado = $permiso->crear();

            if ($resultado['resultado'] == 1) {
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Permiso guardado correctamente'
                ]);
            } else {
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Error al guardar el permiso'
                ]);
            }

        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    // 3. BUSCAR TODOS LOS PERMISOS
    public static function buscarAPI()
    {
        getHeadersApi();

        try {
            $permisos = Permisos::obtenerPermisosActivos();

            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Permisos obtenidos correctamente',
                'data' => $permisos
            ]);

        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al buscar permisos: ' . $e->getMessage()
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
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El nombre del permiso es obligatorio'
                ]);
                return;
            }

            // Buscar el permiso y actualizarlo
            $permiso = Permisos::find($id);
            $permiso->sincronizar([
                'nombre_permiso' => trim($_POST['nombre_permiso']),
                'descripcion' => trim($_POST['descripcion']),
                'situacion' => 1
            ]);

            $resultado = $permiso->actualizar();

            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Permiso modificado correctamente'
            ]);

        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al modificar: ' . $e->getMessage()
            ]);
        }
    }

    // 5. ELIMINAR PERMISO
    public static function eliminarAPI()
    {
        getHeadersApi();

        try {
            $id = $_GET['id'];
            Permisos::EliminarPermiso($id);

            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Permiso eliminado correctamente'
            ]);

        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al eliminar: ' . $e->getMessage()
            ]);
        }
    }
}