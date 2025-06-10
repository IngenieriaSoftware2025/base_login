<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white text-center">
                    <h5 class="mb-1">¡Sistema de Gestión de Inventario!</h5>
                    <h4 class="mb-0">ADMINISTRACIÓN DE INVENTARIO DE CELULARES</h4>
                </div>
                <div class="card-body">
                    <form id="FormInventario">
                        <input type="hidden" id="id_inventario" name="id_inventario">
                        
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="id_marca" class="form-label">Marca</label>
                                <select class="form-select" id="id_marca" name="id_marca" required>
                                    <option value="">-- Seleccione una marca --</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="estado_dispositivo" class="form-label">Estado del Dispositivo</label>
                                <select class="form-select" id="estado_dispositivo" name="estado_dispositivo" required>
                                    <option value="NUEVO">NUEVO</option>
                                    <option value="USADO">USADO</option>
                                    <option value="REPARADO">REPARADO</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="estado_inventario" class="form-label">Estado del Inventario</label>
                                <select class="form-select" id="estado_inventario" name="estado_inventario" required>
                                    <option value="DISPONIBLE">DISPONIBLE</option>
                                    <option value="VENDIDO">VENDIDO</option>
                                    <option value="EN_REPARACION">EN REPARACIÓN</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="numero_serie" class="form-label">Número de Serie</label>
                                <input type="text" class="form-control" id="numero_serie" name="numero_serie" placeholder="Ingrese el número de serie">
                            </div>
                            <div class="col-md-4">
                                <label for="stock_disponible" class="form-label">Stock Disponible</label>
                                <input type="number" class="form-control" id="stock_disponible" name="stock_disponible" placeholder="Cantidad" min="1" required>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="precio_compra" class="form-label">Precio de Compra</label>
                                <div class="input-group">
                                    <span class="input-group-text">Q</span>
                                    <input type="number" class="form-control" id="precio_compra" name="precio_compra" placeholder="0.00" step="0.01" min="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="precio_venta" class="form-label">Precio de Venta</label>
                                <div class="input-group">
                                    <span class="input-group-text">Q</span>
                                    <input type="number" class="form-control" id="precio_venta" name="precio_venta" placeholder="0.00" step="0.01" min="0.01" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="observaciones" class="form-label">Observaciones</label>
                                <textarea class="form-control" id="observaciones" name="observaciones" rows="3" placeholder="Observaciones adicionales..."></textarea>
                            </div>
                        </div>
                        
                        <div class="text-center">
                            <button class="btn btn-success me-2" type="submit" id="BtnGuardar">
                                <i class="bi bi-floppy me-1"></i>Guardar
                            </button>
                            <button class="btn btn-warning me-2 d-none" type="button" id="BtnModificar">
                                <i class="bi bi-pencil-square me-1"></i>Modificar
                            </button>
                            <button class="btn btn-info me-2" type="button" id="BtnBuscar">
                                <i class="bi bi-search me-1"></i>Buscar
                            </button>
                            <button class="btn btn-secondary" type="reset" id="BtnLimpiar">
                                <i class="bi bi-arrow-clockwise me-1"></i>Limpiar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h4 class="text-center mb-0">Productos en inventario</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="TableInventario">
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= asset('build/js/inventario/index.js') ?>"></script>