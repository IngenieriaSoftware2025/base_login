<div class="row justify-content-center p-3">
    <div class="col-lg-12">
        <div class="card custom-card shadow-lg" style="border-radius: 15px; border: 2px solid #3b82f6; background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);">
            <div class="card-body p-4">
                <div class="row mb-4">
                    <h1 class="text-center mb-2" style="color: #1e3a8a; font-weight: 600; text-shadow: 1px 1px 2px rgba(0,0,0,0.1);">Sistema de Gestión</h1>
                    <h1 class="text-center mb-2" style="color: #2563eb; font-weight: 700; text-shadow: 1px 1px 2px rgba(0,0,0,0.1);">REPARACIÓN DE CELULARES</h1>
                </div>

                <div class="row mb-4">
                    <h5 class="text-center mb-2" style="color: #1d4ed8;">Dashboard</h5>
                    <h4 class="text-center mb-2" style="color: #3b82f6; font-weight: 600;">Estadísticas de Reparaciones por Cliente</h4>
                </div>

                <div class="row p-3 justify-content-center">
                    <div class="col-lg-5 rounded shadow-lg m-2" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border: 1px solid #e2e8f0; padding: 20px;">
                        <canvas id="grafico1" width="400" height="200"></canvas>
                    </div>

                    <div class="col-lg-5 rounded shadow-lg m-2" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border: 1px solid #e2e8f0; padding: 20px;">
                        <canvas id="grafico2" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center p-3">
    <div class="col-lg-12">
        <div class="card custom-card shadow-lg" style="border-radius: 15px; border: 2px solid #10b981; background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);">
            <div class="card-body p-4">
                <div class="row mb-4">
                    <h1 class="text-center mb-2" style="color: #064e3b; font-weight: 600; text-shadow: 1px 1px 2px rgba(0,0,0,0.1);">Sistema de Gestión</h1>
                    <h1 class="text-center mb-2" style="color: #059669; font-weight: 700; text-shadow: 1px 1px 2px rgba(0,0,0,0.1);">INVENTARIO DE CELULARES</h1>
                </div>

                <div class="row mb-4">
                    <h5 class="text-center mb-2" style="color: #047857;">Inventario</h5>
                    <h4 class="text-center mb-2" style="color: #10b981; font-weight: 600;">Distribución por Marcas</h4>
                </div>

                <div class="row p-3 justify-content-center">
                    <div class="col-lg-8 rounded shadow-lg" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border: 1px solid #e5e7eb; padding: 20px;">
                        <canvas id="grafico3" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center p-3">
    <div class="col-lg-12">
        <div class="card custom-card shadow-lg" style="border-radius: 15px; border: 2px solid #f59e0b; background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);">
            <div class="card-body p-4">
                <div class="row mb-4">
                    <h1 class="text-center mb-2" style="color: #92400e; font-weight: 600; text-shadow: 1px 1px 2px rgba(0,0,0,0.1);">Sistema de Gestión</h1>
                    <h1 class="text-center mb-2" style="color: #d97706; font-weight: 700; text-shadow: 1px 1px 2px rgba(0,0,0,0.1);">REPARACIONES MENSUALES</h1>
                </div>

                <div class="row mb-4">
                    <h5 class="text-center mb-2" style="color: #b45309;">Progreso</h5>
                    <h4 class="text-center mb-2" style="color: #f59e0b; font-weight: 600;">Reparaciones por Mes</h4>
                </div>

                <div class="row p-3 justify-content-center">
                    <div class="col-lg-8 rounded shadow-lg" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border: 1px solid #e5e7eb; padding: 20px;">
                        <canvas id="grafico4" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center p-3">
    <div class="col-lg-12">
        <div class="card custom-card shadow-lg" style="border-radius: 15px; border: 2px solid #8b5cf6; background: linear-gradient(135deg, #f3e8ff 0%, #ede9fe 100%);">
            <div class="card-body p-4">
                <div class="row mb-4">
                    <h1 class="text-center mb-2" style="color: #581c87; font-weight: 600; text-shadow: 1px 1px 2px rgba(0,0,0,0.1);">Sistema de Gestión</h1>
                    <h1 class="text-center mb-2" style="color: #7c3aed; font-weight: 700; text-shadow: 1px 1px 2px rgba(0,0,0,0.1);">USUARIOS DEL SISTEMA</h1>
                </div>

                <div class="row mb-4">
                    <h5 class="text-center mb-2" style="color: #6b21a8;">Personal</h5>
                    <h4 class="text-center mb-2" style="color: #8b5cf6; font-weight: 600;">Distribución de Usuarios por Rol</h4>
                </div>

                <div class="row p-3 justify-content-center">
                    <div class="col-lg-6 rounded shadow-lg" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border: 1px solid #e5e7eb; padding: 20px;">
                        <canvas id="grafico5" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= asset('build/js/estadisticas/index.js') ?>"></script>