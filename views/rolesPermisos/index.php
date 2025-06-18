<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white text-center" style="background: linear-gradient(135deg,rgb(114, 70, 196) 0%,rgb(49, 22, 98) 100%);">
                    <h5 class="mb-1">¡Sistema de Gestión de Permisos!</h5>
                    <h4 class="mb-0">ASIGNACIÓN DE PERMISOS A ROLES</h4>
                </div>
                <div class="card-body">
                    <form id="FormRolesPermisos">
                        <input type="hidden" id="id_rol_permiso" name="id_rol_permiso">
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="id_rol" class="form-label">Rol *</label>
                                <select id="id_rol" name="id_rol" class="form-select" required>
                                    <option value="">Seleccione un rol</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="id_permiso" class="form-label">Permiso *</label>
                                <select id="id_permiso" name="id_permiso" class="form-select" required>
                                    <option value="">Seleccione un permiso</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="usuario_asigna" class="form-label">Usuario que Asigna *</label>
                                <select id="usuario_asigna" name="usuario_asigna" class="form-select" required>
                                    <option value="">Seleccione quien asigna</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="motivo_asignacion" class="form-label">Motivo de la Asignación *</label>
                                <textarea id="motivo_asignacion" name="motivo_asignacion" 
                                          class="form-control" rows="3" maxlength="250" required
                                          placeholder="Indique el motivo por el cual este rol necesita este permiso..."></textarea>
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
                <div class="card-header bg-info text-white" style="background: linear-gradient(135deg,rgb(114, 70, 196) 0%,rgb(49, 22, 98) 100%);">
                    <h4 class="text-center mb-0">Permisos asignados a roles en el sistema</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="TableRolesPermisos">
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= asset('build/js/rolesPermisos/index.js') ?>"></script>