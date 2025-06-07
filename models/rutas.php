<?php

namespace Model;

class Rutas extends ActiveRecord{

    public static $tabla = 'rutas';
    public static $columnasDB = [
        'id_aplicacion',
        'ruta',
        'descripcion',
        'situacion'
    ];

    public static $idTabla = 'id_ruta';
    public $id_ruta;
    public $id_aplicacion;
    public $ruta;
    public $descripcion;
    public $situacion;

    public function __construct($args = []){
        $this->id_ruta = $args['id_ruta'] ?? null;
        $this->id_aplicacion = $args['id_aplicacion'] ?? '';
        $this->ruta = $args['ruta'] ?? '';
        $this->descripcion = $args['descripcion'] ?? '';
        $this->situacion = $args['situacion'] ?? 1;
    }
}