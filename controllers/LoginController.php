<?php

namespace Controllers;

use Exception;
use Model\ActiveRecord;
use Model\Usuarios;
use MVC\Router;

class LoginController extends ActiveRecord
{
    public static function renderizarPagina(Router $router)
    {
        $router->render('login/index', [], 'layout/layoutlogin');
    }

    public static function loginAPI()
    {
        // CRÍTICO: Limpiar cualquier output buffer
        while (ob_get_level()) {
            ob_end_clean();
        }
        
        // Establecer headers JSON
        header("Content-Type: application/json; charset=utf-8");
        header("Cache-Control: no-cache, must-revalidate");
        
        try {
            // Verificar método POST
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                http_response_code(405);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Solo se permite método POST'
                ]);
                exit;
            }

            // Obtener y validar datos
            $dpi = isset($_POST['dpi']) ? trim($_POST['dpi']) : '';
            $contrasena = isset($_POST['contrasena']) ? trim($_POST['contrasena']) : '';

            // Validaciones
            if (empty($dpi) || empty($contrasena)) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'DPI y contraseña son obligatorios'
                ]);
                exit;
            }

            if (strlen($dpi) != 13 || !ctype_digit($dpi)) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El DPI debe tener exactamente 13 dígitos'
                ]);
                exit;
            }

            // Buscar usuario usando consulta preparada
            $db = ActiveRecord::getDB();
            $sql = "SELECT primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, contrasena, correo 
                    FROM usuarios 
                    WHERE dpi = ? AND situacion = 1";
            
            $stmt = $db->prepare($sql);
            $stmt->execute([$dpi]);
            $usuario = $stmt->fetch();

            if (!$usuario) {
                http_response_code(404);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Usuario no encontrado o inactivo'
                ]);
                exit;
            }

            // Verificar contraseña
            if (password_verify($contrasena, $usuario['contrasena'])) {
                // Iniciar sesión
                session_start();
                
                // Crear nombre completo
                $nombres = array_filter([
                    $usuario['primer_nombre'],
                    $usuario['segundo_nombre']
                ]);
                $apellidos = array_filter([
                    $usuario['primer_apellido'],
                    $usuario['segundo_apellido']
                ]);
                
                $nombreCompleto = trim(implode(' ', $nombres) . ' ' . implode(' ', $apellidos));

                // Establecer variables de sesión
                $_SESSION['user'] = $nombreCompleto;
                $_SESSION['dpi'] = $dpi;
                $_SESSION['correo'] = $usuario['correo'];
                $_SESSION['login'] = true;

                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Login exitoso',
                    'usuario' => $nombreCompleto
                ]);
            } else {
                http_response_code(401);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Contraseña incorrecta'
                ]);
            }

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error interno del servidor',
                'detalle' => $e->getMessage()
            ]);
        }
        
        exit;
    }

    public static function logout()
    {
        while (ob_get_level()) {
            ob_end_clean();
        }
        
        header("Content-Type: application/json; charset=utf-8");
        
        session_start();
        session_destroy();
        
        echo json_encode([
            'codigo' => 1,
            'mensaje' => 'Sesión cerrada exitosamente'
        ]);
        exit;
    }
}