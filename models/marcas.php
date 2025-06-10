<?php

namespace Model;

class Marcas extends ActiveRecord {

    public static $tabla = 'marcas';
    public static $columnasDB = [
        'marca_nombre',
        'marca_descripcion',
        'marca_modelo',
        'marca_situacion'
        // NO incluir marca_fecha_ingreso porque es DEFAULT TODAY
    ];

    public static $idTabla = 'id_marca';
    public $id_marca;
    public $marca_nombre;
    public $marca_descripcion;
    public $marca_fecha_ingreso;  // SÍ incluir como propiedad para leer
    public $marca_modelo;
    public $marca_situacion;

    public function __construct($args = []){
        $this->id_marca = $args['id_marca'] ?? null;
        $this->marca_nombre = $args['marca_nombre'] ?? '';
        $this->marca_descripcion = $args['marca_descripcion'] ?? '';
        $this->marca_fecha_ingreso = $args['marca_fecha_ingreso'] ?? '';
        $this->marca_modelo = $args['marca_modelo'] ?? '';
        $this->marca_situacion = $args['marca_situacion'] ?? 1;
    }

    public static function EliminarMarca($id){
        $sql = "UPDATE marcas SET marca_situacion = 0 WHERE id_marca = $id";
        return self::SQL($sql);
    }
}