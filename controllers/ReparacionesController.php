<?php

namespace Controllers;

use Exception;
use Model\Reparaciones;
use MVC\Router;

class ReparacionesController
{
    public static function renderizarPagina(Router $router)
    {
        $router->render('reparaciones/index', []);
    }

    public static function guardarAPI()
    {
        getHeadersApi();

        // Validaciones básicas
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

        // Validaciones básicas
        if (empty($_POST['id_cliente'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe seleccionar un cliente'
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

        try {
            $id = $_POST['id_reparacion'];
            $reparacion = Reparaciones::find($id);
            $reparacion->sincronizar($_POST);
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
?>