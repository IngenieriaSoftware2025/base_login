<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white text-center">
                    <h5 class="mb-1">¡Sistema de Gestión de Permisos!</h5>
                    <h4 class="mb-0">ADMINISTRACIÓN DE PERMISOS</h4>
                </div>
                <div class="card-body">
                    <form id="FormPermisos">
                        <input type="hidden" id="id_permiso" name="id_permiso">
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="id_aplicacion" class="form-label">Aplicación</label>
                                <select class="form-select" id="id_aplicacion" name="id_aplicacion" required>
                                    <option value="">-- Seleccione una aplicación --</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="nombre_permiso" class="form-label">Nombre del Permiso</label>
                                <input type="text" class="form-control" id="nombre_permiso" name="nombre_permiso" placeholder="Ingrese el nombre del permiso" required>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="clave_permiso" class="form-label">Clave del Permiso</label>
                                <input type="text" class="form-control" id="clave_permiso" name="clave_permiso" placeholder="Ingrese la clave del permiso" required>
                            </div>
                            <div class="col-md-6">
                                <label for="descripcion" class="form-label">Descripción</label>
                                <textarea class="form-control" id="descripcion" name="descripcion" rows="3" placeholder="Ingrese la descripción del permiso" required></textarea>
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
                    <h4 class="text-center mb-0">Permisos registrados en el sistema</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="TablePermisos">
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= asset('build/js/permisos/index.js') ?>"></script>