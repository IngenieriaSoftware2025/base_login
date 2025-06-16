<?php
namespace Model;

class Ventas extends ActiveRecord {
    
    public static $tabla = 'ventas';
    public static $idTabla = 'id_venta';
    public static $columnasDB = [
        'id_cliente',
        'id_usuario',
        'subtotal',
        'descuento',
        'total',
        'metodo_pago',
        'estado_venta',
        'observaciones',
        'situacion'
    ];
    
    public $id_venta;
    public $id_cliente;
    public $id_usuario;
    public $fecha_venta;
    public $subtotal;
    public $descuento;
    public $total;
    public $metodo_pago;
    public $estado_venta;
    public $observaciones;
    public $situacion;
    
    public function __construct($venta = [])
    {
        $this->id_venta = $venta['id_venta'] ?? null;
        $this->id_cliente = $venta['id_cliente'] ?? '';
        $this->id_usuario = $venta['id_usuario'] ?? '';
        $this->fecha_venta = $venta['fecha_venta'] ?? '';
        $this->subtotal = $venta['subtotal'] ?? 0;
        $this->descuento = $venta['descuento'] ?? 0;
        $this->total = $venta['total'] ?? 0;
        $this->metodo_pago = $venta['metodo_pago'] ?? 'efectivo';
        $this->estado_venta = $venta['estado_venta'] ?? 'completada';
        $this->observaciones = $venta['observaciones'] ?? '';
        $this->situacion = $venta['situacion'] ?? 1;
    }
    
    // BUSCAR TODAS LAS VENTAS ACTIVAS
    public static function obtenerVentasActivas(){
        $sql = "SELECT v.id_venta, v.fecha_venta, v.subtotal, v.descuento, v.total, 
                       v.metodo_pago, v.estado_venta, v.observaciones,
                       c.primer_nombre, c.primer_apellido,
                       u.primer_nombre as vendedor_nombre, u.primer_apellido as vendedor_apellido,
                       v.id_cliente, v.id_usuario
                FROM ventas v 
                INNER JOIN clientes c ON v.id_cliente = c.id_cliente 
                INNER JOIN usuarios u ON v.id_usuario = u.id_usuario 
                WHERE v.situacion = 1 
                ORDER BY v.fecha_venta DESC";
        return self::fetchArray($sql);
    }
    
    // OBTENER CLIENTES 
    public static function obtenerClientesActivos(){
        $sql = "SELECT id_cliente, 
                       primer_nombre || ' ' || primer_apellido as nombre_completo
                FROM clientes 
                WHERE situacion = 1 
                ORDER BY primer_nombre";
        return self::fetchArray($sql);
    }
    
    // OBTENER USUARIOS 
    public static function obtenerUsuariosActivos(){
        $sql = "SELECT id_usuario, 
                       primer_nombre || ' ' || primer_apellido as nombre_completo
                FROM usuarios 
                WHERE situacion = 1 
                ORDER BY primer_nombre";
        return self::fetchArray($sql);
    }
    
    // OBTENER PRODUCTOS DISPONIBLES 
    public static function obtenerProductosDisponibles(){
        $sql = "SELECT i.id_inventario, i.estado_celular, i.precio_venta,
                       m.nombre_modelo, m.color,
                       ma.nombre_marca,
                       ma.nombre_marca || ' ' || m.nombre_modelo || 
                       CASE WHEN m.color IS NOT NULL AND m.color != '' 
                            THEN ' - ' || m.color 
                            ELSE '' 
                       END as producto_completo
                FROM inventario i 
                INNER JOIN modelos m ON i.id_modelo = m.id_modelo 
                INNER JOIN marcas ma ON m.id_marca = ma.id_marca 
                WHERE i.estado_inventario = 'disponible' 
                AND i.situacion = 1 AND m.situacion = 1 AND ma.situacion = 1 
                ORDER BY ma.nombre_marca, m.nombre_modelo";
        return self::fetchArray($sql);
    }
    
    // ELIMINAR VENTA
    public static function EliminarVenta($id){
        $sql = "UPDATE ventas SET situacion = 0 WHERE id_venta = $id";
        return self::SQL($sql);
    }
}
?>