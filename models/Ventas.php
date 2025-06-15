<?php

namespace Model;

class Ventas extends ActiveRecord {

    public static $tabla = 'ventas';
    public static $columnasDB = [
        'id_cliente',
        'id_usuario', 
        'numero_venta',
        'fecha_venta',
        'subtotal',
        'descuento',
        'total',
        'metodo_pago',
        'estado_venta',
        'observaciones',
        'situacion'
    ];

    public static $idTabla = 'id_venta';
    public $id_venta;
    public $id_cliente;
    public $id_usuario;
    public $numero_venta;
    public $fecha_venta;
    public $subtotal;
    public $descuento;
    public $total;
    public $metodo_pago;
    public $estado_venta;
    public $observaciones;
    public $situacion;

    public function __construct($args = []){
        $this->id_venta = $args['id_venta'] ?? null;
        $this->id_cliente = $args['id_cliente'] ?? 0;
        $this->id_usuario = $args['id_usuario'] ?? 0;
        $this->numero_venta = $args['numero_venta'] ?? '';
        $this->fecha_venta = $args['fecha_venta'] ?? date('Y-m-d');
        $this->subtotal = $args['subtotal'] ?? 0;
        $this->descuento = $args['descuento'] ?? 0;
        $this->total = $args['total'] ?? 0;
        $this->metodo_pago = $args['metodo_pago'] ?? 'efectivo';
        $this->estado_venta = $args['estado_venta'] ?? 'completada';
        $this->observaciones = $args['observaciones'] ?? '';
        $this->situacion = $args['situacion'] ?? 1;
    }

    public static function EliminarVenta($id){
        $sql = "UPDATE ventas SET situacion = 0 WHERE id_venta = $id";
        return self::SQL($sql);
    }

    public static function ObtenerVentasConClientes(){
        $sql = "SELECT v.id_venta, v.numero_venta, v.fecha_venta, v.subtotal, v.descuento, v.total, 
                       v.metodo_pago, v.estado_venta,
                       c.nombres, c.apellidos, c.correo
                FROM ventas v 
                INNER JOIN clientes c ON v.id_cliente = c.id_cliente 
                WHERE v.situacion = 1 ORDER BY v.fecha_venta DESC";
        return self::fetchArray($sql);
    }

    public static function ObtenerVentaPorId($id){
        $sql = "SELECT v.*, c.nombres, c.apellidos, c.correo, c.telefono
                FROM ventas v 
                INNER JOIN clientes c ON v.id_cliente = c.id_cliente 
                WHERE v.id_venta = $id AND v.situacion = 1";
        return self::fetchFirst($sql);
    }

    public static function ObtenerVentasPorCliente($cliente_id){
        $sql = "SELECT * FROM ventas WHERE id_cliente = $cliente_id AND situacion = 1 ORDER BY fecha_venta DESC";
        return self::fetchArray($sql);
    }

    public static function ObtenerVentasPorFecha($fecha_inicio, $fecha_fin){
        $sql = "SELECT v.*, c.nombres, c.apellidos 
                FROM ventas v 
                INNER JOIN clientes c ON v.id_cliente = c.id_cliente 
                WHERE v.fecha_venta BETWEEN " . self::$db->quote($fecha_inicio) . " AND " . self::$db->quote($fecha_fin) . "
                AND v.situacion = 1 ORDER BY v.fecha_venta DESC";
        return self::fetchArray($sql);
    }

    public static function ObtenerTotalVentasDelDia($fecha = null){
        if($fecha === null){
            $fecha = date('Y-m-d');
        }
        $sql = "SELECT SUM(total) as total FROM ventas 
                WHERE fecha_venta = " . self::$db->quote($fecha) . " AND situacion = 1";
        $resultado = self::fetchFirst($sql);
        return $resultado['total'] ?? 0;
    }

    public static function ObtenerVentasPorMetodoPago($metodo_pago){
        $sql = "SELECT v.*, c.nombres, c.apellidos 
                FROM ventas v 
                INNER JOIN clientes c ON v.id_cliente = c.id_cliente 
                WHERE v.metodo_pago = " . self::$db->quote($metodo_pago) . " 
                AND v.situacion = 1 ORDER BY v.fecha_venta DESC";
        return self::fetchArray($sql);
    }

    public static function CambiarEstadoVenta($id, $nuevo_estado){
        if (!in_array($nuevo_estado, ['completada', 'cancelada', 'pendiente'])) {
            return false;
        }
        
        $sql = "UPDATE ventas SET estado_venta = " . self::$db->quote($nuevo_estado) . " WHERE id_venta = $id";
        return self::SQL($sql);
    }
}