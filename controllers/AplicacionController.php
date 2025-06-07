<?php

namespace Controllers;

use MVC\Router;
use Model\ActiveRecord;
use Model\aplicaciones;
use Exception;

class AplicacionController extends ActiveRecord
{

    public static function renderizarPagina(Router $router)
    {
        $router->render('aplicacion/index', []);
    }

    public static function guardarAPI()
    {
        getHeadersApi();

        // Validaciones
        if (empty($_POST['nombre_app_lg'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El nombre largo de la aplicación es obligatorio'
            ]);
            return;
        }

        if (empty($_POST['nombre_app_md'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El nombre mediano de la aplicación es obligatorio'
            ]);
            return;
        }

        if (empty($_POST['nombre_app_ct'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El nombre corto de la aplicación es obligatorio'
            ]);
            return;
        }

        try {
          
            $_POST['nombre_app_lg'] = ucwords(strtolower(trim(htmlspecialchars($_POST['nombre_app_lg']))));
            $_POST['nombre_app_md'] = ucwords(strtolower(trim(htmlspecialchars($_POST['nombre_app_md']))));
            $_POST['nombre_app_ct'] = strtoupper(trim(htmlspecialchars($_POST['nombre_app_ct'])));

            $aplicacion = new Aplicaciones([
                'nombre_app_lg' => $_POST['nombre_app_lg'],
                'nombre_app_md' => $_POST['nombre_app_md'],
                'nombre_app_ct' => $_POST['nombre_app_ct'],
                // 'fecha_creacion' => date('Y-m-d H:i:s'), // Fecha automática
                'situacion' => 1
            ]);

            $crear = $aplicacion->crear();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Aplicación guardada exitosamente'
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al guardar la aplicación',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public static function buscarAPI()
    {
        getHeadersApi();
        try {
            $consulta = "SELECT * FROM aplicaciones WHERE situacion = 1 ORDER BY nombre_app_lg";
            $aplicaciones = self::fetchArray($consulta);

            if (count($aplicaciones) > 0) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Éxito al buscar',
                    'data' => $aplicaciones
                ]);
            } else {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Error al buscar',
                    'data' => 'No hay aplicaciones'
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

    public static function ModificarAPI()
    {
        getHeadersApi();
        $id = $_POST['id_aplicacion'];

        // Validaciones
        if (empty($_POST['nombre_app_lg'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El nombre largo de la aplicación es obligatorio'
            ]);
            return;
        }

        if (empty($_POST['nombre_app_md'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El nombre mediano de la aplicación es obligatorio'
            ]);
            return;
        }

        if (empty($_POST['nombre_app_ct'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El nombre corto de la aplicación es obligatorio'
            ]);
            return;
        }

        try {
           
            $_POST['nombre_app_lg'] = ucwords(strtolower(trim(htmlspecialchars($_POST['nombre_app_lg']))));
            $_POST['nombre_app_md'] = ucwords(strtolower(trim(htmlspecialchars($_POST['nombre_app_md']))));
            $_POST['nombre_app_ct'] = strtoupper(trim(htmlspecialchars($_POST['nombre_app_ct'])));

            $data = Aplicaciones::find($id);
            $data->sincronizar([
                'nombre_app_lg' => $_POST['nombre_app_lg'],
                'nombre_app_md' => $_POST['nombre_app_md'],
                'nombre_app_ct' => $_POST['nombre_app_ct'],
                'situacion' => 1
          
            ]);
            
            $data->actualizar();
                
            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Éxito al actualizar'
            ]);
            
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al actualizar',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public static function EliminarAPI()
    {
        try {
            $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
            $ejecutar = Aplicaciones::EliminarAplicaciones($id);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'La aplicación ha sido eliminada correctamente'
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
}