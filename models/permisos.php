<?php

namespace Model;

class Permisos extends ActiveRecord {

    public static $tabla = 'permisos';
    public static $columnasDB = [
        'id_aplicacion',
        'nombre_permiso',
        'clave_permiso',
        'descripcion',
        'fecha',
        'situacion'
    ];

    public static $idTabla = 'id_permiso';
    public $id_permiso;
    public $id_aplicacion;
    public $nombre_permiso;
    public $clave_permiso;
    public $descripcion;
    public $fecha;
    public $situacion;

    public function __construct($args = []){
        $this->id_permiso = $args['id_permiso'] ?? null;
        $this->id_aplicacion = $args['id_aplicacion'] ?? null;
        $this->nombre_permiso = $args['nombre_permiso'] ?? '';
        $this->clave_permiso = $args['clave_permiso'] ?? '';
        $this->descripcion = $args['descripcion'] ?? '';
        $this->fecha = $args['fecha'] ?? '';
        $this->situacion = $args['situacion'] ?? 1;
    }

    // CAMBIO: Eliminación física igual que Prestamos
    public static function EliminarPermisos($id){
        $sql = "DELETE FROM permisos WHERE id_permiso = $id";
        return self::SQL($sql);
    }

    // Método para obtener aplicaciones activas para el dropdown
    public static function obtenerAplicaciones(){
        $sql = "SELECT id_aplicacion, nombre_app_md FROM aplicaciones WHERE situacion = 1 ORDER BY nombre_app_md";
        return self::fetchArray($sql);
    }
}