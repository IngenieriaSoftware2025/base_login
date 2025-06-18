<?php

namespace Controllers;

use Exception;
use MVC\Router;
use Model\ActiveRecord;
use Model\RolPermiso;
use Model\Usuarios;
use Model\Roles;
use Model\Permisos;

class RolesPermisosController extends ActiveRecord
{

    public static function renderizarPagina(Router $router)
    {
        $router->render('rolesPermisos/index', []);
    }

    public static function guardarAPI()
    {
        getHeadersApi();
        
        // Validar rol
        if (empty($_POST['id_rol'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe seleccionar un rol'
            ]);
            exit;
        }
        
        // Validar permiso
        if (empty($_POST['id_permiso'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe seleccionar un permiso'
            ]);
            exit;
        }
        
        // Validar usuario que asigna
        if (empty($_POST['usuario_asigna'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe indicar el usuario que asigna'
            ]);
            exit;
        }
        
        // Sanitizar motivo
        $_POST['motivo_asignacion'] = ucfirst(strtolower(trim(htmlspecialchars($_POST['motivo_asignacion']))));
        
        if (strlen($_POST['motivo_asignacion']) < 5) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El motivo debe tener más de 4 caracteres'
            ]);
            exit;
        }
        
        // Verificar si ya existe la asignación
        $asignacionExistente = RolPermiso::fetchFirst("SELECT * FROM roles_permisos WHERE id_rol = {$_POST['id_rol']} AND id_permiso = {$_POST['id_permiso']} AND situacion = 1");
        if ($asignacionExistente) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Este rol ya tiene asignado este permiso'
            ]);
            exit;
        }
        
        $rolPermiso = new RolPermiso($_POST);
        $resultado = $rolPermiso->crear();

        if($resultado['resultado'] == 1){
            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Permiso asignado al rol correctamente',
            ]);
            exit;
        } else {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al asignar el permiso al rol',
            ]);
            exit;
        }
    }

    public static function buscarAPI()
    {
        getHeadersApi();
        
        try {
       
            $sql = "SELECT rp.*, 
                           r.nombre_rol,
                           r.nombre_corto as rol_corto,
                           p.nombre_permiso,
                           p.descripcion as permiso_descripcion,
                           u.primer_nombre || ' ' || u.segundo_nombre || ' ' || u.primer_apellido || ' ' || u.segundo_apellido as usuario_asigna_completo
                    FROM roles_permisos rp 
                    INNER JOIN roles r ON rp.id_rol = r.id_rol 
                    INNER JOIN permisos p ON rp.id_permiso = p.id_permiso 
                    INNER JOIN usuarios u ON rp.usuario_asigna = u.id_usuario 
                    WHERE rp.situacion = 1 
                    ORDER BY rp.id_rol_permiso DESC";
            
            $asignaciones = RolPermiso::fetchArray($sql);
            
            if (!empty($asignaciones)) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Asignaciones encontradas: ' . count($asignaciones),
                    'data' => $asignaciones
                ]);
            } else {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'No se encontraron asignaciones',
                    'data' => []
                ]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al buscar asignaciones: ' . $e->getMessage()
            ]);
        }
        exit;
    }

    public static function modificarAPI()
    {
        getHeadersApi();
        
        if (empty($_POST['id_rol_permiso'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'ID de la asignación es requerido'
            ]);
            exit;
        }
        
        // Validar campos obligatorios
        if (empty($_POST['id_rol']) || empty($_POST['id_permiso']) || empty($_POST['usuario_asigna'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Todos los campos son obligatorios'
            ]);
            exit;
        }
        
        // Sanitizar motivo
        $_POST['motivo_asignacion'] = ucfirst(strtolower(trim(htmlspecialchars($_POST['motivo_asignacion']))));
        
        // Verificar si ya existe otra asignación igual 
        $asignacionExistente = RolPermiso::fetchFirst("SELECT * FROM roles_permisos WHERE id_rol = {$_POST['id_rol']} AND id_permiso = {$_POST['id_permiso']} AND id_rol_permiso != {$_POST['id_rol_permiso']} AND situacion = 1");
        if ($asignacionExistente) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Ya existe otra asignación igual para este rol'
            ]);
            exit;
        }
        
        try {
            // Usar consulta SQL directa para actualizar
            $sql = "UPDATE roles_permisos SET 
                    id_rol = {$_POST['id_rol']},
                    id_permiso = {$_POST['id_permiso']},
                    usuario_asigna = {$_POST['usuario_asigna']},
                    motivo_asignacion = '{$_POST['motivo_asignacion']}'
                    WHERE id_rol_permiso = {$_POST['id_rol_permiso']}";
            
            $resultado = RolPermiso::getDB()->exec($sql);

            if($resultado >= 0){
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Asignación modificada correctamente',
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Error al modificar la asignación',
                ]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error: ' . $e->getMessage()
            ]);
        }
        exit;
    }

    public static function eliminarAPI()
    {
        getHeadersApi();
        
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'ID de la asignación es requerido'
            ]);
            exit;
        }
        
        try {
            
            $sql = "UPDATE roles_permisos SET situacion = 0 WHERE id_rol_permiso = $id AND situacion = 1";
            $resultado = RolPermiso::getDB()->exec($sql);
            
            if($resultado > 0){
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Permiso removido del rol correctamente',
                ]);
            } else {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'No se pudo eliminar la asignación (puede que ya esté eliminada)',
                ]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error: ' . $e->getMessage()
            ]);
        }
        exit;
    }

    public static function obtenerRolesAPI()
    {
        getHeadersApi();
        
        try {
            $roles = Roles::where('situacion', 1);
            
            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Roles encontrados',
                'data' => $roles
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener roles: ' . $e->getMessage()
            ]);
        }
        exit;
    }

    public static function obtenerPermisosAPI()
    {
        getHeadersApi();
        
        try {
            $permisos = Permisos::where('situacion', 1);
            
            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Permisos encontrados',
                'data' => $permisos
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener permisos: ' . $e->getMessage()
            ]);
        }
        exit;
    }

    public static function obtenerUsuariosAPI()
    {
        getHeadersApi();
        
        try {
            $usuarios = Usuarios::where('situacion', 1);
            
            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Usuarios encontrados',
                'data' => $usuarios
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener usuarios: ' . $e->getMessage()
            ]);
        }
        exit;
    }

    public static function obtenerPermisosPorRolAPI()
    {
        getHeadersApi();
        
        $id_rol = $_GET['id_rol'] ?? null;
        
        if (!$id_rol) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'ID de rol es requerido'
            ]);
            exit;
        }
        
        try {
            $permisos = RolPermiso::obtenerPermisosPorRol($id_rol);
            
            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Permisos del rol encontrados',
                'data' => $permisos
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener permisos del rol: ' . $e->getMessage()
            ]);
        }
        exit;
    }

    public static function estadisticasAPI()
    {
        getHeadersApi();
        
        try {
            $estadisticas = RolPermiso::obtenerEstadisticas();
            
            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Estadísticas obtenidas',
                'data' => $estadisticas
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener estadísticas: ' . $e->getMessage()
            ]);
        }
        exit;
    }
}