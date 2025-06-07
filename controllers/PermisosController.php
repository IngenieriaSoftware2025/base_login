<?php

namespace Controllers;

use MVC\Router;
use Model\ActiveRecord;
use Model\Permisos;
use Exception;

class PermisosController extends ActiveRecord
{

    public static function renderizarPagina(Router $router)
    {
        $router->render('permisos/index', []);
    }

    public static function guardarAPI()
    {
        getHeadersApi();

        // Validaciones
        if (empty($_POST['id_aplicacion'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe seleccionar una aplicación'
            ]);
            return;
        }

        if (empty($_POST['nombre_permiso'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El nombre del permiso es obligatorio'
            ]);
            return;
        }

        if (empty($_POST['clave_permiso'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La clave del permiso es obligatoria'
            ]);
            return;
        }

        if (empty($_POST['descripcion'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La descripción es obligatoria'
            ]);
            return;
        }

        try {
           
            $_POST['id_aplicacion'] = filter_var($_POST['id_aplicacion'], FILTER_SANITIZE_NUMBER_INT);
            $_POST['nombre_permiso'] = ucwords(strtolower(trim(htmlspecialchars($_POST['nombre_permiso']))));
            $_POST['clave_permiso'] = strtoupper(trim(htmlspecialchars($_POST['clave_permiso'])));
            $_POST['descripcion'] = trim(htmlspecialchars($_POST['descripcion']));

            $permiso = new Permisos([
                'id_aplicacion' => $_POST['id_aplicacion'],
                'nombre_permiso' => $_POST['nombre_permiso'],
                'clave_permiso' => $_POST['clave_permiso'],
                'descripcion' => $_POST['descripcion'],
                // 'fecha' => date('Y-m-d H:i:s'),
                'situacion' => 1
            ]);

            $crear = $permiso->crear();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Permiso guardado exitosamente'
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al guardar el permiso',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public static function buscarAPI()
{
    try {
        $consulta = "SELECT 
                        p.id_permiso,
                        p.id_aplicacion,
                        p.nombre_permiso,
                        p.clave_permiso,
                        p.descripcion,
                        p.fecha,
                        p.situacion,
                        a.nombre_app_md as aplicacion_nombre
                    FROM permisos p 
                    INNER JOIN aplicaciones a ON p.id_aplicacion = a.id_aplicacion 
                    WHERE p.situacion = 1 AND a.situacion = 1
                    ORDER BY p.nombre_permiso";
        
        $permisos = self::fetchArray($consulta);

        http_response_code(200);
        echo json_encode([
            'codigo' => 1,
            'mensaje' => 'Permisos obtenidos correctamente',
            'data' => $permisos
        ]);
        
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'codigo' => 0,
            'mensaje' => 'Error al obtener permisos',
            'detalle' => $e->getMessage()
        ]);
    }
}
    public static function ModificarAPI()
    {
        getHeadersApi();
        $id = $_POST['id_permiso'];

      
        if (empty($_POST['id_aplicacion'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe seleccionar una aplicación'
            ]);
            return;
        }

        if (empty($_POST['nombre_permiso'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El nombre del permiso es obligatorio'
            ]);
            return;
        }

        if (empty($_POST['clave_permiso'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La clave del permiso es obligatoria'
            ]);
            return;
        }

        if (empty($_POST['descripcion'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La descripción es obligatoria'
            ]);
            return;
        }

        try {
        
            $_POST['id_aplicacion'] = filter_var($_POST['id_aplicacion'], FILTER_SANITIZE_NUMBER_INT);
            $_POST['nombre_permiso'] = ucwords(strtolower(trim(htmlspecialchars($_POST['nombre_permiso']))));
            $_POST['clave_permiso'] = strtoupper(trim(htmlspecialchars($_POST['clave_permiso'])));
            $_POST['descripcion'] = trim(htmlspecialchars($_POST['descripcion']));

            $data = Permisos::find($id);
            $data->sincronizar([
                'id_aplicacion' => $_POST['id_aplicacion'],
                'nombre_permiso' => $_POST['nombre_permiso'],
                'clave_permiso' => $_POST['clave_permiso'],
                'descripcion' => $_POST['descripcion'],
                'situacion' => 1
               
            ]);
            
            $data->actualizar();
                
            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Éxito al actualizar'
            ]);
            
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al actualizar',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public static function EliminarAPI()
    {
        try {
            $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
            $ejecutar = Permisos::EliminarPermisos($id);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'El permiso ha sido eliminado correctamente'
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al eliminar',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function obtenerAplicacionesAPI()
    {
   
        try {
            $aplicaciones = Permisos::obtenerAplicaciones();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Aplicaciones obtenidas correctamente',
                'data' => $aplicaciones
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener aplicaciones',
                'detalle' => $e->getMessage()
            ]);
        }
    }
}