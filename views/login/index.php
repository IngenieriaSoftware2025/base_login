<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
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
                                <form id="formlogin">
                                    <div class="form-outline mb-4">
                                        <input type="text" id="username" class="form-control form-control-lg" />
                                        <label class="form-label" for="username">Usuario</label>
                                    </div>
                                    
                                    <div class="form-outline mb-4">
                                        <input type="password" id="password" class="form-control form-control-lg" />
                                        <label class="form-label" for="password">Contraseña</label>
                                    </div>
                                    
                                    <div class="d-flex justify-content-center">
                                        <button id="botoniniciar" type="submit" class="btn btn-success btn-block btn-lg gradient-custom-4 text-body">Iniciar Sesión</button>
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
</body>
</html>

<script src="<?= asset('build/js/login/index.js') ?>"></script>