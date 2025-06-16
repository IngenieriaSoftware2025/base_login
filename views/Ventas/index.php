<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Ventas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .card-header {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        
        
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header text-white text-center">
                        <h3 class="mb-0">
                            <i class="bi bi-cart-plus me-2"></i>
                            Sistema de Gestión de Ventas
                        </h3>
                        <p class="mb-0 mt-2">Registra y administra las ventas de celulares</p>
                    </div>
                    <div class="card-body">
                        <form id="FormVentas">
                            <input type="hidden" id="id_venta" name="id_venta">

                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="id_cliente" class="form-label">Cliente </label>
                                    <select class="form-select" id="id_cliente" name="id_cliente" required>
                                        <option value="">-- Seleccione un cliente --</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="id_usuario" class="form-label">Vendedor </label>
                                    <select class="form-select" id="id_usuario" name="id_usuario" required>
                                        <option value="">-- Seleccione un vendedor --</option>
                                    </select>
                                </div>
                            </div>

                           
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="metodo_pago" class="form-label">Método de Pago</label>
                                    <select class="form-select" id="metodo_pago" name="metodo_pago">
                                        <option value="efectivo">Efectivo</option>
                                        <option value="tarjeta">Tarjeta</option>
                                        <option value="transferencia">Transferencia</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="fecha_venta" class="form-label">Fecha de Venta</label>
                                    <input type="date" class="form-control" id="fecha_venta" name="fecha_venta">
                                </div>
                            </div>

                 
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="subtotal" class="form-label">Subtotal</label>
                                    <input type="number" step="0.01" class="form-control" id="subtotal" name="subtotal" value="0">
                                </div>
                                <div class="col-md-4">
                                    <label for="descuento" class="form-label">Descuento</label>
                                    <input type="number" step="0.01" class="form-control" id="descuento" name="descuento" value="0">
                                </div>
                                <div class="col-md-4">
                                    <label for="total" class="form-label">Total </label>
                                    <input type="number" step="0.01" class="form-control" id="total" name="total" required>
                                </div>
                            </div>

                
                            <div class="row mb-3">
                                <div class="col-12">
                                    <label for="observaciones" class="form-label">Observaciones</label>
                                    <textarea class="form-control" id="observaciones" name="observaciones" rows="2" 
                                              placeholder="Comentarios adicionales sobre la venta"></textarea>
                                </div>
                            </div>

                    
                            <div class="row">
                                <div class="col-12 text-center">
                                    <button class="btn btn-success me-2" type="submit" id="BtnGuardar">
                                        <i class="bi bi-floppy me-1"></i>Guardar Venta
                                    </button>
                                    <button class="btn btn-warning me-2 d-none" type="button" id="BtnModificar">
                                        <i class="bi bi-pencil-square me-1"></i>Modificar
                                    </button>
                                    <button class="btn btn-secondary me-2" type="reset" id="BtnLimpiar">
                                        <i class="bi bi-arrow-clockwise me-1"></i>Limpiar
                                    </button>
                                    <button class="btn btn-primary" type="button" id="BtnBuscarVentas">
                                        <i class="bi bi-search me-1"></i>Ver Ventas
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    
        <div class="row mb-4" id="seccionProductos" style="display: none;">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header bg-success text-white text-center">
                        <h4 class="mb-0">
                            <i class="bi bi-plus-circle me-2"></i>
                            Agregar Productos a la Venta
                        </h4>
                    </div>
                    <div class="card-body">
                        <form id="FormDetalle">
                            <input type="hidden" id="id_venta_detalle" name="id_venta">
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="id_inventario" class="form-label">Producto </label>
                                    <select class="form-select" id="id_inventario" name="id_inventario" required>
                                        <option value="">-- Seleccione un producto --</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="precio_unitario" class="form-label">Precio </label>
                                    <input type="number" step="0.01" class="form-control" id="precio_unitario" name="precio_unitario" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="cantidad" class="form-label">Cantidad</label>
                                    <input type="number" class="form-control" id="cantidad" name="cantidad" value="1" min="1">
                                </div>
                            </div>
                            
                            <div class="text-center">
                                <button class="btn btn-primary" type="submit" id="BtnAgregarProducto">
                                    <i class="bi bi-plus-lg me-1"></i>Agregar Producto
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <div class="row mt-4" id="seccionTablaVentas" style="display: none;">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header bg-info text-white text-center">
                        <h4 class="mb-0">
                            <i class="bi bi-list-ul me-2"></i>
                            Ventas Registradas
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="TableVentas">
           
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= asset('build/js/ventas/index.js') ?>"></script>
</body>

</html>