
import Swal from "sweetalert2";
import { validarFormulario } from '../funciones';
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";


const FormUsuarios = document.getElementById('FormUsuarios');
const BtnGuardar = document.getElementById('BtnGuardar');
const BtnModificar = document.getElementById('BtnModificar');
const BtnLimpiar = document.getElementById('BtnLimpiar');
const BtnBuscarUsuarios = document.getElementById('BtnBuscarUsuarios');
const seccionTabla = document.getElementById('seccionTabla');
const SelectRol = document.getElementById('id_rol'); 

// Cargar roles en el dropdown
const CargarRoles = async () => {
    const url = `/base_login/registro/obtenerRolesAPI`;
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
                SelectRol.innerHTML += `<option value="${rol.id_rol}">${rol.nombre_rol}</option>`;
            });
        }

    } catch (error) {
        console.log(error)
    }
}

const GuardarUsuario = async (event) => {
    event.preventDefault();
    BtnGuardar.disabled = true;

    if (!validarFormulario(FormUsuarios, 
        ['id_usuario', 
          'token', 
          'fecha_creacion', 
          'fecha_contrasena', 
          'situacion', 
          'fotografia'])) {
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

    const body = new FormData(FormUsuarios);
    const url = '/base_login/registro/guardarAPI';
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
            BuscarUsuarios();
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

const BuscarUsuarios = async () => {
    const url = `/base_login/registro/buscarAPI`;
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

const MostrarTabla = () => {
    if (seccionTabla.style.display === 'none') {
        seccionTabla.style.display = 'block';
        BuscarUsuarios();
    } else {
        seccionTabla.style.display = 'none';
    }
}

const datatable = new DataTable('#TableUsuarios', {
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
            data: 'id_usuario',
            width: '3%',
            render: (data, type, row, meta) => meta.row + 1
        },
        { title: 'Primer Nombre', data: 'primer_nombre', width: '9%' },
        { title: 'Segundo Nombre', data: 'segundo_nombre', width: '9%' },
        { title: 'Primer Apellido', data: 'primer_apellido', width: '9%' },
        { title: 'Segundo Apellido', data: 'segundo_apellido', width: '9%' },
        { title: 'Correo', data: 'correo', width: '12%' },
        { title: 'Teléfono', data: 'telefono', width: '8%' },
        { title: 'DPI', data: 'dpi', width: '8%' },
        { title: 'Rol', data: 'nombre_rol', width: '8%' }, 
        { title: 'Dirección', data: 'direccion', width: '10%' },
        {
            title: 'Fotografía',
            data: 'fotografia',
            searchable: false,
            orderable: false,
            width: '7%',
            render: (data, type, row) => {
                if (data && data.trim() !== '') {
                    return `<img src="${data}" alt="Foto" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">`;
                } else {
                    return '<span class="text-muted">Sin foto</span>';
                }
            }
        },
        
        {
            title: 'Acciones',
            data: 'id_usuario',
            searchable: false,
            orderable: false,
            width: '8%',
            render: (data, type, row, meta) => {
                return `
                 <div class='d-flex justify-content-center'>
                     <button class='btn btn-warning modificar mx-1 btn-sm' 
                         data-id="${data}" 
                         data-primer_nombre="${row.primer_nombre || ''}"  
                         data-segundo_nombre="${row.segundo_nombre || ''}"  
                         data-primer_apellido="${row.primer_apellido || ''}"  
                         data-segundo_apellido="${row.segundo_apellido || ''}"  
                         data-telefono="${row.telefono || ''}"  
                         data-direccion="${row.direccion || ''}"  
                         data-dpi="${row.dpi || ''}"  
                         data-correo="${row.correo || ''}"
                         data-id_rol="${row.id_rol || ''}">
                         <i class='bi bi-pencil-square me-1'></i> Modificar
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

    document.getElementById('id_usuario').value = datos.id;
    document.getElementById('primer_nombre').value = datos.primer_nombre;
    document.getElementById('segundo_nombre').value = datos.segundo_nombre;
    document.getElementById('primer_apellido').value = datos.primer_apellido;
    document.getElementById('segundo_apellido').value = datos.segundo_apellido;
    document.getElementById('telefono').value = datos.telefono;
    document.getElementById('direccion').value = datos.direccion;
    document.getElementById('dpi').value = datos.dpi;
    document.getElementById('correo').value = datos.correo;
    document.getElementById('id_rol').value = datos.id_rol;

   
    document.getElementById('contrasena').style.display = 'none';
    document.getElementById('contrasena2').style.display = 'none';
    document.querySelector('label[for="contrasena"]').style.display = 'none';
    document.querySelector('label[for="contrasena2"]').style.display = 'none';

    BtnGuardar.classList.add('d-none');
    BtnModificar.classList.remove('d-none');

    window.scrollTo({
        top: 0,
    });
}

const limpiarTodo = () => {
    FormUsuarios.reset();
    
   
    document.getElementById('contrasena').style.display = 'block';
    document.getElementById('contrasena2').style.display = 'block';
    document.querySelector('label[for="contrasena"]').style.display = 'block';
    document.querySelector('label[for="contrasena2"]').style.display = 'block';
    
    BtnGuardar.classList.remove('d-none');
    BtnModificar.classList.add('d-none');
}

const ModificarUsuario = async (event) => {
    event.preventDefault();
    BtnModificar.disabled = true;

    if (!validarFormulario(FormUsuarios, [
        'id_usuario', 
        'token', 
        'fecha_creacion', 
        'fecha_contrasena', 
        'situacion', 
        'fotografia', 
        'contrasena', 
        'contrasena2'])) {
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

    const body = new FormData(FormUsuarios);
    const url = '/base_login/registro/modificarAPI';
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
            BuscarUsuarios();
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

const EliminarUsuarios = async (e) => {
    const idUsuario = e.currentTarget.dataset.id

    const AlertaConfirmarEliminar = await Swal.fire({
        position: "center",
        icon: "question",
        title: "¿Desea eliminar este usuario?",
        text: 'Esta acción no se puede deshacer',
        showConfirmButton: true,
        confirmButtonText: 'Sí, Eliminar',
        confirmButtonColor: '#dc3545',
        cancelButtonText: 'No, Cancelar',
        showCancelButton: true
    });

    if (AlertaConfirmarEliminar.isConfirmed) {
        const url =`/base_login/registro/eliminarAPI?id=${idUsuario}`;
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
                
                BuscarUsuarios();
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


CargarRoles(); 

datatable.on('click', '.eliminar', EliminarUsuarios);
datatable.on('click', '.modificar', llenarFormulario);
FormUsuarios.addEventListener('submit', GuardarUsuario);
BtnLimpiar.addEventListener('click', limpiarTodo);
BtnModificar.addEventListener('click', ModificarUsuario);
BtnBuscarUsuarios.addEventListener('click', MostrarTabla);