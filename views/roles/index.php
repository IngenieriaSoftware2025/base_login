<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Roles</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .gradient-custom-3 {
            background: linear-gradient(to right, rgba(132, 250, 176, 0.5), rgba(143, 211, 244, 0.5));
        }

        .gradient-custom-4 {
            background: linear-gradient(to right, rgba(132, 250, 176, 1), rgba(143, 211, 244, 1));
        }

        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
    </style>
</head>

<body>
    <section class="vh-100 bg-image" style="background-image: url('https://mdbcdn.b-cdn.net/img/Photos/new-templates/search-box/img4.webp');">
        <div class="mask d-flex align-items-center h-100 gradient-custom-3">
            <div class="container h-100">
                <div class="row d-flex justify-content-center align-items-center h-100">
                    <div class="col-12 col-md-10 col-lg-8 col-xl-7">
                        <div class="card" style="border-radius: 15px;">
                            <div class="card-header text-white text-center" style="border-radius: 15px 15px 0 0;">
                                <h3 class="mb-0">
                                    <i class="bi bi-shield-check me-2"></i>
                                    Gestión de Roles del Sistema
                                </h3>
                                <p class="mb-0 mt-2">Administra los roles y permisos de usuarios</p>
                            </div>
                            <div class="card-body p-5">
                                <form id="FormRoles">
                                    <input type="hidden" id="id_rol" name="id_rol">

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-outline mb-4">
                                                <label class="form-label" for="nombre_rol">Nombre del Rol *</label>
                                                <input type="text" id="nombre_rol" name="nombre_rol" 
                                                       class="form-control form-control-lg" 
                                                       placeholder="Ej: Administrador, Vendedor..." required />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-outline mb-4">
                                                <label class="form-label" for="nombre_corto">Nombre Corto *</label>
                                                <input type="text" id="nombre_corto" name="nombre_corto" 
                                                       class="form-control form-control-lg" 
                                                       placeholder="Ej: ADMIN, VENDEDOR..." 
                                                       maxlength="25" required />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-outline mb-4">
                                                <label class="form-label" for="descripcion">Descripción del Rol *</label>
                                                <textarea id="descripcion" name="descripcion" 
                                                          class="form-control form-control-lg" 
                                                          rows="3" 
                                                          placeholder="Describe las funciones y permisos de este rol..."
                                                          required></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-center mb-4">
                                        <button class="btn btn-success me-2" type="submit" id="BtnGuardar">
                                            <i class="bi bi-floppy me-1"></i>Guardar Rol
                                        </button>
                                        <button class="btn btn-warning me-2 d-none" type="button" id="BtnModificar">
                                            <i class="bi bi-pencil-square me-1"></i>Modificar Rol
                                        </button>
                                        <button class="btn btn-info me-2" type="button" id="BtnBuscar">
                                            <i class="bi bi-search me-1"></i>Buscar Roles
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

                <!-- TABLA DE ROLES -->
                <div class="row mt-4" id="seccionTablaRoles" style="display: none;">
                    <div class="col-12">
                        <div class="card" style="border-radius: 15px;">
                            <div class="card-header bg-info text-white text-center" style="border-radius: 15px 15px 0 0;">
                                <h4 class="mb-0">
                                    <i class="bi bi-list-ul me-2"></i>
                                    Roles Registrados en el Sistema
                                </h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover" id="TableRoles">
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= asset('build/js/roles/index.js') ?>"></script>
</body>

</html>