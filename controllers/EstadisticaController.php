<?php

namespace Controllers;

use Exception;
use Model\ActiveRecord;
use MVC\Router;

class EstadisticaController extends ActiveRecord
{
    

public static function renderizarPagina(Router $router)
    {
        $router->render('estadisticas/index', []);
    }



     public static function buscarAPI()
    {
        getHeadersApi();
        
        try {
            // Consulta Reparaciones por cliente
            $sqlReparaciones = "SELECT 
                                    c.primer_nombre || ' ' || c.primer_apellido as cliente, 
                                    COUNT(*) as total_reparaciones
                                FROM reparaciones r, clientes c 
                                WHERE r.id_cliente = c.id_cliente 
                                  AND r.situacion = 1 
                                  AND c.situacion = 1
                                GROUP BY 1 
                                ORDER BY 2 DESC";
            $reparaciones = self::fetchArray($sqlReparaciones);

            // Consulta  Inventario por marca
            $sqlInventario = "SELECT 
                                 m.nombre_marca as marca, 
                                 COUNT(*) as cantidad_celulares
                             FROM inventario i, modelos mo, marcas m 
                             WHERE i.id_modelo = mo.id_modelo 
                               AND mo.id_marca = m.id_marca 
                               AND i.situacion = 1 
                               AND mo.situacion = 1 
                               AND m.situacion = 1
                             GROUP BY 1 
                             ORDER BY 2 DESC";
            $inventario = self::fetchArray($sqlInventario);

            // Consulta Reparaciones por mes
            $sqlReparacionesMes = "SELECT 
                                      MONTH(fecha_ingreso) as mes, 
                                      COUNT(*) as total_reparaciones
                                   FROM reparaciones 
                                   WHERE situacion = 1 
                                   GROUP BY 1 
                                   ORDER BY 1";
            $reparacionesMes = self::fetchArray($sqlReparacionesMes);

            // Consulta Usuarios por rol
            $sqlUsuarios = "SELECT 
                               r.nombre_rol as rol, 
                               COUNT(*) as total_usuarios
                           FROM usuarios u, roles r 
                           WHERE u.id_rol = r.id_rol 
                             AND u.situacion = 1 
                             AND r.situacion = 1
                           GROUP BY 1 
                           ORDER BY 2 DESC";
            $usuarios = self::fetchArray($sqlUsuarios);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Estadísticas obtenidas correctamente',
                'reparaciones' => $reparaciones,
                'inventario' => $inventario,
                'reparacionesMes' => $reparacionesMes,
                'usuarios' => $usuarios
            ]);
            
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener las estadísticas',
                'detalle' => $e->getMessage()
            ]);
        }
    }
}