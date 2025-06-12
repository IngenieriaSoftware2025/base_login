<?php

namespace Controllers;

use MVC\Router;
use Model\ActiveRecord;
use Model\Roles;
use Exception;

class RolesController extends ActiveRecord
{

    public static function renderizarPagina(Router $router)
    {
        $router->render('roles/index', []);
    }

    public static function guardarAPI()
    {
        getHeadersApi();

        // Validaciones
        if (empty($_POST['nombre_rol'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El nombre del rol es obligatorio'
            ]);
            return;
        }

        if (empty($_POST['nombre_corto'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El nombre corto del rol es obligatorio'
            ]);
            return;
        }

        if (empty($_POST['descripcion'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La descripción del rol es obligatoria'
            ]);
            return;
        }

        try {
            // Sanitizar datos
            $_POST['nombre_rol'] = ucwords(strtolower(trim(htmlspecialchars($_POST['nombre_rol']))));
            $_POST['nombre_corto'] = strtoupper(trim(htmlspecialchars($_POST['nombre_corto'])));
            $_POST['descripcion'] = trim(htmlspecialchars($_POST['descripcion']));

            // Validar que no exista un rol con el mismo nombre
            if (Roles::existeRol($_POST['nombre_rol'])) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Ya existe un rol con ese nombre'
                ]);
                return;
            }

            $rol = new Roles([
                'nombre_rol' => $_POST['nombre_rol'],
                'nombre_corto' => $_POST['nombre_corto'],
                'descripcion' => $_POST['descripcion'],
                'situacion' => 1
            ]);

            $crear = $rol->crear();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Rol guardado exitosamente'
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al guardar el rol',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public static function buscarAPI()
    {
        getHeadersApi();
        try {
            $consulta = "SELECT * FROM roles WHERE situacion = 1 ORDER BY nombre_rol";
            $roles = self::fetchArray($consulta);

            if (count($roles) > 0) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Éxito al buscar',
                    'data' => $roles
                ]);
            } else {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'No hay roles registrados',
                    'data' => []
                ]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error de conexión',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public static function modificarAPI()
    {
        getHeadersApi();
        $id = $_POST['id_rol'];

        // Validaciones
        if (empty($_POST['nombre_rol'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El nombre del rol es obligatorio'
            ]);
            return;
        }

        if (empty($_POST['nombre_corto'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El nombre corto del rol es obligatorio'
            ]);
            return;
        }

        if (empty($_POST['descripcion'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La descripción del rol es obligatoria'
            ]);
            return;
        }

        try {
            // Sanitizar datos
            $_POST['nombre_rol'] = ucwords(strtolower(trim(htmlspecialchars($_POST['nombre_rol']))));
            $_POST['nombre_corto'] = strtoupper(trim(htmlspecialchars($_POST['nombre_corto'])));
            $_POST['descripcion'] = trim(htmlspecialchars($_POST['descripcion']));

            // Validar que no exista otro rol con el mismo nombre (excluyendo el actual)
            if (Roles::existeRol($_POST['nombre_rol'], $id)) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Ya existe otro rol con ese nombre'
                ]);
                return;
            }

            $data = Roles::find($id);
            $data->sincronizar([
                'nombre_rol' => $_POST['nombre_rol'],
                'nombre_corto' => $_POST['nombre_corto'],
                'descripcion' => $_POST['descripcion'],
                'situacion' => 1
            ]);
            
            $data->actualizar();
                
            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Rol actualizado exitosamente'
            ]);
            
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al actualizar el rol',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public static function eliminarAPI()
    {
        getHeadersApi();
        try {
            $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

            // Verificar si el rol está siendo usado por algún usuario
            $sql = "SELECT COUNT(*) as total FROM usuarios WHERE id_rol = $id AND situacion = 1";
            $resultado = self::fetchFirst($sql);
            
            if ($resultado['total'] > 0) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'No se puede eliminar el rol porque está asignado a uno o más usuarios'
                ]);
                return;
            }

            $ejecutar = Roles::EliminarRol($id);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'El rol ha sido eliminado correctamente'
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al eliminar',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    // Método adicional para obtener estadísticas de roles
    public static function estadisticasAPI()
    {
        getHeadersApi();
        try {
            $sql = "SELECT 
                        r.nombre_rol, 
                        r.nombre_corto,
                        COUNT(u.id_usuario) as total_usuarios
                    FROM roles r 
                    LEFT JOIN usuarios u ON r.id_rol = u.id_rol AND u.situacion = 1
                    WHERE r.situacion = 1 
                    GROUP BY r.id_rol, r.nombre_rol, r.nombre_corto
                    ORDER BY total_usuarios DESC";
            
            $estadisticas = self::fetchArray($sql);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Estadísticas obtenidas correctamente',
                'data' => $estadisticas
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener estadísticas',
                'detalle' => $e->getMessage()
            ]);
        }
    }
}