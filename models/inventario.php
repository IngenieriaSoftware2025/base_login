<?php
namespace Model;

class Inventario extends ActiveRecord {
    
    public static $tabla = 'inventario';
    public static $idTabla = 'id_inventario';
    public static $columnasDB = [
        'id_modelo',
        'estado_celular',
        'precio_compra',
        'precio_venta',
        'estado_inventario',
        'situacion'
    ];
    
    public $id_inventario;
    public $id_modelo;
    public $estado_celular;
    public $precio_compra;
    public $precio_venta;
    public $fecha_ingreso;
    public $estado_inventario;
    public $situacion;
    
    public function __construct($inventario = [])
    {
        $this->id_inventario = $inventario['id_inventario'] ?? null;
        $this->id_modelo = $inventario['id_modelo'] ?? '';
        $this->estado_celular = $inventario['estado_celular'] ?? 'nuevo';
        $this->precio_compra = $inventario['precio_compra'] ?? '';
        $this->precio_venta = $inventario['precio_venta'] ?? '';
        $this->fecha_ingreso = $inventario['fecha_ingreso'] ?? '';
        $this->estado_inventario = $inventario['estado_inventario'] ?? 'disponible';
        $this->situacion = $inventario['situacion'] ?? 1;
    }
    

    public static function EliminarInventario($id){
        $sql = "UPDATE inventario SET situacion = 0 WHERE id_inventario = $id";
        return self::SQL($sql);
    }
    
 
    public static function obtenerInventarioActivo(){
        $sql = "SELECT i.id_inventario, i.id_modelo, i.estado_celular, 
                       i.precio_compra, i.precio_venta, i.fecha_ingreso, 
                       i.estado_inventario, i.situacion,
                       m.nombre_modelo, ma.nombre_marca 
                FROM inventario i 
                INNER JOIN modelos m ON i.id_modelo = m.id_modelo 
                INNER JOIN marcas ma ON m.id_marca = ma.id_marca 
                WHERE i.situacion = 1 AND m.situacion = 1 AND ma.situacion = 1 
                ORDER BY ma.nombre_marca, m.nombre_modelo, i.fecha_ingreso DESC";
        return self::fetchArray($sql);
    }
    
   
    public static function obtenerMarcasActivas(){
        $sql = "SELECT id_marca, nombre_marca FROM marcas WHERE situacion = 1 ORDER BY nombre_marca";
        return self::fetchArray($sql);
    }
    
    
    public static function obtenerModelosPorMarca($id_marca){
        $sql = "SELECT id_modelo, nombre_modelo FROM modelos WHERE id_marca = $id_marca AND situacion = 1 ORDER BY nombre_modelo";
        return self::fetchArray($sql);
    }
}
?>