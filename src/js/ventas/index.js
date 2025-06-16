//import { Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import { validarFormulario } from '../funciones';
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";

const FormVentas = document.getElementById('FormVentas');
const FormDetalle = document.getElementById('FormDetalle');
const BtnGuardar = document.getElementById('BtnGuardar');
const BtnModificar = document.getElementById('BtnModificar');
const BtnLimpiar = document.getElementById('BtnLimpiar');
const BtnBuscarVentas = document.getElementById('BtnBuscarVentas');
const BtnAgregarProducto = document.getElementById('BtnAgregarProducto');
const SelectCliente = document.getElementById('id_cliente');
const SelectUsuario = document.getElementById('id_usuario');
const SelectProducto = document.getElementById('id_inventario');
const seccionTablaVentas = document.getElementById('seccionTablaVentas');
const seccionProductos = document.getElementById('seccionProductos');

const GuardarVenta = async (event) => {
    event.preventDefault();
    BtnGuardar.disabled = true;

    if (!validarFormulario(FormVentas, ['id_venta', 'fecha_venta', 'observaciones'])) {
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

    const body = new FormData(FormVentas);
    const url = '/base_login/ventas/guardarAPI';
    const config = {
        method: 'POST',
        body
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje, id_venta } = datos

        if (codigo == 1) {
            await Swal.fire({
                position: "center",
                icon: "success",
                title: "Éxito",
                text: mensaje,
                showConfirmButton: true,
            });

            
            document.getElementById('id_venta_detalle').value = id_venta;
            seccionProductos.style.display = 'block';
            
            limpiarTodo();
            BuscarVentas();
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

const BuscarVentas = async () => {
    const url = `/base_login/ventas/buscarAPI`;
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
    const url = `/base_login/ventas/obtenerClientesAPI`;
    const config = {
        method: 'GET'
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, data } = datos

        if (codigo == 1) {
            SelectCliente.innerHTML = '<option value=""> Seleccione un cliente </option>';
            data.forEach(cliente => {
                SelectCliente.innerHTML += `<option value="${cliente.id_cliente}">${cliente.nombre_completo}</option>`;
            });
        }

    } catch (error) {
        console.log(error)
    }
}

const CargarUsuarios = async () => {
    const url = `/base_login/ventas/obtenerUsuariosAPI`;
    const config = {
        method: 'GET'
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, data } = datos

        if (codigo == 1) {
            SelectUsuario.innerHTML = '<option value=""> Seleccione un vendedor </option>';
            data.forEach(usuario => {
                SelectUsuario.innerHTML += `<option value="${usuario.id_usuario}">${usuario.nombre_completo}</option>`;
            });
        }

    } catch (error) {
        console.log(error)
    }
}

const CargarProductos = async () => {
    const url = `/base_login/ventas/obtenerProductosAPI`;
    const config = {
        method: 'GET'
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, data } = datos

        if (codigo == 1) {
            SelectProducto.innerHTML = '<option value=""> Seleccione un producto </option>';
            data.forEach(producto => {
                SelectProducto.innerHTML += `<option value="${producto.id_inventario}" data-precio="${producto.precio_venta}">${producto.producto_completo} - Q.${producto.precio_venta}</option>`;
            });
        }

    } catch (error) {
        console.log(error)
    }
}

const AgregarProducto = async (event) => {
    event.preventDefault();
    BtnAgregarProducto.disabled = true;

    if (!validarFormulario(FormDetalle, [])) {
        Swal.fire({
            position: "center",
            icon: "info",
            title: "FORMULARIO INCOMPLETO",
            text: "Debe completar todos los campos",
            showConfirmButton: true,
        });
        BtnAgregarProducto.disabled = false;
        return;
    }

    const body = new FormData(FormDetalle);
    const url = '/base_login/ventas/guardarDetalleAPI';
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

            FormDetalle.reset();
            CargarProductos();
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
    BtnAgregarProducto.disabled = false;
}

const datatable = new DataTable('#TableVentas', {
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
            data: 'id_venta',
            width: '5%',
            render: (data, type, row, meta) => meta.row + 1
        },
        { 
            title: 'Fecha', 
            data: 'fecha_venta', 
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
            title: 'Cliente', 
            data: 'primer_nombre', 
            width: '20%',
            render: (data, type, row) => `${row.primer_nombre} ${row.primer_apellido}`
        },
        { 
            title: 'Vendedor', 
            data: 'vendedor_nombre', 
            width: '20%',
            render: (data, type, row) => `${row.vendedor_nombre} ${row.vendedor_apellido}`
        },
        { 
            title: 'Total', 
            data: 'total', 
            width: '10%',
            render: (data) => `Q. ${parseFloat(data).toFixed(2)}`
        },
        { 
            title: 'Método Pago', 
            data: 'metodo_pago', 
            width: '10%',
            render: (data) => {
                const badges = {
                    'efectivo': '<span class="badge bg-success">Efectivo</span>',
                    'tarjeta': '<span class="badge bg-primary">Tarjeta</span>',
                    'transferencia': '<span class="badge bg-info">Transferencia</span>'
                };
                return badges[data] || data;
            }
        },
        { 
            title: 'Estado', 
            data: 'estado_venta', 
            width: '8%',
            render: (data) => {
                const badges = {
                    'completada': '<span class="badge bg-success">Completada</span>',
                    'pendiente': '<span class="badge bg-warning">Pendiente</span>',
                    'cancelada': '<span class="badge bg-danger">Cancelada</span>'
                };
                return badges[data] || data;
            }
        },
        {
            title: 'Acciones',
            data: 'id_venta',
            searchable: false,
            orderable: false,
            width: '15%',
            render: (data, type, row, meta) => {
                return `
                 <div class='d-flex justify-content-center'>
                     <button class='btn btn-warning modificar mx-1 btn-sm' 
                         data-id="${data}" 
                         data-cliente="${row.id_cliente}"
                         data-subtotal="${row.subtotal}"
                         data-descuento="${row.descuento}"
                         data-total="${row.total}"
                         data-metodo-pago="${row.metodo_pago}"
                         data-observaciones="${row.observaciones || ''}">
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

    document.getElementById('id_venta').value = datos.id;
    document.getElementById('id_cliente').value = datos.cliente;
    document.getElementById('subtotal').value = datos.subtotal;
    document.getElementById('descuento').value = datos.descuento;
    document.getElementById('total').value = datos.total;
    document.getElementById('metodo_pago').value = datos.metodoPago;
    document.getElementById('observaciones').value = datos.observaciones;

    BtnGuardar.classList.add('d-none');
    BtnModificar.classList.remove('d-none');

    window.scrollTo({ top: 0 });
}

const limpiarTodo = () => {
    FormVentas.reset();
    document.getElementById('fecha_venta').value = new Date().toISOString().split('T')[0];
    BtnGuardar.classList.remove('d-none');
    BtnModificar.classList.add('d-none');
    seccionProductos.style.display = 'none';
}

const ModificarVenta = async (event) => {
    event.preventDefault();
    BtnModificar.disabled = true;

    if (!validarFormulario(FormVentas, ['fecha_venta', 'observaciones'])) {
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

    const body = new FormData(FormVentas);
    const url = '/base_login/ventas/modificarAPI';
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
            BuscarVentas();
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

const EliminarVenta = async (e) => {
    const idVenta = e.currentTarget.dataset.id

    const AlertaConfirmarEliminar = await Swal.fire({
        position: "center",
        icon: "question",
        title: "¿Desea eliminar esta venta?",
        text: 'Esta acción no se puede deshacer',
        showConfirmButton: true,
        confirmButtonText: 'Sí, Eliminar',
        confirmButtonColor: '#dc3545',
        cancelButtonText: 'No, Cancelar',
        showCancelButton: true
    });

    if (AlertaConfirmarEliminar.isConfirmed) {
        const url = `/base_login/ventas/eliminarAPI?id=${idVenta}`;
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
                
                BuscarVentas();
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

const MostrarTablaVentas = () => {
    if (seccionTablaVentas.style.display === 'none') {
        seccionTablaVentas.style.display = 'block';
        BuscarVentas();
    } else {
        seccionTablaVentas.style.display = 'none';
    }
}

// Event listener para autocompletar precio
SelectProducto.addEventListener('change', (event) => {
    const selectedOption = event.target.selectedOptions[0];
    if (selectedOption && selectedOption.dataset.precio) {
        document.getElementById('precio_unitario').value = selectedOption.dataset.precio;
    }
});

// CARGAR DATOS CUANDO EL DOM ESTÉ LISTO
document.addEventListener('DOMContentLoaded', () => {
    // Cargar datos al inicializar
    CargarClientes();
    CargarUsuarios();
    CargarProductos();

    // Establecer fecha actual
    document.getElementById('fecha_venta').value = new Date().toISOString().split('T')[0];
});


datatable.on('click', '.eliminar', EliminarVenta);
datatable.on('click', '.modificar', llenarFormulario);
FormVentas.addEventListener('submit', GuardarVenta);
FormDetalle.addEventListener('submit', AgregarProducto);
BtnLimpiar.addEventListener('click', limpiarTodo);
BtnModificar.addEventListener('click', ModificarVenta);
BtnBuscarVentas.addEventListener('click', MostrarTablaVentas);