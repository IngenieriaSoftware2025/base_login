<?php

namespace Controllers;

use Exception;
use MVC\Router;
use Model\ActiveRecord;
use Model\Reparaciones;






class ReparacionesController extends ActiveRecord

{

    
    public static function renderizarPagina(Router $router)
    {
        $router->render('reparaciones/index', []);
    }

    public static function guardarAPI()
    {
        getHeadersApi();

        // Validación cliente
        if (empty($_POST['id_cliente'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe seleccionar un cliente'
            ]);
            return;
        }

        // Validación usuario que recibe
        if (empty($_POST['id_usuario_recibe'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe seleccionar el usuario que recibe'
            ]);
            return;
        }

        // Validación motivo de ingreso
        if (empty($_POST['motivo_ingreso'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El motivo de ingreso es obligatorio'
            ]);
            return;
        }

        // Sanitizar datos
        $_POST['id_cliente'] = filter_var($_POST['id_cliente'], FILTER_SANITIZE_NUMBER_INT);
        $_POST['id_usuario_recibe'] = filter_var($_POST['id_usuario_recibe'], FILTER_SANITIZE_NUMBER_INT);
        $_POST['id_usuario_asignado'] = !empty($_POST['id_usuario_asignado']) ? filter_var($_POST['id_usuario_asignado'], FILTER_SANITIZE_NUMBER_INT) : null;
        $_POST['tipo_celular'] = trim(htmlspecialchars($_POST['tipo_celular'] ?? ''));
        $_POST['marca_celular'] = trim(htmlspecialchars($_POST['marca_celular'] ?? ''));
        $_POST['imei'] = trim(htmlspecialchars($_POST['imei'] ?? ''));
        $_POST['motivo_ingreso'] = trim(htmlspecialchars($_POST['motivo_ingreso']));
        $_POST['diagnostico'] = trim(htmlspecialchars($_POST['diagnostico'] ?? ''));
        $_POST['tipo_servicio'] = trim(htmlspecialchars($_POST['tipo_servicio'] ?? ''));
        $_POST['estado_reparacion'] = trim(htmlspecialchars($_POST['estado_reparacion'] ?? 'recibido'));
        $_POST['costo_total'] = !empty($_POST['costo_total']) ? filter_var($_POST['costo_total'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) : 0;

        // Generar número de orden automático
        $_POST['numero_orden'] = Reparaciones::generarNumeroOrden();

        try {
            $reparacion = new Reparaciones($_POST);
            $resultado = $reparacion->crear();

            if ($resultado['resultado'] == 1) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Reparación registrada correctamente'
                ]);
            } else {
                throw new Exception('Error al crear la reparación');
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al registrar la reparación',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public static function buscarAPI()
    {
        getHeadersApi();
        try {
            $reparaciones = Reparaciones::obtenerReparacionesActivas();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Reparaciones obtenidas correctamente',
                'data' => $reparaciones
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener las reparaciones',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public static function modificarAPI()
    {
        getHeadersApi();

        $id = $_POST['id_reparacion'];

        // Validaciones
        if (empty($_POST['id_cliente'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe seleccionar un cliente'
            ]);
            return;
        }

        if (empty($_POST['id_usuario_recibe'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe seleccionar el usuario que recibe'
            ]);
            return;
        }

        if (empty($_POST['motivo_ingreso'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El motivo de ingreso es obligatorio'
            ]);
            return;
        }

        // Sanitizar datos
        $_POST['id_cliente'] = filter_var($_POST['id_cliente'], FILTER_SANITIZE_NUMBER_INT);
        $_POST['id_usuario_recibe'] = filter_var($_POST['id_usuario_recibe'], FILTER_SANITIZE_NUMBER_INT);
        $_POST['id_usuario_asignado'] = !empty($_POST['id_usuario_asignado']) ? filter_var($_POST['id_usuario_asignado'], FILTER_SANITIZE_NUMBER_INT) : null;
        $_POST['tipo_celular'] = trim(htmlspecialchars($_POST['tipo_celular'] ?? ''));
        $_POST['marca_celular'] = trim(htmlspecialchars($_POST['marca_celular'] ?? ''));
        $_POST['imei'] = trim(htmlspecialchars($_POST['imei'] ?? ''));
        $_POST['motivo_ingreso'] = trim(htmlspecialchars($_POST['motivo_ingreso']));
        $_POST['diagnostico'] = trim(htmlspecialchars($_POST['diagnostico'] ?? ''));
        $_POST['fecha_asignacion'] = !empty($_POST['fecha_asignacion']) ? $_POST['fecha_asignacion'] : null;
        $_POST['fecha_entrega_real'] = !empty($_POST['fecha_entrega_real']) ? $_POST['fecha_entrega_real'] : null;
        $_POST['tipo_servicio'] = trim(htmlspecialchars($_POST['tipo_servicio'] ?? ''));
        $_POST['estado_reparacion'] = trim(htmlspecialchars($_POST['estado_reparacion'] ?? 'recibido'));
        $_POST['costo_total'] = !empty($_POST['costo_total']) ? filter_var($_POST['costo_total'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) : 0;

        try {
            $reparacion = Reparaciones::find($id);
            $reparacion->sincronizar([
                'id_cliente' => $_POST['id_cliente'],
                'id_usuario_recibe' => $_POST['id_usuario_recibe'],
                'id_usuario_asignado' => $_POST['id_usuario_asignado'],
                'tipo_celular' => $_POST['tipo_celular'],
                'marca_celular' => $_POST['marca_celular'],
                'imei' => $_POST['imei'],
                'motivo_ingreso' => $_POST['motivo_ingreso'],
                'diagnostico' => $_POST['diagnostico'],
                'fecha_asignacion' => $_POST['fecha_asignacion'],
                'fecha_entrega_real' => $_POST['fecha_entrega_real'],
                'tipo_servicio' => $_POST['tipo_servicio'],
                'estado_reparacion' => $_POST['estado_reparacion'],
                'costo_total' => $_POST['costo_total'],
                'situacion' => 1
            ]);

            $resultado = $reparacion->actualizar();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Reparación modificada correctamente'
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al modificar la reparación',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public static function eliminarAPI()
    {
        getHeadersApi();
        try {
            $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
            Reparaciones::EliminarReparacion($id);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'La reparación ha sido eliminada correctamente'
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al eliminar la reparación',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public static function obtenerClientesAPI()
    {
        getHeadersApi();
        try {
            $clientes = Reparaciones::obtenerClientesActivos();

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

    public static function obtenerUsuariosAPI()
    {
        getHeadersApi();
        try {
            $usuarios = Reparaciones::obtenerUsuariosActivos();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Usuarios obtenidos correctamente',
                'data' => $usuarios
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener los usuarios',
                'detalle' => $e->getMessage()
            ]);
        }
    }
}