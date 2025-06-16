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
    

    public static function obtenerDetallesPorVenta($id_venta){
        $sql = "SELECT dv.id_detalle, dv.id_venta, dv.id_inventario, 
                       dv.precio_unitario, dv.cantidad, dv.subtotal_detalle,
                       i.estado_celular,
                       m.nombre_modelo, m.color,
                       ma.nombre_marca,
                       ma.nombre_marca || ' ' || m.nombre_modelo || 
                       CASE WHEN m.color IS NOT NULL AND m.color != '' 
                            THEN ' - ' || m.color 
                            ELSE '' 
                       END as producto_completo
                FROM detalle_ventas dv 
                INNER JOIN inventario i ON dv.id_inventario = i.id_inventario 
                INNER JOIN modelos m ON i.id_modelo = m.id_modelo 
                INNER JOIN marcas ma ON m.id_marca = ma.id_marca 
                WHERE dv.id_venta = $id_venta AND dv.situacion = 1
                ORDER BY dv.id_detalle";
        return self::fetchArray($sql);
    }
    

    public static function EliminarDetalle($id){
        $sql = "UPDATE detalle_ventas SET situacion = 0 WHERE id_detalle = $id";
        return self::SQL($sql);
    }
}
?>