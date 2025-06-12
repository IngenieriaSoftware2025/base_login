<?php

namespace Controllers;

use Exception;
use MVC\Router;
use Model\ActiveRecord;
use Model\Inventario;

class InventarioController extends ActiveRecord
{
    public static function renderizarPagina(Router $router)
    {
        $router->render('inventario/index', []);
    }

    public static function guardarAPI()
    {
        getHeadersApi();

        // Validación modelo
        if (empty($_POST['id_modelo'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe seleccionar un modelo'
            ]);
            return;
        }

        // Validación IMEI
        if (empty($_POST['imei'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El IMEI es obligatorio'
            ]);
            return;
        }

        // Validación precio de compra
        if (empty($_POST['precio_compra']) || $_POST['precio_compra'] <= 0) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El precio de compra debe ser mayor a 0'
            ]);
            return;
        }

        // Validación precio de venta
        if (empty($_POST['precio_venta']) || $_POST['precio_venta'] <= 0) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El precio de venta debe ser mayor a 0'
            ]);
            return;
        }

        // Sanitizar datos
        $_POST['id_modelo'] = filter_var($_POST['id_modelo'], FILTER_SANITIZE_NUMBER_INT);
        $_POST['imei'] = trim(htmlspecialchars($_POST['imei']));
        $_POST['estado_celular'] = trim(htmlspecialchars($_POST['estado_celular'] ?? 'nuevo'));
        $_POST['precio_compra'] = filter_var($_POST['precio_compra'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $_POST['precio_venta'] = filter_var($_POST['precio_venta'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $_POST['estado_inventario'] = trim(htmlspecialchars($_POST['estado_inventario'] ?? 'disponible'));

        try {
            $inventario = new Inventario($_POST);
            $resultado = $inventario->crear();

            if ($resultado['resultado'] == 1) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Inventario registrado correctamente'
                ]);
            } else {
                throw new Exception('Error al crear el inventario');
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al registrar el inventario',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public static function buscarAPI()
    {
        getHeadersApi();
        try {
            $inventario = Inventario::obtenerInventarioActivo();

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

    public static function modificarAPI()
    {
        getHeadersApi();

        $id = $_POST['id_inventario'];

        // Validaciones
        if (empty($_POST['id_modelo'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe seleccionar un modelo'
            ]);
            return;
        }

        if (empty($_POST['imei'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El IMEI es obligatorio'
            ]);
            return;
        }

        if (empty($_POST['precio_compra']) || $_POST['precio_compra'] <= 0) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El precio de compra debe ser mayor a 0'
            ]);
            return;
        }

        if (empty($_POST['precio_venta']) || $_POST['precio_venta'] <= 0) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El precio de venta debe ser mayor a 0'
            ]);
            return;
        }

        // Sanitizar datos
        $_POST['id_modelo'] = filter_var($_POST['id_modelo'], FILTER_SANITIZE_NUMBER_INT);
        $_POST['imei'] = trim(htmlspecialchars($_POST['imei']));
        $_POST['estado_celular'] = trim(htmlspecialchars($_POST['estado_celular'] ?? 'nuevo'));
        $_POST['precio_compra'] = filter_var($_POST['precio_compra'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $_POST['precio_venta'] = filter_var($_POST['precio_venta'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $_POST['estado_inventario'] = trim(htmlspecialchars($_POST['estado_inventario'] ?? 'disponible'));

        try {
            $inventario = Inventario::find($id);
            $inventario->sincronizar([
                'id_modelo' => $_POST['id_modelo'],
                'imei' => $_POST['imei'],
                'estado_celular' => $_POST['estado_celular'],
                'precio_compra' => $_POST['precio_compra'],
                'precio_venta' => $_POST['precio_venta'],
                'estado_inventario' => $_POST['estado_inventario'],
                'situacion' => 1
            ]);

            $resultado = $inventario->actualizar();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Inventario modificado correctamente'
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al modificar el inventario',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public static function eliminarAPI()
    {
        getHeadersApi();
        try {
            $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
            Inventario::EliminarInventario($id);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'El inventario ha sido eliminado correctamente'
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al eliminar el inventario',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public static function obtenerMarcasAPI()
    {
        getHeadersApi();
        try {
            $marcas = Inventario::obtenerMarcasActivas();

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
                'mensaje' => 'Error al obtener las marcas',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public static function obtenerModelosAPI()
    {
        getHeadersApi();
        try {
            $id_marca = filter_var($_GET['id_marca'], FILTER_SANITIZE_NUMBER_INT);
            $modelos = Inventario::obtenerModelosPorMarca($id_marca);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Modelos obtenidos correctamente',
                'data' => $modelos
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener los modelos',
                'detalle' => $e->getMessage()
            ]);
        }
    }
}