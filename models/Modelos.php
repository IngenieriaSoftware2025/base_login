<?php
namespace Model;

class Modelos extends ActiveRecord {
    
    public static $tabla = 'modelos';
    public static $idTabla = 'id_modelo';
    public static $columnasDB = [
        'id_marca',
        'nombre_modelo',
        'color',
        'situacion'
    ];
    
    public $id_modelo;
    public $id_marca;
    public $nombre_modelo;
    public $color;
    public $fecha_creacion;
    public $situacion;
    
    public function __construct($modelo = [])
    {
        $this->id_modelo = $modelo['id_modelo'] ?? null;
        $this->id_marca = $modelo['id_marca'] ?? '';
        $this->nombre_modelo = $modelo['nombre_modelo'] ?? '';
        $this->color = $modelo['color'] ?? '';
        $this->fecha_creacion = $modelo['fecha_creacion'] ?? '';
        $this->situacion = $modelo['situacion'] ?? 1;
    }
    
    // Método para eliminar modelo (cambiar situacion = 0)
    public static function EliminarModelo($id){
        $sql = "UPDATE modelos SET situacion = 0 WHERE id_modelo = $id";
        return self::SQL($sql);
    }
    
    // Método para buscar modelos activos con información de marca
    public static function obtenerModelosActivos(){
        $sql = "SELECT m.id_modelo, m.id_marca, m.nombre_modelo, m.color, m.fecha_creacion, m.situacion, 
                       ma.nombre_marca 
                FROM modelos m 
                INNER JOIN marcas ma ON m.id_marca = ma.id_marca 
                WHERE m.situacion = 1 AND ma.situacion = 1 
                ORDER BY ma.nombre_marca, m.nombre_modelo";
        return self::fetchArray($sql);
    }
    
    // Método para obtener modelos por marca
    public static function obtenerModelosPorMarca($id_marca){
        $sql = "SELECT * FROM modelos WHERE id_marca = $id_marca AND situacion = 1 ORDER BY nombre_modelo";
        return self::fetchArray($sql);
    }
    
    // Método para obtener marcas activas para el dropdown
    public static function obtenerMarcasActivas(){
        $sql = "SELECT id_marca, nombre_marca FROM marcas WHERE situacion = 1 ORDER BY nombre_marca";
        return self::fetchArray($sql);
    }
}
?>