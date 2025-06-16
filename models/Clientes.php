<?php
namespace Model;

class Clientes extends ActiveRecord {
    
    public static $tabla = 'clientes';
    public static $idTabla = 'id_cliente';
    public static $columnasDB = [
        'primer_nombre',
        'segundo_nombre',
        'primer_apellido',
        'segundo_apellido',
        'telefono',
        'dpi',
        'correo',
        'direccion',
        'situacion'
    ];
    
    public $id_cliente;
    public $primer_nombre;
    public $segundo_nombre;
    public $primer_apellido;
    public $segundo_apellido;
    public $telefono;
    public $dpi;
    public $correo;
    public $direccion;
    public $fecha_registro;
    public $situacion;
    
    public function __construct($cliente = [])
    {
        $this->id_cliente = $cliente['id_cliente'] ?? null;
        $this->primer_nombre = $cliente['primer_nombre'] ?? '';
        $this->segundo_nombre = $cliente['segundo_nombre'] ?? '';
        $this->primer_apellido = $cliente['primer_apellido'] ?? '';
        $this->segundo_apellido = $cliente['segundo_apellido'] ?? '';
        $this->telefono = $cliente['telefono'] ?? '';
        $this->dpi = $cliente['dpi'] ?? '';
        $this->correo = $cliente['correo'] ?? '';
        $this->direccion = $cliente['direccion'] ?? '';
        $this->fecha_registro = $cliente['fecha_registro'] ?? '';
        $this->situacion = $cliente['situacion'] ?? 1;
    }
    

    public static function EliminarCliente($id){
        $sql = "UPDATE clientes SET situacion = 0 WHERE id_cliente = $id";
        return self::SQL($sql);
    }

    public static function obtenerClientesActivos(){
        $sql = "SELECT * FROM clientes WHERE situacion = 1 ORDER BY primer_apellido, primer_nombre";
        return self::fetchArray($sql);
    }
}
?>