<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="build/js/app.js"></script>
    <link rel="shortcut icon" href="<?= asset('images/cit.png') ?>" type="image/x-icon">
    <link rel="stylesheet" href="<?= asset('build/styles.css') ?>">
    <title>proyecto</title>
    <style>
        .nav-select {
            background: rgba(37, 50, 83, 0.9);
            color: white;
            border: 1px solid rgba(52, 42, 42, 0.3);
            border-radius: 6px;
            padding: 0.4rem 0.8rem;
            margin: 0 0.3rem;
            font-size: 0.9rem;
        }

        .nav-select:hover {
            background: rgb(87, 85, 164);
            border-color: #4a90e2;
        }

        .nav-select:focus {
            background: rgba(30, 50, 100, 1);
            border-color: #4a90e2;
            outline: none;
            box-shadow: 0 0 0 2px rgba(74, 144, 226, 0.3);
        }

        .nav-select option {
            background: #1a2a56;
            color: white;
            padding: 0.5rem;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark" style="background: linear-gradient(135deg,rgb(152, 65, 158) 0%,rgb(37, 53, 117) 100%);">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggler" aria-controls="navbarToggler" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand" href="/base_login/inicio">
                <img src="<?= asset('./images/celular.png') ?>" width="35px'" alt="cit">
                Inicio
            </a>
            <div class="collapse navbar-collapse" id="navbarToggler">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0" style="margin: 0;">


                    <li class="nav-item">
                        <select class="nav-select" onchange="if(this.value) window.location.href=this.value">
                            <option value="">Autenticación</option>
                            <option value="/base_login/login"><i class="bi bi-door-open"></i> Login</option>
                            <option value="/base_login/registro"><i class="bi bi-person"></i> Registro</option>
                        </select>
                    </li>


                    <li class="nav-item">
                        <select class="nav-select" onchange="if(this.value) window.location.href=this.value">
                            <option value="">Catálogos</option>
                            <option value="/base_login/marcas"><i class="bi bi-people"></i> Marcas</option>
                            <option value="/base_login/modelos"><i class="bi bi-phone"></i> Modelos</option>
                            <option value="/base_login/clientes"><i class="bi bi-person-vcard"></i> Clientes</option>
                        </select>
                    </li>


                    <li class="nav-item">
                        <select class="nav-select" onchange="if(this.value) window.location.href=this.value">
                            <option value="">Operaciones</option>
                            <option value="/base_login/inventario"><i class="bi bi-box-seam"></i> Inventario</option>
                            <option value="/base_login/reparaciones"><i class="bi bi-tools"></i> Reparaciones</option>
                            <option value="/base_login/ventas"><i class="bi bi-tools"></i> Ventas</option>
                        </select>
                    </li>


                    <li class="nav-item">
                        <select class="nav-select" onchange="if(this.value) window.location.href=this.value">
                            <option value="">Administración</option>
                            <option value="/base_login/roles"><i class="bi bi-shield-check"></i> Roles</option>
                            <option value="/base_login/permisos"><i class="bi bi-key"></i> Permisos</option>
                        </select>
                    </li>

                    <div class="nav-item dropdown ">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-gear me-2"></i>Dropdown
                        </a>
                        <ul class="dropdown-menu  dropdown-menu-dark " id="dropwdownRevision" style="margin: 0;">
                            <li>
                                <a class="dropdown-item nav-link text-white " href="/aplicaciones/nueva"><i class="ms-lg-0 ms-2 bi bi-plus-circle me-2"></i>Subitem</a>
                            </li>
                        </ul>
                    </div>

                </ul>
              
                <?php
                session_start();
                if (isset($_SESSION['user'])):
                ?>
                    <div class="d-flex align-items-center me-3">
                        <span class="text-white me-3">
                            <i class="bi bi-person-circle me-1"></i>
                            <?= $_SESSION['user'] ?> (<?= $_SESSION['rol'] ?>)
                        </span>
                    </div>
                    <div class="col-lg-2 d-grid mb-lg-0 mb-2">
                        <div class="d-flex gap-2">
                            <a href="/base_login/logout" class="btn btn-danger">
                                <i class="bi bi-box-arrow-right"></i>Cerrar Sesion
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="col-lg-1 d-grid mb-lg-0 mb-2">
                        <a href="/base_login/login" class="btn btn-primary">
                            <i class="bi bi-box-arrow-in-right"></i>Login
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="progress fixed-bottom" style="height: 6px;">
        <div class="progress-bar progress-bar-animated bg-danger" id="bar" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
    </div>

    <div class="container-fluid pt-5 mb-4" style="min-height: 85vh">
        <?php echo $contenido; ?>
    </div>

    <div class="container-fluid ">
        <div class="row justify-content-center text-center">
            <div class="col-12">
                <p style="font-size:xx-small; font-weight: bold;">
                    Comando de Informática y Tecnología, <?= date('Y') ?> &copy;
                </p>
            </div>
        </div>
    </div>


</body>

</html>