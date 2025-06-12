<?php

namespace Model;

class Roles extends ActiveRecord {

    public static $tabla = 'roles';
    public static $columnasDB = [
        'nombre_rol',
        'nombre_corto', 
        'descripcion',
        'fecha_creacion',
        'situacion'
    ];

    public static $idTabla = 'id_rol';
    public $id_rol;
    public $nombre_rol;
    public $nombre_corto;
    public $descripcion;
    public $fecha_creacion;
    public $situacion;

    public function __construct($args = []){
        $this->id_rol = $args['id_rol'] ?? null;
        $this->nombre_rol = $args['nombre_rol'] ?? '';
        $this->nombre_corto = $args['nombre_corto'] ?? '';
        $this->descripcion = $args['descripcion'] ?? '';
        $this->fecha_creacion = $args['fecha_creacion'] ?? '';
        $this->situacion = $args['situacion'] ?? 1;
    }

    // Método para obtener roles activos (para dropdowns)
    public static function obtenerRolesActivos(){
        $sql = "SELECT id_rol, nombre_rol, nombre_corto FROM roles WHERE situacion = 1 ORDER BY nombre_rol";
        return self::fetchArray($sql);
    }

    // Eliminar rol (cambio de situación)
    public static function EliminarRol($id){
        $sql = "UPDATE roles SET situacion = 0 WHERE id_rol = $id";
        return self::SQL($sql);
    }

    // Validar que no exista rol duplicado
    public static function existeRol($nombre_rol, $id_excluir = null){
        $sql = "SELECT COUNT(*) as total FROM roles WHERE nombre_rol = '$nombre_rol' AND situacion = 1";
        if($id_excluir){
            $sql .= " AND id_rol != $id_excluir";
        }
        $resultado = self::fetchFirst($sql);
        return $resultado['total'] > 0;
    }
}