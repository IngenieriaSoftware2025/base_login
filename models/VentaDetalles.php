<?php

namespace Model;

class VentaDetalles extends ActiveRecord {

    public static $tabla = 'venta_detalles';
    public static $columnasDB = [
        'venta_id',
        'producto_id',
        'cantidad',
        'precio_unitario',
        'subtotal'
    ];

    public static $idTabla = 'id_detalle';
    public $id_detalle;
    public $venta_id;
    public $producto_id;
    public $cantidad;
    public $precio_unitario;
    public $subtotal;

    public function __construct($args = []){
        $this->id_detalle = $args['id_detalle'] ?? null;
        $this->venta_id = $args['venta_id'] ?? 0;
        $this->producto_id = $args['producto_id'] ?? 0;
        $this->cantidad = $args['cantidad'] ?? 0;
        $this->precio_unitario = $args['precio_unitario'] ?? 0;
        $this->subtotal = $args['subtotal'] ?? 0;
    }

    public static function ObtenerDetallesPorVenta($venta_id){
        $sql = "SELECT vd.*, p.nombre as producto_nombre, p.descripcion as producto_descripcion 
                FROM venta_detalles vd 
                INNER JOIN productos p ON vd.producto_id = p.id_producto 
                WHERE vd.venta_id = $venta_id 
                ORDER BY p.nombre";
        return self::fetchArray($sql);
    }

    public static function EliminarDetallesPorVenta($venta_id){
        $sql = "DELETE FROM venta_detalles WHERE venta_id = $venta_id";
        return self::SQL($sql);
    }

    public static function ObtenerProductosMasVendidos($limite = 10){
        $sql = "SELECT p.nombre as producto_nombre, SUM(vd.cantidad) as total_vendido, 
                       SUM(vd.subtotal) as total_ingresos
                FROM venta_detalles vd 
                INNER JOIN productos p ON vd.producto_id = p.id_producto 
                INNER JOIN ventas v ON vd.venta_id = v.id_venta 
                WHERE v.situacion = 1
                GROUP BY p.id_producto, p.nombre 
                ORDER BY total_vendido DESC 
                LIMIT $limite";
        return self::fetchArray($sql);
    }

    public static function ObtenerVentasPorProducto($producto_id){
        $sql = "SELECT vd.*, v.fecha_venta, c.nombres, c.apellidos
                FROM venta_detalles vd 
                INNER JOIN ventas v ON vd.venta_id = v.id_venta 
                INNER JOIN clientes c ON v.id_cliente = c.id_cliente 
                WHERE vd.producto_id = $producto_id AND v.situacion = 1
                ORDER BY v.fecha_venta DESC";
        return self::fetchArray($sql);
    }

    public static function CalcularTotalDetalle($venta_id){
        $sql = "SELECT SUM(subtotal) as total FROM venta_detalles WHERE venta_id = $venta_id";
        $resultado = self::fetchFirst($sql);
        return $resultado['total'] ?? 0;
    }

    public static function RestaurarStockDeVenta($venta_id){
        $detalles = self::fetchArray("SELECT producto_id, cantidad 
                                     FROM venta_detalles WHERE venta_id = $venta_id");
        
        foreach($detalles as $detalle){
            self::SQL("UPDATE productos SET cantidad = cantidad + " . 
                     $detalle['cantidad'] . " WHERE id_producto = " . $detalle['producto_id']);
        }
        return true;
    }

    public static function ObtenerResumenVentasPorPeriodo($fecha_inicio, $fecha_fin){
        $sql = "SELECT DATE(v.fecha_venta) as fecha, 
                       COUNT(v.id_venta) as total_ventas,
                       SUM(v.total) as total_ingresos,
                       AVG(v.total) as promedio_venta
                FROM ventas v 
                WHERE v.fecha_venta BETWEEN " . self::$db->quote($fecha_inicio) . " AND " . self::$db->quote($fecha_fin) . "
                AND v.situacion = 1
                GROUP BY DATE(v.fecha_venta)
                ORDER BY fecha DESC";
        return self::fetchArray($sql);
    }
}