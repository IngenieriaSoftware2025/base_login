<?php

namespace Controllers;

use Exception;
use MVC\Router;
use Model\ActiveRecord;
use Model\Marcas;

class MarcasController extends ActiveRecord
{
    public static function renderizarPagina(Router $router)
    {
        $router->render('marcas/index', []);
    }

    public static function guardarAPI()
    {
        getHeadersApi();

        // Validación nombre marca
        if (empty($_POST['nombre_marca'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El nombre de la marca es obligatorio'
            ]);
            return;
        }

        // Validación descripción
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

        // Verificar si la marca ya existe
        $marcaExistente = Marcas::where('nombre_marca', $_POST['nombre_marca']);
        if (count($marcaExistente) > 0) {
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
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Marca registrada correctamente'
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Error al crear la marca'
                ]);
            }
        } catch (Exception $e) {
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
        try {
            $marcas = Marcas::obtenerMarcasActivas();

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

    public static function modificarAPI()
    {
        getHeadersApi();

        $id = $_POST['id_marca'];

        // Validaciones
        if (empty($_POST['nombre_marca'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El nombre de la marca es obligatorio'
            ]);
            return;
        }


        // Validación descripción
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

        try {
            $marca = Marcas::find($id);
            $marca->sincronizar([
                'nombre_marca' => $_POST['nombre_marca'],
                'descripcion' => $_POST['descripcion'],
                'situacion' => 1
            ]);

            $resultado = $marca->actualizar();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Marca modificada correctamente'
            ]);
        } catch (Exception $e) {
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
