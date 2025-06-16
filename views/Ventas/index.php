<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white text-center">
                    <h5 class="mb-1">¡Sistema de Gestión de Ventas!</h5>
                    <h4 class="mb-0">ADMINISTRACIÓN DE VENTAS DE CELULARES</h4>
                </div>
                <div class="card-body">
                    <form id="FormVentas">
                        <input type="hidden" id="id_venta" name="id_venta">
                        
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="id_cliente" class="form-label">Cliente *</label>
                                <select class="form-select" id="id_cliente" name="id_cliente" required>
                                    <option value="">Seleccione un cliente</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="id_usuario" class="form-label">Usuario Vendedor *</label>
                                <select class="form-select" id="id_usuario" name="id_usuario" required>
                                    <option value="">Seleccione usuario</option>
                                </select>
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="button" class="btn btn-info w-100" id="BtnCargarInventario">
                                    <i class="bi bi-cart-plus me-1"></i>Cargar Inventario
                                </button>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="metodo_pago" class="form-label">Método de Pago</label>
                                <select class="form-select" id="metodo_pago" name="metodo_pago">
                                    <option value="efectivo">Efectivo</option>
                                    <option value="tarjeta">Tarjeta</option>
                                    <option value="transferencia">Transferencia</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="descuento" class="form-label">Descuento</label>
                                <input type="number" class="form-control" id="descuento" name="descuento" 
                                       placeholder="0.00" step="0.01" min="0" value="0">
                            </div>
                            <div class="col-md-4">
                                <label for="observaciones" class="form-label">Observaciones</label>
                                <input type="text" class="form-control" id="observaciones" name="observaciones" 
                                       placeholder="Observaciones de la venta">
                            </div>
                        </div>
                        
                        <div class="text-center">
                            <button class="btn btn-success me-2" type="submit" id="BtnGuardar" style="display: none;">
                                <i class="bi bi-floppy me-1"></i>Guardar
                            </button>
                            <button class="btn btn-warning me-2 d-none" type="button" id="BtnModificar">
                                <i class="bi bi-pencil-square me-1"></i>Modificar
                            </button>
                            <button class="btn btn-info me-2" type="button" id="BtnBuscar">
                                <i class="bi bi-search me-1"></i>Buscar
                            </button>
                            <button class="btn btn-secondary" type="button" id="BtnCancelar">
                                <i class="bi bi-x-circle me-1"></i>Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Inventario Disponible -->
    <div id="seccionInventario" style="display: none;">
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Inventario Disponible</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th width="5%">Seleccionar</th>
                                        <th width="20%">Marca</th>
                                        <th width="20%">Modelo</th>
                                        <th width="15%">Estado</th>
                                        <th width="15%">Precio</th>
                                        <th width="15%">IMEI</th>
                                        <th width="10%">Acción</th>
                                    </tr>
                                </thead>
                                <tbody id="inventarioDisponible">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Carrito de Compras -->
    <div id="seccionCarrito" style="display: none;">
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">Carrito de Compras</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th>Estado</th>
                                        <th>Precio</th>
                                        <th>Cantidad</th>
                                        <th>Subtotal</th>
                                        <th>IMEI</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody id="carritoItems">
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="4" class="text-end">Subtotal:</td>
                                        <td><span id="subtotalVenta">Q. 0.00</span></td>
                                        <td colspan="2"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="text-end">Descuento:</td>
                                        <td><span id="descuentoVenta">Q. 0.00</span></td>
                                        <td colspan="2"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="text-end">TOTAL FINAL:</td>
                                        <td><span id="totalVenta">Q. 0.00</span></td>
                                        <td colspan="2"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ventas Registradas -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h4 class="text-center mb-0">Ventas registradas en el sistema</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="TableVentas">
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Ver Detalle -->
<div class="modal fade" id="modalDetalleVenta" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalle de Venta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="contenidoDetalleVenta">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script src="<?= asset('build/js/ventas/index.js') ?>"></script>