<?php

namespace Controllers;

use Exception;
use MVC\Router;
use Model\ActiveRecord;
use Model\Marcas;
use Controllers\RutasActividadesController;

class MarcasController extends ActiveRecord
{
    public static function renderizarPagina(Router $router)
    {
        // Registrar acceso al módulo
        RutasActividadesController::registrarRutaActividad(
            'MARCAS', 
            'ACCEDER', 
            'Usuario accedió al módulo de marcas'
        );
        
        $router->render('marcas/index', []);
    }

    public static function guardarAPI()
    {
        getHeadersApi();

        // Validaciones existentes...
        if (empty($_POST['nombre_marca'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El nombre de la marca es obligatorio'
            ]);
            return;
        }

        if (empty($_POST['descripcion'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La descripción de la marca es obligatoria'
            ]);
            return;
        }

        // Sanitizar datos
        $_POST['nombre_marca'] = ucwords(strtolower(trim(htmlspecialchars($_POST['nombre_marca']))));
        $_POST['descripcion'] = trim(htmlspecialchars($_POST['descripcion'] ?? ''));

        // Verificar si existe
        $marcaExistente = Marcas::where('nombre_marca', $_POST['nombre_marca']);
        if (count($marcaExistente) > 0) {
            // Registrar intento de duplicar
            RutasActividadesController::registrarRutaActividad(
                'MARCAS', 
                'INTENTO_DUPLICAR', 
                "Intentó crear marca duplicada: {$_POST['nombre_marca']}"
            );
            
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Esta marca ya existe en el sistema'
            ]);
            return;
        }

        try {
            $marca = new Marcas($_POST);
            $resultado = $marca->crear();

            if ($resultado['resultado'] == 1) {
                // Registrar creación exitosa
                RutasActividadesController::registrarRutaActividad(
                    'MARCAS', 
                    'CREAR', 
                    "Creó nueva marca: {$_POST['nombre_marca']} (ID: {$resultado['id']})"
                );
                
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Marca registrada correctamente'
                ]);
            } else {
                // Registrar error
                RutasActividadesController::registrarRutaActividad(
                    'MARCAS', 
                    'ERROR_CREAR', 
                    "Error al crear marca: {$_POST['nombre_marca']}"
                );
                
                http_response_code(500);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Error al crear la marca'
                ]);
            }
        } catch (Exception $e) {
            // Registrar excepción
            RutasActividadesController::registrarRutaActividad(
                'MARCAS', 
                'EXCEPCION', 
                "Excepción al crear marca: " . $e->getMessage()
            );
            
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al registrar la marca',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public static function buscarAPI()
    {
        getHeadersApi();
        
        // Registrar consulta
        RutasActividadesController::registrarRutaActividad(
            'MARCAS', 
            'CONSULTAR', 
            'Usuario consultó lista de marcas'
        );
        
        try {
            $marcas = Marcas::obtenerMarcasActivas();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Marcas obtenidas correctamente',
                'data' => $marcas
            ]);
        } catch (Exception $e) {
            RutasActividadesController::registrarRutaActividad(
                'MARCAS', 
                'ERROR_CONSULTAR', 
                "Error al consultar marcas: " . $e->getMessage()
            );
            
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener las marcas',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public static function modificarAPI()
    {
        getHeadersApi();

        $id = $_POST['id_marca'];

        // Validaciones existentes...
        
        try {
            $marca = Marcas::find($id);
            $nombreAnterior = $marca->nombre_marca;
            
            $marca->sincronizar([
                'nombre_marca' => $_POST['nombre_marca'],
                'descripcion' => $_POST['descripcion'],
                'situacion' => 1
            ]);

            $resultado = $marca->actualizar();

            // Registrar modificación
            RutasActividadesController::registrarRutaActividad(
                'MARCAS', 
                'ACTUALIZAR', 
                "Actualizó marca: '{$nombreAnterior}' → '{$_POST['nombre_marca']}' (ID: $id)"
            );

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Marca modificada correctamente'
            ]);
        } catch (Exception $e) {
            RutasActividadesController::registrarRutaActividad(
                'MARCAS', 
                'ERROR_ACTUALIZAR', 
                "Error al actualizar marca ID $id: " . $e->getMessage()
            );
            
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al modificar la marca',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public static function eliminarAPI()
    {
        getHeadersApi();
        try {
            $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
            
            // Obtener nombre antes de eliminar
            $marca = Marcas::find($id);
            $nombreMarca = $marca ? $marca->nombre_marca : "ID: $id";
            
            Marcas::EliminarMarca($id);

            // Registrar eliminación
            RutasActividadesController::registrarRutaActividad(
                'MARCAS', 
                'ELIMINAR', 
                "Eliminó marca: $nombreMarca (ID: $id)"
            );

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'La marca ha sido eliminada correctamente'
            ]);
        } catch (Exception $e) {
            RutasActividadesController::registrarRutaActividad(
                'MARCAS', 
                'ERROR_ELIMINAR', 
                "Error al eliminar marca ID $id: " . $e->getMessage()
            );
            
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al eliminar la marca',
                'detalle' => $e->getMessage()
            ]);
        }
    }
}