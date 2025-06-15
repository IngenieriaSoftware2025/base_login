<div class="container mt-4">
    <!-- Formulario de Ventas -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white text-center">
                    <h5 class="mb-1">¡Bienvenido al Sistema de Ventas!</h5>
                    <h4 class="mb-0">REGISTRO DE VENTAS</h4>
                </div>
                <div class="card-body">
                    <form id="FormVentas">
                        <input type="hidden" id="id_venta" name="id_venta">
                        <input type="hidden" id="id_usuario" name="id_usuario" value="1">
                        
                        <!-- Información del Cliente -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="id_cliente" class="form-label">Cliente</label>
                                <select class="form-select" id="id_cliente" name="id_cliente" required>
                                    <option value="">-- Seleccione un cliente --</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="metodo_pago" class="form-label">Método de Pago</label>
                                <select class="form-select" id="metodo_pago" name="metodo_pago" required>
                                    <option value="efectivo">Efectivo</option>
                                    <option value="tarjeta">Tarjeta</option>
                                    <option value="transferencia">Transferencia</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="descuento" class="form-label">Descuento</label>
                                <input type="number" step="0.01" class="form-control" id="descuento" name="descuento" value="0" min="0">
                            </div>
                        </div>

                        <!-- Botón para cargar productos -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <button type="button" class="btn btn-info" id="BtnCargarProductos">
                                    <i class="bi bi-cart-plus me-1"></i>Cargar Productos Disponibles
                                </button>
                            </div>
                        </div>

                        <!-- Sección de Productos Disponibles -->
                        <div id="seccionProductos" style="display: none;">
                            <div class="card mb-4">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0">Productos Disponibles</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover" id="TableProductosDisponibles">
                                            <!-- Tabla completamente dinámica generada por DataTables -->
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Carrito de Compras -->
                        <div id="seccionCarrito" style="display: none;">
                            <div class="card mb-4">
                                <div class="card-header bg-warning text-dark">
                                    <h5 class="mb-0">Carrito de Compras</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="TableCarrito">
                                            <!-- Tabla dinámica del carrito -->
                                        </table>
                                    </div>
                                    
                                    <!-- Resumen de totales -->
                                    <div class="row mt-3">
                                        <div class="col-md-8"></div>
                                        <div class="col-md-4">
                                            <table class="table table-sm">
                                                <tr>
                                                    <td><strong>Subtotal:</strong></td>
                                                    <td class="text-end"><span id="subtotalVenta">Q. 0.00</span></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Descuento:</strong></td>
                                                    <td class="text-end"><span id="descuentoVenta">Q. 0.00</span></td>
                                                </tr>
                                                <tr class="table-info">
                                                    <td><strong>TOTAL:</strong></td>
                                                    <td class="text-end"><strong><span id="totalVenta">Q. 0.00</span></strong></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Observaciones -->
                        <div class="row mb-3" id="seccionObservaciones" style="display: none;">
                            <div class="col-12">
                                <label for="observaciones" class="form-label">Observaciones</label>
                                <textarea class="form-control" id="observaciones" name="observaciones" rows="2" placeholder="Ingrese observaciones adicionales (opcional)"></textarea>
                            </div>
                        </div>
                        
                        <!-- Botones -->
                        <div class="text-center">
                            <button class="btn btn-success me-2" type="submit" id="BtnGuardar" style="display: none;">
                                <i class="bi bi-save me-1"></i>Guardar Venta
                            </button>
                            <button class="btn btn-warning me-2" type="button" id="BtnModificar" style="display: none;">
                                <i class="bi bi-pencil-square me-1"></i>Modificar Venta
                            </button>
                            <button class="btn btn-secondary" type="button" id="BtnLimpiar">
                                <i class="bi bi-arrow-clockwise me-1"></i>Limpiar Todo
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Ventas Registradas -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h4 class="text-center mb-0">Ventas Registradas</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="TableVentas">
                            <!-- La tabla se llena dinámicamente -->
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para ver detalle de venta -->
<div class="modal fade" id="modalDetalleVenta" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalle de Venta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="contenidoDetalleVenta">
                <!-- El contenido se carga dinámicamente -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="ImprimirFactura()">
                    <i class="bi bi-printer me-1"></i>Imprimir
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Estilos y Scripts -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
<script src="<?= asset('build/js/ventas/index.js') ?>"></script>