<?php

namespace Controllers;

use Exception;
use MVC\Router;
use Model\ActiveRecord;
use Model\Clientes;
use Controllers\RutasActividadesController; 

class ClientesController extends ActiveRecord
{
    public static function renderizarPagina(Router $router)
    {
        // REGISTRAR ACCESO AL MÓDULO DE CLIENTES
        RutasActividadesController::registrarRutaActividad(
            'CLIENTES', 
            'ACCEDER', 
            'Usuario accedió al módulo de clientes'
        );
        
        $router->render('clientes/index', []);
    }

    public static function guardarAPI()
    {
        getHeadersApi();

        // Validación primer nombre
        if (empty($_POST['primer_nombre'])) {
            // REGISTRAR INTENTO DE CREAR SIN PRIMER NOMBRE
            RutasActividadesController::registrarRutaActividad(
                'CLIENTES', 
                'ERROR_VALIDACION', 
                'Intento de crear cliente sin primer nombre'
            );
            
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El primer nombre es obligatorio'
            ]);
            return;
        }

        // Validación primer apellido
        if (empty($_POST['primer_apellido'])) {
            // REGISTRAR INTENTO DE CREAR SIN PRIMER APELLIDO
            RutasActividadesController::registrarRutaActividad(
                'CLIENTES', 
                'ERROR_VALIDACION', 
                'Intento de crear cliente sin primer apellido'
            );
            
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El primer apellido es obligatorio'
            ]);
            return;
        }

        if (empty($_POST['telefono'])) {
            // REGISTRAR INTENTO DE CREAR SIN TELÉFONO
            RutasActividadesController::registrarRutaActividad(
                'CLIENTES', 
                'ERROR_VALIDACION', 
                'Intento de crear cliente sin teléfono'
            );
            
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El teléfono es obligatorio'
            ]);
            return;
        }

        // Validación DPI
        if (empty($_POST['dpi'])) {
            // REGISTRAR INTENTO DE CREAR SIN DPI
            RutasActividadesController::registrarRutaActividad(
                'CLIENTES', 
                'ERROR_VALIDACION', 
                'Intento de crear cliente sin DPI'
            );
            
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
            // REGISTRAR INTENTO DE CREAR CON DPI INVÁLIDO
            RutasActividadesController::registrarRutaActividad(
                'CLIENTES', 
                'ERROR_VALIDACION', 
                "Intento de crear cliente con DPI inválido: {$_POST['dpi']} (longitud: " . strlen($_POST['dpi']) . ")"
            );
            
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El DPI debe tener 13 dígitos'
            ]);
            return;
        }

        // Validación correo
        if (empty($_POST['correo'])) {
            // REGISTRAR INTENTO DE CREAR SIN CORREO
            RutasActividadesController::registrarRutaActividad(
                'CLIENTES', 
                'ERROR_VALIDACION', 
                'Intento de crear cliente sin correo electrónico'
            );
            
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El correo electrónico es obligatorio'
            ]);
            return;
        }

        // Validación dirección
        if (empty($_POST['direccion'])) {
            // REGISTRAR INTENTO DE CREAR SIN DIRECCIÓN
            RutasActividadesController::registrarRutaActividad(
                'CLIENTES', 
                'ERROR_VALIDACION', 
                'Intento de crear cliente sin dirección'
            );
            
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
            // REGISTRAR INTENTO DE DUPLICAR DPI
            RutasActividadesController::registrarRutaActividad(
                'CLIENTES', 
                'INTENTO_DUPLICAR', 
                "Intentó crear cliente con DPI duplicado: {$_POST['dpi']}"
            );
            
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
            // REGISTRAR INTENTO DE DUPLICAR CORREO
            RutasActividadesController::registrarRutaActividad(
                'CLIENTES', 
                'INTENTO_DUPLICAR', 
                "Intentó crear cliente con correo duplicado: {$_POST['correo']}"
            );
            
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
                // REGISTRAR CREACIÓN EXITOSA
                $nombreCompleto = $_POST['primer_nombre'] . ' ' . $_POST['primer_apellido'];
                RutasActividadesController::registrarRutaActividad(
                    'CLIENTES', 
                    'CREAR', 
                    "Creó cliente exitosamente: $nombreCompleto (ID: {$resultado['id']}, DPI: {$_POST['dpi']})"
                );
                
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Cliente registrado correctamente'
                ]);
            } else {
                // REGISTRAR ERROR EN CREACIÓN
                RutasActividadesController::registrarRutaActividad(
                    'CLIENTES', 
                    'ERROR_CREAR', 
                    "Error al crear cliente: {$_POST['primer_nombre']} {$_POST['primer_apellido']}"
                );
                
                http_response_code(500);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Error al crear el cliente'
                ]);
            }
        } catch (Exception $e) {
            // REGISTRAR EXCEPCIÓN
            RutasActividadesController::registrarRutaActividad(
                'CLIENTES', 
                'EXCEPCION', 
                "Excepción al crear cliente: " . $e->getMessage()
            );
            
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
        
        // REGISTRAR CONSULTA DE CLIENTES
        RutasActividadesController::registrarRutaActividad(
            'CLIENTES', 
            'CONSULTAR', 
            'Usuario consultó lista de clientes'
        );
        
        try {
            $clientes = Clientes::obtenerClientesActivos();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Clientes obtenidos correctamente',
                'data' => $clientes
            ]);
        } catch (Exception $e) {
            // REGISTRAR ERROR EN CONSULTA
            RutasActividadesController::registrarRutaActividad(
                'CLIENTES', 
                'ERROR_CONSULTAR', 
                "Error al consultar clientes: " . $e->getMessage()
            );
            
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

        // Validaciones (mantenemos las existentes pero agregamos registros de actividad)
        if (empty($_POST['primer_nombre'])) {
            // REGISTRAR ERROR DE VALIDACIÓN EN ACTUALIZACIÓN
            RutasActividadesController::registrarRutaActividad(
                'CLIENTES', 
                'ERROR_VALIDACION', 
                "Intento de actualizar cliente ID $id sin primer nombre"
            );
            
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El primer nombre es obligatorio'
            ]);
            return;
        }

        if (empty($_POST['primer_apellido'])) {
            RutasActividadesController::registrarRutaActividad(
                'CLIENTES', 
                'ERROR_VALIDACION', 
                "Intento de actualizar cliente ID $id sin primer apellido"
            );
            
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El primer apellido es obligatorio'
            ]);
            return;
        }

        // Validación teléfono
        if (empty($_POST['telefono'])) {
            RutasActividadesController::registrarRutaActividad(
                'CLIENTES', 
                'ERROR_VALIDACION', 
                "Intento de actualizar cliente ID $id sin teléfono"
            );
            
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El teléfono es obligatorio'
            ]);
            return;
        }

        // Validación DPI
        if (empty($_POST['dpi'])) {
            RutasActividadesController::registrarRutaActividad(
                'CLIENTES', 
                'ERROR_VALIDACION', 
                "Intento de actualizar cliente ID $id sin DPI"
            );
            
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
            RutasActividadesController::registrarRutaActividad(
                'CLIENTES', 
                'ERROR_VALIDACION', 
                "Intento de actualizar cliente ID $id con DPI inválido: {$_POST['dpi']}"
            );
            
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El DPI debe tener 13 dígitos'
            ]);
            return;
        }

        // Validación correo
        if (empty($_POST['correo'])) {
            RutasActividadesController::registrarRutaActividad(
                'CLIENTES', 
                'ERROR_VALIDACION', 
                "Intento de actualizar cliente ID $id sin correo"
            );
            
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El correo electrónico es obligatorio'
            ]);
            return;
        }

        // Validación dirección
        if (empty($_POST['direccion'])) {
            RutasActividadesController::registrarRutaActividad(
                'CLIENTES', 
                'ERROR_VALIDACION', 
                "Intento de actualizar cliente ID $id sin dirección"
            );
            
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

        // Verificar si el DPI ya existe en otro cliente
        $consultaDpi = "SELECT * FROM clientes WHERE dpi = '{$_POST['dpi']}' AND id_cliente != {$id}";
        $dpiExistente = Clientes::fetchArray($consultaDpi);
        if (count($dpiExistente) > 0) {
            // REGISTRAR INTENTO DE DUPLICAR DPI EN ACTUALIZACIÓN
            RutasActividadesController::registrarRutaActividad(
                'CLIENTES', 
                'INTENTO_DUPLICAR', 
                "Intentó actualizar cliente ID $id con DPI ya existente: {$_POST['dpi']}"
            );
            
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Este DPI ya está registrado por otro cliente'
            ]);
            return;
        }

        // Verificar si el correo ya existe en otro cliente
        $consultaCorreo = "SELECT * FROM clientes WHERE correo = '{$_POST['correo']}' AND id_cliente != {$id}";
        $correoExistente = Clientes::fetchArray($consultaCorreo);
        if (count($correoExistente) > 0) {
            // REGISTRAR INTENTO DE DUPLICAR CORREO EN ACTUALIZACIÓN
            RutasActividadesController::registrarRutaActividad(
                'CLIENTES', 
                'INTENTO_DUPLICAR', 
                "Intentó actualizar cliente ID $id con correo ya existente: {$_POST['correo']}"
            );
            
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El correo electrónico ya está registrado por otro cliente'
            ]);
            return;
        }

        try {
            // OBTENER DATOS ANTERIORES PARA EL LOG
            $clienteAnterior = Clientes::find($id);
            $nombreAnterior = $clienteAnterior ? 
                $clienteAnterior->primer_nombre . ' ' . $clienteAnterior->primer_apellido : 
                "Cliente ID $id";
            
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

            // REGISTRAR ACTUALIZACIÓN EXITOSA
            $nombreNuevo = $_POST['primer_nombre'] . ' ' . $_POST['primer_apellido'];
            RutasActividadesController::registrarRutaActividad(
                'CLIENTES', 
                'ACTUALIZAR', 
                "Actualizó cliente: '$nombreAnterior' → '$nombreNuevo' (ID: $id, DPI: {$_POST['dpi']})"
            );

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Cliente modificado correctamente'
            ]);
        } catch (Exception $e) {
            // REGISTRAR ERROR EN ACTUALIZACIÓN
            RutasActividadesController::registrarRutaActividad(
                'CLIENTES', 
                'ERROR_ACTUALIZAR', 
                "Error al actualizar cliente ID $id: " . $e->getMessage()
            );
            
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
            
            // OBTENER INFORMACIÓN DEL CLIENTE ANTES DE ELIMINAR
            $cliente = Clientes::find($id);
            $nombreCliente = $cliente ? 
                $cliente->primer_nombre . ' ' . $cliente->primer_apellido . " (DPI: {$cliente->dpi})" : 
                "Cliente ID: $id";
            
            Clientes::EliminarCliente($id);

            // REGISTRAR ELIMINACIÓN EXITOSA
            RutasActividadesController::registrarRutaActividad(
                'CLIENTES', 
                'ELIMINAR', 
                "Eliminó cliente: $nombreCliente"
            );

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'El cliente ha sido eliminado correctamente'
            ]);
        } catch (Exception $e) {
            // REGISTRAR ERROR EN ELIMINACIÓN
            RutasActividadesController::registrarRutaActividad(
                'CLIENTES', 
                'ERROR_ELIMINAR', 
                "Error al eliminar cliente ID $id: " . $e->getMessage()
            );
            
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al eliminar el cliente',
                'detalle' => $e->getMessage()
            ]);
        }
    }
}