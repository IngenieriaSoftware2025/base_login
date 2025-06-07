<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white text-center">
                    <h5 class="mb-1">¡Sistema de Gestión de Aplicaciones!</h5>
                    <h4 class="mb-0">ADMINISTRACIÓN DE APLICACIONES</h4>
                </div>
                <div class="card-body">
                    <form id="FormAplicaciones">
                        <input type="hidden" id="id_aplicacion" name="id_aplicacion">
                        
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="nombre_app_lg" class="form-label">Nombre Largo</label>
                                <input type="text" class="form-control" id="nombre_app_lg" name="nombre_app_lg" placeholder="Ingrese el nombre largo" required>
                            </div>
                            <div class="col-md-4">
                                <label for="nombre_app_md" class="form-label">Nombre Mediano</label>
                                <input type="text" class="form-control" id="nombre_app_md" name="nombre_app_md" placeholder="Ingrese el nombre mediano" required>
                            </div>
                            <div class="col-md-4">
                                <label for="nombre_app_ct" class="form-label">Nombre Corto</label>
                                <input type="text" class="form-control" id="nombre_app_ct" name="nombre_app_ct" placeholder="Ingrese las siglas" required>
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
                    <h4 class="text-center mb-0">Aplicaciones registradas en el sistema</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="TableAplicacion">
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= asset('build/js/aplicacion/index.js') ?>"></script>