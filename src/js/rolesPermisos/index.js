//import { Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import { validarFormulario } from '../funciones';
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";

const FormRolesPermisos = document.getElementById('FormRolesPermisos');
const BtnGuardar = document.getElementById('BtnGuardar');
const BtnModificar = document.getElementById('BtnModificar');
const BtnLimpiar = document.getElementById('BtnLimpiar');
const BtnBuscar = document.getElementById('BtnBuscar');
const SelectRol = document.getElementById('id_rol');
const SelectPermiso = document.getElementById('id_permiso');
const SelectUsuario = document.getElementById('usuario_asigna');

const GuardarAsignacion = async (event) => {
    event.preventDefault();
    BtnGuardar.disabled = true;

    if (!validarFormulario(FormRolesPermisos, ['id_rol_permiso'])) {
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

    const body = new FormData(FormRolesPermisos);
    const url = '/base_login/rolesPermisos/guardarAPI';
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
            BuscarAsignaciones();
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

const BuscarAsignaciones = async () => {
    const url = `/base_login/rolesPermisos/buscarAPI`;
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

const CargarRoles = async () => {
    const url = `/base_login/rolesPermisos/obtenerRolesAPI`;
    const config = {
        method: 'GET'
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, data } = datos

        if (codigo == 1) {
            SelectRol.innerHTML = '<option value="">Seleccione un rol</option>';
            data.forEach(rol => {
                SelectRol.innerHTML += `<option value="${rol.id_rol}">${rol.nombre_rol} (${rol.nombre_corto})</option>`;
            });
        }

    } catch (error) {
        console.log(error)
    }
}

const CargarPermisos = async () => {
    const url = `/base_login/rolesPermisos/obtenerPermisosAPI`;
    const config = {
        method: 'GET'
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, data } = datos

        if (codigo == 1) {
            SelectPermiso.innerHTML = '<option value="">Seleccione un permiso</option>';
            data.forEach(permiso => {
                SelectPermiso.innerHTML += `<option value="${permiso.id_permiso}">${permiso.nombre_permiso}</option>`;
            });
        }

    } catch (error) {
        console.log(error)
    }
}

const CargarUsuarios = async () => {
    const url = `/base_login/rolesPermisos/obtenerUsuariosAPI`;
    const config = {
        method: 'GET'
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, data } = datos

        if (codigo == 1) {
            SelectUsuario.innerHTML = '<option value="">Seleccione quien asigna</option>';
            data.forEach(usuario => {
                const nombreCompleto = `${usuario.primer_nombre} 
                ${usuario.segundo_nombre || ''} 
                ${usuario.primer_apellido} 
                ${usuario.segundo_apellido || ''}`.trim();
                SelectUsuario.innerHTML +=
                 `<option value="${usuario.id_usuario}">${nombreCompleto}</option>`;
            });
        }

    } catch (error) {
        console.log(error)
    }
}

const datatable = new DataTable('#TableRolesPermisos', {
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
            data: 'id_rol_permiso',
            width: '5%',
            render: (data, type, row, meta) => meta.row + 1
        },
        { 
            title: 'Rol', 
            data: 'nombre_rol', 
            width: '15%',
            render: (data, type, row) => {
                return `
                    <span class="badge bg-primary">${row.rol_corto}</span><br>
                    <small class="text-muted">${data}</small>
                `;
            }
        },
        { 
            title: 'Permiso', 
            data: 'nombre_permiso', 
            width: '20%',
            render: (data, type, row) => {
                return `
                    <strong>${data}</strong><br>
                    <small class="text-muted">${row.permiso_descripcion}</small>
                `;
            }
        },
        { 
            title: 'Asignado Por', 
            data: 'usuario_asigna_completo', 
            width: '15%'
        },
        { 
            title: 'Motivo', 
            data: 'motivo_asignacion', 
            width: '25%',
            render: (data) => {
                return `<small>${data}</small>`;
            }
        },
        { 
            title: 'Fecha', 
            data: 'fecha_asignacion', 
            width: '10%',
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
            data: 'id_rol_permiso',
            searchable: false,
            orderable: false,
            width: '10%',
            render: (data, type, row, meta) => {
                return `
                 <div class='d-flex justify-content-center'>
                     <button class='btn btn-warning modificar mx-1 btn-sm' 
                         data-id="${data}" 
                         data-rol="${row.id_rol}"  
                         data-permiso="${row.id_permiso}"  
                         data-usuario="${row.usuario_asigna}"
                         data-motivo="${row.motivo_asignacion}">
                         <i class='bi bi-pencil-square me-1'></i> Editar
                     </button>
                     <button class='btn btn-danger eliminar mx-1 btn-sm' 
                         data-id="${data}"
                         data-rol-nombre="${row.nombre_rol}"
                         data-permiso-nombre="${row.nombre_permiso}">
                        <i class="bi bi-trash3 me-1"></i>Eliminar
                     </button>
                 </div>`;
            }
        }
    ]
});

const llenarFormulario = (event) => {
    const datos = event.currentTarget.dataset;

    document.getElementById('id_rol_permiso').value = datos.id;
    document.getElementById('id_rol').value = datos.rol;
    document.getElementById('id_permiso').value = datos.permiso;
    document.getElementById('usuario_asigna').value = datos.usuario;
    document.getElementById('motivo_asignacion').value = datos.motivo;

    BtnGuardar.classList.add('d-none');
    BtnModificar.classList.remove('d-none');

    window.scrollTo({ top: 0 });
}

const limpiarTodo = () => {
    FormRolesPermisos.reset();
    BtnGuardar.classList.remove('d-none');
    BtnModificar.classList.add('d-none');
}

const ModificarAsignacion = async (event) => {
    event.preventDefault();
    BtnModificar.disabled = true;

    if (!validarFormulario(FormRolesPermisos, ['id_rol_permiso'])) {
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

    const body = new FormData(FormRolesPermisos);
    const url = '/base_login/rolesPermisos/modificarAPI';
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
            BuscarAsignaciones();
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

const EliminarAsignacion = async (e) => {
    const idAsignacion = e.currentTarget.dataset.id
    const nombreRol = e.currentTarget.dataset.rolNombre
    const nombrePermiso = e.currentTarget.dataset.permisoNombre

    const AlertaConfirmarEliminar = await Swal.fire({
        position: "center",
        icon: "question",
        title: "¿Desea remover este permiso del rol?",
        html: `¿Desea remover el permiso "<strong>${nombrePermiso}</strong>" del rol "<strong>${nombreRol}</strong>"?<br><small class="text-muted">Esta acción se puede revertir volviendo a asignar el permiso</small>`,
        showConfirmButton: true,
        confirmButtonText: 'Sí, Remover',
        confirmButtonColor: '#dc3545',
        cancelButtonText: 'No, Cancelar',
        showCancelButton: true
    });

    if (AlertaConfirmarEliminar.isConfirmed) {
        const url = `/base_login/rolesPermisos/eliminarAPI?id=${idAsignacion}`;
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
                
                BuscarAsignaciones();
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

// Cargar datos al inicio
CargarRoles();
CargarPermisos();
CargarUsuarios();
BuscarAsignaciones();

// Event listeners
datatable.on('click', '.eliminar', EliminarAsignacion);
datatable.on('click', '.modificar', llenarFormulario);
FormRolesPermisos.addEventListener('submit', GuardarAsignacion);
BtnLimpiar.addEventListener('click', limpiarTodo);
BtnModificar.addEventListener('click', ModificarAsignacion);
BtnBuscar.addEventListener('click', BuscarAsignaciones);