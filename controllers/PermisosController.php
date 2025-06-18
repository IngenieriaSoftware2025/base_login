<?php

namespace Controllers;

use MVC\Router;
use Model\ActiveRecord;
use Model\Permisos;
use Controllers\RutasActividadesController; // IMPORTAR EL CONTROLADOR DE ACTIVIDADES
use Exception;

class PermisosController extends ActiveRecord
{

    public static function renderizarPagina(Router $router)
    {
        //REGISTRAR ACCESO AL MÓDULO DE PERMISOS
        RutasActividadesController::registrarRutaActividad(
            'PERMISOS', 
            'ACCEDER', 
            'Usuario accedió al módulo de permisos'
        );
        
        $router->render('permisos/index', []);
    }

    public static function guardarAPI()
    {
        getHeadersApi();

        try {
            // Validación nombre del permiso
            if (empty($_POST['nombre_permiso'])) {
                //REGISTRAR INTENTO DE CREAR SIN NOMBRE
                RutasActividadesController::registrarRutaActividad(
                    'PERMISOS', 
                    'ERROR_VALIDACION', 
                    'Intento de crear permiso sin nombre'
                );
                
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El nombre del permiso es obligatorio'
                ]);
                return;
            }

            // Validación descripción
            if (empty($_POST['descripcion'])) {
                //REGISTRAR INTENTO DE CREAR SIN DESCRIPCIÓN
                RutasActividadesController::registrarRutaActividad(
                    'PERMISOS', 
                    'ERROR_VALIDACION', 
                    'Intento de crear permiso sin descripción'
                );
                
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'La descripción es obligatoria'
                ]);
                return;
            }

            // Sanitizar datos
            $_POST['nombre_permiso'] = ucfirst(strtolower(trim($_POST['nombre_permiso'])));
            $_POST['descripcion'] = ucfirst(strtolower(trim($_POST['descripcion'])));

            // Verificar si ya existe un permiso con el mismo nombre
            $permisoExistente = self::fetchFirst("SELECT id_permiso FROM permisos WHERE nombre_permiso = '" . $_POST['nombre_permiso'] . "' AND situacion = 1");

            if ($permisoExistente) {
                //REGISTRAR INTENTO DE DUPLICAR PERMISO
                RutasActividadesController::registrarRutaActividad(
                    'PERMISOS', 
                    'INTENTO_DUPLICAR', 
                    "Intentó crear permiso duplicado: '{$_POST['nombre_permiso']}'"
                );
                
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Ya existe un permiso con ese nombre'
                ]);
                return;
            }

            // Crear el permiso
            $permiso = new Permisos($_POST);
            $resultado = $permiso->crear();

            if ($resultado['resultado'] == 1) {
                //REGISTRAR CREACIÓN EXITOSA
                RutasActividadesController::registrarRutaActividad(
                    'PERMISOS', 
                    'CREAR', 
                    "Creó permiso exitosamente: '{$_POST['nombre_permiso']}' (ID: {$resultado['id']}) - Descripción: '{$_POST['descripcion']}'"
                );
                
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Permiso guardado correctamente'
                ]);
            } else {
                //REGISTRAR ERROR EN CREACIÓN
                RutasActividadesController::registrarRutaActividad(
                    'PERMISOS', 
                    'ERROR_CREAR', 
                    "Error al crear permiso: '{$_POST['nombre_permiso']}'"
                );
                
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Error al guardar el permiso'
                ]);
            }
        } catch (Exception $e) {
            //REGISTRAR EXCEPCIÓN
            $nombrePermiso = $_POST['nombre_permiso'] ?? 'no especificado';
            RutasActividadesController::registrarRutaActividad(
                'PERMISOS', 
                'EXCEPCION', 
                "Excepción al crear permiso '$nombrePermiso': " . $e->getMessage()
            );
            
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al guardar el permiso',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public static function buscarAPI()
    {
        getHeadersApi();

        //REGISTRAR CONSULTA DE PERMISOS
        RutasActividadesController::registrarRutaActividad(
            'PERMISOS', 
            'CONSULTAR', 
            'Usuario consultó lista de permisos'
        );

        try {
            $permisos = Permisos::obtenerPermisosActivos();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Permisos obtenidos correctamente',
                'data' => $permisos
            ]);
        } catch (Exception $e) {
            //REGISTRAR ERROR EN CONSULTA
            RutasActividadesController::registrarRutaActividad(
                'PERMISOS', 
                'ERROR_CONSULTAR', 
                "Error al consultar permisos: " . $e->getMessage()
            );
            
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al buscar permisos',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public static function modificarAPI()
    {
        getHeadersApi();

        try {
            $id = $_POST['id_permiso'];

            // Validaciones con registro de actividades
            if (empty($_POST['nombre_permiso'])) {
                //REGISTRAR ERROR DE VALIDACIÓN EN ACTUALIZACIÓN
                RutasActividadesController::registrarRutaActividad(
                    'PERMISOS', 
                    'ERROR_VALIDACION', 
                    "Intento de actualizar permiso ID $id sin nombre"
                );
                
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El nombre del permiso es obligatorio'
                ]);
                return;
            }

            if (empty($_POST['descripcion'])) {
                RutasActividadesController::registrarRutaActividad(
                    'PERMISOS', 
                    'ERROR_VALIDACION', 
                    "Intento de actualizar permiso ID $id sin descripción"
                );
                
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'La descripción es obligatoria'
                ]);
                return;
            }

            // Sanitizar datos
            $_POST['nombre_permiso'] = ucfirst(strtolower(trim($_POST['nombre_permiso'])));
            $_POST['descripcion'] = ucfirst(strtolower(trim($_POST['descripcion'])));

            // Verificar si ya existe otro permiso con el mismo nombre (excluyendo el actual)
            $permisoExistente = self::fetchFirst("SELECT id_permiso FROM permisos WHERE nombre_permiso = '" . $_POST['nombre_permiso'] . "' AND situacion = 1 AND id_permiso != $id");

            if ($permisoExistente) {
                //REGISTRAR INTENTO DE DUPLICAR EN ACTUALIZACIÓN
                RutasActividadesController::registrarRutaActividad(
                    'PERMISOS', 
                    'INTENTO_DUPLICAR', 
                    "Intentó actualizar permiso ID $id con nombre ya existente: '{$_POST['nombre_permiso']}'"
                );
                
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Ya existe otro permiso con ese nombre'
                ]);
                return;
            }

            //OBTENER INFORMACIÓN ANTERIOR PARA EL LOG
            $permisoAnterior = Permisos::find($id);
            $nombreAnterior = $permisoAnterior ? $permisoAnterior->nombre_permiso : "Permiso ID $id";
            $descripcionAnterior = $permisoAnterior ? $permisoAnterior->descripcion : "Descripción desconocida";

            // Actualizar el permiso
            $permiso = Permisos::find($id);
            $permiso->sincronizar([
                'nombre_permiso' => $_POST['nombre_permiso'],
                'descripcion' => $_POST['descripcion'],
                'situacion' => 1
            ]);

            $resultado = $permiso->actualizar();

            //REGISTRAR ACTUALIZACIÓN EXITOSA
            $cambios = [];
            
            // Verificar cambios en nombre
            if ($nombreAnterior != $_POST['nombre_permiso']) {
                $cambios[] = "Nombre: '$nombreAnterior' → '{$_POST['nombre_permiso']}'";
            }
            
            // Verificar cambios en descripción
            if ($descripcionAnterior != $_POST['descripcion']) {
                $cambios[] = "Descripción: '$descripcionAnterior' → '{$_POST['descripcion']}'";
            }
            
            $descripcionCambios = empty($cambios) ? 
                "Actualizó permiso ID $id: '{$_POST['nombre_permiso']}'" : 
                "Actualizó permiso ID $id: " . implode(', ', $cambios);
            
            RutasActividadesController::registrarRutaActividad(
                'PERMISOS', 
                'ACTUALIZAR', 
                $descripcionCambios
            );

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Permiso modificado correctamente'
            ]);
        } catch (Exception $e) {
            //REGISTRAR ERROR EN ACTUALIZACIÓN
            $id = $_POST['id_permiso'] ?? 'no especificado';
            RutasActividadesController::registrarRutaActividad(
                'PERMISOS', 
                'ERROR_ACTUALIZAR', 
                "Error al actualizar permiso ID $id: " . $e->getMessage()
            );
            
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al modificar el permiso',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public static function eliminarAPI()
    {
        getHeadersApi();

        try {
            $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
            
            //  OBTENER INFORMACIÓN DEL PERMISO ANTES DE ELIMINAR
            $permiso = Permisos::find($id);
            $descripcionPermiso = $permiso ? 
                "'{$permiso->nombre_permiso}' - {$permiso->descripcion}" : 
                "Permiso ID: $id";
            
            Permisos::EliminarPermiso($id);

            //  REGISTRAR ELIMINACIÓN EXITOSA
            RutasActividadesController::registrarRutaActividad(
                'PERMISOS', 
                'ELIMINAR', 
                "Eliminó permiso: $descripcionPermiso"
            );

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Permiso eliminado correctamente'
            ]);
        } catch (Exception $e) {
            //REGISTRAR ERROR EN ELIMINACIÓN
            $id = $_GET['id'] ?? 'no especificado';
            RutasActividadesController::registrarRutaActividad(
                'PERMISOS', 
                'ERROR_ELIMINAR', 
                "Error al eliminar permiso ID $id: " . $e->getMessage()
            );
            
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al eliminar el permiso',
                'detalle' => $e->getMessage()
            ]);
        }
    }
}