<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .gradient-custom-3 {
            background: linear-gradient(to right, rgba(132, 250, 176, 0.5), rgba(143, 211, 244, 0.5));
        }

        .gradient-custom-4 {
            background: linear-gradient(to right, rgba(132, 250, 176, 1), rgba(143, 211, 244, 1));
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
                            <div class="card-body p-5">
                                <h2 class="text-uppercase text-center mb-5">Registro de Usuario</h2>
                                <form id="FormUsuarios">
                                    <input type="hidden" id="id_usuario" name="id_usuario">

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-outline mb-4">
                                                <input type="text" id="primer_nombre" name="primer_nombre" class="form-control form-control-lg"  />
                                                <label class="form-label" for="primer_nombre">Primer Nombre </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-outline mb-4">
                                                <input type="text" id="segundo_nombre" name="segundo_nombre" class="form-control form-control-lg" />
                                                <label class="form-label" for="segundo_nombre">Segundo Nombre</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-outline mb-4">
                                                <input type="text" id="primer_apellido" name="primer_apellido" class="form-control form-control-lg"  />
                                                <label class="form-label" for="primer_apellido">Primer Apellido </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-outline mb-4">
                                                <input type="text" id="segundo_apellido" name="segundo_apellido" class="form-control form-control-lg" />
                                                <label class="form-label" for="segundo_apellido">Segundo Apellido</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-outline mb-4">
                                                <input type="tel" id="telefono" name="telefono" class="form-control form-control-lg" />
                                                <label class="form-label" for="telefono">Teléfono</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-outline mb-4">
                                                <input type="email" id="correo" name="correo" class="form-control form-control-lg"  />
                                                <label class="form-label" for="correo">Correo Electrónico </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-outline mb-4">
                                                <input type="text" id="direccion" name="direccion" class="form-control form-control-lg" />
                                                <label class="form-label" for="direccion">Dirección</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-outline mb-4">
                                                <input type="text" id="dpi" name="dpi" class="form-control form-control-lg" />
                                                <label class="form-label" for="dpi">DPI</label>
                                            </div>
                                        </div>
                                    </div>

                 
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-4">
                                                <label class="form-label" for="id_rol">Rol del Usuario </label>
                                                <select id="id_rol" name="id_rol" class="form-select form-control-lg" required>
                                                    <option value="">Seleccione un rol</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-4">
                                                <label class="form-label" for="fotografia">Fotografía</label>
                                                <input type="file" id="fotografia" name="fotografia" class="form-control form-control-lg" accept="image/" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-outline mb-4">
                                                <input type="password" id="contrasena" name="contrasena" class="form-control form-control-lg"  />
                                                <label class="form-label" for="contrasena">Contraseña </label>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-outline mb-4">
                                                <input type="password" id="contrasena2" name="contrasena2" class="form-control form-control-lg"  />
                                                <label class="form-label" for="contrasena2">Confirmar Contraseña</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-center mb-4">
                                        <button class="btn btn-success me-2" type="submit" id="BtnGuardar">
                                            <i class="bi bi-floppy me-1"></i>Guardar
                                        </button>
                                        <button class="btn btn-warning me-2 d-none" type="button" id="BtnModificar">
                                            <i class="bi bi-pencil-square me-1"></i>Modificar
                                        </button>
                                        <button class="btn btn-secondary me-2" type="reset" id="BtnLimpiar">
                                            <i class="bi bi-arrow-clockwise me-1"></i>Limpiar
                                        </button>
                                        <button class="btn btn-primary" type="button" id="BtnBuscarUsuarios">
                                            <i class="bi bi-search me-1"></i>Buscar Usuarios
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

               
                <div class="row mt-4" id="seccionTabla" style="display: none;">
                    <div class="col-12">
                        <div class="card" style="border-radius: 15px;">
                            <div class="card-body" >
                                <h4 class="text-center mb-4" >Usuarios Registrados</h4>
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover" id="TableUsuarios">
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
    <script src="<?= asset('build/js/registro/index.js') ?>"></script>
</body>

</html>