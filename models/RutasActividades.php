<?php

namespace Model;

class RutasActividades extends ActiveRecord 
{
    public static $tabla = 'rutas_actividades';
    public static $idTabla = 'ruta_id';
    public static $columnasDB = [
        'ruta_usuario_id',
        'ruta_usuario_nombre',
        'ruta_modulo',
        'ruta_accion',
        'ruta_descripcion',
        'ruta_ip',
        'ruta_ruta',
        'ruta_situacion',
    ];
    
    public $ruta_id;
    public $ruta_usuario_id;
    public $ruta_usuario_nombre;
    public $ruta_modulo;
    public $ruta_accion;
    public $ruta_descripcion;
    public $ruta_ip;
    public $ruta_ruta;
    public $ruta_situacion;
    public $ruta_fecha_creacion;
    
    public function __construct($ruta = [])
    {
        $this->ruta_id = $ruta['ruta_id'] ?? null;
        $this->ruta_usuario_id = $ruta['ruta_usuario_id'] ?? '';
        $this->ruta_usuario_nombre = $ruta['ruta_usuario_nombre'] ?? '';
        $this->ruta_modulo = $ruta['ruta_modulo'] ?? '';
        $this->ruta_accion = $ruta['ruta_accion'] ?? '';
        $this->ruta_descripcion = $ruta['ruta_descripcion'] ?? '';
        $this->ruta_ip = $ruta['ruta_ip'] ?? '';
        $this->ruta_ruta = $ruta['ruta_ruta'] ?? '';
        $this->ruta_situacion = $ruta['ruta_situacion'] ?? 1;
        $this->ruta_fecha_creacion = $ruta['ruta_fecha_creacion'] ?? '';
    }

    // Método estático para obtener actividades con filtros
    public static function obtenerActividadesConFiltros($filtros = [])
    {
        $condiciones = ["ruta_situacion = 1"];

        if (!empty($filtros['fecha_inicio'])) {
            $condiciones[] = "DATE(ruta_fecha_creacion) >= '{$filtros['fecha_inicio']}'";
        }

        if (!empty($filtros['fecha_fin'])) {
            $condiciones[] = "DATE(ruta_fecha_creacion) <= '{$filtros['fecha_fin']}'";
        }

        if (!empty($filtros['usuario_id'])) {
            $condiciones[] = "ruta_usuario_id = {$filtros['usuario_id']}";
        }

        if (!empty($filtros['modulo'])) {
            $condiciones[] = "ruta_modulo = '{$filtros['modulo']}'";
        }

        if (!empty($filtros['accion'])) {
            $condiciones[] = "ruta_accion = '{$filtros['accion']}'";
        }

        $where = implode(" AND ", $condiciones);
        $sql = "SELECT * FROM rutas_actividades WHERE $where ORDER BY ruta_fecha_creacion DESC, ruta_id DESC LIMIT 1000";
        
        return self::fetchArray($sql);
    }

    // Obtener usuarios únicos para filtros
    public static function obtenerUsuariosActivos()
    {
        $sql = "SELECT DISTINCT ruta_usuario_id, ruta_usuario_nombre 
                FROM rutas_actividades 
                WHERE ruta_situacion = 1
                ORDER BY ruta_usuario_nombre";
        return self::fetchArray($sql);
    }
}