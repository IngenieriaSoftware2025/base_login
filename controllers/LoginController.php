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
            $dpi = htmlspecialchars($_POST['usu_codigo']);
            $contrasena = htmlspecialchars($_POST['usu_password']);


            $queryExisteUser = "SELECT u.id_usuario, u.primer_nombre, u.contrasena, r.nombre_corto, r.nombre_rol 
                   FROM usuarios u 
                   LEFT JOIN roles r ON u.id_rol = r.id_rol 
                   WHERE u.dpi = '$dpi' AND u.situacion = 1";

            $existeUsuario = ActiveRecord::fetchArray($queryExisteUser)[0];

            if ($existeUsuario) {
                $passDB = $existeUsuario['contrasena'];

                if (password_verify($contrasena, $passDB)) {
                    session_start();

                    $nombreUser = $existeUsuario['primer_nombre'];
                    $usuarioId = $existeUsuario['id_usuario'];

                    $_SESSION['user'] = $nombreUser;
                    $_SESSION['dpi'] = $dpi;
                    $_SESSION['usuario_id'] = $usuarioId;



                    //PARA LOGIN NOMBRE CUANDO INICIA SESION
                    if (!empty($existeUsuario['nombre_rol'])) {
                        $_SESSION['rol'] = $existeUsuario['nombre_rol'];
                        // $_SESSION['rol_codigo'] = $existeUsuario['nombre_corto']; 
                    } else {
                        $_SESSION['rol'] = 'Usuario Básico';
                    }

                    echo json_encode([
                        'codigo' => 1,
                        'mensaje' => 'Usuario iniciado exitosamente',
                    ]);
                } else {
                    echo json_encode([
                        'codigo' => 0,
                        'mensaje' => 'La contraseña que ingreso es incorrecta',
                    ]);
                }
            } else {
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El usuario que intenta ingresar no existe',
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al intentar ingresar',
                'detalle' => $e->getMessage()
            ]);
        }
    }


    public static function renderInicio(Router $router)
    {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }


        if (!isset($_SESSION['user'])) {
            header('Location: /base_login/');
            exit;
        }

        $router->render('pages/index', [], 'layout/layout');
    }

    public static function logout()
    {
        isAuth();
        $_SESSION = [];
        $login = $_ENV['APP_NAME'];
        header("Location: /$login");
    }
}
