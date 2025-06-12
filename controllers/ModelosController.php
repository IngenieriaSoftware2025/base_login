<?php

namespace Controllers;

use Exception;
use MVC\Router;
use Model\ActiveRecord;
use Model\Modelos;

class ModelosController extends ActiveRecord
{
    public static function renderizarPagina(Router $router)
    {
        $router->render('modelos/index', []);
    }

    public static function guardarAPI()
    {
        getHeadersApi();

        // Validación marca
        if (empty($_POST['id_marca'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe seleccionar una marca'
            ]);
            return;
        }

        // Validación nombre modelo
        if (empty($_POST['nombre_modelo'])) {
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
            $modelo = new Modelos($_POST);
            $resultado = $modelo->crear();

            if ($resultado['resultado'] == 1) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Modelo registrado correctamente'
                ]);
            } else {
                throw new Exception('Error al crear el modelo');
            }
        } catch (Exception $e) {
            http_response_code(500);
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
        try {
            $modelos = Modelos::obtenerModelosActivos();

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

    public static function modificarAPI()
    {
        getHeadersApi();

        $id = $_POST['id_modelo'];

        // Validaciones
        if (empty($_POST['id_marca'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe seleccionar una marca'
            ]);
            return;
        }

        if (empty($_POST['nombre_modelo'])) {
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
            $modelo = Modelos::find($id);
            $modelo->sincronizar([
                'id_marca' => $_POST['id_marca'],
                'nombre_modelo' => $_POST['nombre_modelo'],
                'color' => $_POST['color'],
                'situacion' => 1
            ]);

            $resultado = $modelo->actualizar();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Modelo modificado correctamente'
            ]);
        } catch (Exception $e) {
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
            Modelos::EliminarModelo($id);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'El modelo ha sido eliminado correctamente'
            ]);
        } catch (Exception $e) {
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
        try {
            $marcas = Modelos::obtenerMarcasActivas();

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
}