<?php

namespace Controllers;

use Exception;
use MVC\Router;
use Model\ActiveRecord;
use Model\RutasActividades;

class RutasActividadesController extends ActiveRecord
{
    public static function renderizarPagina(Router $router)
    {
        // Verificar que el usuario esté logueado
        session_start();
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /base_login/login');
            exit;
        }
        
        // Registrar que accedió al módulo de actividades
        self::registrarRutaActividad('ACTIVIDADES', 'ACCEDER', 'Usuario accedió al módulo de rutas y actividades', '/base_login/actividades');
        
        $router->render('actividades/index', []);
    }

    // Método estático para registrar actividades desde cualquier controlador
    public static function registrarRutaActividad($modulo, $accion, $descripcion, $ruta = '')
    {
        try {
            if (!isset($_SESSION)) {
                session_start();
            }
            
            if (isset($_SESSION['usuario_id']) && isset($_SESSION['user'])) {
                $ruta_actividad = new RutasActividades([
                    'ruta_usuario_id' => $_SESSION['usuario_id'],
                    'ruta_usuario_nombre' => $_SESSION['user'],
                    'ruta_modulo' => strtoupper($modulo),
                    'ruta_accion' => strtoupper($accion),
                    'ruta_descripcion' => $descripcion,
                    'ruta_ip' => self::obtenerIP(),
                    'ruta_ruta' => $ruta ?: $_SERVER['REQUEST_URI'] ?? '',
                    'ruta_situacion' => 1
                ]);
                $ruta_actividad->crear();
            }
        } catch (Exception $e) {
            // No mostrar error para no afectar la funcionalidad principal
            error_log("Error registrando actividad: " . $e->getMessage());
        }
    }

    // Obtener IP del usuario
    private static function obtenerIP()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            return $_SERVER['REMOTE_ADDR'] ?? 'No disponible';
        }
    }

    public static function buscarAPI()
    {
        getHeadersApi();
        
        try {
            // Obtener filtros de la URL
            $filtros = [
                'fecha_inicio' => $_GET['fecha_inicio'] ?? '',
                'fecha_fin' => $_GET['fecha_fin'] ?? '',
                'usuario_id' => $_GET['usuario_id'] ?? '',
                'modulo' => $_GET['modulo'] ?? '',
                'accion' => $_GET['accion'] ?? ''
            ];

            $data = RutasActividades::obtenerActividadesConFiltros($filtros);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Actividades obtenidas correctamente',
                'data' => $data
            ]);

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener las actividades',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function buscarUsuariosAPI()
    {
        getHeadersApi();
        
        try {
            $data = RutasActividades::obtenerUsuariosActivos();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Usuarios obtenidos correctamente',
                'data' => $data
            ]);

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener los usuarios',
                'detalle' => $e->getMessage(),
            ]);
        }
    }
}