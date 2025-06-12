<?php
//session_start();
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-success text-white text-center">
                    <h3 class="mb-0">
                        <i class="bi bi-house-door-fill me-2"></i>
                        Bienvenido al Sistema de Gestion de Celulares
                    </h3>
                </div>
                <div class="card-body text-center">
                    <div class="row mb-4">
                        <div class="col-12">
                            <h4 class="text-primary">
                                <?= $_SESSION['user'] ?? 'Usuario' ?> 
                            </h4>
                            <p class="lead">
                              
                                <span class="badge bg-primary fs-6"><?= $_SESSION['rol'] ?? 'Sin rol' ?></span>
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <i class="bi bi-people text-primary" style="font-size: 2rem;"></i>
                                    <h6 class="card-title mt-2">Usuarios</h6>
                                    <a href="/base_login/registro" class="btn btn-primary btn-sm">Gestionar</a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 mb-3">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <i class="bi bi-shield-check text-success" style="font-size: 2rem;"></i>
                                    <h6 class="card-title mt-2">Roles</h6>
                                    <a href="/base_login/roles" class="btn btn-success btn-sm">Gestionar</a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 mb-3">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <i class="bi bi-key text-warning" style="font-size: 2rem;"></i>
                                    <h6 class="card-title mt-2">Permisos</h6>
                                    <a href="/base_login/permisos" class="btn btn-warning btn-sm">Gestionar</a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 mb-3">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <i class="bi bi-phone text-info" style="font-size: 2rem;"></i>
                                    <h6 class="card-title mt-2">Inventario</h6>
                                    <a href="/base_login/inventario" class="btn btn-info btn-sm">Ver</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <h5 class="text-muted">Tienes Acceso a:</h5>
                        </div>
                        <div class="col-md-4 mb-2">
                            <a href="/base_login/marcas" class="btn btn-outline-secondary w-100">
                                <i class="bi bi-tag me-2"></i>Marcas
                            </a>
                        </div>
                        <div class="col-md-4 mb-2">
                            <a href="/base_login/clientes" class="btn btn-outline-secondary w-100">
                                <i class="bi bi-person-vcard me-2"></i>Clientes
                            </a>
                        </div>
                        <div class="col-md-4 mb-2">
                            <a href="/base_login/reparaciones" class="btn btn-outline-secondary w-100">
                                <i class="bi bi-tools me-2"></i>Reparaciones
                            </a>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <p class="text-muted">
                                <small>
                                   Sesión iniciada
                                </small>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>