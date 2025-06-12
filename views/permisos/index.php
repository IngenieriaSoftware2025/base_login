<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Permisos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .card-header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        
        <!-- FORMULARIO SIMPLE -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header text-white text-center">
                        <h3 class="mb-0">
                            <i class="bi bi-key me-2"></i>
                            Gestión de Permisos
                        </h3>
                    </div>
                    <div class="card-body">
                        <form id="FormPermisos">
                            <input type="hidden" id="id_permiso" name="id_permiso">

                            <div class="row">
                                <div class="col-md-6">
                                    <label for="nombre_permiso" class="form-label">Nombre del Permiso *</label>
                                    <input type="text" id="nombre_permiso" name="nombre_permiso" 
                                           class="form-control" 
                                           placeholder="Ej: Administrar Usuarios" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="descripcion" class="form-label">Descripción *</label>
                                    <input type="text" id="descripcion" name="descripcion" 
                                           class="form-control" 
                                           placeholder="Ej: Puede crear, editar y eliminar usuarios" required>
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
                                    <i class="bi bi-search me-1"></i>Ver Permisos
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

        <!-- TABLA SIMPLE -->
        <div class="row" id="seccionTabla" style="display: none;">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-info text-white text-center">
                        <h4 class="mb-0">
                            <i class="bi bi-list-ul me-2"></i>
                            Permisos del Sistema
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="TablePermisos">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No.</th>
                                        <th>Nombre del Permiso</th>
                                        <th>Descripción</th>
                                        <th>Fecha</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="bodyPermisos">
                                    <!-- Aquí se llenan los datos con JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= asset('build/js/permisos/index.js') ?>"></script>
</body>

</html>