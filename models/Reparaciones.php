<?php

namespace Model;

class Reparaciones extends ActiveRecord
{

    public static $tabla = 'reparaciones';
    public static $idTabla = 'id_reparacion';
    public static $columnasDB = [
        'id_cliente',
        'id_usuario_recibe',
        'id_usuario_asignado',
        'numero_orden',
        'tipo_celular',
        'marca_celular',
        'imei',
        'motivo_ingreso',
        'diagnostico',
        //'fecha_ingreso',
        'fecha_asignacion',
        'fecha_entrega_real',
        'tipo_servicio',
        'estado_reparacion',
        'costo_total',
        'situacion'
    ];

    public $id_reparacion;
    public $id_cliente;
    public $id_usuario_recibe;
    public $id_usuario_asignado;
    public $numero_orden;
    public $tipo_celular;
    public $marca_celular;
    public $imei;
    public $motivo_ingreso;
    public $diagnostico;
    public $fecha_ingreso;
    public $fecha_asignacion;
    public $fecha_entrega_real;
    public $tipo_servicio;
    public $estado_reparacion;
    public $costo_total;
    public $situacion;

    public function __construct($reparacion = [])
    {
        $this->id_reparacion = $reparacion['id_reparacion'] ?? null;
        $this->id_cliente = $reparacion['id_cliente'] ?? '';
        $this->id_usuario_recibe = $reparacion['id_usuario_recibe'] ?? '';
        $this->id_usuario_asignado = $reparacion['id_usuario_asignado'] ?? '';
        $this->numero_orden = $reparacion['numero_orden'] ?? '';
        $this->tipo_celular = $reparacion['tipo_celular'] ?? '';
        $this->marca_celular = $reparacion['marca_celular'] ?? '';
        $this->imei = $reparacion['imei'] ?? '';
        $this->motivo_ingreso = $reparacion['motivo_ingreso'] ?? '';
        $this->diagnostico = $reparacion['diagnostico'] ?? '';
        $this->fecha_ingreso = $reparacion['fecha_ingreso'] ?? '';
        $this->fecha_asignacion = $reparacion['fecha_asignacion'] ?? '';
        $this->fecha_entrega_real = $reparacion['fecha_entrega_real'] ?? '';
        $this->tipo_servicio = $reparacion['tipo_servicio'] ?? '';
        $this->estado_reparacion = $reparacion['estado_reparacion'] ?? 'recibido';
        $this->costo_total = $reparacion['costo_total'] ?? 0;
        $this->fecha_asignacion = $reparacion['fecha_asignacion'] ?? null;
        $this->fecha_entrega_real = $reparacion['fecha_entrega_real'] ?? null;
        $this->situacion = $reparacion['situacion'] ?? 1;
    }

    // Método para eliminar reparación
    public static function EliminarReparacion($id)
    {
        $sql = "UPDATE reparaciones SET situacion = 0 WHERE id_reparacion = $id";
        return self::SQL($sql);
    }

    // Método para buscar reparaciones activas
    public static function obtenerReparacionesActivas()
    {
        $sql = "SELECT r.*, 
                       c.primer_nombre || ' ' || c.primer_apellido AS nombre_cliente,
                       u1.primer_nombre || ' ' || u1.primer_apellido AS usuario_recibe,
                       u2.primer_nombre || ' ' || u2.primer_apellido AS usuario_asignado
                FROM reparaciones r 
                INNER JOIN clientes c ON r.id_cliente = c.id_cliente 
                INNER JOIN usuarios u1 ON r.id_usuario_recibe = u1.id_usuario 
                LEFT JOIN usuarios u2 ON r.id_usuario_asignado = u2.id_usuario 
                WHERE r.situacion = 1 AND c.situacion = 1 AND u1.situacion = 1
                ORDER BY r.fecha_ingreso DESC, r.id_reparacion DESC";
        return self::fetchArray($sql);
    }

    // Método para obtener clientes activos
    public static function obtenerClientesActivos()
    {
        $sql = "SELECT id_cliente, primer_nombre || ' ' || primer_apellido AS nombre_completo 
                FROM clientes WHERE situacion = 1 ORDER BY primer_apellido, primer_nombre";
        return self::fetchArray($sql);
    }

    // Método para obtener usuarios activos
    public static function obtenerUsuariosActivos()
    {
        $sql = "SELECT id_usuario, primer_nombre || ' ' || primer_apellido AS nombre_completo 
                FROM usuarios WHERE situacion = 1 ORDER BY primer_apellido, primer_nombre";
        return self::fetchArray($sql);
    }

    // Método para generar número de orden automático
    public static function generarNumeroOrden()
    {
        $sql = "SELECT MAX(id_reparacion) AS ultimo_id FROM reparaciones";
        $resultado = self::fetchFirst($sql);
        $siguiente = ($resultado['ultimo_id'] ?? 0) + 1;

        // Formatear número con ceros a la izquierda
        if ($siguiente < 10) {
            return 'REP-000' . $siguiente;
        } else if ($siguiente < 100) {
            return 'REP-00' . $siguiente;
        } else if ($siguiente < 1000) {
            return 'REP-0' . $siguiente;
        } else {
            return 'REP-' . $siguiente;
        }
    }
}
