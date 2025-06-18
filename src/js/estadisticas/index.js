import { Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import { Chart } from "chart.js/auto";

const grafico1 = document.getElementById("grafico1").getContext("2d");
const grafico2 = document.getElementById("grafico2").getContext("2d");
const grafico3 = document.getElementById("grafico3").getContext("2d");
const grafico4 = document.getElementById("grafico4").getContext("2d");
const grafico5 = document.getElementById("grafico5").getContext("2d");

// Función para obtener colores dinámicos
function getColorForIndex(index) {
    const colores = [
        'rgba(59, 130, 246, 0.8)',   // Azul
        'rgba(16, 185, 129, 0.8)',   // Verde
        'rgba(245, 158, 11, 0.8)',   // Amarillo/Naranja
        'rgba(139, 92, 246, 0.8)',   // Púrpura
        'rgba(239, 68, 68, 0.8)',    // Rojo
        'rgba(6, 182, 212, 0.8)',    // Cian
        'rgba(34, 197, 94, 0.8)',    // Verde claro
        'rgba(168, 85, 247, 0.8)'    // Violeta
    ];
    
    return colores[index % colores.length];
}

// Crear las gráficas
window.graficaReparacionesClientes = new Chart(grafico1, {
    type: 'bar',
    data: {
        labels: [],
        datasets: []
    },
    options: {
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: 'Top 10 Clientes - Reparaciones',
                color: '#1e3a8a',
                font: {
                    size: 14,
                    weight: 'bold'
                }
            }
        },
        scales: {
            y: { beginAtZero: true }
        }
    }
});

window.graficaReparacionesPie = new Chart(grafico2, {
    type: 'doughnut',
    data: {
        labels: [],
        datasets: []
    },
    options: {
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: 'Distribución de Reparaciones',
                color: '#1e3a8a',
                font: {
                    size: 14,
                    weight: 'bold'
                }
            }
        }
    }
});

window.graficaInventario = new Chart(grafico3, {
    type: 'bar',
    data: {
        labels: [],
        datasets: []
    },
    options: {
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: 'Inventario por Marca',
                color: '#064e3b',
                font: {
                    size: 16,
                    weight: 'bold'
                }
            }
        },
        scales: {
            y: { beginAtZero: true }
        }
    }
});

window.graficaReparacionesMes = new Chart(grafico4, {
    type: 'line',
    data: {
        labels: [],
        datasets: []
    },
    options: {
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: 'Reparaciones por Mes',
                color: '#92400e',
                font: {
                    size: 16,
                    weight: 'bold'
                }
            }
        },
        scales: {
            x: {
                ticks: {
                    color: '#b45309'
                },
                grid: {
                    color: 'rgba(245, 158, 11, 0.1)'
                }
            },
            y: { 
                beginAtZero: true,
                ticks: {
                    color: '#b45309'
                },
                grid: {
                    color: 'rgba(245, 158, 11, 0.1)'
                }
            }
        }
    }
});

window.graficaUsuarios = new Chart(grafico5, {
    type: 'pie',
    data: {
        labels: [],
        datasets: []
    },
    options: {
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: 'Usuarios por Rol',
                color: '#581c87',
                font: {
                    size: 16,
                    weight: 'bold'
                }
            }
        }
    }
});

const BuscarEstadisticas = async () => {
    const url = `/${window.location.pathname.split('/')[1]}/estadisticas/buscarAPI`;
    const config = {
        method: 'GET'
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje, reparaciones, inventario, reparacionesMes, usuarios } = datos;
        
        if (codigo == 1) {
            console.log('Reparaciones:', reparaciones);
            console.log('Inventario:', inventario);
            console.log('Reparaciones por mes:', reparacionesMes);
            console.log('Usuarios:', usuarios);
            
            // GRÁFICA DE REPARACIONES POR CLIENTE
            if (reparaciones && reparaciones.length > 0) {
                const etiquetasClientes = reparaciones.map(r => r.cliente);
                const datosReparaciones = reparaciones.map(r => parseInt(r.total_reparaciones));
                
                window.graficaReparacionesClientes.data.labels = etiquetasClientes;
                window.graficaReparacionesClientes.data.datasets = [{
                    label: 'Total Reparaciones',
                    data: datosReparaciones,
                    backgroundColor: datosReparaciones.map((_, index) => getColorForIndex(index)),
                    borderColor: datosReparaciones.map((_, index) => getColorForIndex(index).replace('0.8', '1')),
                    borderWidth: 1
                }];
                window.graficaReparacionesClientes.update();

                // CHART DE REPARACIONES
                window.graficaReparacionesPie.data.labels = etiquetasClientes.slice(0, 5); // Top 5
                window.graficaReparacionesPie.data.datasets = [{
                    data: datosReparaciones.slice(0, 5),
                    backgroundColor: datosReparaciones.slice(0, 5).map((_, index) => getColorForIndex(index))
                }];
                window.graficaReparacionesPie.update();
            }
            
            // GRÁFICA DE INVENTARIO POR MARCA
            if (inventario && inventario.length > 0) {
                const etiquetasMarcas = inventario.map(i => i.marca);
                const datosCantidad = inventario.map(i => parseInt(i.cantidad_celulares));
                
                window.graficaInventario.data.labels = etiquetasMarcas;
                window.graficaInventario.data.datasets = [{
                    label: 'Cantidad de Celulares',
                    data: datosCantidad,
                    backgroundColor: datosCantidad.map((_, index) => getColorForIndex(index)),
                    borderColor: datosCantidad.map((_, index) => getColorForIndex(index).replace('0.8', '1')),
                    borderWidth: 1
                }];
                window.graficaInventario.update();
            }
            
            // GRÁFICA DE REPARACIONES POR MES
            if (reparacionesMes && reparacionesMes.length > 0) {
                const mesesNombres = ['ENE', 'FEB', 'MAR', 'ABR', 'MAY', 'JUN', 
                                    'JUL', 'AGO', 'SEP', 'OCT', 'NOV', 'DIC'];
                
                const etiquetasMeses = reparacionesMes.map(v => mesesNombres[v.mes - 1]);
                const datosVentasArray = reparacionesMes.map(v => parseInt(v.total_reparaciones));
                
                window.graficaReparacionesMes.data.labels = etiquetasMeses;
                window.graficaReparacionesMes.data.datasets = [{
                    label: 'Reparaciones por mes',
                    data: datosVentasArray,
                    backgroundColor: 'rgba(245, 158, 11, 0.3)',
                    borderColor: 'rgba(245, 158, 11, 1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: 'rgba(245, 158, 11, 1)',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 6
                }];
                window.graficaReparacionesMes.update();
            }

            // GRÁFICA DE USUARIOS POR ROL
            if (usuarios && usuarios.length > 0) {
                const etiquetasRoles = usuarios.map(u => u.rol);
                const datosUsuarios = usuarios.map(u => parseInt(u.total_usuarios));
                
                window.graficaUsuarios.data.labels = etiquetasRoles;
                window.graficaUsuarios.data.datasets = [{
                    data: datosUsuarios,
                    backgroundColor: datosUsuarios.map((_, index) => getColorForIndex(index))
                }];
                window.graficaUsuarios.update();
            }

        } else {
            await Swal.fire({
                position: "center",
                icon: "error",
                title: "Error",
                text: mensaje,
                showConfirmButton: true,
            });
        }

    } catch (error) {
        console.log(error);
        await Swal.fire({
            position: "center",
            icon: "error",
            title: "Error",
            text: "Error al cargar las estadísticas",
            showConfirmButton: true,
        });
    }
}


BuscarEstadisticas();