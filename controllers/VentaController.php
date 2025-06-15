<?php

namespace Controllers;

use Exception;
use Model\ActiveRecord;
use Model\Ventas;
use Model\VentaDetalles;
use Model\Productos;
use Model\Clientes;
use MVC\Router;

class VentaController extends ActiveRecord{
    
    public static function renderizarPagina(Router $router){
        $router->render('ventas/index', []);
    }

    // Guardar Venta
    public static function guardarAPI(){
        getHeadersApi();

        // Validar cliente
        if (empty($_POST['id_cliente'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe seleccionar un cliente'
            ]);
            return;
        }

        // Validar usuario (puedes ajustar esto según tu sistema de usuarios)
        if (empty($_POST['id_usuario'])) {
            $_POST['id_usuario'] = 1; // Usuario por defecto, ajusta según tu lógica
        }

        // Validar productos
        if (empty($_POST['productos'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe seleccionar al menos un producto'
            ]);
            return;
        }

        $productos = is_string($_POST['productos']) ? json_decode($_POST['productos'], true) : $_POST['productos'];
        
        if (!is_array($productos)) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Formato de productos inválido'
            ]);
            return;
        }

        // Validar método de pago
        $metodo_pago = $_POST['metodo_pago'] ?? 'efectivo';
        if (!in_array($metodo_pago, ['efectivo', 'tarjeta', 'transferencia'])) {
            $metodo_pago = 'efectivo';
        }

        $subtotal = 0;
        $descuento = floatval($_POST['descuento'] ?? 0);

        // Validar stock y calcular subtotal
        foreach ($productos as $p) {
            $producto_id = $p['producto_id'];
            $cantidad_solicitada = $p['cantidad'];

            // Verificar stock disponible
            $stock_disponible = Productos::ValidarStockProducto($producto_id);

            if ($stock_disponible < $cantidad_solicitada) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => "Stock insuficiente para producto ID: {$producto_id}"
                ]);
                return;
            }

            $precio_unitario = $p['precio'];
            $subtotal_producto = $cantidad_solicitada * $precio_unitario;
            $subtotal += $subtotal_producto;
        }

        $total = $subtotal - $descuento;

        try {
            // Generar número de venta
            $numero_venta = 'V-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);

            $venta = new Ventas([
                'id_cliente' => $_POST['id_cliente'],
                'id_usuario' => $_POST['id_usuario'],
                'numero_venta' => $numero_venta,
                'fecha_venta' => date('Y-m-d'),
                'subtotal' => $subtotal,
                'descuento' => $descuento,
                'total' => $total,
                'metodo_pago' => $metodo_pago,
                'estado_venta' => 'completada',
                'observaciones' => $_POST['observaciones'] ?? '',
                'situacion' => 1
            ]);

            $resultado_venta = $venta->crear();
            $venta_id = $resultado_venta['id'];

            // Guardar detalles
            foreach ($productos as $p) {
                $producto_id = $p['producto_id'];
                $cantidad = $p['cantidad'];
                $precio_unitario = $p['precio'];
                $subtotal_detalle = $cantidad * $precio_unitario;

                $detalle = new VentaDetalles([
                    'venta_id' => $venta_id,
                    'producto_id' => $producto_id,
                    'cantidad' => $cantidad,
                    'precio_unitario' => $precio_unitario,
                    'subtotal' => $subtotal_detalle
                ]);

                $detalle->crear();

                // Actualizar stock
                Productos::ActualizarStockProducto($producto_id, $cantidad);
            }

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Venta registrada correctamente',
                'venta_id' => $venta_id,
                'numero_venta' => $numero_venta
            ]);

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al guardar la venta',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    // Buscar Ventas
    public static function buscarAPI(){
        try {
            $data = Ventas::ObtenerVentasConClientes();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Ventas obtenidas correctamente',
                'data' => $data
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener las ventas',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    // Obtener Detalle de Venta
    public static function obtenerDetalleAPI(){
        try {
            $venta_id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
            
            $venta = Ventas::ObtenerVentaPorId($venta_id);
            $detalles = VentaDetalles::ObtenerDetallesPorVenta($venta_id);

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
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    // Obtener Clientes para el select
    public static function obtenerClientesAPI(){
        try {
            $sql = "SELECT id_cliente, nombres, apellidos, correo 
                    FROM clientes WHERE situacion = 1 
                    ORDER BY nombres";
            $data = self::fetchArray($sql);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Clientes obtenidos correctamente',
                'data' => $data
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener los clientes',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    // Obtener Productos Disponibles
    public static function obtenerProductosAPI(){
        try {
            $data = Productos::ObtenerProductosDisponibles();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Productos disponibles obtenidos correctamente',
                'data' => $data
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener los productos disponibles',
                'detalle' => $e->getMessage(),
            ]);
        }
    }
}