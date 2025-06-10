<?php

namespace Controllers;

use MVC\Router;
use Model\ActiveRecord;
use Model\Inventario;
use Exception;

class InventarioController extends ActiveRecord
{
    public static function renderizarPagina(Router $router)
    {
        $router->render('inventario/index', []);
    }

    public static function guardarAPI()
    {
        getHeadersApi();

        // Validaciones
        if (empty($_POST['id_marca'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe seleccionar una marca'
            ]);
            return;
        }

        if (empty($_POST['precio_compra']) || $_POST['precio_compra'] <= 0) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El precio de compra es obligatorio y debe ser mayor a 0'
            ]);
            return;
        }

        if (empty($_POST['precio_venta']) || $_POST['precio_venta'] <= 0) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El precio de venta es obligatorio y debe ser mayor a 0'
            ]);
            return;
        }

        if (empty($_POST['stock_disponible']) || $_POST['stock_disponible'] <= 0) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El stock disponible es obligatorio y debe ser mayor a 0'
            ]);
            return;
        }

        try {
            $_POST['id_marca'] = filter_var($_POST['id_marca'], FILTER_SANITIZE_NUMBER_INT);
            $_POST['estado_dispositivo'] = trim(htmlspecialchars($_POST['estado_dispositivo'] ?? 'NUEVO'));
            $_POST['estado_inventario'] = trim(htmlspecialchars($_POST['estado_inventario'] ?? 'DISPONIBLE'));
            $_POST['numero_serie'] = trim(htmlspecialchars($_POST['numero_serie'] ?? ''));
            $_POST['precio_compra'] = filter_var($_POST['precio_compra'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $_POST['precio_venta'] = filter_var($_POST['precio_venta'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $_POST['stock_disponible'] = filter_var($_POST['stock_disponible'], FILTER_SANITIZE_NUMBER_INT);
            $_POST['observaciones'] = trim(htmlspecialchars($_POST['observaciones'] ?? ''));

            $inventario = new Inventario([
                'id_marca' => $_POST['id_marca'],
                'estado_dispositivo' => $_POST['estado_dispositivo'],
                'estado_inventario' => $_POST['estado_inventario'],
                'numero_serie' => $_POST['numero_serie'],
                'precio_compra' => $_POST['precio_compra'],
                'precio_venta' => $_POST['precio_venta'],
                'stock_disponible' => $_POST['stock_disponible'],
                'observaciones' => $_POST['observaciones'],
                'situacion' => 1
            ]);

            $crear = $inventario->crear();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Producto del inventario guardado exitosamente'
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al guardar el producto',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public static function buscarAPI()
    {
        getHeadersApi();
        try {
            $inventario = Inventario::buscarConMarcas();

            if (count($inventario) > 0) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Inventario obtenido correctamente',
                    'data' => $inventario
                ]);
            } else {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'No hay productos en el inventario',
                    'data' => []
                ]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener el inventario',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public static function modificarAPI()
    {
        getHeadersApi();
        $id = $_POST['id_inventario'];

        if (empty($_POST['id_marca'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe seleccionar una marca'
            ]);
            return;
        }

        if (empty($_POST['precio_compra']) || $_POST['precio_compra'] <= 0) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El precio de compra es obligatorio y debe ser mayor a 0'
            ]);
            return;
        }

        if (empty($_POST['precio_venta']) || $_POST['precio_venta'] <= 0) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El precio de venta es obligatorio y debe ser mayor a 0'
            ]);
            return;
        }

        if (empty($_POST['stock_disponible']) || $_POST['stock_disponible'] <= 0) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El stock disponible es obligatorio y debe ser mayor a 0'
            ]);
            return;
        }

        try {
            $_POST['id_marca'] = filter_var($_POST['id_marca'], FILTER_SANITIZE_NUMBER_INT);
            $_POST['estado_dispositivo'] = trim(htmlspecialchars($_POST['estado_dispositivo'] ?? 'NUEVO'));
            $_POST['estado_inventario'] = trim(htmlspecialchars($_POST['estado_inventario'] ?? 'DISPONIBLE'));
            $_POST['numero_serie'] = trim(htmlspecialchars($_POST['numero_serie'] ?? ''));
            $_POST['precio_compra'] = filter_var($_POST['precio_compra'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $_POST['precio_venta'] = filter_var($_POST['precio_venta'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $_POST['stock_disponible'] = filter_var($_POST['stock_disponible'], FILTER_SANITIZE_NUMBER_INT);
            $_POST['observaciones'] = trim(htmlspecialchars($_POST['observaciones'] ?? ''));

            $data = Inventario::find($id);
            $data->sincronizar([
                'id_marca' => $_POST['id_marca'],
                'estado_dispositivo' => $_POST['estado_dispositivo'],
                'estado_inventario' => $_POST['estado_inventario'],
                'numero_serie' => $_POST['numero_serie'],
                'precio_compra' => $_POST['precio_compra'],
                'precio_venta' => $_POST['precio_venta'],
                'stock_disponible' => $_POST['stock_disponible'],
                'observaciones' => $_POST['observaciones'],
                'situacion' => 1
            ]);
            
            $data->actualizar();
                
            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Producto del inventario actualizado exitosamente'
            ]);
            
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al actualizar el producto',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public static function eliminarAPI()
    {
        try {
            $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
            Inventario::EliminarInventario($id);
            
            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'El producto ha sido eliminado del inventario correctamente'
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al eliminar el producto',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public static function obtenerMarcasAPI()
    {
        try {
            $marcas = Inventario::obtenerMarcas();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Marcas obtenidas correctamente',
                'data' => $marcas
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener marcas',
                'detalle' => $e->getMessage()
            ]);
        }
    }
}