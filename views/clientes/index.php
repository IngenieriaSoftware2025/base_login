<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white text-center">
                    <h5 class="mb-1">¡Sistema de Gestión de Clientes!</h5>
                    <h4 class="mb-0">ADMINISTRACIÓN DE CLIENTES</h4>
                </div>
                <div class="card-body">
                    <form id="FormClientes">
                        <input type="hidden" id="id_cliente" name="id_cliente">
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="primer_nombre" class="form-label">Primer Nombre </label>
                                <input type="text" class="form-control" id="primer_nombre" name="primer_nombre" 
                                       placeholder="Ingrese el primer nombre" required>
                            </div>
                            <div class="col-md-6">
                                <label for="segundo_nombre" class="form-label">Segundo Nombre</label>
                                <input type="text" class="form-control" id="segundo_nombre" name="segundo_nombre" 
                                       placeholder="Ingrese el segundo nombre">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="primer_apellido" class="form-label">Primer Apellido </label>
                                <input type="text" class="form-control" id="primer_apellido" name="primer_apellido" 
                                       placeholder="Ingrese el primer apellido" required>
                            </div>
                            <div class="col-md-6">
                                <label for="segundo_apellido" class="form-label">Segundo Apellido</label>
                                <input type="text" class="form-control" id="segundo_apellido" name="segundo_apellido" 
                                       placeholder="Ingrese el segundo apellido">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="tel" class="form-control" id="telefono" name="telefono" 
                                       placeholder="Ej: 12345678">
                            </div>
                            <div class="col-md-6">
                                <label for="dpi" class="form-label">DPI</label>
                                <input type="text" class="form-control" id="dpi" name="dpi" 
                                       placeholder="Ej: 1234567890123">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="correo" class="form-label">Correo Electrónico</label>
                                <input type="email" class="form-control" id="correo" name="correo" 
                                       placeholder="Ej: cliente@correo.com">
                            </div>
                            <div class="col-md-6">
                                <label for="direccion" class="form-label">Dirección</label>
                                <input type="text" class="form-control" id="direccion" name="direccion" 
                                       placeholder="Ingrese la dirección completa">
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
                    <h4 class="text-center mb-0">Clientes registrados en el sistema</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="TableClientes">
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= asset('build/js/clientes/index.js') ?>"></script>