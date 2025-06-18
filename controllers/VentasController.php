<?php

namespace Controllers;

use Exception;
use MVC\Router;
use Model\ActiveRecord;
use Model\Ventas;
use Model\DetalleVentas;

class VentasController extends ActiveRecord
{
    // 1. MOSTRAR LA PÁGINA DE VENTAS
    public static function renderizarPagina(Router $router)
    {
        $router->render('ventas/index', []);
    }

    // 2. GUARDAR NUEVA VENTA
    public static function guardarAPI()
    {
        getHeadersApi();

        try {
            // Validaciones básicas
            if (empty($_POST['id_cliente'])) {
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Debe seleccionar un cliente'
                ]);
                return;
            }

            if (empty($_POST['id_usuario'])) {
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Debe seleccionar un vendedor'
                ]);
                return;
            }

            if (empty($_POST['total']) || $_POST['total'] <= 0) {
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El total debe ser mayor a 0'
                ]);
                return;
            }

            // Limpiar datos
            $_POST['id_cliente'] = filter_var($_POST['id_cliente'], FILTER_SANITIZE_NUMBER_INT);
            $_POST['id_usuario'] = filter_var($_POST['id_usuario'], FILTER_SANITIZE_NUMBER_INT);
            $_POST['subtotal'] = filter_var($_POST['subtotal'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $_POST['descuento'] = filter_var($_POST['descuento'] ?? 0, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $_POST['total'] = filter_var($_POST['total'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $_POST['metodo_pago'] = trim(htmlspecialchars($_POST['metodo_pago'] ?? 'efectivo'));
            $_POST['observaciones'] = trim(htmlspecialchars($_POST['observaciones'] ?? ''));

            // Crear la venta
            $venta = new Ventas($_POST);
            $resultado = $venta->crear();

            if ($resultado['resultado'] == 1) {
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Venta registrada correctamente',
                    'id_venta' => $resultado['id']
                ]);
            } else {
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Error al crear la venta'
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al registrar la venta: ' . $e->getMessage()
            ]);
        }
    }

    // 3. BUSCAR TODAS LAS VENTAS
    public static function buscarAPI()
    {
        getHeadersApi();
        try {
            $ventas = Ventas::obtenerVentasActivas();

            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Ventas obtenidas correctamente',
                'data' => $ventas
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener las ventas: ' . $e->getMessage()
            ]);
        }
    }

    // 4. MODIFICAR VENTA
    public static function modificarAPI()
    {
        getHeadersApi();

        try {
            $id = $_POST['id_venta'];

            // Validaciones
            if (empty($_POST['id_cliente'])) {
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Debe seleccionar un cliente'
                ]);
                return;
            }

            if (empty($_POST['total']) || $_POST['total'] <= 0) {
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El total debe ser mayor a 0'
                ]);
                return;
            }

            // Limpiar datos
            $_POST['id_cliente'] = filter_var($_POST['id_cliente'], FILTER_SANITIZE_NUMBER_INT);
            $_POST['subtotal'] = filter_var($_POST['subtotal'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $_POST['descuento'] = filter_var($_POST['descuento'] ?? 0, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $_POST['total'] = filter_var($_POST['total'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $_POST['metodo_pago'] = trim(htmlspecialchars($_POST['metodo_pago'] ?? 'efectivo'));
            $_POST['observaciones'] = trim(htmlspecialchars($_POST['observaciones'] ?? ''));

            // Buscar y actualizar
            $venta = Ventas::find($id);
            $venta->sincronizar([
                'id_cliente' => $_POST['id_cliente'],
                'subtotal' => $_POST['subtotal'],
                'descuento' => $_POST['descuento'],
                'total' => $_POST['total'],
                'metodo_pago' => $_POST['metodo_pago'],
                'observaciones' => $_POST['observaciones'],
                'situacion' => 1
            ]);

            $resultado = $venta->actualizar();

            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Venta modificada correctamente'
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al modificar la venta: ' . $e->getMessage()
            ]);
        }
    }

    // 5. ELIMINAR VENTA
    public static function eliminarAPI()
    {
        getHeadersApi();
        try {
            $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
            Ventas::EliminarVenta($id);

            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'La venta ha sido eliminada correctamente'
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al eliminar la venta: ' . $e->getMessage()
            ]);
        }
    }

    // 6. OBTENER CLIENTES PARA DROPDOWN
    public static function obtenerClientesAPI()
    {
        getHeadersApi();
        try {
            $clientes = Ventas::obtenerClientesActivos();

            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Clientes obtenidos correctamente',
                'data' => $clientes
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener los clientes: ' . $e->getMessage()
            ]);
        }
    }

    // 7. OBTENER USUARIOS PARA DROPDOWN
    public static function obtenerUsuariosAPI()
    {
        getHeadersApi();
        try {
            $usuarios = Ventas::obtenerUsuariosActivos();

            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Usuarios obtenidos correctamente',
                'data' => $usuarios
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener los usuarios: ' . $e->getMessage()
            ]);
        }
    }

    // 8. OBTENER PRODUCTOS PARA DROPDOWN
    public static function obtenerProductosAPI()
    {
        getHeadersApi();
        try {
            $productos = Ventas::obtenerProductosDisponibles();

            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Productos obtenidos correctamente',
                'data' => $productos
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener los productos: ' . $e->getMessage()
            ]);
        }
    }

    // 9. GUARDAR DETALLE DE VENTA
    public static function guardarDetalleAPI()
    {
        getHeadersApi();

        try {
            // Validaciones
            if (empty($_POST['id_venta'])) {
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'ID de venta es obligatorio'
                ]);
                return;
            }

            if (empty($_POST['id_inventario'])) {
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Debe seleccionar un producto'
                ]);
                return;
            }

            if (empty($_POST['precio_unitario']) || $_POST['precio_unitario'] <= 0) {
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El precio debe ser mayor a 0'
                ]);
                return;
            }

            // Limpiar datos
            $_POST['id_venta'] = filter_var($_POST['id_venta'], FILTER_SANITIZE_NUMBER_INT);
            $_POST['id_inventario'] = filter_var($_POST['id_inventario'], FILTER_SANITIZE_NUMBER_INT);
            $_POST['precio_unitario'] = filter_var($_POST['precio_unitario'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $_POST['cantidad'] = filter_var($_POST['cantidad'] ?? 1, FILTER_SANITIZE_NUMBER_INT);
            $_POST['subtotal_detalle'] = $_POST['precio_unitario'] * $_POST['cantidad'];

            // Crear el detalle
            $detalle = new DetalleVentas($_POST);
            $resultado = $detalle->crear();

            if ($resultado['resultado'] == 1) {
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Producto agregado a la venta correctamente'
                ]);
            } else {
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Error al agregar el producto'
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al agregar el producto: ' . $e->getMessage()
            ]);
        }
    }

    // OBTENER DETALLES DE UNA VENTA
    public static function obtenerDetallesAPI()
    {
        getHeadersApi();
        try {
            $id_venta = filter_var($_GET['id_venta'], FILTER_SANITIZE_NUMBER_INT);
            $detalles = DetalleVentas::obtenerDetallesPorVenta($id_venta);

            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Detalles obtenidos correctamente',
                'data' => $detalles
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener los detalles: ' . $e->getMessage()
            ]);
        }
    }
}