<?php

namespace Model;

class Inventario extends ActiveRecord {

    public static $tabla = 'inventario';
    public static $columnasDB = [
        'id_marca',
        'estado_dispositivo',
        'estado_inventario',
        'numero_serie',
        'precio_compra',
        'precio_venta',
        'stock_disponible',
        'observaciones',
        'situacion'
        // fecha_ingreso automaticamnte en la bd
    ];

    public static $idTabla = 'id_inventario';
    public $id_inventario;
    public $id_marca;
    public $estado_dispositivo;
    public $estado_inventario;
    public $numero_serie;
    public $precio_compra;
    public $precio_venta;
    public $stock_disponible;
    public $fecha_ingreso;
    public $observaciones;
    public $situacion;

    public function __construct($args = []){
        $this->id_inventario = $args['id_inventario'] ?? null;
        $this->id_marca = $args['id_marca'] ?? null;
        $this->estado_dispositivo = $args['estado_dispositivo'] ?? 'NUEVO';
        $this->estado_inventario = $args['estado_inventario'] ?? 'DISPONIBLE';
        $this->numero_serie = $args['numero_serie'] ?? '';
        $this->precio_compra = $args['precio_compra'] ?? 0;
        $this->precio_venta = $args['precio_venta'] ?? 0;
        $this->stock_disponible = $args['stock_disponible'] ?? 0;
        $this->fecha_ingreso = $args['fecha_ingreso'] ?? '';
        $this->observaciones = $args['observaciones'] ?? '';
        $this->situacion = $args['situacion'] ?? 1;
    }

    public static function EliminarInventario($id){
        $sql = "UPDATE inventario SET situacion = 0 WHERE id_inventario = $id";
        return self::SQL($sql);
    }

    // Método para obtener marcas para el dropdown
    public static function obtenerMarcas(){
        $sql = "SELECT id_marca, marca_nombre FROM marcas WHERE marca_situacion = 1 ORDER BY marca_nombre";
        return self::fetchArray($sql);
    }

    // Método para buscar inventario con información de marca
    public static function buscarConMarcas(){
        $sql = "SELECT 
                    i.*,
                    m.marca_nombre
                FROM inventario i 
                INNER JOIN marcas m ON i.id_marca = m.id_marca 
                WHERE i.situacion = 1 
                ORDER BY i.fecha_ingreso DESC";
        return self::fetchArray($sql);
    }
}