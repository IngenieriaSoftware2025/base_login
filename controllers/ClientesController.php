<?php

namespace Controllers;

use Exception;
use MVC\Router;
use Model\ActiveRecord;
use Model\Clientes;

class ClientesController extends ActiveRecord
{
    public static function renderizarPagina(Router $router)
    {
        $router->render('clientes/index', []);
    }
//ee
    public static function guardarAPI()
    {
        getHeadersApi();

        // Validación primer nombre
        if (empty($_POST['primer_nombre'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El primer nombre es obligatorio'
            ]);
            return;
        }

        // Validación primer apellido
        if (empty($_POST['primer_apellido'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El primer apellido es obligatorio'
            ]);
            return;
        }

        if (empty($_POST['telefono'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El teléfono es obligatorio'
            ]);
            return;
        }


        // Validación DPI
        if (empty($_POST['dpi'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El DPI es obligatorio'
            ]);
            return;
        }

        // Validación formato DPI 13 números
        $_POST['dpi'] = trim(htmlspecialchars($_POST['dpi']));
        if (strlen($_POST['dpi']) != 13) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El DPI debe tener 13 dígitos'
            ]);
            return;
        }

        // Validación correo
        if (empty($_POST['correo'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El correo electrónico es obligatorio'
            ]);
            return;
        }

        // Validación dirección
        if (empty($_POST['direccion'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La dirección es obligatoria'
            ]);
            return;
        }

        // Sanitizar datos
        $_POST['primer_nombre'] = ucwords(strtolower(trim(htmlspecialchars($_POST['primer_nombre']))));
        $_POST['segundo_nombre'] = ucwords(strtolower(trim(htmlspecialchars($_POST['segundo_nombre'] ?? ''))));
        $_POST['primer_apellido'] = ucwords(strtolower(trim(htmlspecialchars($_POST['primer_apellido']))));
        $_POST['segundo_apellido'] = ucwords(strtolower(trim(htmlspecialchars($_POST['segundo_apellido'] ?? ''))));
        $_POST['telefono'] = trim(htmlspecialchars($_POST['telefono'] ?? ''));
        $_POST['dpi'] = trim(htmlspecialchars($_POST['dpi'] ?? ''));
        $_POST['correo'] = strtolower(trim(htmlspecialchars($_POST['correo'] ?? '')));
        $_POST['direccion'] = ucwords(strtolower(trim(htmlspecialchars($_POST['direccion'] ?? ''))));

        // Verificar si el DPI ya existe
        $dpiExistente = Clientes::where('dpi', $_POST['dpi']);
        if (count($dpiExistente) > 0) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Este DPI ya está registrado en el sistema'
            ]);
            return;
        }

        // Verificar si el correo ya existe
        $correoExistente = Clientes::where('correo', $_POST['correo']);
        if (count($correoExistente) > 0) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El correo electrónico ya está registrado'
            ]);
            return;
        }


        try {
            $cliente = new Clientes($_POST);
            $resultado = $cliente->crear();

            if ($resultado['resultado'] == 1) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Cliente registrado correctamente'
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Error al crear el cliente'
                ]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al registrar el cliente',
                'detalle' => $e->getMessage()
            ]);
        }
    }





    public static function buscarAPI()
    {
        getHeadersApi();
        try {
            $clientes = Clientes::obtenerClientesActivos();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Clientes obtenidos correctamente',
                'data' => $clientes
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener los clientes',
                'detalle' => $e->getMessage()
            ]);
        }
    }




    public static function modificarAPI()
    {
        getHeadersApi();

        $id = $_POST['id_cliente'];

        // Validaciones
        if (empty($_POST['primer_nombre'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El primer nombre es obligatorio'
            ]);
            return;
        }

        if (empty($_POST['primer_apellido'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El primer apellido es obligatorio'
            ]);
            return;
        }

        // Validación teléfono
        if (empty($_POST['telefono'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El teléfono es obligatorio'
            ]);
            return;
        }

        // Validación DPI
        if (empty($_POST['dpi'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El DPI es obligatorio'
            ]);
            return;
        }

        // Validación  DPI 13 números
        $_POST['dpi'] = trim(htmlspecialchars($_POST['dpi']));
        if (strlen($_POST['dpi']) != 13) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El DPI debe tener 13 dígitos'
            ]);
            return;
        }

        // Validación correo
        if (empty($_POST['correo'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El correo electrónico es obligatorio'
            ]);
            return;
        }

        // Validación dirección
        if (empty($_POST['direccion'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La dirección es obligatoria'
            ]);
            return;
        }

        // Sanitizar datos
        $_POST['primer_nombre'] = ucwords(strtolower(trim(htmlspecialchars($_POST['primer_nombre']))));
        $_POST['segundo_nombre'] = ucwords(strtolower(trim(htmlspecialchars($_POST['segundo_nombre'] ?? ''))));
        $_POST['primer_apellido'] = ucwords(strtolower(trim(htmlspecialchars($_POST['primer_apellido']))));
        $_POST['segundo_apellido'] = ucwords(strtolower(trim(htmlspecialchars($_POST['segundo_apellido'] ?? ''))));
        $_POST['telefono'] = trim(htmlspecialchars($_POST['telefono'] ?? ''));
        $_POST['dpi'] = trim(htmlspecialchars($_POST['dpi'] ?? ''));
        $_POST['correo'] = strtolower(trim(htmlspecialchars($_POST['correo'] ?? '')));
        $_POST['direccion'] = ucwords(strtolower(trim(htmlspecialchars($_POST['direccion'] ?? ''))));


        // Verificar si el DPI ya existe 
        $consultaDpi = "SELECT * FROM clientes WHERE dpi = '{$_POST['dpi']}' AND id_cliente != {$id}";
        $dpiExistente = Clientes::fetchArray($consultaDpi);
        if (count($dpiExistente) > 0) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Este DPI ya está registrado por otro cliente'
            ]);
            return;
        }

        // Verificar si el correo ya existe 
        $consultaCorreo = "SELECT * FROM clientes WHERE correo = '{$_POST['correo']}' AND id_cliente != {$id}";
        $correoExistente = Clientes::fetchArray($consultaCorreo);
        if (count($correoExistente) > 0) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El correo electrónico ya está registrado por otro cliente'
            ]);
            return;
        }



        try {
            $cliente = Clientes::find($id);
            $cliente->sincronizar([
                'primer_nombre' => $_POST['primer_nombre'],
                'segundo_nombre' => $_POST['segundo_nombre'],
                'primer_apellido' => $_POST['primer_apellido'],
                'segundo_apellido' => $_POST['segundo_apellido'],
                'telefono' => $_POST['telefono'],
                'dpi' => $_POST['dpi'],
                'correo' => $_POST['correo'],
                'direccion' => $_POST['direccion'],
                'situacion' => 1
            ]);

            $resultado = $cliente->actualizar();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Cliente modificado correctamente'
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al modificar el cliente',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public static function eliminarAPI()
    {
        getHeadersApi();
        try {
            $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
            Clientes::EliminarCliente($id);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'El cliente ha sido eliminado correctamente'
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al eliminar el cliente',
                'detalle' => $e->getMessage()
            ]);
        }
    }
}
