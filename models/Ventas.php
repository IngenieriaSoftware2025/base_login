<?php
namespace Model;

class Ventas extends ActiveRecord {
    
    public static $tabla = 'ventas';
    public static $idTabla = 'id_venta';
    public static $columnasDB = [
        'id_cliente',
        'id_usuario',
        'fecha_venta',
        'total',
        'descuento',
        'metodo_pago',
        'estado_venta',
        'observaciones',
        'situacion'
    ];
    
    public $id_venta;
    public $id_cliente;
    public $id_usuario;
    public $fecha_venta;
    public $total;
    public $descuento;
    public $metodo_pago;
    public $estado_venta;
    public $observaciones;
    public $situacion;
    
    public function __construct($venta = [])
    {
        $this->id_venta = $venta['id_venta'] ?? null;
        $this->id_cliente = $venta['id_cliente'] ?? '';
        $this->id_usuario = $venta['id_usuario'] ?? '';
        $this->fecha_venta = $venta['fecha_venta'] ?? date('Y-m-d');
        $this->total = $venta['total'] ?? 0;
        $this->descuento = $venta['descuento'] ?? 0;
        $this->metodo_pago = $venta['metodo_pago'] ?? 'efectivo';
        $this->estado_venta = $venta['estado_venta'] ?? 'completada';
        $this->observaciones = $venta['observaciones'] ?? '';
        $this->situacion = $venta['situacion'] ?? 1;
    }
    
    // Método para eliminar venta (cambiar situacion = 0)
    public static function EliminarVenta($id){
        $sql = "UPDATE ventas SET situacion = 0 WHERE id_venta = $id";
        return self::SQL($sql);
    }
    
    // Método para buscar ventas activas con información de clientes y usuarios
    public static function obtenerVentasActivas(){
        $sql = "SELECT v.*, 
                       c.primer_nombre AS cliente_nombre, c.primer_apellido AS cliente_apellido,
                       u.primer_nombre AS usuario_nombre, u.primer_apellido AS usuario_apellido
                FROM ventas v 
                INNER JOIN clientes c ON v.id_cliente = c.id_cliente 
                INNER JOIN usuarios u ON v.id_usuario = u.id_usuario
                WHERE v.situacion = 1 
                ORDER BY v.fecha_venta DESC";
        return self::fetchArray($sql);
    }
    
    // Método para obtener clientes activos
    public static function obtenerClientesActivos(){
        $sql = "SELECT id_cliente, primer_nombre, primer_apellido, telefono FROM clientes WHERE situacion = 1 ORDER BY primer_nombre";
        return self::fetchArray($sql);
    }
    
    // Método para obtener usuarios activos
    public static function obtenerUsuariosActivos(){
        $sql = "SELECT id_usuario, primer_nombre, primer_apellido FROM usuarios WHERE situacion = 1 ORDER BY primer_nombre";
        return self::fetchArray($sql);
    }
    
    // Método para obtener inventario disponible para ventas
    public static function obtenerInventarioDisponible(){
        $sql = "SELECT i.id_inventario, i.precio_venta, i.estado_celular, i.imei,
                       m.nombre_modelo, ma.nombre_marca
                FROM inventario i 
                INNER JOIN modelos m ON i.id_modelo = m.id_modelo 
                INNER JOIN marcas ma ON m.id_marca = ma.id_marca 
                WHERE i.situacion = 1 AND i.estado_inventario = 'disponible'
                ORDER BY ma.nombre_marca, m.nombre_modelo";
        return self::fetchArray($sql);
    }
    
    // Método para obtener detalle de una venta específica
    public static function obtenerVentaConDetalle($id_venta){
        $sql = "SELECT v.*, 
                       c.primer_nombre AS cliente_nombre, c.primer_apellido AS cliente_apellido, c.telefono,
                       u.primer_nombre AS usuario_nombre, u.primer_apellido AS usuario_apellido
                FROM ventas v 
                INNER JOIN clientes c ON v.id_cliente = c.id_cliente 
                INNER JOIN usuarios u ON v.id_usuario = u.id_usuario
                WHERE v.id_venta = $id_venta AND v.situacion = 1";
        return self::fetchFirst($sql);
    }
    
    // Método para calcular total de ventas del día
    public static function obtenerTotalVentasDelDia($fecha = null){
        if($fecha === null){
            $fecha = date('Y-m-d');
        }
        $sql = "SELECT SUM(total) as total_dia FROM ventas 
                WHERE DATE(fecha_venta) = '$fecha' AND situacion = 1 AND estado_venta = 'completada'";
        $resultado = self::fetchFirst($sql);
        return $resultado['total_dia'] ?? 0;
    }
}
?>