import Swal from "sweetalert2";
import { validarFormulario } from '../funciones';
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";

const FormReparaciones = document.getElementById('FormReparaciones');
const BtnGuardar = document.getElementById('BtnGuardar');
const BtnModificar = document.getElementById('BtnModificar');
const BtnCancelar = document.getElementById('BtnCancelar');
const BtnBuscar = document.getElementById('BtnBuscar');

const SelectCliente = document.getElementById('id_cliente');
const SelectUsuarioRecibe = document.getElementById('id_usuario_recibe');
const SelectUsuarioAsignado = document.getElementById('id_usuario_asignado');

const GuardarReparacion = async (event) => {
    event.preventDefault();
    BtnGuardar.disabled = true;

    if (!validarFormulario(FormReparaciones, ['id_reparacion', 'id_usuario_asignado', 'tipo_celular', 'marca_celular', 'diagnostico', 'tipo_servicio', 'costo_total'])) {
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

    const body = new FormData(FormReparaciones);
    const url = '/base_login/reparaciones/guardarAPI';
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
            BuscarReparaciones();
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

const BuscarReparaciones = async () => {
    const url = `/base_login/reparaciones/buscarAPI`;
    const config = {
        method: 'GET'
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, data } = datos

        if (codigo == 1) {
            datatable.clear();
            datatable.rows.add(data).draw();
        }

    } catch (error) {
        console.log(error)
    }
}

const llenarFormulario = (e) => {
    const button = e.currentTarget;
    const id = button.getAttribute('data-id');
    
    document.getElementById('id_reparacion').value = id;
    document.getElementById('id_cliente').value = button.getAttribute('data-cliente');
    document.getElementById('id_usuario_recibe').value = button.getAttribute('data-usuario-recibe');
    document.getElementById('id_usuario_asignado').value = button.getAttribute('data-usuario-asignado');
    document.getElementById('tipo_celular').value = button.getAttribute('data-tipo-celular');
    document.getElementById('marca_celular').value = button.getAttribute('data-marca-celular');
    document.getElementById('motivo_ingreso').value = button.getAttribute('data-motivo');
    document.getElementById('diagnostico').value = button.getAttribute('data-diagnostico');
    document.getElementById('tipo_servicio').value = button.getAttribute('data-tipo-servicio');
    document.getElementById('estado_reparacion').value = button.getAttribute('data-estado');
    document.getElementById('costo_total').value = button.getAttribute('data-costo');

    BtnGuardar.classList.add('d-none');
    BtnModificar.classList.remove('d-none');
    BtnCancelar.classList.remove('d-none');
}

const limpiarTodo = () => {
    FormReparaciones.reset();
    BtnGuardar.classList.remove('d-none');
    BtnModificar.classList.add('d-none');
    BtnCancelar.classList.add('d-none');
}

const ModificarReparacion = async () => {
    BtnModificar.disabled = true;

    if (!validarFormulario(FormReparaciones, ['id_usuario_asignado', 'tipo_celular', 'marca_celular', 'diagnostico', 'tipo_servicio', 'costo_total'])) {
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

    const body = new FormData(FormReparaciones);
    const url = '/base_login/reparaciones/modificarAPI';
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
            BuscarReparaciones();
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

const EliminarReparacion = async (e) => {
    const button = e.currentTarget;
    const idReparacion = button.getAttribute('data-id');
    
    let AlertaConfirmarEliminar = await Swal.fire({
        title: '¿Está seguro que desea eliminar esta reparación?',
        icon: 'warning',
        text: 'Esta acción no se puede deshacer',
        showConfirmButton: true,
        confirmButtonText: 'Sí, Eliminar',
        confirmButtonColor: '#dc3545',
        cancelButtonText: 'No, Cancelar',
        showCancelButton: true
    });

    if (AlertaConfirmarEliminar.isConfirmed) {
        const url = `/base_login/reparaciones/eliminarAPI?id=${idReparacion}`;
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
                
                BuscarReparaciones();
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

const CargarClientes = async () => {
    const url = `/base_login/reparaciones/obtenerClientesAPI`;
    const config = {
        method: 'GET'
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, data } = datos

        if (codigo == 1) {
            SelectCliente.innerHTML = '<option value="">Seleccione un cliente</option>';
            data.forEach(cliente => {
                SelectCliente.innerHTML += `<option value="${cliente.id_cliente}">${cliente.primer_nombre} ${cliente.primer_apellido}</option>`;
            });
        }

    } catch (error) {
        console.log(error)
    }
}

const CargarUsuarios = async () => {
    const url = `/base_login/reparaciones/obtenerUsuariosAPI`;
    const config = {
        method: 'GET'
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, data } = datos

        if (codigo == 1) {
            SelectUsuarioRecibe.innerHTML = '<option value="">Seleccione usuario</option>';
            SelectUsuarioAsignado.innerHTML = '<option value="">Sin asignar</option>';
            
            data.forEach(usuario => {
                SelectUsuarioRecibe.innerHTML += `<option value="${usuario.id_usuario}">${usuario.primer_nombre} ${usuario.primer_apellido}</option>`;
                SelectUsuarioAsignado.innerHTML += `<option value="${usuario.id_usuario}">${usuario.primer_nombre} ${usuario.primer_apellido}</option>`;
            });
        }

    } catch (error) {
        console.log(error)
    }
}

const datatable = new DataTable('#TableReparaciones', {
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
            data: 'id_reparacion',
            width: '5%',
            render: (data, type, row, meta) => meta.row + 1
        },
        { 
            title: 'Cliente', 
            data: null, 
            width: '15%',
            render: (data, type, row) => `${row.cliente_nombre} ${row.cliente_apellido}`
        },
        { 
            title: 'Tipo/Marca', 
            data: null, 
            width: '15%',
            render: (data, type, row) => {
                let texto = '';
                if (row.tipo_celular) texto += row.tipo_celular;
                if (row.marca_celular) {
                    if (texto) texto += ' / ';
                    texto += row.marca_celular;
                }
                return texto || 'No especificado';
            }
        },
        { 
            title: 'Motivo', 
            data: 'motivo_ingreso', 
            width: '20%'
        },
        { 
            title: 'Diagnóstico', 
            data: 'diagnostico', 
            width: '15%'
        },
        { 
            title: 'Estado', 
            data: 'estado_reparacion', 
            width: '8%'
        },
        { 
            title: 'Usuario Recibe', 
            data: null, 
            width: '10%',
            render: (data, type, row) => `${row.usuario_nombre} ${row.usuario_apellido || ''}`
        },
        { 
            title: 'Técnico Asignado', 
            data: null, 
            width: '10%',
            render: (data, type, row) => {
                if (row.tecnico_nombre) {
                    return `${row.tecnico_nombre} ${row.tecnico_apellido || ''}`;
                }
                return 'Sin asignar';
            }
        },
        { 
            title: 'Fecha', 
            data: 'fecha_ingreso', 
            width: '8%'
        },
        { 
            title: 'Costo', 
            data: 'costo_total', 
            width: '8%'
        },
        {
            title: 'Acciones',
            data: 'id_reparacion',
            searchable: false,
            orderable: false,
            width: '5%',
            render: (data, type, row, meta) => {
                return `
                    <div class='d-flex justify-content-center'>
                        <button class='btn btn-warning modificar mx-1 btn-sm' 
                            data-id="${data}" 
                            data-cliente="${row.id_cliente}"  
                            data-usuario-recibe="${row.id_usuario_recibe}"
                            data-usuario-asignado="${row.id_usuario_asignado || ''}"
                            data-tipo-celular="${row.tipo_celular || ''}"
                            data-marca-celular="${row.marca_celular || ''}"
                            data-motivo="${row.motivo_ingreso || ''}"
                            data-diagnostico="${row.diagnostico || ''}"
                            data-tipo-servicio="${row.tipo_servicio || ''}"
                            data-estado="${row.estado_reparacion}"
                            data-costo="${row.costo_total}"
                            title="Modificar">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        <button class='btn btn-danger eliminar mx-1 btn-sm' 
                            data-id="${data}" 
                            title="Eliminar">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                `;
            }
        }
    ]
});


FormReparaciones.addEventListener('submit', GuardarReparacion);
BtnCancelar.addEventListener('click', limpiarTodo);
BtnModificar.addEventListener('click', ModificarReparacion);
BtnBuscar.addEventListener('click', BuscarReparaciones);

datatable.on('click', '.eliminar', EliminarReparacion);
datatable.on('click', '.modificar', llenarFormulario);


CargarClientes();
CargarUsuarios();
BuscarReparaciones();