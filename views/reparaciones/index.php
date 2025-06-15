<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white text-center">
                    <h5 class="mb-1">¡Sistema de Gestión de Reparaciones!</h5>
                    <h4 class="mb-0">ADMINISTRACIÓN DE REPARACIONES DE CELULARES</h4>
                </div>
                <div class="card-body">
                    <form id="FormReparaciones">
                        <input type="hidden" id="id_reparacion" name="id_reparacion">
                        
                        <!-- Primera fila -->
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label for="id_cliente" class="form-label">Cliente</label>
                                <select class="form-select" id="id_cliente" name="id_cliente" >
                                    <option value="">-- Seleccione cliente --</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="id_usuario_recibe" class="form-label">Usuario que Recibe</label>
                                <select class="form-select" id="id_usuario_recibe" name="id_usuario_recibe" >
                                    <option value="">-- Seleccione usuario --</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="id_usuario_asignado" class="form-label">Usuario Asignado</label>
                                <select class="form-select" id="id_usuario_asignado" name="id_usuario_asignado">
                                    <option value="">-- Seleccione usuario --</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="numero_orden" class="form-label">Número de Orden</label>
                                <input type="text" class="form-control" id="numero_orden" name="numero_orden" 
                                       placeholder="REP-001" >
                            </div>
                        </div>
                        
                        <!-- Segunda fila -->
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label for="tipo_celular" class="form-label">Tipo de Celular</label>
                                <input type="text" class="form-control" id="tipo_celular" name="tipo_celular" 
                                       placeholder="Smartphone, Celular básico" >
                            </div>
                            <div class="col-md-3">
                                <label for="marca_celular" class="form-label">Marca del Celular</label>
                                <input type="text" class="form-control" id="marca_celular" name="marca_celular" 
                                       placeholder="Samsung, Apple, Huawei" >
                            </div>
                            <div class="col-md-3">
                                <label for="imei" class="form-label">IMEI</label>
                                <input type="text" class="form-control" id="imei" name="imei" 
                                       placeholder="123456789012345">
                            </div>
                            <div class="col-md-3">
                                <label for="costo_total" class="form-label">Costo Total</label>
                                <input type="number" class="form-control" id="costo_total" name="costo_total" 
                                       placeholder="0.00" step="0.01" min="0">
                            </div>
                        </div>
                        
                        <!-- Tercera fila -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="motivo_ingreso" class="form-label">Motivo de Ingreso</label>
                                <textarea class="form-control" id="motivo_ingreso" name="motivo_ingreso" rows="3" 
                                          placeholder="Describe el problema que presenta el celular" ></textarea>
                            </div>
                            <div class="col-md-6">
                                <label for="diagnostico" class="form-label">Diagnóstico</label>
                                <textarea class="form-control" id="diagnostico" name="diagnostico" rows="3" 
                                          placeholder="Diagnóstico técnico del problema"></textarea>
                            </div>
                        </div>
                        
                        <!-- Cuarta fila -->
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label for="tipo_servicio" class="form-label">Tipo de Servicio</label>
                                <select class="form-select" id="tipo_servicio" name="tipo_servicio">
                                    <option value="">-- Seleccione servicio --</option>
                                    <option value="cambio_pantalla">Cambio de Pantalla</option>
                                    <option value="cambio_bateria">Cambio de Batería</option>
                                    <option value="formateo">Formateo</option>
                                    <option value="limpieza">Limpieza</option>
                                    <option value="reparacion_placa">Reparación de Placa</option>
                                    <option value="cambio_camara">Cambio de Cámara</option>
                                    <option value="cambio_altavoz">Cambio de Altavoz</option>
                                    <option value="otros">Otros</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="estado_reparacion" class="form-label">Estado</label>
                                <select class="form-select" id="estado_reparacion" name="estado_reparacion" >
                                    <option value="recibido">Recibido</option>
                                    <option value="en_proceso">En Proceso</option>
                                    <option value="terminado">Terminado</option>
                                    <option value="entregado">Entregado</option>
                                    <option value="cancelado">Cancelado</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="fecha_asignacion" class="form-label">Fecha Asignación</label>
                                <input type="date" class="form-control" id="fecha_asignacion" name="fecha_asignacion">
                            </div>
                            <div class="col-md-3">
                                <label for="fecha_entrega_real" class="form-label">Fecha Entrega</label>
                                <input type="date" class="form-control" id="fecha_entrega_real" name="fecha_entrega_real">
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
                    <h4 class="text-center mb-0">Reparaciones registradas en el sistema</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="TableReparaciones">
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= asset('build/js/reparaciones/index.js') ?>"></script>