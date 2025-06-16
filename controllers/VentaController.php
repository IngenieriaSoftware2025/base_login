<?php

namespace Controllers;

use Exception;
use Model\Ventas;
use Model\DetalleVentas;
use MVC\Router;

class VentasController
{
    public static function renderizarPagina(Router $router)
    {
        $router->render('ventas/index', []);
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

        if (empty($_POST['id_usuario'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe especificar el usuario vendedor'
            ]);
            return;
        }

        if (empty($_POST['productos'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe seleccionar al menos un producto'
            ]);
            return;
        }

        // Decodificar productos del carrito
        $productos = json_decode($_POST['productos'], true);
        if (!is_array($productos) || empty($productos)) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Formato de productos inválido'
            ]);
            return;
        }

        // Sanitizar datos
        $_POST['id_cliente'] = filter_var($_POST['id_cliente'], FILTER_SANITIZE_NUMBER_INT);
        $_POST['id_usuario'] = filter_var($_POST['id_usuario'], FILTER_SANITIZE_NUMBER_INT);
        $_POST['descuento'] = !empty($_POST['descuento']) ? 
            filter_var($_POST['descuento'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) : 0;
        $_POST['metodo_pago'] = trim(htmlspecialchars($_POST['metodo_pago'] ?? 'efectivo'));
        $_POST['observaciones'] = trim(htmlspecialchars($_POST['observaciones'] ?? ''));

        try {
            // Calcular total
            $total = 0;
            foreach ($productos as $producto) {
                $total += $producto['subtotal'];
            }
            
            $descuento = floatval($_POST['descuento']);
            $total_final = $total - $descuento;

            // Crear venta
            $venta = new Ventas([
                'id_cliente' => $_POST['id_cliente'],
                'id_usuario' => $_POST['id_usuario'],
                'total' => $total_final,
                'descuento' => $descuento,
                'metodo_pago' => $_POST['metodo_pago'],
                'estado_venta' => 'completada',
                'observaciones' => $_POST['observaciones']
            ]);

            $resultado_venta = $venta->crear();
            $id_venta = $resultado_venta['id'];

            // Guardar detalles y actualizar inventario
            foreach ($productos as $producto) {
                $detalle = new DetalleVentas([
                    'id_venta' => $id_venta,
                    'id_inventario' => $producto['id_inventario'],
                    'precio_unitario' => $producto['precio'],
                    'cantidad' => $producto['cantidad'],
                    'subtotal_detalle' => $producto['subtotal']
                ]);

                $detalle->crear();

                // Marcar inventario como vendido
                DetalleVentas::marcarInventarioVendido($producto['id_inventario']);
            }

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Venta registrada correctamente',
                'id_venta' => $id_venta
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al registrar la venta',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public static function buscarAPI()
    {
        getHeadersApi();
        try {
            $ventas = Ventas::obtenerVentasActivas();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Ventas obtenidas correctamente',
                'data' => $ventas
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener las ventas',
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

        if (empty($_POST['total'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El total es obligatorio'
            ]);
            return;
        }

        // Sanitizar datos
        $_POST['id_cliente'] = filter_var($_POST['id_cliente'], FILTER_SANITIZE_NUMBER_INT);
        $_POST['id_usuario'] = filter_var($_POST['id_usuario'], FILTER_SANITIZE_NUMBER_INT);
        $_POST['total'] = filter_var($_POST['total'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $_POST['descuento'] = !empty($_POST['descuento']) ? 
            filter_var($_POST['descuento'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) : 0;
        $_POST['metodo_pago'] = trim(htmlspecialchars($_POST['metodo_pago'] ?? 'efectivo'));
        $_POST['estado_venta'] = trim(htmlspecialchars($_POST['estado_venta'] ?? 'completada'));
        $_POST['observaciones'] = trim(htmlspecialchars($_POST['observaciones'] ?? ''));

        try {
            $id = $_POST['id_venta'];
            $venta = Ventas::find($id);
            $venta->sincronizar([
                'id_cliente' => $_POST['id_cliente'],
                'id_usuario' => $_POST['id_usuario'],
                'total' => $_POST['total'],
                'descuento' => $_POST['descuento'],
                'metodo_pago' => $_POST['metodo_pago'],
                'estado_venta' => $_POST['estado_venta'],
                'observaciones' => $_POST['observaciones'],
                'situacion' => 1
            ]);

            $resultado = $venta->actualizar();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Venta modificada correctamente'
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al modificar la venta',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public static function eliminarAPI()
    {
        getHeadersApi();
        try {
            $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
            Ventas::EliminarVenta($id);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'La venta ha sido eliminada correctamente'
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al eliminar la venta',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public static function obtenerClientesAPI()
    {
        getHeadersApi();
        try {
            $clientes = Ventas::obtenerClientesActivos();

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
            $usuarios = Ventas::obtenerUsuariosActivos();

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

    public static function obtenerInventarioAPI()
    {
        getHeadersApi();
        try {
            $inventario = Ventas::obtenerInventarioDisponible();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Inventario obtenido correctamente',
                'data' => $inventario
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener el inventario',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public static function obtenerDetalleAPI()
    {
        getHeadersApi();
        try {
            $id_venta = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
            
            $venta = Ventas::obtenerVentaConDetalle($id_venta);
            $detalles = DetalleVentas::obtenerDetallesPorVenta($id_venta);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Detalle de venta obtenido correctamente',
                'venta' => $venta,
                'detalles' => $detalles
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener el detalle',
                'detalle' => $e->getMessage()
            ]);
        }
    }
}
?>