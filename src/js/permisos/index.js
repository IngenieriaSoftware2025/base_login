import { Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import { validarFormulario } from '../funciones';
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";

const FormPermisos = document.getElementById('FormPermisos');
const BtnGuardar = document.getElementById('BtnGuardar');
const BtnModificar = document.getElementById('BtnModificar');
const BtnLimpiar = document.getElementById('BtnLimpiar');
const BtnBuscar = document.getElementById('BtnBuscar');
const SelectAplicacion = document.getElementById('id_aplicacion');

const GuardarPermiso = async (event) => {
    event.preventDefault();
    BtnGuardar.disabled = true;

    if (!validarFormulario(FormPermisos, ['id_permiso'])) {
        Swal.fire({
            position: "center",
            icon: "info",
            title: "FORMULARIO INCOMPLETO",
            text: "Debe completar todos los campos",
            showConfirmButton: true,
        });
        BtnGuardar.disabled = false;
        return;
    }

    const body = new FormData(FormPermisos);
    const url = '/base_login/permisos/guardarAPI';
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
            BuscarPermisos();
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

const BuscarPermisos = async () => {
    const url = `/base_login/permisos/buscarAPI`;
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
                title: "Error",
                text: mensaje,
                showConfirmButton: true,
            });
        }

    } catch (error) {
        console.log(error)
    }
}

const CargarAplicaciones = async () => {
    const url = `/base_login/permisos/obtenerAplicacionesAPI`;
    const config = {
        method: 'GET'
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, data } = datos

        if (codigo == 1) {
            SelectAplicacion.innerHTML = '<option value="">-- Seleccione una aplicación --</option>';
            data.forEach(aplicacion => {
                SelectAplicacion.innerHTML += `<option value="${aplicacion.id_aplicacion}">${aplicacion.nombre_app_md}</option>`;
            });
        }

    } catch (error) {
        console.log(error)
    }
}

const datatable = new DataTable('#TablePermisos', {
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
        {title: 'No.',data: 'id_permiso',width: '5%',render: (data, type, row, meta) => meta.row + 1},
        { title: 'Aplicación', data: 'aplicacion_nombre', width: '20%'},
        { title: 'Nombre Permiso', data: 'nombre_permiso', width: '25%'},
        { title: 'Clave Permiso', data: 'clave_permiso', width: '15%'},
        { title: 'Descripción', data: 'descripcion', width: '25%'},
        { title: 'Fecha Creación', data: 'fecha', width: '15%', render: (data) => {
            if(data) {
                const fecha = new Date(data);
                return fecha.toLocaleDateString('es-GT');
            }
            return '';
        }},
        {
            title: 'Acciones',
            data: 'id_permiso',
            searchable: false,
            orderable: false,
            width: '10%',
            render: (data, type, row, meta) => {
                return `
                    <div class='d-flex flex-column align-items-center'>
                        <button class='btn btn-warning modificar btn-sm mb-1' 
                            data-id="${data}" 
                            data-aplicacion="${row.id_aplicacion}"  
                            data-nombre="${row.nombre_permiso}"
                            data-clave="${row.clave_permiso}"
                            data-descripcion="${row.descripcion}"
                            title="Modificar">
                            <i class='bi bi-pencil-square'></i>
                        </button>
                        <button class='btn btn-danger eliminar btn-sm mb-1' 
                            data-id="${data}"
                            title="Eliminar">
                            <i class="bi bi-trash3"></i>
                        </button>
                    </div>`;
            }
        }
    ]
});

const limpiarTodo = () => {
    FormPermisos.reset();
    BtnGuardar.classList.remove('d-none');
    BtnModificar.classList.add('d-none');
}

const llenarFormulario = (event) => {
    const datos = event.currentTarget.dataset

    document.getElementById('id_permiso').value = datos.id
    document.getElementById('id_aplicacion').value = datos.aplicacion
    document.getElementById('nombre_permiso').value = datos.nombre
    document.getElementById('clave_permiso').value = datos.clave
    document.getElementById('descripcion').value = datos.descripcion

    BtnGuardar.classList.add('d-none');
    BtnModificar.classList.remove('d-none');

    window.scrollTo({
        top: 0,
    })
}

const ModificarPermiso = async (event) => {
    event.preventDefault();
    BtnModificar.disabled = true;

    if (!validarFormulario(FormPermisos, [''])) {
        Swal.fire({
            position: "center",
            icon: "info",
            title: "FORMULARIO INCOMPLETO",
            text: "Debe completar todos los campos",
            showConfirmButton: true,
        });
        BtnModificar.disabled = false;
        return;
    }

    const body = new FormData(FormPermisos);
    const url = '/base_login/permisos/modificarAPI';
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
            BuscarPermisos();
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

const EliminarPermisos = async (e) => {
    const idPermiso = e.currentTarget.dataset.id

    const AlertaConfirmarEliminar = await Swal.fire({
        position: "center",
        icon: "question",
        title: "¿Desea eliminar este permiso?",
        text: 'Esta acción no se puede deshacer',
        showConfirmButton: true,
        confirmButtonText: 'Sí, Eliminar',
        confirmButtonColor: '#dc3545',
        cancelButtonText: 'No, Cancelar',
        showCancelButton: true
    });

    if (AlertaConfirmarEliminar.isConfirmed) {
        const url = `/base_login/permisos/eliminarAPI?id=${idPermiso}`;
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
                
                BuscarPermisos();
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


CargarAplicaciones();
BuscarPermisos();


datatable.on('click', '.eliminar', EliminarPermisos);
datatable.on('click', '.modificar', llenarFormulario);
FormPermisos.addEventListener('submit', GuardarPermiso);
BtnLimpiar.addEventListener('click', limpiarTodo);
BtnModificar.addEventListener('click', ModificarPermiso);
BtnBuscar.addEventListener('click', BuscarPermisos);