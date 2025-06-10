<?php

namespace Controllers;

use MVC\Router;
use Model\ActiveRecord;
use Model\Marcas;
use Exception;

class MarcasController extends ActiveRecord
{
    public static function renderizarPagina(Router $router)
    {
        $router->render('marcas/index', []);
    }

    public static function guardarAPI()
    {
        getHeadersApi();

        // Validaciones
        if (empty($_POST['marca_nombre'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El nombre de la marca es obligatorio'
            ]);
            return;
        }

        try {
            $_POST['marca_nombre'] = ucwords(strtolower(trim(htmlspecialchars($_POST['marca_nombre']))));
            $_POST['marca_descripcion'] = trim(htmlspecialchars($_POST['marca_descripcion'] ?? ''));
            $_POST['marca_modelo'] = trim(htmlspecialchars($_POST['marca_modelo'] ?? ''));

            $marca = new Marcas([
                'marca_nombre' => $_POST['marca_nombre'],
                'marca_descripcion' => $_POST['marca_descripcion'],
                'marca_modelo' => $_POST['marca_modelo'],
                'marca_situacion' => 1
            ]);

            $crear = $marca->crear();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Marca guardada exitosamente'
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al guardar la marca',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public static function buscarAPI()
    {
        getHeadersApi();
        try {
            $consulta = "SELECT * FROM marcas WHERE marca_situacion = 1 ORDER BY marca_nombre";
            $marcas = self::fetchArray($consulta);

            if (count($marcas) > 0) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Marcas obtenidas correctamente',
                    'data' => $marcas
                ]);
            } else {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'No hay marcas registradas',
                    'data' => []
                ]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener marcas',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public static function modificarAPI()
    {
        getHeadersApi();
        $id = $_POST['id_marca'];

        if (empty($_POST['marca_nombre'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El nombre de la marca es obligatorio'
            ]);
            return;
        }

        try {
            $_POST['marca_nombre'] = ucwords(strtolower(trim(htmlspecialchars($_POST['marca_nombre']))));
            $_POST['marca_descripcion'] = trim(htmlspecialchars($_POST['marca_descripcion'] ?? ''));
            $_POST['marca_modelo'] = trim(htmlspecialchars($_POST['marca_modelo'] ?? ''));

            $data = Marcas::find($id);
            $data->sincronizar([
                'marca_nombre' => $_POST['marca_nombre'],
                'marca_descripcion' => $_POST['marca_descripcion'],
                'marca_modelo' => $_POST['marca_modelo'],
                'marca_situacion' => 1
            ]);
            
            $data->actualizar();
                
            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Marca actualizada exitosamente'
            ]);
            
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al actualizar la marca',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public static function eliminarAPI()
    {
        try {
            $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
            Marcas::EliminarMarca($id);
            
            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'La marca ha sido eliminada correctamente'
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al eliminar la marca',
                'detalle' => $e->getMessage()
            ]);
        }
    }
}