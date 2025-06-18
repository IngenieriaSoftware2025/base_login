<?php

namespace Controllers;

use Exception;
use MVC\Router;
use Model\ActiveRecord;
use Model\Modelos;
use Controllers\RutasActividadesController; // IMPORTAR EL CONTROLADOR DE ACTIVIDADES

class ModelosController extends ActiveRecord
{
    public static function renderizarPagina(Router $router)
    {
        // REGISTRAR ACCESO AL MÓDULO DE MODELOS
        RutasActividadesController::registrarRutaActividad(
            'MODELOS', 
            'ACCEDER', 
            'Usuario accedió al módulo de modelos'
        );
        
        $router->render('modelos/index', []);
    }

    public static function guardarAPI()
    {
        getHeadersApi();

        // Validación marca
        if (empty($_POST['id_marca'])) {
            // REGISTRAR INTENTO DE CREAR SIN MARCA
            RutasActividadesController::registrarRutaActividad(
                'MODELOS', 
                'ERROR_VALIDACION', 
                'Intento de crear modelo sin seleccionar marca'
            );
            
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe seleccionar una marca'
            ]);
            return;
        }

        // Validación nombre modelo
        if (empty($_POST['nombre_modelo'])) {
            // REGISTRAR INTENTO DE CREAR SIN NOMBRE
            RutasActividadesController::registrarRutaActividad(
                'MODELOS', 
                'ERROR_VALIDACION', 
                'Intento de crear modelo sin nombre'
            );
            
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El nombre del modelo es obligatorio'
            ]);
            return;
        }

        // Sanitizar datos
        $_POST['id_marca'] = filter_var($_POST['id_marca'], FILTER_SANITIZE_NUMBER_INT);
        $_POST['nombre_modelo'] = ucwords(strtolower(trim(htmlspecialchars($_POST['nombre_modelo']))));
        $_POST['color'] = ucwords(strtolower(trim(htmlspecialchars($_POST['color'] ?? ''))));

        try {
            // OBTENER INFORMACIÓN DE LA MARCA PARA EL LOG
            $infoMarca = self::fetchFirst("SELECT nombre_marca FROM marcas WHERE id_marca = {$_POST['id_marca']}");
            
            $modelo = new Modelos($_POST);
            $resultado = $modelo->crear();

            if ($resultado['resultado'] == 1) {
                // REGISTRAR CREACIÓN EXITOSA
                $nombreMarca = $infoMarca['nombre_marca'] ?? 'Marca desconocida';
                $descripcionModelo = $nombreMarca . ' ' . $_POST['nombre_modelo'];
                
                if (!empty($_POST['color'])) {
                    $descripcionModelo .= ' - ' . $_POST['color'];
                }
                
                RutasActividadesController::registrarRutaActividad(
                    'MODELOS', 
                    'CREAR', 
                    "Creó modelo exitosamente: $descripcionModelo (ID: {$resultado['id']}, Marca ID: {$_POST['id_marca']})"
                );
                
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Modelo registrado correctamente'
                ]);
            } else {
                // REGISTRAR ERROR EN CREACIÓN
                RutasActividadesController::registrarRutaActividad(
                    'MODELOS', 
                    'ERROR_CREAR', 
                    "Error al crear modelo: {$_POST['nombre_modelo']} (Marca ID: {$_POST['id_marca']})"
                );
                
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Error al crear el modelo'
                ]);
            }
        } catch (Exception $e) {
            // REGISTRAR EXCEPCIÓN
            RutasActividadesController::registrarRutaActividad(
                'MODELOS', 
                'EXCEPCION', 
                "Excepción al crear modelo '{$_POST['nombre_modelo']}': " . $e->getMessage()
            );
            
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al registrar el modelo',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public static function buscarAPI()
    {
        getHeadersApi();
        
        // REGISTRAR CONSULTA DE MODELOS
        RutasActividadesController::registrarRutaActividad(
            'MODELOS', 
            'CONSULTAR', 
            'Usuario consultó lista de modelos'
        );
        
        try {
            $modelos = Modelos::obtenerModelosActivos();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Modelos obtenidos correctamente',
                'data' => $modelos
            ]);
        } catch (Exception $e) {
            // REGISTRAR ERROR EN CONSULTA
            RutasActividadesController::registrarRutaActividad(
                'MODELOS', 
                'ERROR_CONSULTAR', 
                "Error al consultar modelos: " . $e->getMessage()
            );
            
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener los modelos',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public static function modificarAPI()
    {
        getHeadersApi();

        $id = $_POST['id_modelo'];

        // Validaciones con registro de actividades
        if (empty($_POST['id_marca'])) {
            // REGISTRAR ERROR DE VALIDACIÓN EN ACTUALIZACIÓN
            RutasActividadesController::registrarRutaActividad(
                'MODELOS', 
                'ERROR_VALIDACION', 
                "Intento de actualizar modelo ID $id sin marca"
            );
            
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe seleccionar una marca'
            ]);
            return;
        }

        if (empty($_POST['nombre_modelo'])) {
            RutasActividadesController::registrarRutaActividad(
                'MODELOS', 
                'ERROR_VALIDACION', 
                "Intento de actualizar modelo ID $id sin nombre"
            );
            
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El nombre del modelo es obligatorio'
            ]);
            return;
        }

        // Sanitizar datos
        $_POST['id_marca'] = filter_var($_POST['id_marca'], FILTER_SANITIZE_NUMBER_INT);
        $_POST['nombre_modelo'] = ucwords(strtolower(trim(htmlspecialchars($_POST['nombre_modelo']))));
        $_POST['color'] = ucwords(strtolower(trim(htmlspecialchars($_POST['color'] ?? ''))));

        try {
            // OBTENER INFORMACIÓN ANTERIOR PARA EL LOG
            $modeloAnterior = Modelos::find($id);
            $infoMarcaAnterior = null;
            $infoMarcaNueva = null;
            
            if ($modeloAnterior) {
                $infoMarcaAnterior = self::fetchFirst("SELECT nombre_marca FROM marcas WHERE id_marca = {$modeloAnterior->id_marca}");
            }
            
            $infoMarcaNueva = self::fetchFirst("SELECT nombre_marca FROM marcas WHERE id_marca = {$_POST['id_marca']}");
            
            $modelo = Modelos::find($id);
            $modelo->sincronizar([
                'id_marca' => $_POST['id_marca'],
                'nombre_modelo' => $_POST['nombre_modelo'],
                'color' => $_POST['color'],
                'situacion' => 1
            ]);

            $resultado = $modelo->actualizar();

            // REGISTRAR ACTUALIZACIÓN EXITOSA
            $marcaAnterior = $infoMarcaAnterior['nombre_marca'] ?? 'Marca desconocida';
            $marcaNueva = $infoMarcaNueva['nombre_marca'] ?? 'Marca desconocida';
            
            $cambios = [];
            if ($modeloAnterior) {
                // Verificar cambios en marca
                if ($modeloAnterior->id_marca != $_POST['id_marca']) {
                    $cambios[] = "Marca: '$marcaAnterior' → '$marcaNueva'";
                }
                
                // Verificar cambios en nombre
                if ($modeloAnterior->nombre_modelo != $_POST['nombre_modelo']) {
                    $cambios[] = "Nombre: '{$modeloAnterior->nombre_modelo}' → '{$_POST['nombre_modelo']}'";
                }
                
                // Verificar cambios en color
                $colorAnterior = $modeloAnterior->color ?? '';
                $colorNuevo = $_POST['color'] ?? '';
                if ($colorAnterior != $colorNuevo) {
                    $colorAnteriorTexto = empty($colorAnterior) ? 'Sin color' : $colorAnterior;
                    $colorNuevoTexto = empty($colorNuevo) ? 'Sin color' : $colorNuevo;
                    $cambios[] = "Color: '$colorAnteriorTexto' → '$colorNuevoTexto'";
                }
            }
            
            $descripcionModelo = $marcaNueva . ' ' . $_POST['nombre_modelo'];
            if (!empty($_POST['color'])) {
                $descripcionModelo .= ' - ' . $_POST['color'];
            }
            
            $descripcionCambios = empty($cambios) ? 
                "Actualizó modelo ID $id: $descripcionModelo" : 
                "Actualizó modelo ID $id: " . implode(', ', $cambios);
            
            RutasActividadesController::registrarRutaActividad(
                'MODELOS', 
                'ACTUALIZAR', 
                $descripcionCambios
            );

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Modelo modificado correctamente'
            ]);
        } catch (Exception $e) {
            // REGISTRAR ERROR EN ACTUALIZACIÓN
            RutasActividadesController::registrarRutaActividad(
                'MODELOS', 
                'ERROR_ACTUALIZAR', 
                "Error al actualizar modelo ID $id: " . $e->getMessage()
            );
            
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al modificar el modelo',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public static function eliminarAPI()
    {
        getHeadersApi();
        try {
            $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
            
            // OBTENER INFORMACIÓN DEL MODELO ANTES DE ELIMINAR
            $modelo = Modelos::find($id);
            $descripcionModelo = "Modelo ID: $id";
            
            if ($modelo) {
                $infoMarca = self::fetchFirst("SELECT nombre_marca FROM marcas WHERE id_marca = {$modelo->id_marca}");
                $nombreMarca = $infoMarca['nombre_marca'] ?? 'Marca desconocida';
                
                $descripcionModelo = $nombreMarca . ' ' . $modelo->nombre_modelo;
                if (!empty($modelo->color)) {
                    $descripcionModelo .= ' - ' . $modelo->color;
                }
            }
            
            Modelos::EliminarModelo($id);

            // REGISTRAR ELIMINACIÓN EXITOSA
            RutasActividadesController::registrarRutaActividad(
                'MODELOS', 
                'ELIMINAR', 
                "Eliminó modelo: $descripcionModelo"
            );

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'El modelo ha sido eliminado correctamente'
            ]);
        } catch (Exception $e) {
            // REGISTRAR ERROR EN ELIMINACIÓN
            $id = $_GET['id'] ?? 'no especificado';
            RutasActividadesController::registrarRutaActividad(
                'MODELOS', 
                'ERROR_ELIMINAR', 
                "Error al eliminar modelo ID $id: " . $e->getMessage()
            );
            
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al eliminar el modelo',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public static function obtenerMarcasAPI()
    {
        getHeadersApi();
        
        // REGISTRAR CONSULTA DE MARCAS PARA MODELOS
        RutasActividadesController::registrarRutaActividad(
            'MODELOS', 
            'CONSULTAR_MARCAS', 
            'Usuario consultó marcas para modelos'
        );
        
        try {
            $marcas = Modelos::obtenerMarcasActivas();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Marcas obtenidas correctamente',
                'data' => $marcas
            ]);
        } catch (Exception $e) {
            // REGISTRAR ERROR EN CONSULTA DE MARCAS
            RutasActividadesController::registrarRutaActividad(
                'MODELOS', 
                'ERROR_CONSULTAR_MARCAS', 
                "Error al consultar marcas para modelos: " . $e->getMessage()
            );
            
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener las marcas',
                'detalle' => $e->getMessage()
            ]);
        }
    }
}