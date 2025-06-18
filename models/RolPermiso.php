<?php

namespace Model;


class RolPermiso extends ActiveRecord {
    
    public static $tabla = 'roles_permisos';
    public static $columnasDB = [
        'id_rol',
        'id_permiso',
        'usuario_asigna',
        'motivo_asignacion',
        'situacion'
    ];

    public static $idTabla = 'id_rol_permiso';
    
    public $id_rol_permiso;
    public $id_rol;
    public $id_permiso;
    public $usuario_asigna;
    public $motivo_asignacion;
    public $fecha_asignacion;
    public $situacion;

    public function __construct($args = []){
        $this->id_rol_permiso = $args['id_rol_permiso'] ?? null;
        $this->id_rol = $args['id_rol'] ?? '';
        $this->id_permiso = $args['id_permiso'] ?? '';
        $this->usuario_asigna = $args['usuario_asigna'] ?? '';
        $this->motivo_asignacion = $args['motivo_asignacion'] ?? '';
        $this->fecha_asignacion = $args['fecha_asignacion'] ?? date('Y-m-d');
        $this->situacion = $args['situacion'] ?? 1;
    }


    public static function obtenerPermisosPorRol($id_rol) {
        $sql = "SELECT p.*, rp.fecha_asignacion, rp.motivo_asignacion
                FROM permisos p 
                INNER JOIN roles_permisos rp ON p.id_permiso = rp.id_permiso 
                WHERE rp.id_rol = $id_rol AND rp.situacion = 1 AND p.situacion = 1";
        return static::fetchArray($sql);
    }

    public static function obtenerRolesPorPermiso($id_permiso) {
        $sql = "SELECT r.*, rp.fecha_asignacion, rp.motivo_asignacion
                FROM roles r 
                INNER JOIN roles_permisos rp ON r.id_rol = rp.id_rol 
                WHERE rp.id_permiso = $id_permiso AND rp.situacion = 1 AND r.situacion = 1";
        return static::fetchArray($sql);
    }


    public static function rolTienePermiso($id_rol, $id_permiso) {
        $sql = "SELECT COUNT(*) as tiene_permiso 
                FROM roles_permisos 
                WHERE id_rol = $id_rol AND id_permiso = $id_permiso AND situacion = 1";
        $resultado = static::fetchFirst($sql);
        return $resultado['tiene_permiso'] > 0;
    }


    public static function obtenerPermisosUsuario($id_usuario) {
        $sql = "SELECT DISTINCT p.* 
                FROM permisos p 
                INNER JOIN roles_permisos rp ON p.id_permiso = rp.id_permiso 
                INNER JOIN usuarios u ON u.id_rol = rp.id_rol 
                WHERE u.id_usuario = $id_usuario 
                AND rp.situacion = 1 AND p.situacion = 1 AND u.situacion = 1";
        return static::fetchArray($sql);
    }


    public static function usuarioTienePermiso($id_usuario, $nombre_permiso) {
        $sql = "SELECT COUNT(*) as tiene_permiso 
                FROM permisos p 
                INNER JOIN roles_permisos rp ON p.id_permiso = rp.id_permiso 
                INNER JOIN usuarios u ON u.id_rol = rp.id_rol 
                WHERE u.id_usuario = $id_usuario 
                AND p.nombre_permiso = '$nombre_permiso'
                AND rp.situacion = 1 AND p.situacion = 1 AND u.situacion = 1";
        $resultado = static::fetchFirst($sql);
        return $resultado['tiene_permiso'] > 0;
    }

    public static function quitarPermisoDeRol($id_rol, $id_permiso) {
        $sql = "UPDATE roles_permisos 
                SET situacion = 0 
                WHERE id_rol = $id_rol AND id_permiso = $id_permiso";
        return static::getDB()->exec($sql);
    }

    public static function obtenerEstadisticas() {
        $sql = "SELECT 
                    r.nombre_rol,
                    COUNT(rp.id_permiso) as total_permisos
                FROM roles r 
                LEFT JOIN roles_permisos rp ON r.id_rol = rp.id_rol AND rp.situacion = 1
                WHERE r.situacion = 1
                GROUP BY r.id_rol, r.nombre_rol
                ORDER BY r.nombre_rol";
        return static::fetchArray($sql);
    }
}