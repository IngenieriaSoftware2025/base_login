<?php
namespace Model;

class DetalleVentas extends ActiveRecord {
    
    public static $tabla = 'detalle_ventas';
    public static $idTabla = 'id_detalle';
    public static $columnasDB = [
        'id_venta',
        'id_inventario',
        'precio_unitario',
        'cantidad',
        'subtotal_detalle',
        'situacion'
    ];
    
    public $id_detalle;
    public $id_venta;
    public $id_inventario;
    public $precio_unitario;
    public $cantidad;
    public $subtotal_detalle;
    public $situacion;
    
    public function __construct($detalle = [])
    {
        $this->id_detalle = $detalle['id_detalle'] ?? null;
        $this->id_venta = $detalle['id_venta'] ?? '';
        $this->id_inventario = $detalle['id_inventario'] ?? '';
        $this->precio_unitario = $detalle['precio_unitario'] ?? 0;
        $this->cantidad = $detalle['cantidad'] ?? 1;
        $this->subtotal_detalle = $detalle['subtotal_detalle'] ?? 0;
        $this->situacion = $detalle['situacion'] ?? 1;
    }
    
    // Método para obtener detalles de una venta
    public static function obtenerDetallesPorVenta($id_venta){
        $sql = "SELECT d.*, 
                       m.nombre_modelo, ma.nombre_marca, i.estado_celular
                FROM detalle_ventas d 
                INNER JOIN inventario i ON d.id_inventario = i.id_inventario
                INNER JOIN modelos m ON i.id_modelo = m.id_modelo 
                INNER JOIN marcas ma ON m.id_marca = ma.id_marca 
                WHERE d.id_venta = $id_venta AND d.situacion = 1
                ORDER BY d.id_detalle";
        return self::fetchArray($sql);
    }
    
    // Método para marcar inventario como vendido
    public static function marcarInventarioVendido($id_inventario){
        $sql = "UPDATE inventario SET estado_inventario = 'vendido' WHERE id_inventario = $id_inventario";
        return self::SQL($sql);
    }
}
?>