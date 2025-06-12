<?php
namespace Model;

class Marcas extends ActiveRecord {
    
    public static $tabla = 'marcas';
    public static $idTabla = 'id_marca';
    public static $columnasDB = [
        'nombre_marca',
        'descripcion',
        'situacion'
    ];
    
    public $id_marca;
    public $nombre_marca;
    public $descripcion;
    public $fecha_creacion;
    public $situacion;
    
    public function __construct($marca = [])
    {
        $this->id_marca = $marca['id_marca'] ?? null;
        $this->nombre_marca = $marca['nombre_marca'] ?? '';
        $this->descripcion = $marca['descripcion'] ?? '';
        $this->fecha_creacion = $marca['fecha_creacion'] ?? '';
        $this->situacion = $marca['situacion'] ?? 1;
    }
    
    // Método para eliminar marca (cambiar situacion = 0)
    public static function EliminarMarca($id){
        $sql = "UPDATE marcas SET situacion = 0 WHERE id_marca = $id";
        return self::SQL($sql);
    }
    
    // Método para buscar marcas activas
    public static function obtenerMarcasActivas(){
        $sql = "SELECT * FROM marcas WHERE situacion = 1 ORDER BY nombre_marca";
        return self::fetchArray($sql);
    }
}
?>