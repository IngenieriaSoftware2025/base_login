<?php

namespace Model;

class HistorialActividades extends ActiveRecord {

    public static $tabla = 'historial_actividades';
    public static $columnasDB = [
        'id_usuario',
        'fecha',
        'id_ruta',
        'ejecucion',
        'situacion'
    ];

    public static $idTabla = 'id_hist_actividad';
    public $id_hist_actividad;
    public $id_usuario;
    public $fecha;
    public $id_ruta;
    public $ejecucion;
    public $situacion;

    public function __construct($args = []){
        $this->id_hist_actividad = $args['id_hist_actividad'] ?? null;
        $this->id_usuario = $args['id_usuario'] ?? '';
        $this->fecha = $args['fecha'] ?? '';
        $this->id_ruta = $args['id_ruta'] ?? '';
        $this->ejecucion = $args['ejecucion'] ?? '';
        $this->situacion = $args['situacion'] ?? 1;
    }
}