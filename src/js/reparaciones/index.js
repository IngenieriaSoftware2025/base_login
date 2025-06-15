//import { Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import { validarFormulario } from '../funciones';
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";

const FormReparaciones = document.getElementById('FormReparaciones');
const BtnGuardar = document.getElementById('BtnGuardar');
const BtnModificar = document.getElementById('BtnModificar');
const BtnLimpiar = document.getElementById('BtnLimpiar');
const BtnBuscar = document.getElementById('BtnBuscar');
const SelectCliente = document.getElementById('id_cliente');
const SelectUsuarioRecibe = document.getElementById('id_usuario_recibe');
const SelectUsuarioAsignado = document.getElementById('id_usuario_asignado');

const GuardarReparacion = async (event) => {
    event.preventDefault();
    BtnGuardar.disabled = true;

    if (!validarFormulario(FormReparaciones, ['id_reparacion', 'id_usuario_asignado', 'imei', 'diagnostico', 'fecha_asignacion', 'fecha_entrega_real', 'tipo_servicio', 'costo_total'])) {
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
            SelectCliente.innerHTML = '<option value="">-- Seleccione un cliente --</option>';
            data.forEach(cliente => {
                SelectCliente.innerHTML += `<option value="${cliente.id_cliente}">${cliente.nombre_completo}</option>`;
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
            SelectUsuarioRecibe.innerHTML = '<option value="">-- Seleccione empleado --</option>';
            SelectUsuarioAsignado.innerHTML = '<option value="">-- Seleccione técnico --</option>';
            
            data.forEach(usuario => {
                SelectUsuarioRecibe.innerHTML += `<option value="${usuario.id_usuario}">${usuario.nombre_completo}</option>`;
                SelectUsuarioAsignado.innerHTML += `<option value="${usuario.id_usuario}">${usuario.nombre_completo}</option>`;
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
            width: '4%',
            render: (data, type, row, meta) => meta.row + 1
        },
        { 
            title: 'N° Orden', 
            data: 'numero_orden', 
            width: '8%' 
        },
        { 
            title: 'Cliente', 
            data: 'nombre_cliente', 
            width: '12%' 
        },
        { 
            title: 'Dispositivo', 
            data: 'tipo_celular', 
            width: '10%',
            render: (data, type, row) => {
                let dispositivo = data || 'N/A';
                if(row.marca_celular) {
                    dispositivo += ' - ' + row.marca_celular;
                }
                return dispositivo;
            }
        },
        { 
            title: 'Motivo', 
            data: 'motivo_ingreso', 
            width: '15%',
            render: (data) => {
                return data ? (data.length > 50 ? data.substring(0, 50) + '...' : data) : 'Sin motivo';
            }
        },
        { 
            title: 'Estado', 
            data: 'estado_reparacion', 
            width: '8%',
            render: (data) => {
                const estados = {
                    'recibido': 'bg-info',
                    'en_proceso': 'bg-warning text-dark',
                    'terminado': 'bg-success',
                    'entregado': 'bg-primary',
                    'cancelado': 'bg-danger'
                };
                const clase = estados[data] || 'bg-secondary';
                const texto = data.replace('_', ' ').toUpperCase();
                return `<span class="badge ${clase}">${texto}</span>`;
            }
        },
        { 
            title: 'Usuario Recibe', 
            data: 'usuario_recibe_nombre', 
            width: '10%',
            render: (data) => data || 'N/A'
        },
        { 
            title: 'Usuario Asignado', 
            data: 'usuario_asignado_nombre', 
            width: '10%',
            render: (data) => data || 'Sin asignar'
        },
        { 
            title: 'Costo', 
            data: 'costo_total', 
            width: '7%',
            render: (data) => {
                return data ? `Q${parseFloat(data).toFixed(2)}` : 'Q0.00';
            }
        },
        { 
            title: 'Fecha Ingreso', 
            data: 'fecha_creacion', 
            width: '8%',
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
            data: 'id_reparacion',
            searchable: false,
            orderable: false,
            width: '8%',
            render: (data, type, row, meta) => {
                return `
                 <div class='d-flex justify-content-center'>
                     <button class='btn btn-warning modificar mx-1 btn-sm' 
                         data-id="${data}" 
                         data-id-cliente="${row.id_cliente || ''}"
                         data-id-usuario-recibe="${row.id_usuario_recibe || ''}"
                         data-id-usuario-asignado="${row.id_usuario_asignado || ''}"
                         data-numero-orden="${row.numero_orden || ''}"
                         data-tipo-celular="${row.tipo_celular || ''}"
                         data-marca-celular="${row.marca_celular || ''}"
                         data-imei="${row.imei || ''}"
                         data-motivo-ingreso="${row.motivo_ingreso || ''}"
                         data-diagnostico="${row.diagnostico || ''}"
                         data-tipo-servicio="${row.tipo_servicio || ''}"
                         data-estado-reparacion="${row.estado_reparacion || 'recibido'}"
                         data-fecha-asignacion="${row.fecha_asignacion || ''}"
                         data-fecha-entrega-real="${row.fecha_entrega_real || ''}"
                         data-costo-total="${row.costo_total || ''}">
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

    document.getElementById('id_reparacion').value = datos.id;
    document.getElementById('id_cliente').value = datos.idCliente;
    document.getElementById('id_usuario_recibe').value = datos.idUsuarioRecibe;
    document.getElementById('id_usuario_asignado').value = datos.idUsuarioAsignado;
    document.getElementById('numero_orden').value = datos.numeroOrden;
    document.getElementById('tipo_celular').value = datos.tipoCelular;
    document.getElementById('marca_celular').value = datos.marcaCelular;
    document.getElementById('imei').value = datos.imei;
    document.getElementById('motivo_ingreso').value = datos.motivoIngreso;
    document.getElementById('diagnostico').value = datos.diagnostico;
    document.getElementById('tipo_servicio').value = datos.tipoServicio;
    document.getElementById('estado_reparacion').value = datos.estadoReparacion;
    document.getElementById('fecha_asignacion').value = datos.fechaAsignacion;
    document.getElementById('fecha_entrega_real').value = datos.fechaEntregaReal;
    document.getElementById('costo_total').value = datos.costoTotal;

    BtnGuardar.classList.add('d-none');
    BtnModificar.classList.remove('d-none');

    window.scrollTo({ top: 0, behavior: 'smooth' });
}

const limpiarTodo = () => {
    FormReparaciones.reset();
    document.getElementById('id_reparacion').value = '';
    document.getElementById('estado_reparacion').value = 'recibido';
    BtnGuardar.classList.remove('d-none');
    BtnModificar.classList.add('d-none');
}

const ModificarReparacion = async (event) => {
    event.preventDefault();
    BtnModificar.disabled = true;

    if (!validarFormulario(FormReparaciones, ['id_usuario_asignado', 'imei', 'diagnostico', 'fecha_asignacion', 'fecha_entrega_real', 'tipo_servicio', 'costo_total'])) {
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
    const idReparacion = e.currentTarget.dataset.id

    const AlertaConfirmarEliminar = await Swal.fire({
        position: "center",
        icon: "question",
        title: "¿Desea eliminar esta reparación?",
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

// Cargar datos iniciales
CargarClientes();
CargarUsuarios();
BuscarReparaciones();

// Event Listeners
datatable.on('click', '.eliminar', EliminarReparacion);
datatable.on('click', '.modificar', llenarFormulario);
FormReparaciones.addEventListener('submit', GuardarReparacion);
BtnLimpiar.addEventListener('click', limpiarTodo);
BtnModificar.addEventListener('click', ModificarReparacion);
BtnBuscar.addEventListener('click', BuscarReparaciones);