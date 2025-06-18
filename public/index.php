<?php 
require_once __DIR__ . '/../includes/app.php';

use MVC\Router;
use Controllers\AppController;
use Controllers\LoginController;
use Controllers\RegistroController;
use Controllers\MarcasController;
use Controllers\ModelosController;
use Controllers\ClientesController;
use Controllers\InventarioController;
use Controllers\ReparacionesController;
use Controllers\RolesController;
use Controllers\PermisosController;
use Controllers\VentasController;
use Controllers\EstadisticaController;
use Controllers\RolesPermisosController;

$router = new Router();
$router->setBaseURL('/' . $_ENV['APP_NAME']);

//  LOGIN RUTA PRINCIPAL AL ABRIR DESDE DOCKER
$router->get('/', [LoginController::class, 'renderizarPagina']);

// Rutas del de login
$router->get('/', [LoginController::class, 'renderizarPagina']);
$router->get('/login', [LoginController::class, 'renderizarPagina']); //MOSTRAR FORMULARIO DE LOGIN
$router->post('/login', [LoginController::class, 'login']); //PROCESAR DATOS LOGIN 
$router->get('/inicio', [LoginController::class, 'renderInicio']); //PAGINA PRINCIPAL
$router->get('/logout', [LoginController::class, 'logout']); //CERRAR SESION 

// Ruta de prueba
$router->post('/test', [AppController::class, 'testLogin']);


// REGISTRO/USUARIOS
$router->get('/registro', [RegistroController::class, 'renderizarPagina']);
$router->post('/registro/guardarAPI', [RegistroController::class, 'guardarAPI']);
$router->get('/registro/buscarAPI', [RegistroController::class, 'buscarAPI']);
$router->post('/registro/modificarAPI', [RegistroController::class, 'modificarAPI']);
$router->get('/registro/eliminarAPI', [RegistroController::class, 'eliminarAPI']);
$router->get('/registro/obtenerRolesAPI', [RegistroController::class, 'obtenerRolesAPI']);

// MARCAS
$router->get('/marcas', [MarcasController::class, 'renderizarPagina']);
$router->post('/marcas/guardarAPI', [MarcasController::class, 'guardarAPI']);
$router->get('/marcas/buscarAPI', [MarcasController::class, 'buscarAPI']);
$router->post('/marcas/modificarAPI', [MarcasController::class, 'modificarAPI']);
$router->get('/marcas/eliminarAPI', [MarcasController::class, 'eliminarAPI']);

// MODELOS
$router->get('/modelos', [ModelosController::class, 'renderizarPagina']);
$router->post('/modelos/guardarAPI', [ModelosController::class, 'guardarAPI']);
$router->get('/modelos/buscarAPI', [ModelosController::class, 'buscarAPI']);
$router->post('/modelos/modificarAPI', [ModelosController::class, 'modificarAPI']);
$router->get('/modelos/eliminarAPI', [ModelosController::class, 'eliminarAPI']);
$router->get('/modelos/obtenerMarcasAPI', [ModelosController::class, 'obtenerMarcasAPI']);

// CLIENTES
$router->get('/clientes', [ClientesController::class, 'renderizarPagina']);
$router->post('/clientes/guardarAPI', [ClientesController::class, 'guardarAPI']);
$router->get('/clientes/buscarAPI', [ClientesController::class, 'buscarAPI']);
$router->post('/clientes/modificarAPI', [ClientesController::class, 'modificarAPI']);
$router->get('/clientes/eliminarAPI', [ClientesController::class, 'eliminarAPI']);

// INVENTARIO
$router->get('/inventario', [InventarioController::class, 'renderizarPagina']);
$router->post('/inventario/guardarAPI', [InventarioController::class, 'guardarAPI']);
$router->get('/inventario/buscarAPI', [InventarioController::class, 'buscarAPI']);
$router->post('/inventario/modificarAPI', [InventarioController::class, 'modificarAPI']);
$router->get('/inventario/eliminarAPI', [InventarioController::class, 'eliminarAPI']);
$router->get('/inventario/obtenerMarcasAPI', [InventarioController::class, 'obtenerMarcasAPI']);
$router->get('/inventario/obtenerModelosAPI', [InventarioController::class, 'obtenerModelosAPI']);

// REPARACIONES
$router->get('/reparaciones', [ReparacionesController::class, 'renderizarPagina']);
$router->post('/reparaciones/guardarAPI', [ReparacionesController::class, 'guardarAPI']);
$router->get('/reparaciones/buscarAPI', [ReparacionesController::class, 'buscarAPI']);
$router->post('/reparaciones/modificarAPI', [ReparacionesController::class, 'modificarAPI']);
$router->get('/reparaciones/eliminarAPI', [ReparacionesController::class, 'eliminarAPI']);
$router->get('/reparaciones/obtenerClientesAPI', [ReparacionesController::class, 'obtenerClientesAPI']);
$router->get('/reparaciones/obtenerUsuariosAPI', [ReparacionesController::class, 'obtenerUsuariosAPI']);

// ROLES
$router->get('/roles', [RolesController::class, 'renderizarPagina']);
$router->post('/roles/guardarAPI', [RolesController::class, 'guardarAPI']);
$router->get('/roles/buscarAPI', [RolesController::class, 'buscarAPI']);
$router->post('/roles/modificarAPI', [RolesController::class, 'modificarAPI']);
$router->get('/roles/eliminarAPI', [RolesController::class, 'eliminarAPI']);
$router->get('/roles/estadisticasAPI', [RolesController::class, 'estadisticasAPI']);

// PERMISOS
$router->get('/permisos', [PermisosController::class, 'renderizarPagina']);
$router->post('/permisos/guardarAPI', [PermisosController::class, 'guardarAPI']);
$router->get('/permisos/buscarAPI', [PermisosController::class, 'buscarAPI']);
$router->post('/permisos/modificarAPI', [PermisosController::class, 'modificarAPI']);
$router->get('/permisos/eliminarAPI', [PermisosController::class, 'eliminarAPI']);



// VENTAS 
$router->get('/ventas', [VentasController::class, 'renderizarPagina']);
$router->post('/ventas/guardarAPI', [VentasController::class, 'guardarAPI']);
$router->get('/ventas/buscarAPI', [VentasController::class, 'buscarAPI']);
$router->get('/ventas/obtenerClientesAPI', [VentasController::class, 'obtenerClientesAPI']);
$router->get('/ventas/obtenerUsuariosAPI', [VentasController::class, 'obtenerUsuariosAPI']);
$router->get('/ventas/obtenerProductosAPI', [VentasController::class, 'obtenerProductosAPI']);
$router->post('/ventas/guardarDetalleAPI', [VentasController::class, 'guardarDetalleAPI']);
$router->get('/ventas/obtenerDetallesAPI', [VentasController::class, 'obtenerDetallesAPI']);
$router->post('/ventas/modificarAPI', [VentasController::class, 'modificarAPI']);
$router->get('/ventas/eliminarAPI', [VentasController::class, 'eliminarAPI']);





// ROLES-PERMISOS
$router->get('/rolesPermisos', [RolesPermisosController::class, 'renderizarPagina']);
$router->post('/rolesPermisos/guardarAPI', [RolesPermisosController::class, 'guardarAPI']);
$router->get('/rolesPermisos/buscarAPI', [RolesPermisosController::class, 'buscarAPI']);
$router->post('/rolesPermisos/modificarAPI', [RolesPermisosController::class, 'modificarAPI']);
$router->get('/rolesPermisos/eliminarAPI', [RolesPermisosController::class, 'eliminarAPI']);
$router->get('/rolesPermisos/obtenerRolesAPI', [RolesPermisosController::class, 'obtenerRolesAPI']);
$router->get('/rolesPermisos/obtenerPermisosAPI', [RolesPermisosController::class, 'obtenerPermisosAPI']);
$router->get('/rolesPermisos/obtenerUsuariosAPI', [RolesPermisosController::class, 'obtenerUsuariosAPI']);
$router->get('/rolesPermisos/obtenerPermisosPorRolAPI', [RolesPermisosController::class, 'obtenerPermisosPorRolAPI']);
$router->get('/rolesPermisos/estadisticasAPI', [RolesPermisosController::class, 'estadisticasAPI']);


// ESTADÍSTICAS
$router->get('/estadisticas', [EstadisticaController::class, 'renderizarPagina']);
$router->get('/estadisticas/buscarAPI', [EstadisticaController::class, 'buscarAPI']);

$router->comprobarRutas();