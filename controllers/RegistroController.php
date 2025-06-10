<?php

namespace Controllers;

use Exception;
use MVC\Router;
use Model\ActiveRecord;
use Model\Usuarios;

class RegistroController extends ActiveRecord
{
    public static function renderizarPagina(Router $router)
    {
        $router->render('registro/index', []);
    }



    public static function guardarAPI()
    {
        getHeadersApi();

        // PRIMER NOMBRE OBLIGATORIO
        $_POST['primer_nombre'] = ucwords(strtolower(trim(htmlspecialchars($_POST['primer_nombre']))));
        $cantidad_nombre = strlen($_POST['primer_nombre']);
        if ($cantidad_nombre < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El primer nombre debe tener más de 1 caracter'
            ]);
            exit;
        }



        // SEGUNDO NOMBRE OPCIONAL
        if (!empty($_POST['segundo_nombre'])) {
            $_POST['segundo_nombre'] = ucwords(strtolower(trim(htmlspecialchars($_POST['segundo_nombre']))));
            $cantidad_nombre2 = strlen($_POST['segundo_nombre']);
            if ($cantidad_nombre2 < 2) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El segundo nombre debe tener más de 1 caracter'
                ]);
                exit;
            }
        } else {
            $_POST['segundo_nombre'] = '';
        }





        // PRIMER APELLIDO OBLIGATORIO
        $_POST['primer_apellido'] = ucwords(strtolower(trim(htmlspecialchars($_POST['primer_apellido']))));
        $cantidad_apellido = strlen($_POST['primer_apellido']);
        if ($cantidad_apellido < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El primer apellido debe tener más de 1 caracter'
            ]);
            exit;
        }

        // SEGUNDO APELLIDO OPCIONAL
        if (!empty($_POST['segundo_apellido'])) {
            $_POST['segundo_apellido'] = ucwords(strtolower(trim(htmlspecialchars($_POST['segundo_apellido']))));
            $cantidad_apellido2 = strlen($_POST['segundo_apellido']);

            if ($cantidad_apellido2 < 2) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El segundo apellido debe tener más de 1 caracter'
                ]);
                exit;
            }
        } else {
            $_POST['segundo_apellido'] = '';
        }




        // TELÉFONO OBLIGATORIO
        $_POST['telefono'] = filter_var($_POST['telefono'], FILTER_SANITIZE_NUMBER_INT);
        if (strlen($_POST['telefono']) != 8) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El teléfono debe tener 8 números'
            ]);
            exit;
        }





        // DIRECCIÓN OBLIGATORIA
        $_POST['direccion'] = ucwords(strtolower(trim(htmlspecialchars($_POST['direccion']))));
        $cantidad_direccion = strlen($_POST['direccion']);
        if ($cantidad_direccion < 5) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La dirección debe tener al menos 5 caracteres'
            ]);
            exit;
        }





        // DPI OBLIGATORIO
        $_POST['dpi'] = filter_var($_POST['dpi'], FILTER_VALIDATE_INT);
        if (strlen($_POST['dpi']) != 13) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La cantidad de dígitos del DPI debe ser igual a 13'
            ]);
            exit;
        }




        // CORREO OBLIGATORIO
        $_POST['correo'] = filter_var($_POST['correo'], FILTER_SANITIZE_EMAIL);

        if (!filter_var($_POST['correo'], FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El correo electrónico no es válido'
            ]);
            exit;
        }



        //  NO SE TIENE QUE DUPLICAR CORREIOS
        $usuarioExistente = Usuarios::where('correo', $_POST['correo']);
        if ($usuarioExistente) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El correo electrónico ya está registrado'
            ]);
            exit;
        }

        // NO SE DEBE REPETIR EL DPI
        $dpiExistente = Usuarios::where('dpi', $_POST['dpi']);
        if ($dpiExistente) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El DPI ya está registrado'
            ]);
            exit;
        }



        // CONTRASEÑA OBLIGATORIA
        if (strlen($_POST['contrasena']) < 10) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La contraseña debe tener al menos 10 caracteres'
            ]);
            exit;
        }




        // CONFIRMAR CONTRASEÑA OBLIGATORIO
        if ($_POST['contrasena'] !== $_POST['contrasena2']) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Las contraseñas no coinciden'
            ]);
            exit;
        }
        $fecha = date('Y-m-d H:i');

        $_POST['token'] = uniqid();
        $_POST['fecha_creacion'] = $fecha;
        $_POST['fecha_contrasena'] = $fecha;




        // FOTOGRAFÍA OBLIGATORIA
        if (!isset($_FILES['fotografia']) || $_FILES['fotografia']['error'] === UPLOAD_ERR_NO_FILE) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 2,
                'mensaje' => 'La fotografía es obligatoria'
            ]);
            exit;
        }

        $file = $_FILES['fotografia'];
        $fileName = $file['name'];
        $fileTmpName = $file['tmp_name'];
        $fileSize = $file['size'];
        $fileError = $file['error'];

        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // Extensiones permitidas
        $allowed = ['jpg', 'jpeg', 'png'];

        if (!in_array($fileExtension, $allowed)) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 2,
                'mensaje' => 'Solo puede cargar archivos JPG, PNG o JPEG',
            ]);
            exit;
        }

        if ($fileSize >= 2000000) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 2,
                'mensaje' => 'La imagen debe pesar menos de 2MB',
            ]);
            exit;
        }

        if ($fileError === 0) {
            // USAR EL DPI COMPLETO para nombrar el archivo
            $dpiCompleto = $_POST['dpi']; // Usar el DPI completo de 13 dígitos
            $ruta = "storage/fotosusuarios/$dpiCompleto.$fileExtension";

            $subido = move_uploaded_file($file['tmp_name'], __DIR__ . "/../../" . $ruta);

            if ($subido) {
                $_POST['contrasena'] = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);

                $usuario = new Usuarios($_POST);
                $usuario->fotografia = $ruta;
                $resultado = $usuario->crear();

                if ($resultado['resultado'] == 1) {
                    http_response_code(200);
                    echo json_encode([
                        'codigo' => 1,
                        'mensaje' => 'Usuario registrado correctamente',
                    ]);
                    exit;
                } else {
                    http_response_code(500);
                    echo json_encode([
                        'codigo' => 0,
                        'mensaje' => 'Error al registrar el usuario',
                        'datos' => $_POST,
                        'usuario' => $usuario,
                    ]);
                    exit;
                }
            } else {
                http_response_code(500);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Error al subir la fotografía',
                ]);
                exit;
            }
        } else {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error en la carga de fotografía',
            ]);
            exit;
        }
    }




    public static function buscarAPI()
    {
        try {
            $sql = "SELECT * FROM usuarios WHERE situacion = 1 ORDER BY fecha_creacion DESC";
            $data = self::fetchArray($sql);

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




    public static function modificarAPI()
    {
        getHeadersApi();

        try {
            $id = $_POST['id_usuario'];
            // PRIMER NOMBRE OBLIGATORIO
            $_POST['primer_nombre'] = ucwords(strtolower(trim(htmlspecialchars($_POST['primer_nombre']))));
            $cantidad_nombre = strlen($_POST['primer_nombre']);
            if ($cantidad_nombre < 2) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El primer nombre debe tener más de 1 caracter'
                ]);
                exit;
            }




            // SEGUNDO NOMBRE OPCIONAL
            if (!empty($_POST['segundo_nombre'])) {
                $_POST['segundo_nombre'] = ucwords(strtolower(trim(htmlspecialchars($_POST['segundo_nombre']))));
                $cantidad_nombre2 = strlen($_POST['segundo_nombre']);
                if ($cantidad_nombre2 < 2) {
                    http_response_code(400);
                    echo json_encode([
                        'codigo' => 0,
                        'mensaje' => 'El segundo nombre debe tener más de 1 caracter'
                    ]);
                    exit;
                }
            } else {
                $_POST['segundo_nombre'] = '';
            }




            // PRIMER APELLIDO OBLIGATORIO
            $_POST['primer_apellido'] = ucwords(strtolower(trim(htmlspecialchars($_POST['primer_apellido']))));
            $cantidad_apellido = strlen($_POST['primer_apellido']);
            if ($cantidad_apellido < 2) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El primer apellido debe tener más de 1 caracter'
                ]);
                exit;
            }




            // SEGUNDO APELLIDO OPCIONAL
            if (!empty($_POST['segundo_apellido'])) {
                $_POST['segundo_apellido'] = ucwords(strtolower(trim(htmlspecialchars($_POST['segundo_apellido']))));
                $cantidad_apellido2 = strlen($_POST['segundo_apellido']);
                if ($cantidad_apellido2 < 2) {
                    http_response_code(400);
                    echo json_encode([
                        'codigo' => 0,
                        'mensaje' => 'El segundo apellido debe tener más de 1 caracter'
                    ]);
                    exit;
                }
            } else {
                $_POST['segundo_apellido'] = '';
            }





            // TELÉFONO OBLIGATORIO
            $_POST['telefono'] = filter_var($_POST['telefono'], FILTER_SANITIZE_NUMBER_INT);
            if (strlen($_POST['telefono']) != 8) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El teléfono debe tener 8 números'
                ]);
                exit;
            }




            // DIRECCIÓN OBLIGATORIA
            $_POST['direccion'] = ucwords(strtolower(trim(htmlspecialchars($_POST['direccion']))));
            $cantidad_direccion = strlen($_POST['direccion']);
            if ($cantidad_direccion < 5) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'La dirección debe tener al menos 5 caracteres'
                ]);
                exit;
            }



            // DPI OBLIGATORIO
            $_POST['dpi'] = trim(htmlspecialchars($_POST['dpi']));
            if (strlen($_POST['dpi']) != 13) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El DPI debe tener 13 dígitos'
                ]);
                exit;
            }



            // CORREO OBLIGATORIO
            $_POST['correo'] = filter_var($_POST['correo'], FILTER_SANITIZE_EMAIL);

            if (!filter_var($_POST['correo'], FILTER_VALIDATE_EMAIL)) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El correo electrónico no es válido'
                ]);
                exit;
            }


            
            //ACTUALIZAR USUARIO
            $usuario = Usuarios::find($id);
            $usuario->sincronizar([
                'primer_nombre' => $_POST['primer_nombre'],
                'segundo_nombre' => $_POST['segundo_nombre'],
                'primer_apellido' => $_POST['primer_apellido'],
                'segundo_apellido' => $_POST['segundo_apellido'],
                'telefono' => $_POST['telefono'],
                'direccion' => $_POST['direccion'],
                'dpi' => $_POST['dpi'],
                'correo' => $_POST['correo'],
                'situacion' => 1
            ]);

            $resultado = $usuario->actualizar();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Usuario modificado correctamente'
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al modificar el usuario',
                'detalle' => $e->getMessage(),
            ]);
        }
    }





    public static function eliminarAPI()
    {
        try {
            $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
            Usuarios::EliminarUsuarios($id);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'El registro ha sido eliminado correctamente'
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
