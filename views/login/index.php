<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .gradient-custom-3 {
            background: linear-gradient(to right, rgba(132, 250, 176, 0.5), rgba(143, 211, 244, 0.5));
        }
        
        .gradient-custom-4 {
            background: linear-gradient(to right, rgba(132, 250, 176, 1), rgba(143, 211, 244, 1));
        }
        
        .bg-image {
            background-size: cover;
            background-position: center;
        }
        
        .mask {
            background-color: rgba(0, 0, 0, 0.6);
        }
        
        .card {
            backdrop-filter: blur(10px);
            background-color: rgba(255, 255, 255, 0.95);
        }
    </style>
</head>
<body>
    <section class="vh-100 bg-image"
        style="background-image: url('https://mdbcdn.b-cdn.net/img/Photos/new-templates/search-box/img4.webp');">
        <div class="mask d-flex align-items-center h-100 gradient-custom-3">
            <div class="container h-100">
                <div class="row d-flex justify-content-center align-items-center h-100">
                    <div class="col-12 col-md-9 col-lg-7 col-xl-6">
                        <div class="card" style="border-radius: 15px;">
                            <div class="card-body p-5">
                                <h2 class="text-uppercase text-center mb-5">Iniciar Sesión</h2>
                                
                                <form id="FormLogin">
                                    <div class="form-outline mb-4">
                                        <label class="form-label" for="correo">Correo Electrónico</label>
                                        <input type="email" 
                                               id="correo" 
                                               name="correo" 
                                               class="form-control form-control-lg" 
                                               placeholder="Ingrese su correo electrónico"
                                               required />
                                    </div>
                                    
                                    <div class="form-outline mb-4">
                                        <label class="form-label" for="contrasena">Contraseña</label>
                                        <input type="password" 
                                               id="contrasena" 
                                               name="contrasena" 
                                               class="form-control form-control-lg" 
                                               placeholder="Ingrese su contraseña"
                                               required />
                                    </div>
                                    
                                    <div class="d-flex justify-content-center mb-4">
                                        <button type="submit" 
                                                id="BtnIniciarSesion" 
                                                class="btn btn-success btn-block btn-lg gradient-custom-4 text-body">
                                            <i class="bi bi-box-arrow-in-right me-2"></i>Iniciar Sesión
                                        </button>
                                    </div>

                                    <div class="text-center">
                                        <p class="mb-0">¿No tienes cuenta? 
                                            <a href="/base_login/registro" class="text-decoration-none fw-bold">Regístrate aquí</a>
                                        </p>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="<?= asset('build/js/login/index.js') ?>"></script>
</body>
</html>