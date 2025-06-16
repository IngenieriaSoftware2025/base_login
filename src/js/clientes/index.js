//import { Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import { validarFormulario } from '../funciones';
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";

const FormClientes = document.getElementById('FormClientes');
const BtnGuardar = document.getElementById('BtnGuardar');
const BtnModificar = document.getElementById('BtnModificar');
const BtnLimpiar = document.getElementById('BtnLimpiar');
const BtnBuscar = document.getElementById('BtnBuscar');

const GuardarCliente = async (event) => {
    event.preventDefault();
    BtnGuardar.disabled = true;

    if (!validarFormulario(FormClientes, ['id_cliente', 'segundo_nombre', 'segundo_apellido', 'telefono', 'dpi', 'correo', 'direccion'])) {
        Swal.fire({
            position: "center",
            icon: "info",
            title: "FORMULARIO INCOMPLETO",
            text: "Debe completar los campos obligatorios",
            showConfirmButton: true,
        });
        BtnGuardar.disabled = false;
        return;
    }

    const body = new FormData(FormClientes);
    const url = '/base_login/clientes/guardarAPI';
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
                title: "Éxito",
                text: mensaje,
                showConfirmButton: true,
            });

            limpiarTodo();
            BuscarClientes();
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
        console.log(error)
    }
    BtnGuardar.disabled = false;
}

const BuscarClientes = async () => {
    const url = `/base_login/clientes/buscarAPI`;
    const config = {
        method: 'GET'
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos

        if (codigo == 1) {
            datatable.clear().draw();
            datatable.rows.add(data).draw();
        } else {
            await Swal.fire({
                position: "center",
                icon: "info",
                title: "Info",
                text: mensaje,
                showConfirmButton: true,
            });
        }

    } catch (error) {
        console.log(error)
    }
}

const datatable = new DataTable('#TableClientes', {
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
            data: 'id_cliente',
            width: '5%',
            render: (data, type, row, meta) => meta.row + 1
        },
        { 
            title: 'Primer Nombre', 
            data: 'primer_nombre', 
            width: '15%' 
        },
        { 
            title: 'Segundo Nombre', 
            data: 'segundo_nombre', 
            width: '15%',
            render: (data) => data || ''
        },
        { 
            title: 'Primer Apellido', 
            data: 'primer_apellido', 
            width: '15%' 
        },
        { 
            title: 'Segundo Apellido', 
            data: 'segundo_apellido', 
            width: '15%',
            render: (data) => data || ''
        },
        { 
            title: 'Teléfono', 
            data: 'telefono', 
            width: '10%',
            render: (data) => data || 'Sin teléfono'
        },
        { 
            title: 'Correo', 
            data: 'correo', 
            width: '15%',
            render: (data) => data || 'Sin correo'
        },
        {
            title: 'Acciones',
            data: 'id_cliente',
            searchable: false,
            orderable: false,
            width: '10%',
            render: (data, type, row, meta) => {
                return `
                 <div class='d-flex justify-content-center'>
                     <button class='btn btn-warning modificar mx-1 btn-sm' 
                         data-id="${data}" 
                         data-primer-nombre="${row.primer_nombre}"  
                         data-segundo-nombre="${row.segundo_nombre || ''}"  
                         data-primer-apellido="${row.primer_apellido}"  
                         data-segundo-apellido="${row.segundo_apellido || ''}"  
                         data-telefono="${row.telefono || ''}"  
                         data-dpi="${row.dpi || ''}"  
                         data-correo="${row.correo || ''}"  
                         data-direccion="${row.direccion || ''}">
                         <i class='bi bi-pencil-square me-1'></i> Editar
                     </button>
                     <button class='btn btn-danger eliminar mx-1 btn-sm' 
                         data-id="${data}">
                        <i class="bi bi-trash3 me-1"></i>Eliminar
                     </button>
                 </div>`;
            }
        }
    ]
});

const llenarFormulario = (event) => {
    const datos = event.currentTarget.dataset;

    document.getElementById('id_cliente').value = datos.id;
    document.getElementById('primer_nombre').value = datos.primerNombre;
    document.getElementById('segundo_nombre').value = datos.segundoNombre;
    document.getElementById('primer_apellido').value = datos.primerApellido;
    document.getElementById('segundo_apellido').value = datos.segundoApellido;
    document.getElementById('telefono').value = datos.telefono;
    document.getElementById('dpi').value = datos.dpi;
    document.getElementById('correo').value = datos.correo;
    document.getElementById('direccion').value = datos.direccion;

    BtnGuardar.classList.add('d-none');
    BtnModificar.classList.remove('d-none');

    window.scrollTo({ top: 0 });
}

const limpiarTodo = () => {
    FormClientes.reset();
    BtnGuardar.classList.remove('d-none');
    BtnModificar.classList.add('d-none');
}

const ModificarCliente = async (event) => {
    event.preventDefault();
    BtnModificar.disabled = true;

    if (!validarFormulario(FormClientes, ['segundo_nombre', 'segundo_apellido', 'telefono', 'dpi', 'correo', 'direccion'])) {
        Swal.fire({
            position: "center",
            icon: "info",
            title: "FORMULARIO INCOMPLETO",
            text: "Debe completar los campos obligatorios",
            showConfirmButton: true,
        });
        BtnModificar.disabled = false;
        return;
    }

    const body = new FormData(FormClientes);
    const url = '/base_login/clientes/modificarAPI';
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
                title: "Éxito",
                text: mensaje,
                showConfirmButton: true,
            });

            limpiarTodo();
            BuscarClientes();
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
        console.log(error)
    }
    BtnModificar.disabled = false;
}

const EliminarCliente = async (e) => {
    const idCliente = e.currentTarget.dataset.id

    const AlertaConfirmarEliminar = await Swal.fire({
        position: "center",
        icon: "question",
        title: "¿Desea eliminar este cliente?",
        text: 'Esta acción no se puede deshacer',
        showConfirmButton: true,
        confirmButtonText: 'Sí, Eliminar',
        confirmButtonColor: '#dc3545',
        cancelButtonText: 'No, Cancelar',
        showCancelButton: true
    });

    if (AlertaConfirmarEliminar.isConfirmed) {
        const url = `/base_login/clientes/eliminarAPI?id=${idCliente}`;
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
                    title: "Éxito",
                    text: mensaje,
                    showConfirmButton: true,
                });
                
                BuscarClientes();
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
            console.log(error)
        }
    }
}


BuscarClientes();


datatable.on('click', '.eliminar', EliminarCliente);
datatable.on('click', '.modificar', llenarFormulario);
FormClientes.addEventListener('submit', GuardarCliente);
BtnLimpiar.addEventListener('click', limpiarTodo);
BtnModificar.addEventListener('click', ModificarCliente);
BtnBuscar.addEventListener('click', BuscarClientes);