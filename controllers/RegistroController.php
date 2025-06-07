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

        try {
            // Validar primer nombre
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

            // Validar segundo nombre 
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

            // Validar primer apellido
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

            // Validar segundo apellido 
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

            // Validar teléfono 
            if (!empty($_POST['telefono'])) {
                $_POST['telefono'] = filter_var($_POST['telefono'], FILTER_SANITIZE_NUMBER_INT);
                if (strlen($_POST['telefono']) != 8) {
                    http_response_code(400);
                    echo json_encode([
                        'codigo' => 0,
                        'mensaje' => 'El teléfono debe tener 8 números'
                    ]);
                    exit;
                }
            } else {
                $_POST['telefono'] = '';
            }

            // Validar dirección 
            $_POST['direccion'] = !empty($_POST['direccion']) ? ucwords(strtolower(trim(htmlspecialchars($_POST['direccion'])))) : '';

            // Validar DPI 
            if (!empty($_POST['dpi'])) {
                $_POST['dpi'] = trim(htmlspecialchars($_POST['dpi']));
                if (strlen($_POST['dpi']) != 13) {
                    http_response_code(400);
                    echo json_encode([
                        'codigo' => 0,
                        'mensaje' => 'El DPI debe tener 13 dígitos'
                    ]);
                    exit;
                }
            } else {
                $_POST['dpi'] = '';
            }

            // Validar correo
            $_POST['correo'] = filter_var($_POST['correo'], FILTER_SANITIZE_EMAIL);

            if (!filter_var($_POST['correo'], FILTER_VALIDATE_EMAIL)) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El correo electrónico no es válido'
                ]);
                exit;
            }

            // Validar contraseña
            if (strlen($_POST['contrasena']) < 10) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'La contraseña debe tener al menos 10 caracteres'
                ]);
                exit;
            }

            if (!preg_match('/[A-Z]/', $_POST['contrasena'])) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'La contraseña debe contener al menos una letra mayúscula'
                ]);
                exit;
            }

            if (!preg_match('/[a-z]/', $_POST['contrasena'])) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'La contraseña debe contener al menos una letra minúscula'
                ]);
                exit;
            }

            if ($_POST['contrasena'] !== $_POST['contrasena2']) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Las contraseñas no coinciden'
                ]);
                exit;
            }

            // Generar token
            $_POST['token'] = uniqid();
            $correo_limpio = preg_replace('/[^a-zA-Z0-9]/', '_', $_POST['correo']);


            unset($_POST['fecha_creacion']);
            unset($_POST['fecha_contrasena']);

            // Manejo de fotografía
            if (isset($_FILES['fotografia']) && $_FILES['fotografia']['error'] !== UPLOAD_ERR_NO_FILE) {
                $file = $_FILES['fotografia'];
                $fileName = $file['name'];
                $fileTmpName = $file['tmp_name'];
                $fileSize = $file['size'];
                $fileError = $file['error'];

                $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

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
                    $ruta = "storage/fotosusuarios/$correo_limpio.$fileExtension";

                    $directorioFotos = __DIR__ . "/../../storage/fotosusuarios/";
                    if (!file_exists($directorioFotos)) {
                        mkdir($directorioFotos, 0755, true);
                    }

                    $subido = move_uploaded_file($file['tmp_name'], __DIR__ . "/../../" . $ruta);

                    if ($subido) {
                        $_POST['fotografia'] = $ruta;
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
            } else {
                $_POST['fotografia'] = '';
            }

     
            $_POST['contrasena'] = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);

         
            $usuario = new Usuarios($_POST);
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
                ]);
                exit;
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error interno del servidor',
                'detalle' => $e->getMessage(),
            ]);
            exit;
        }
    }

    public static function buscarAPI()
    {
        try {
            $fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : null;
            $fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : null;

            $condiciones = ["situacion = 1"];

            if ($fecha_inicio) {
                $condiciones[] = "fecha_creacion >= '{$fecha_inicio}'";
            }

            if ($fecha_fin) {
                $condiciones[] = "fecha_creacion <= '{$fecha_fin}'";
            }

            $where = implode(" AND ", $condiciones);
            $sql = "SELECT * FROM usuarios WHERE $where ORDER BY fecha_creacion DESC";
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

            // Validaciones similares al guardar pero sin contraseña
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

            if (!empty($_POST['telefono'])) {
                $_POST['telefono'] = filter_var($_POST['telefono'], FILTER_SANITIZE_NUMBER_INT);
                if (strlen($_POST['telefono']) != 8) {
                    http_response_code(400);
                    echo json_encode([
                        'codigo' => 0,
                        'mensaje' => 'El teléfono debe tener 8 números'
                    ]);
                    exit;
                }
            } else {
                $_POST['telefono'] = '';
            }

            $_POST['direccion'] = !empty($_POST['direccion']) ? ucwords(strtolower(trim(htmlspecialchars($_POST['direccion'])))) : '';

            if (!empty($_POST['dpi'])) {
                $_POST['dpi'] = trim(htmlspecialchars($_POST['dpi']));
                if (strlen($_POST['dpi']) != 13) {
                    http_response_code(400);
                    echo json_encode([
                        'codigo' => 0,
                        'mensaje' => 'El DPI debe tener 13 dígitos'
                    ]);
                    exit;
                }
            } else {
                $_POST['dpi'] = '';
            }

            $_POST['correo'] = filter_var($_POST['correo'], FILTER_SANITIZE_EMAIL);

            if (!filter_var($_POST['correo'], FILTER_VALIDATE_EMAIL)) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El correo electrónico no es válido'
                ]);
                exit;
            }

            // Actualizar usuario
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
