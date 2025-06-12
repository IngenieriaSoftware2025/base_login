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
        getHeadersApi();

        try {
            $correo = filter_var($_POST['correo'], FILTER_SANITIZE_EMAIL);
            $contrasena = htmlspecialchars($_POST['contrasena']);

            $queryExisteUser = "SELECT u.*, r.nombre_rol, r.nombre_corto FROM usuarios u 
                               LEFT JOIN roles r ON u.id_rol = r.id_rol 
                               WHERE u.correo = '$correo' AND u.situacion = 1";

            $ExisteUsuario = ActiveRecord::fetchFirst($queryExisteUser);

            if ($ExisteUsuario) {

                $passDB = $ExisteUsuario['contrasena'];

                if (password_verify($contrasena, $passDB)) {

                    session_start();

                    $nombreUser = $ExisteUsuario['primer_nombre'] . ' ' . $ExisteUsuario['primer_apellido'];
                    $rolUser = $ExisteUsuario['nombre_corto'];

                    $_SESSION['user'] = $nombreUser;
                    $_SESSION['rol'] = $rolUser;
                    $_SESSION['id_usuario'] = $ExisteUsuario['id_usuario'];
                    $_SESSION['login'] = true;

                    echo json_encode([
                        'codigo' => 1,
                        'mensaje' => 'Usuario logueado exitosamente',
                    ]);
                } else {
                    echo json_encode([
                        'codigo' => 0,
                        'mensaje' => 'La contraseña que ingreso es Incorrecta',
                    ]);
                }
            } else {
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El usuario que intenta loguearse NO EXISTE',
                ]);
            }
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
        session_start();
        if(!isset($_SESSION['login'])) {
            header('Location: /base_login/login');
        }
        
        $router->render('pages/inicio', [], 'layout/layout');
    }
}