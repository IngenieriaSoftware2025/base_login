<?php
namespace Model;
use Model\ActiveRecord;

class Usuarios extends ActiveRecord {
    
    public static $tabla = 'usuarios';
    public static $idTabla = 'id_usuario';
    public static $columnasDB = 
    [
        'primer_nombre',
        'segundo_nombre',
        'primer_apellido',
        'segundo_apellido',
        'telefono',
        'direccion',
        'dpi',
        'correo',
        'contrasena',
        'token',
        // 'fecha_creacion',
        // 'fecha_contrasena',
        'fotografia',
        'situacion'
    ];
    
    public $id_usuario;
    public $primer_nombre;
    public $segundo_nombre;
    public $primer_apellido;
    public $segundo_apellido;
    public $telefono;
    public $direccion;
    public $dpi;
    public $correo;
    public $contrasena;
    public $token;
    public $fecha_creacion;
    public $fecha_contrasena;
    public $fotografia;
    public $situacion;
    
    public function __construct($usuario = [])
    {
        $this->id_usuario = $usuario['id_usuario'] ?? null;
        $this->primer_nombre = $usuario['primer_nombre'] ?? '';
        $this->segundo_nombre = $usuario['segundo_nombre'] ?? '';
        $this->primer_apellido = $usuario['primer_apellido'] ?? '';
        $this->segundo_apellido = $usuario['segundo_apellido'] ?? '';
        $this->telefono = $usuario['telefono'] ?? '';
        $this->direccion = $usuario['direccion'] ?? '';
        $this->dpi = $usuario['dpi'] ?? '';
        $this->correo = $usuario['correo'] ?? '';
        $this->contrasena = $usuario['contrasena'] ?? '';
        $this->token = $usuario['token'] ?? '';
       // $this->fecha_creacion = $usuario['fecha_creacion'] ?? '';
       // $this->fecha_contrasena = $usuario['fecha_contrasena'] ?? '';
        $this->fotografia = $usuario['fotografia'] ?? '';
        $this->situacion = $usuario['situacion'] ?? 1;
    }
    
     public static function EliminarUsuarios($id){
        $sql = "DELETE FROM usuarios WHERE id_usuario = $id";
        return self::SQL($sql);
    }
    }
