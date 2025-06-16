<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white text-center" style="background: linear-gradient(135deg,rgb(114, 70, 196) 0%,rgb(49, 22, 98) 100%);">
                    <h5 class="mb-1">¡Sistema de Gestión de Reparaciones!</h5>
                    <h4 class="mb-0">ADMINISTRACIÓN DE REPARACIONES DE CELULARES</h4>
                </div>
                <div class="card-body">
                    <form id="FormReparaciones">
                        <input type="hidden" id="id_reparacion" name="id_reparacion">
                        
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="id_cliente" class="form-label">Cliente </label>
                                <select class="form-select" id="id_cliente" name="id_cliente" required>
                                    <option value="">Seleccione un cliente</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="id_usuario_recibe" class="form-label">Usuario que Recibe </label>
                                <select class="form-select" id="id_usuario_recibe" name="id_usuario_recibe" required>
                                    <option value="">Seleccione usuario</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="id_usuario_asignado" class="form-label">Técnico Asignado</label>
                                <select class="form-select" id="id_usuario_asignado" name="id_usuario_asignado">
                                    <option value="">Sin asignar</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="tipo_celular" class="form-label">Tipo de Celular</label>
                                <input type="text" class="form-control" id="tipo_celular" name="tipo_celular" 
                                       placeholder="Ej: Smartphone, Tablet">
                            </div>
                            <div class="col-md-4">
                                <label for="marca_celular" class="form-label">Marca</label>
                                <input type="text" class="form-control" id="marca_celular" name="marca_celular" 
                                       placeholder="Ej: Samsung, iPhone, Huawei">
                            </div>
                            <div class="col-md-4">
                                <label for="tipo_servicio" class="form-label">Tipo de Servicio</label>
                                <select class="form-select" id="tipo_servicio" name="tipo_servicio">
                                    <option value="">Seleccione tipo</option>
                                    <option value="Reparación">Reparación</option>
                                    <option value="Mantenimiento">Mantenimiento</option>
                                    <option value="Diagnóstico">Diagnóstico</option>
                                    <option value="Garantía">Garantía</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="motivo_ingreso" class="form-label">Motivo de Ingreso </label>
                                <textarea class="form-control" id="motivo_ingreso" name="motivo_ingreso" rows="3" 
                                          placeholder="Describa el problema reportado por el cliente" required></textarea>
                            </div>
                            <div class="col-md-6">
                                <label for="diagnostico" class="form-label">Diagnóstico Técnico</label>
                                <textarea class="form-control" id="diagnostico" name="diagnostico" rows="3" 
                                          placeholder="Diagnóstico técnico del problema"></textarea>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="estado_reparacion" class="form-label">Estado de Reparación</label>
                                <select class="form-select" id="estado_reparacion" name="estado_reparacion">
                                    <option value="recibido">Recibido</option>
                                    <option value="en_proceso">En Proceso</option>
                                    <option value="terminado">Terminado</option>
                                    <option value="entregado">Entregado</option>
                                    <option value="cancelado">Cancelado</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="costo_total" class="form-label">Costo Total</label>
                                <input type="number" class="form-control" id="costo_total" name="costo_total" 
                                       placeholder="0.00" step="0.01" min="0">
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
                            <button class="btn btn-secondary" type="button" id="BtnCancelar">
                                <i class="bi bi-x-circle me-1"></i>Cancelar
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
                <div class="card-header bg-secondary text-white" style="background: linear-gradient(135deg,rgb(114, 70, 196) 0%,rgb(49, 22, 98) 100%);">
                    <h5 class="mb-0">
                        <i class="bi bi-table me-2"></i>Listado de Reparaciones
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover" id="TableReparaciones">
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= asset('build/js/reparaciones/index.js') ?>"></script>