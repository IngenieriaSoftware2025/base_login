<?php

namespace Model;

class Aplicaciones extends ActiveRecord {

    public static $tabla = 'aplicaciones';
    public static $columnasDB = [
        'nombre_app_lg',
        'nombre_app_md',
        'nombre_app_ct',
        //'fecha_creacion',
        'situacion'
    ];

    public static $idTabla = 'id_aplicacion';
    public $id_aplicacion;
    public $nombre_app_lg;
    public $nombre_app_md;
    public $nombre_app_ct;
    public $fecha_creacion;
    public $situacion;

    public function __construct($args = []){
        $this->id_aplicacion = $args['id_aplicacion'] ?? null;
        $this->nombre_app_lg = $args['nombre_app_lg'] ?? '';
        $this->nombre_app_md = $args['nombre_app_md'] ?? '';
        $this->nombre_app_ct = $args['nombre_app_ct'] ?? '';
       // $this->fecha_creacion = $args['fecha_creacion'] ?? '';
        $this->situacion = $args['situacion'] ?? 1;
    }

    public static function EliminarAplicaciones($id){
        $sql = "UPDATE aplicaciones SET situacion = 0 WHERE id_aplicacion = $id";
        return self::SQL($sql);
    }
}