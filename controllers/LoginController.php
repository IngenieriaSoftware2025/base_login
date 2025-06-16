<?php

namespace Controllers;

use Model\ActiveRecord;
use MVC\Router;
use Exception;

class LoginController extends ActiveRecord
{
    public static function renderizarPagina(Router $router)
    {
        $router->render('login/index', [], 'layout/layoutlogin');
    }

    public static function login() 
    {
        getHeadersApi();
        
        try {
            // Validar que se envíen los datos
            if (empty($_POST['usu_codigo']) || empty($_POST['usu_password'])) {
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Debe completar todos los campos'
                ]);
                return;
            }

            $dpi = htmlspecialchars($_POST['usu_codigo']);
            $contrasena = htmlspecialchars($_POST['usu_password']);

            // Buscar usuario en tabla usuarios con rol
            $queryExisteUser = "SELECT u.primer_nombre, u.primer_apellido, u.contrasena, u.dpi, u.id_rol, r.nombre_corto 
                               FROM usuarios u
                               LEFT JOIN roles r ON u.id_rol = r.id_rol 
                               WHERE u.dpi = '$dpi' AND u.situacion = 1";

            $resultado = ActiveRecord::fetchArray($queryExisteUser);
            
            if (empty($resultado)) {
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El usuario que intenta iniciar sesion NO EXISTE'
                ]);
                return;
            }

            $existeUsuario = $resultado[0];
            $passDB = $existeUsuario['contrasena'];

            // Verificar contraseña
            if (!password_verify($contrasena, $passDB)) {
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'La contraseña que ingresó es incorrecta'
                ]);
                return;
            }

            // Iniciar sesión
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            $nombreUser = $existeUsuario['primer_nombre'] . ' ' . $existeUsuario['primer_apellido'];
            $rolUsuario = $existeUsuario['nombre_corto'] ?? 'USER';
            
            $_SESSION['user'] = $nombreUser;
            $_SESSION['dpi'] = $dpi;
            $_SESSION['rol'] = $rolUsuario;
            $_SESSION['login'] = true;

            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Inicio de Sesion exitosamente'
            ]);

        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al intentar loguearse',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public static function renderInicio(Router $router) 
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
            header('Location: /base_login/');
            exit;
        }
        
        $router->render('pages/index', [], 'layout/layout');
    }

//  public static function logout()
// {
//     getHeadersApi();
    
//     try {
//         if (session_status() === PHP_SESSION_NONE) {
//             session_start();
//         }
        
//         // Limpiar todas las variables de sesión
//         $_SESSION = array();
        
//         // Si se está usando cookies de sesión, eliminarlas también
//         if (ini_get("session.use_cookies")) {
//             $params = session_get_cookie_params();
//             setcookie(session_name(), '', time() - 42000,
//                 $params["path"], $params["domain"],
//                 $params["secure"], $params["httponly"]
//             );
//         }
        
//         // Destruir la sesión
//         session_destroy();
        
//         echo json_encode([
//             'codigo' => 1,
//             'mensaje' => 'Sesión cerrada correctamente'
//         ]);
        
//     } catch (Exception $e) {
//         echo json_encode([
//             'codigo' => 0,
//             'mensaje' => 'Error al cerrar sesión',
//             'detalle' => $e->getMessage()
//         ]);
//     }
// }
    
}