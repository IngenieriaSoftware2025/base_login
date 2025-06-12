//import { Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import { validarFormulario } from '../funciones';
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";

const FormRoles = document.getElementById('FormRoles');
const BtnGuardar = document.getElementById('BtnGuardar');
const BtnModificar = document.getElementById('BtnModificar');
const BtnLimpiar = document.getElementById('BtnLimpiar');
const BtnBuscar = document.getElementById('BtnBuscar');
const seccionTablaRoles = document.getElementById('seccionTablaRoles');

const GuardarRol = async (event) => {
    event.preventDefault();
    BtnGuardar.disabled = true;

    if (!validarFormulario(FormRoles, ['id_rol'])) {
        Swal.fire({
            position: "center",
            icon: "info",
            title: "FORMULARIO INCOMPLETO",
            text: "Debe completar todos los campos obligatorios",
            showConfirmButton: true,
        });
        BtnGuardar.disabled = false;
        return;
    }

    const body = new FormData(FormRoles);
    const url = '/base_login/roles/guardarAPI';
    const config = {
        method: 'POST',
        body
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje } = datos

        if (codigo == 1) {
            await Swal.fire({
                position: "center",
                icon: "success",
                title: "¡Éxito!",
                text: mensaje,
                showConfirmButton: true,
                timer: 2000
            });

            limpiarTodo();
            BuscarRoles();
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
            title: "Error de conexión",
            text: "No se pudo conectar con el servidor",
            showConfirmButton: true,
        });
    }
    BtnGuardar.disabled = false;
}

const BuscarRoles = async () => {
    const url = `/base_login/roles/buscarAPI`;
    const config = {
        method: 'GET'
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos

        if (codigo == 1) {
            datatable.clear().draw();
            if (data && data.length > 0) {
                datatable.rows.add(data).draw();
            }
        } else {
            await Swal.fire({
                position: "center",
                icon: "info",
                title: "Información",
                text: mensaje,
                showConfirmButton: true,
            });
        }

    } catch (error) {
        console.log(error);
        await Swal.fire({
            position: "center",
            icon: "error",
            title: "Error de conexión",
            text: "No se pudieron cargar los roles",
            showConfirmButton: true,
        });
    }
}

const MostrarTabla = () => {
    if (seccionTablaRoles.style.display === 'none') {
        seccionTablaRoles.style.display = 'block';
        BuscarRoles();
    } else {
        seccionTablaRoles.style.display = 'none';
    }
}

const datatable = new DataTable('#TableRoles', {
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
    columns: [
        {
            title: 'No.',
            data: 'id_rol',
            width: '5%',
            render: (data, type, row, meta) => meta.row + 1
        },
        { 
            title: 'Nombre del Rol', 
            data: 'nombre_rol', 
            width: '25%',
            render: (data) => {
                return `<span class="badge bg-primary fs-6">${data}</span>`;
            }
        },
        { 
            title: 'Código', 
            data: 'nombre_corto', 
            width: '15%',
            render: (data) => {
                return `<code class="bg-light p-1 rounded">${data}</code>`;
            }
        },
        { 
            title: 'Descripción', 
            data: 'descripcion', 
            width: '35%',
            render: (data) => {
                if (data && data.length > 50) {
                    return `<span title="${data}">${data.substring(0, 50)}...</span>`;
                }
                return data;
            }
        },
        { 
            title: 'Fecha Creación', 
            data: 'fecha_creacion', 
            width: '12%', 
            render: (data) => {
                if(data) {
                    const fecha = new Date(data);
                    return fecha.toLocaleDateString('es-GT');
                }
                return '';
            }
        },
        {
            title: 'Acciones',
            data: 'id_rol',
            searchable: false,
            orderable: false,
            width: '8%',
            render: (data, type, row, meta) => {
                return `
                 <div class='d-flex flex-column align-items-center'>
                     <button class='btn btn-warning modificar btn-sm mb-1' 
                         data-id="${data}" 
                         data-nombre-rol="${row.nombre_rol}"  
                         data-nombre-corto="${row.nombre_corto}"
                         data-descripcion="${row.descripcion}"
                         title="Modificar rol">
                         <i class='bi bi-pencil-square'></i>
                     </button>
                     <button class='btn btn-danger eliminar btn-sm' 
                         data-id="${data}"
                         data-nombre="${row.nombre_rol}"
                         title="Eliminar rol">
                        <i class="bi bi-trash3"></i>
                     </button>
                 </div>`;
            }
        }
    ]
});

const llenarFormulario = (event) => {
    const datos = event.currentTarget.dataset;

    document.getElementById('id_rol').value = datos.id;
    document.getElementById('nombre_rol').value = datos.nombreRol;
    document.getElementById('nombre_corto').value = datos.nombreCorto;
    document.getElementById('descripcion').value = datos.descripcion;

    BtnGuardar.classList.add('d-none');
    BtnModificar.classList.remove('d-none');

    // Scroll hacia arriba para mostrar el formulario
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });

    // Mostrar mensaje informativo
    Swal.fire({
        position: "top-end",
        icon: "info",
        title: "Modo Edición",
        text: "Modifica los campos y presiona 'Modificar Rol'",
        showConfirmButton: false,
        timer: 2000,
        toast: true
    });
}

const limpiarTodo = () => {
    FormRoles.reset();
    BtnGuardar.classList.remove('d-none');
    BtnModificar.classList.add('d-none');
    
    // Remover clases de validación
    const inputs = FormRoles.querySelectorAll('.form-control');
    inputs.forEach(input => {
        input.classList.remove('is-invalid', 'is-valid');
    });
}

const ModificarRol = async (event) => {
    event.preventDefault();
    BtnModificar.disabled = true;

    if (!validarFormulario(FormRoles, [''])) {
        Swal.fire({
            position: "center",
            icon: "info",
            title: "FORMULARIO INCOMPLETO",
            text: "Debe completar todos los campos obligatorios",
            showConfirmButton: true,
        });
        BtnModificar.disabled = false;
        return;
    }

    const body = new FormData(FormRoles);
    const url = '/base_login/roles/modificarAPI';
    const config = {
        method: 'POST',
        body
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje } = datos

        if (codigo == 1) {
            await Swal.fire({
                position: "center",
                icon: "success",
                title: "¡Éxito!",
                text: mensaje,
                showConfirmButton: true,
                timer: 2000
            });

            limpiarTodo();
            BuscarRoles();
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
            title: "Error de conexión",
            text: "No se pudo conectar con el servidor",
            showConfirmButton: true,
        });
    }
    BtnModificar.disabled = false;
}

const EliminarRol = async (e) => {
    const idRol = e.currentTarget.dataset.id;
    const nombreRol = e.currentTarget.dataset.nombre;

    const AlertaConfirmarEliminar = await Swal.fire({
        position: "center",
        icon: "question",
        title: `¿Eliminar rol "${nombreRol}"?`,
        html: `
            <p>Esta acción no se puede deshacer.</p>
            <p><strong>Nota:</strong> Si hay usuarios asignados a este rol, no se podrá eliminar.</p>
        `,
        showConfirmButton: true,
        confirmButtonText: 'Sí, Eliminar',
        confirmButtonColor: '#dc3545',
        cancelButtonText: 'No, Cancelar',
        showCancelButton: true,
        focusCancel: true
    });

    if (AlertaConfirmarEliminar.isConfirmed) {
        const url = `/base_login/roles/eliminarAPI?id=${idRol}`;
        const config = {
            method: 'GET'
        }

        try {
            const consulta = await fetch(url, config);
            const respuesta = await consulta.json();
            const { codigo, mensaje } = respuesta;

            if (codigo == 1) {
                await Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "¡Eliminado!",
                    text: mensaje,
                    showConfirmButton: true,
                    timer: 2000
                });
                
                BuscarRoles();
            } else {
                await Swal.fire({
                    position: "center",
                    icon: "warning",
                    title: "No se pudo eliminar",
                    text: mensaje,
                    showConfirmButton: true,
                });
            }

        } catch (error) {
            console.log(error);
            await Swal.fire({
                position: "center",
                icon: "error",
                title: "Error de conexión",
                text: "No se pudo eliminar el rol",
                showConfirmButton: true,
            });
        }
    }
}

// Función para capitalizar automáticamente el nombre del rol
const capitalizarNombre = () => {
    const nombreInput = document.getElementById('nombre_rol');
    nombreInput.addEventListener('input', function() {
        const words = this.value.split(' ');
        const capitalizedWords = words.map(word => {
            return word.charAt(0).toUpperCase() + word.slice(1).toLowerCase();
        });
        this.value = capitalizedWords.join(' ');
    });
}

// Función para convertir automáticamente a mayúsculas el nombre corto
const mayusculasCorto = () => {
    const cortoInput = document.getElementById('nombre_corto');
    cortoInput.addEventListener('input', function() {
        this.value = this.value.toUpperCase();
    });
}

// Inicializar funciones automáticas
capitalizarNombre();
mayusculasCorto();

// Event Listeners
datatable.on('click', '.eliminar', EliminarRol);
datatable.on('click', '.modificar', llenarFormulario);
FormRoles.addEventListener('submit', GuardarRol);
BtnLimpiar.addEventListener('click', limpiarTodo);
BtnModificar.addEventListener('click', ModificarRol);
BtnBuscar.addEventListener('click', MostrarTabla);

