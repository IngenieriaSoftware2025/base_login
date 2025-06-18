<?php

namespace Controllers;

use Exception;
use MVC\Router;
use Model\ActiveRecord;
use Model\Inventario;
use Controllers\RutasActividadesController; // IMPORTAR EL CONTROLADOR DE ACTIVIDADES

class InventarioController extends ActiveRecord
{
    public static function renderizarPagina(Router $router)
    {
        // REGISTRAR ACCESO AL MÓDULO DE INVENTARIO
        RutasActividadesController::registrarRutaActividad(
            'INVENTARIO', 
            'ACCEDER', 
            'Usuario accedió al módulo de inventario'
        );
        
        $router->render('inventario/index', []);
    }

    public static function guardarAPI()
    {
        getHeadersApi();

        // Validación modelo
        if (empty($_POST['id_modelo'])) {
            // REGISTRAR INTENTO DE CREAR SIN MODELO
            RutasActividadesController::registrarRutaActividad(
                'INVENTARIO', 
                'ERROR_VALIDACION', 
                'Intento de crear inventario sin seleccionar modelo'
            );
            
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe seleccionar un modelo'
            ]);
            return;
        }

        // Validación precio de compra
        if (empty($_POST['precio_compra']) || $_POST['precio_compra'] <= 0) {
            // REGISTRAR INTENTO DE CREAR SIN PRECIO DE COMPRA VÁLIDO
            RutasActividadesController::registrarRutaActividad(
                'INVENTARIO', 
                'ERROR_VALIDACION', 
                'Intento de crear inventario con precio de compra inválido: ' . ($_POST['precio_compra'] ?? 'vacío')
            );
            
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El precio de compra debe ser mayor a 0'
            ]);
            return;
        }

        // Validación precio de venta
        if (empty($_POST['precio_venta']) || $_POST['precio_venta'] <= 0) {
            // REGISTRAR INTENTO DE CREAR SIN PRECIO DE VENTA VÁLIDO
            RutasActividadesController::registrarRutaActividad(
                'INVENTARIO', 
                'ERROR_VALIDACION', 
                'Intento de crear inventario con precio de venta inválido: ' . ($_POST['precio_venta'] ?? 'vacío')
            );
            
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El precio de venta debe ser mayor a 0'
            ]);
            return;
        }

        // Validar que el modelo exista
        $modeloExiste = self::SQL("SELECT id_modelo FROM modelos WHERE id_modelo = {$_POST['id_modelo']} AND situacion = 1");
        if (empty($modeloExiste)) {
            // REGISTRAR INTENTO DE CREAR CON MODELO INEXISTENTE
            RutasActividadesController::registrarRutaActividad(
                'INVENTARIO', 
                'ERROR_VALIDACION', 
                "Intento de crear inventario con modelo inexistente ID: {$_POST['id_modelo']}"
            );
            
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El modelo seleccionado no existe'
            ]);
            return;
        }

        // Sanitizar datos
        $_POST['id_modelo'] = filter_var($_POST['id_modelo'], FILTER_SANITIZE_NUMBER_INT);
        $_POST['estado_celular'] = trim(htmlspecialchars($_POST['estado_celular'] ?? 'nuevo'));
        $_POST['precio_compra'] = filter_var($_POST['precio_compra'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $_POST['precio_venta'] = filter_var($_POST['precio_venta'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $_POST['estado_inventario'] = trim(htmlspecialchars($_POST['estado_inventario'] ?? 'disponible'));

        try {
            // OBTENER INFORMACIÓN DEL MODELO PARA EL LOG
            $infoModelo = self::fetchFirst("SELECT m.nombre_modelo, ma.nombre_marca 
                                          FROM modelos m 
                                          INNER JOIN marcas ma ON m.id_marca = ma.id_marca 
                                          WHERE m.id_modelo = {$_POST['id_modelo']}");
            
            $inventario = new Inventario($_POST);
            $resultado = $inventario->crear();

            if ($resultado['resultado'] == 1) {
                // REGISTRAR CREACIÓN EXITOSA
                $descripcionProducto = ($infoModelo['nombre_marca'] ?? 'Marca') . ' ' . 
                                     ($infoModelo['nombre_modelo'] ?? 'Modelo');
                
                RutasActividadesController::registrarRutaActividad(
                    'INVENTARIO', 
                    'CREAR', 
                    "Creó inventario exitosamente: $descripcionProducto (ID: {$resultado['id']}, Estado: {$_POST['estado_celular']}, Precio Compra: Q.{$_POST['precio_compra']}, Precio Venta: Q.{$_POST['precio_venta']})"
                );
                
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Inventario registrado correctamente'
                ]);
            } else {
                // REGISTRAR ERROR EN CREACIÓN
                RutasActividadesController::registrarRutaActividad(
                    'INVENTARIO', 
                    'ERROR_CREAR', 
                    "Error al crear inventario para modelo ID: {$_POST['id_modelo']}"
                );
                
                http_response_code(500);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Error al crear el inventario'
                ]);
            }
        } catch (Exception $e) {
            // REGISTRAR EXCEPCIÓN
            RutasActividadesController::registrarRutaActividad(
                'INVENTARIO', 
                'EXCEPCION', 
                "Excepción al crear inventario: " . $e->getMessage()
            );
            
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
        
        // REGISTRAR CONSULTA DE INVENTARIO
        RutasActividadesController::registrarRutaActividad(
            'INVENTARIO', 
            'CONSULTAR', 
            'Usuario consultó lista de inventario'
        );
        
        try {
            $inventario = Inventario::obtenerInventarioActivo();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Inventario obtenido correctamente',
                'data' => $inventario
            ]);
        } catch (Exception $e) {
            // REGISTRAR ERROR EN CONSULTA
            RutasActividadesController::registrarRutaActividad(
                'INVENTARIO', 
                'ERROR_CONSULTAR', 
                "Error al consultar inventario: " . $e->getMessage()
            );
            
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

        // Validaciones con registro de actividades
        if (empty($_POST['id_modelo'])) {
            // REGISTRAR ERROR DE VALIDACIÓN EN ACTUALIZACIÓN
            RutasActividadesController::registrarRutaActividad(
                'INVENTARIO', 
                'ERROR_VALIDACION', 
                "Intento de actualizar inventario ID $id sin modelo"
            );
            
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe seleccionar un modelo'
            ]);
            return;
        }

        if (empty($_POST['precio_compra']) || $_POST['precio_compra'] <= 0) {
            RutasActividadesController::registrarRutaActividad(
                'INVENTARIO', 
                'ERROR_VALIDACION', 
                "Intento de actualizar inventario ID $id con precio de compra inválido: " . ($_POST['precio_compra'] ?? 'vacío')
            );
            
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El precio de compra debe ser mayor a 0'
            ]);
            return;
        }

        if (empty($_POST['precio_venta']) || $_POST['precio_venta'] <= 0) {
            RutasActividadesController::registrarRutaActividad(
                'INVENTARIO', 
                'ERROR_VALIDACION', 
                "Intento de actualizar inventario ID $id con precio de venta inválido: " . ($_POST['precio_venta'] ?? 'vacío')
            );
            
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El precio de venta debe ser mayor a 0'
            ]);
            return;
        }

        // Sanitizar datos
        $_POST['id_modelo'] = filter_var($_POST['id_modelo'], FILTER_SANITIZE_NUMBER_INT);
        $_POST['estado_celular'] = trim(htmlspecialchars($_POST['estado_celular'] ?? 'nuevo'));
        $_POST['precio_compra'] = filter_var($_POST['precio_compra'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $_POST['precio_venta'] = filter_var($_POST['precio_venta'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $_POST['estado_inventario'] = trim(htmlspecialchars($_POST['estado_inventario'] ?? 'disponible'));

        try {
            // OBTENER INFORMACIÓN ANTERIOR PARA EL LOG
            $inventarioAnterior = Inventario::find($id);
            $infoModeloAnterior = null;
            $infoModeloNuevo = null;
            
            if ($inventarioAnterior) {
                $infoModeloAnterior = self::fetchFirst("SELECT m.nombre_modelo, ma.nombre_marca 
                                                       FROM modelos m 
                                                       INNER JOIN marcas ma ON m.id_marca = ma.id_marca 
                                                       WHERE m.id_modelo = {$inventarioAnterior->id_modelo}");
            }
            
            $infoModeloNuevo = self::fetchFirst("SELECT m.nombre_modelo, ma.nombre_marca 
                                               FROM modelos m 
                                               INNER JOIN marcas ma ON m.id_marca = ma.id_marca 
                                               WHERE m.id_modelo = {$_POST['id_modelo']}");
            
            $inventario = Inventario::find($id);
            $inventario->sincronizar([
                'id_modelo' => $_POST['id_modelo'],
                'estado_celular' => $_POST['estado_celular'],
                'precio_compra' => $_POST['precio_compra'],
                'precio_venta' => $_POST['precio_venta'],
                'estado_inventario' => $_POST['estado_inventario'],
                'situacion' => 1
            ]);

            $resultado = $inventario->actualizar();

            // REGISTRAR ACTUALIZACIÓN EXITOSA
            $productoAnterior = $infoModeloAnterior ? 
                ($infoModeloAnterior['nombre_marca'] . ' ' . $infoModeloAnterior['nombre_modelo']) : 
                'Producto desconocido';
            
            $productoNuevo = $infoModeloNuevo ? 
                ($infoModeloNuevo['nombre_marca'] . ' ' . $infoModeloNuevo['nombre_modelo']) : 
                'Producto desconocido';
            
            $cambios = [];
            if ($inventarioAnterior) {
                if ($inventarioAnterior->id_modelo != $_POST['id_modelo']) {
                    $cambios[] = "Producto: '$productoAnterior' → '$productoNuevo'";
                }
                if ($inventarioAnterior->estado_celular != $_POST['estado_celular']) {
                    $cambios[] = "Estado: '{$inventarioAnterior->estado_celular}' → '{$_POST['estado_celular']}'";
                }
                if ($inventarioAnterior->precio_compra != $_POST['precio_compra']) {
                    $cambios[] = "Precio Compra: 'Q.{$inventarioAnterior->precio_compra}' → 'Q.{$_POST['precio_compra']}'";
                }
                if ($inventarioAnterior->precio_venta != $_POST['precio_venta']) {
                    $cambios[] = "Precio Venta: 'Q.{$inventarioAnterior->precio_venta}' → 'Q.{$_POST['precio_venta']}'";
                }
                if ($inventarioAnterior->estado_inventario != $_POST['estado_inventario']) {
                    $cambios[] = "Estado Inventario: '{$inventarioAnterior->estado_inventario}' → '{$_POST['estado_inventario']}'";
                }
            }
            
            $descripcionCambios = empty($cambios) ? 
                "Actualizó inventario ID $id: $productoNuevo" : 
                "Actualizó inventario ID $id: " . implode(', ', $cambios);
            
            RutasActividadesController::registrarRutaActividad(
                'INVENTARIO', 
                'ACTUALIZAR', 
                $descripcionCambios
            );

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Inventario modificado correctamente'
            ]);
        } catch (Exception $e) {
            // REGISTRAR ERROR EN ACTUALIZACIÓN
            RutasActividadesController::registrarRutaActividad(
                'INVENTARIO', 
                'ERROR_ACTUALIZAR', 
                "Error al actualizar inventario ID $id: " . $e->getMessage()
            );
            
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
            
            // OBTENER INFORMACIÓN DEL INVENTARIO ANTES DE ELIMINAR
            $inventario = Inventario::find($id);
            $descripcionProducto = "Inventario ID: $id";
            
            if ($inventario) {
                $infoModelo = self::fetchFirst("SELECT m.nombre_modelo, ma.nombre_marca 
                                              FROM modelos m 
                                              INNER JOIN marcas ma ON m.id_marca = ma.id_marca 
                                              WHERE m.id_modelo = {$inventario->id_modelo}");
                
                if ($infoModelo) {
                    $descripcionProducto = $infoModelo['nombre_marca'] . ' ' . $infoModelo['nombre_modelo'] . 
                                         " (Estado: {$inventario->estado_celular}, P.Venta: Q.{$inventario->precio_venta})";
                }
            }
            
            Inventario::EliminarInventario($id);

            // REGISTRAR ELIMINACIÓN EXITOSA
            RutasActividadesController::registrarRutaActividad(
                'INVENTARIO', 
                'ELIMINAR', 
                "Eliminó inventario: $descripcionProducto"
            );

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'El inventario ha sido eliminado correctamente'
            ]);
        } catch (Exception $e) {
            // REGISTRAR ERROR EN ELIMINACIÓN
            RutasActividadesController::registrarRutaActividad(
                'INVENTARIO', 
                'ERROR_ELIMINAR', 
                "Error al eliminar inventario ID $id: " . $e->getMessage()
            );
            
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
        
        // REGISTRAR CONSULTA DE MARCAS PARA INVENTARIO
        RutasActividadesController::registrarRutaActividad(
            'INVENTARIO', 
            'CONSULTAR_MARCAS', 
            'Usuario consultó marcas para inventario'
        );
        
        try {
            $marcas = Inventario::obtenerMarcasActivas();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Marcas obtenidas correctamente',
                'data' => $marcas
            ]);
        } catch (Exception $e) {
            // REGISTRAR ERROR EN CONSULTA DE MARCAS
            RutasActividadesController::registrarRutaActividad(
                'INVENTARIO', 
                'ERROR_CONSULTAR_MARCAS', 
                "Error al consultar marcas para inventario: " . $e->getMessage()
            );
            
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
            
            // REGISTRAR CONSULTA DE MODELOS PARA INVENTARIO
            RutasActividadesController::registrarRutaActividad(
                'INVENTARIO', 
                'CONSULTAR_MODELOS', 
                "Usuario consultó modelos para inventario (Marca ID: $id_marca)"
            );
            
            $modelos = Inventario::obtenerModelosPorMarca($id_marca);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Modelos obtenidos correctamente',
                'data' => $modelos
            ]);
        } catch (Exception $e) {
            // REGISTRAR ERROR EN CONSULTA DE MODELOS
            $id_marca = $_GET['id_marca'] ?? 'no especificado';
            RutasActividadesController::registrarRutaActividad(
                'INVENTARIO', 
                'ERROR_CONSULTAR_MODELOS', 
                "Error al consultar modelos para inventario (Marca ID: $id_marca): " . $e->getMessage()
            );
            
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener los modelos',
                'detalle' => $e->getMessage()
            ]);
        }
    }
}