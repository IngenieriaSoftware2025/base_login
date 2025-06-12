<?php

namespace Model;

class Permisos extends ActiveRecord {

    // 1. INFORMACIÓN BÁSICA DE LA TABLA
    public static $tabla = 'permisos';
    public static $idTabla = 'id_permiso';
    public static $columnasDB = [
        'nombre_permiso',
        'descripcion',
        'fecha_creacion',
        'situacion'
    ];

    // 2. PROPIEDADES DEL OBJETO (igual que las columnas de la tabla)
    public $id_permiso;
    public $nombre_permiso;
    public $descripcion;
    public $fecha_creacion;
    public $situacion;

    // 3. CONSTRUCTOR - Crea el objeto con los datos
    public function __construct($args = [])
    {
        $this->id_permiso = $args['id_permiso'] ?? null;
        $this->nombre_permiso = $args['nombre_permiso'] ?? '';
        $this->descripcion = $args['descripcion'] ?? '';
        $this->fecha_creacion = $args['fecha_creacion'] ?? '';
        $this->situacion = $args['situacion'] ?? 1;
    }

    // 4. FUNCIÓN PARA ELIMINAR (cambiar situación a 0)
    public static function EliminarPermiso($id)
    {
        $sql = "UPDATE permisos SET situacion = 0 WHERE id_permiso = $id";
        return self::SQL($sql);
    }

    // 5. FUNCIÓN PARA OBTENER TODOS LOS PERMISOS ACTIVOS
    public static function obtenerPermisosActivos()
    {
        $sql = "SELECT * FROM permisos WHERE situacion = 1 ORDER BY nombre_permiso";
        return self::fetchArray($sql);
    }
}