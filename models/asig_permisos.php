<?php

namespace Model;

class AsigPermisos extends ActiveRecord {

    public static $tabla = 'asig_permisos';
    public static $columnasDB = [
        'id_usuario',
        'id_aplicacion',
        'id_permiso',
        'fecha',
        'usuario_asigno',
        'motivo',
        'situacion'
    ];

    public static $idTabla = 'id_asig_permisos';
    public $id_asig_permisos;
    public $id_usuario;
    public $id_aplicacion;
    public $id_permiso;
    public $fecha;
    public $usuario_asigno;
    public $motivo;
    public $situacion;

    public function __construct($args = []){
        $this->id_asig_permisos = $args['id_asig_permisos'] ?? null;
        $this->id_usuario = $args['id_usuario'] ?? '';
        $this->id_aplicacion = $args['id_aplicacion'] ?? '';
        $this->id_permiso = $args['id_permiso'] ?? '';
        $this->fecha = $args['fecha'] ?? '';
        $this->usuario_asigno = $args['usuario_asigno'] ?? '';
        $this->motivo = $args['motivo'] ?? '';
        $this->situacion = $args['situacion'] ?? 1;
    }
}
