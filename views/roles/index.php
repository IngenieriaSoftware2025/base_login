<div class="container mt-4">
    

    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header text-white text-center" style="background: linear-gradient(135deg, #6f42c1 0%, #e83e8c 100%);">
                    <h3 class="mb-0">
                        <i class="bi bi-shield-check me-2"></i>
                        Gestión de Roles
                    </h3>
                </div>
                <div class="card-body">
                    <form id="FormRoles">
                        <input type="hidden" id="id_rol" name="id_rol">

                        <div class="row">
                            <div class="col-md-4">
                                <label for="nombre_rol" class="form-label">Nombre del Rol </label>
                                <input type="text" id="nombre_rol" name="nombre_rol" 
                                       class="form-control" 
                                       placeholder="Ej: Administrador" >
                            </div>
                            <div class="col-md-4">
                                <label for="nombre_corto" class="form-label">Código </label>
                                <input type="text" id="nombre_corto" name="nombre_corto" 
                                       class="form-control" 
                                       placeholder="Ej: ADMIN"  maxlength="25">
                            </div>
                            <div class="col-md-4">
                                <label for="descripcion" class="form-label">Descripción</label>
                                <input type="text" id="descripcion" name="descripcion" 
                                       class="form-control" 
                                       placeholder="Ej: Acceso completo al sistema">
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <button class="btn btn-success me-2" type="submit" id="BtnGuardar">
                                <i class="bi bi-floppy me-1"></i>Guardar
                            </button>
                            <button class="btn btn-warning me-2 d-none" type="button" id="BtnModificar">
                                <i class="bi bi-pencil-square me-1"></i>Modificar
                            </button>
                            <button class="btn btn-info me-2" type="button" id="BtnBuscar">
                                <i class="bi bi-search me-1"></i>Ver Roles
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


    <div class="row" id="seccionTabla" style="display: none;">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info text-white text-center">
                    <h4 class="mb-0">
                        <i class="bi bi-list-ul me-2"></i>
                        Roles del Sistema
                    </h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="TableRoles">
                            <thead class="table-dark">
                                <tr>
                                    <th>No.</th>
                                    <th>Nombre del Rol</th>
                                    <th>Código</th>
                                    <th>Descripción</th>
                                    <th>Fecha</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="bodyRoles">
                              
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= asset('build/js/roles/index.js') ?>"></script>