<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white text-center">
                    <h5 class="mb-1">¡Sistema de Gestión de Modelos!</h5>
                    <h4 class="mb-0">ADMINISTRACIÓN DE MODELOS DE CELULARES</h4>
                </div>
                <div class="card-body">
                    <form id="FormModelos">
                        <input type="hidden" id="id_modelo" name="id_modelo">
                        
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="id_marca" class="form-label">Marca *</label>
                                <select class="form-select" id="id_marca" name="id_marca" required>
                                    <option value="">-- Seleccione una marca --</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="nombre_modelo" class="form-label">Nombre del Modelo *</label>
                                <input type="text" class="form-control" id="nombre_modelo" name="nombre_modelo" 
                                       placeholder="Ej: Galaxy S23, iPhone 14, P30 Pro" required>
                            </div>
                            <div class="col-md-4">
                                <label for="color" class="form-label">Color</label>
                                <input type="text" class="form-control" id="color" name="color" 
                                       placeholder="Ej: Negro, Blanco, Azul">
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
                    <h4 class="text-center mb-0">Modelos registrados en el sistema</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="TableModelos">
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= asset('build/js/modelos/index.js') ?>"></script>