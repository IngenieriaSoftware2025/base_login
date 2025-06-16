<?php
namespace Model;

class Reparaciones extends ActiveRecord {
    
    public static $tabla = 'reparaciones';
    public static $idTabla = 'id_reparacion';
    public static $columnasDB = [
        'id_cliente',
        'id_usuario_recibe',
        'id_usuario_asignado',
        'tipo_celular',
        'marca_celular',
        'motivo_ingreso',
        'diagnostico',
        'tipo_servicio',
        'estado_reparacion',
        'costo_total',
        'situacion'
    ];
    
    public $id_reparacion;
    public $id_cliente;
    public $id_usuario_recibe;
    public $id_usuario_asignado;
    public $tipo_celular;
    public $marca_celular;
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
        $this->id_usuario_asignado = $reparacion['id_usuario_asignado'] ?? null;
        $this->tipo_celular = $reparacion['tipo_celular'] ?? '';
        $this->marca_celular = $reparacion['marca_celular'] ?? '';
        $this->motivo_ingreso = $reparacion['motivo_ingreso'] ?? '';
        $this->diagnostico = $reparacion['diagnostico'] ?? '';
        $this->tipo_servicio = $reparacion['tipo_servicio'] ?? '';
        $this->estado_reparacion = $reparacion['estado_reparacion'] ?? 'recibido';
        $this->costo_total = $reparacion['costo_total'] ?? 0;
        $this->situacion = $reparacion['situacion'] ?? 1;
    }
    
    // Método para eliminar reparación (cambiar situacion = 0)
    public static function EliminarReparacion($id){
        $sql = "UPDATE reparaciones SET situacion = 0 WHERE id_reparacion = $id";
        return self::SQL($sql);
    }
    
    // Método para buscar reparaciones activas
    public static function obtenerReparacionesActivas(){
        $sql = "SELECT r.*, 
                       c.primer_nombre AS cliente_nombre, c.primer_apellido AS cliente_apellido, 
                       u.primer_nombre AS usuario_nombre, u.primer_apellido AS usuario_apellido,
                       ua.primer_nombre AS tecnico_nombre, ua.primer_apellido AS tecnico_apellido
                FROM reparaciones r 
                INNER JOIN clientes c ON r.id_cliente = c.id_cliente 
                INNER JOIN usuarios u ON r.id_usuario_recibe = u.id_usuario
                LEFT JOIN usuarios ua ON r.id_usuario_asignado = ua.id_usuario
                WHERE r.situacion = 1 
                ORDER BY r.fecha_ingreso DESC";
        return self::fetchArray($sql);
    }
    
    // Método para obtener clientes activos
    public static function obtenerClientesActivos(){
        $sql = "SELECT id_cliente, primer_nombre, primer_apellido FROM clientes WHERE situacion = 1 ORDER BY primer_nombre";
        return self::fetchArray($sql);
    }
    
    // Método para obtener usuarios activos
    public static function obtenerUsuariosActivos(){
        $sql = "SELECT id_usuario, primer_nombre, primer_apellido FROM usuarios WHERE situacion = 1 ORDER BY primer_nombre";
        return self::fetchArray($sql);
    }
}
?>