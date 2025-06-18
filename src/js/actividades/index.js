import { Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";

const BtnBuscarActividades = document.getElementById('BtnBuscarActividades');
const SelectUsuario = document.getElementById('filtro_usuario');
const SelectModulo = document.getElementById('filtro_modulo');
const SelectAccion = document.getElementById('filtro_accion');
const InputFechaInicio = document.getElementById('fecha_inicio');
const InputFechaFin = document.getElementById('fecha_fin');
const BtnLimpiarFiltros = document.getElementById('BtnLimpiarFiltros');
const seccionTabla = document.getElementById('seccionTabla');

const cargarUsuarios = async () => {
    const url = `/base_login/actividades/buscarUsuariosAPI`;
    const config = {
        method: 'GET'
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos;

        if (codigo == 1) {
            SelectUsuario.innerHTML = '<option value="">Todos los usuarios</option>';
            
            data.forEach(usuario => {
                const option = document.createElement('option');
                option.value = usuario.id_usuario;
                option.textContent = usuario.nombre_usuario;
                SelectUsuario.appendChild(option);
            });
        } else {
            await Swal.fire({
                position: "center",
                icon: "info",
                title: "Error",
                text: mensaje,
                showConfirmButton: true,
            });
        }

    } catch (error) {
        console.log(error);
    }
}

const organizarDatosPorModulo = (data) => {
    const modulos = ['LOGIN', 'USUARIOS', 'CLIENTES', 'MARCAS', 'MODELOS', 'INVENTARIO', 'REPARACIONES', 'VENTAS'];
    const iconos = {
        'LOGIN': '🔐',
        'USUARIOS': '👤',
        'CLIENTES': '👥',
        'MARCAS': '🏷️',
        'MODELOS': '📱',
        'INVENTARIO': '📦',
        'REPARACIONES': '🔧',
        'VENTAS': '💰'
    };
    
    let datosOrganizados = [];
    let contador = 1;
    
    modulos.forEach(modulo => {
        const actividadesModulo = data.filter(actividad => actividad.modulo === modulo);
        
        if (actividadesModulo.length > 0) {
            datosOrganizados.push({
                esSeparador: true,
                modulo: modulo,
                icono: iconos[modulo],
                cantidad: actividadesModulo.length
            });
            
            actividadesModulo.forEach(actividad => {
                datosOrganizados.push({
                    ...actividad,
                    numeroConsecutivo: contador++,
                    esSeparador: false
                });
            });
        }
    });
    
    return datosOrganizados;
}

const BuscarActividades = async () => {
    const params = new URLSearchParams();
    
    if (InputFechaInicio.value) {
        params.append('fecha_inicio', InputFechaInicio.value);
    }
    
    if (InputFechaFin.value) {
        params.append('fecha_fin', InputFechaFin.value);
    }
    
    if (SelectUsuario.value) {
        params.append('id_usuario', SelectUsuario.value);
    }
    
    if (SelectModulo.value) {
        params.append('modulo', SelectModulo.value);
    }
    
    if (SelectAccion.value) {
        params.append('accion', SelectAccion.value);
    }

    const url = `/base_login/actividades/buscarAPI${params.toString() ? '?' + params.toString() : ''}`;
    const config = {
        method: 'GET'
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos;

        if (codigo == 1) {
            console.log('Actividades encontradas:', data);
            
            const datosOrganizados = organizarDatosPorModulo(data);

            if (datatable) {
                datatable.clear().draw();
                datatable.rows.add(datosOrganizados).draw();
            }
        } else {
            await Swal.fire({
                position: "center",
                icon: "info",
                title: "Error",
                text: mensaje,
                showConfirmButton: true,
            });
        }

    } catch (error) {
        console.log(error);
    }
}

const MostrarTabla = () => {
    if (seccionTabla.style.display === 'none') {
        seccionTabla.style.display = 'block';
        BuscarActividades();
    } else {
        seccionTabla.style.display = 'none';
    }
}

const limpiarFiltros = () => {
    SelectUsuario.value = '';
    SelectModulo.value = '';
    SelectAccion.value = '';
    InputFechaInicio.value = '';
    InputFechaFin.value = '';
    
    if (seccionTabla.style.display !== 'none') {
        BuscarActividades();
    }
}

const datatable = new DataTable('#TableActividades', {
    dom: `
        <"row mt-3 justify-content-between" 
            <"col" l> 
            <"col" B> 
            <"col-3" f>
        >
        t
        <"row mt-3 justify-content-between" 
            <"col-md-3 d-flex align-items-center" i> 
            <"col-md-8 d-flex justify-content-end" p>
        >
    `,
    language: lenguaje,
    data: [],
    ordering: false,
    columns: [
        {
            title: 'No.',
            data: null,
            width: '5%',
            render: (data, type, row, meta) => {
                if (row.esSeparador) {
                    return '';
                }
                return row.numeroConsecutivo;
            }
        },
        { 
            title: 'Usuario', 
            data: 'nombre_usuario',
            width: '15%',
            render: (data, type, row, meta) => {
                if (row.esSeparador) {
                    return `<strong class="text-primary fs-5 text-center w-100 d-block">${row.icono} ${row.modulo} (${row.cantidad})</strong>`;
                }
                return data;
            }
        },
        { 
            title: 'Módulo', 
            data: 'modulo',
            width: '10%',
            render: (data, type, row, meta) => {
                if (row.esSeparador) return '';
                return data;
            }
        },
        { 
            title: 'Acción', 
            data: 'accion',
            width: '10%',
            render: (data, type, row, meta) => {
                if (row.esSeparador) return '';
                const acciones = {
                    'CREAR': '<span class="badge bg-success">CREAR</span>',
                    'ACTUALIZAR': '<span class="badge bg-warning text-dark">ACTUALIZAR</span>',
                    'ELIMINAR': '<span class="badge bg-danger">ELIMINAR</span>',
                    'INICIAR_SESION': '<span class="badge bg-info">INICIAR SESIÓN</span>',
                    'CERRAR_SESION': '<span class="badge bg-secondary">CERRAR SESIÓN</span>',
                    'INICIAR': '<span class="badge bg-primary">INICIAR</span>',
                    'FINALIZAR': '<span class="badge bg-success">FINALIZAR</span>',
                    'ENTREGAR': '<span class="badge bg-warning">ENTREGAR</span>'
                };
                return acciones[data] || data;
            }
        },
        { 
            title: 'Descripción', 
            data: 'descripcion',
            width: '25%',
            render: (data, type, row, meta) => {
                if (row.esSeparador) return '';
                return data;
            }
        },
        { 
            title: 'Ruta', 
            data: 'ruta',
            width: '12%',
            render: (data, type, row, meta) => {
                if (row.esSeparador) return '';
                return data || 'N/A';
            }
        },
        { 
            title: 'IP', 
            data: 'ip_usuario',
            width: '10%',
            render: (data, type, row, meta) => {
                if (row.esSeparador) return '';
                return data || 'N/A';
            }
        },
        { 
            title: 'Fecha', 
            data: 'fecha_actividad',
            width: '8%',
            render: (data, type, row, meta) => {
                if (row.esSeparador) return '';
                return data;
            }
        },
        {
            title: 'Situación',
            data: 'situacion',
            width: '5%',
            render: (data, type, row, meta) => {
                if (row.esSeparador) return '';
                return data == 1 ? "ACTIVO" : "INACTIVO";
            }
        }
    ],
    rowCallback: function(row, data) {
        if (data.esSeparador) {
            row.classList.add('table-secondary');
            row.style.backgroundColor = '#f8f9fa';
            row.cells[1].colSpan = 8;
            for (let i = 2; i < row.cells.length; i++) {
                row.cells[i].style.display = 'none';
            }
        }
    }
});

cargarUsuarios();

BtnBuscarActividades.addEventListener('click', MostrarTabla);
BtnLimpiarFiltros.addEventListener('click', limpiarFiltros);
SelectUsuario.addEventListener('change', () => {
    if (seccionTabla.style.display !== 'none') {
        BuscarActividades();
    }
});
SelectModulo.addEventListener('change', () => {
    if (seccionTabla.style.display !== 'none') {
        BuscarActividades();
    }
});
SelectAccion.addEventListener('change', () => {
    if (seccionTabla.style.display !== 'none') {
        BuscarActividades();
    }
});
InputFechaInicio.addEventListener('change', () => {
    if (seccionTabla.style.display !== 'none') {
        BuscarActividades();
    }
});
InputFechaFin.addEventListener('change', () => {
    if (seccionTabla.style.display !== 'none') {
        BuscarActividades();
    }
});