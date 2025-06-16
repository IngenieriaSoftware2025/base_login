import Swal from "sweetalert2";
import { validarFormulario } from '../funciones';
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";

const FormVentas = document.getElementById('FormVentas');
const BtnGuardar = document.getElementById('BtnGuardar');
const BtnModificar = document.getElementById('BtnModificar');
const BtnCancelar = document.getElementById('BtnCancelar');
const BtnBuscar = document.getElementById('BtnBuscar');
const BtnCargarInventario = document.getElementById('BtnCargarInventario');

const SelectCliente = document.getElementById('id_cliente');
const SelectUsuario = document.getElementById('id_usuario');
const InputDescuento = document.getElementById('descuento');

const seccionInventario = document.getElementById('seccionInventario');
const seccionCarrito = document.getElementById('seccionCarrito');
const inventarioDisponible = document.getElementById('inventarioDisponible');
const carritoItems = document.getElementById('carritoItems');
const subtotalVenta = document.getElementById('subtotalVenta');
const descuentoVenta = document.getElementById('descuentoVenta');
const totalVenta = document.getElementById('totalVenta');

let inventario = [];
let carrito = [];

const GuardarVenta = async (event) => {
    event.preventDefault();
    BtnGuardar.disabled = true;

    if (!validarFormulario(FormVentas, ['id_venta', 'observaciones'])) {
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

    if (carrito.length === 0) {
        Swal.fire({
            position: "center",
            icon: "warning",
            title: "CARRITO VACÍO",
            text: "Debe agregar al menos un producto",
            showConfirmButton: true,
        });
        BtnGuardar.disabled = false;
        return;
    }

    const formData = new FormData();
    formData.append('id_cliente', SelectCliente.value);
    formData.append('id_usuario', SelectUsuario.value);
    formData.append('descuento', InputDescuento.value);
    formData.append('metodo_pago', document.getElementById('metodo_pago').value);
    formData.append('observaciones', document.getElementById('observaciones').value);
    formData.append('productos', JSON.stringify(carrito));

    const url = '/base_login/ventas/guardarAPI';
    const config = {
        method: 'POST',
        body: formData
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
        const { codigo, data } = datos

        if (codigo == 1) {
            datatable.clear();
            datatable.rows.add(data).draw();
        }

    } catch (error) {
        console.log(error)
    }
}

const CargarInventario = async () => {
    if (!SelectCliente.value || !SelectUsuario.value) {
        Swal.fire({
            position: "center",
            icon: "warning",
            title: "DATOS REQUERIDOS",
            text: "Debe seleccionar cliente y usuario vendedor primero",
            showConfirmButton: true,
        });
        return;
    }

    const url = `/base_login/ventas/obtenerInventarioAPI`;
    const config = {
        method: 'GET'
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, data } = datos

        if (codigo == 1) {
            inventario = data;
            MostrarInventario();
            seccionInventario.style.display = 'block';
        } else {
            Swal.fire({
                position: "center",
                icon: "error",
                title: "Error",
                text: datos.mensaje,
                showConfirmButton: true,
            });
        }

    } catch (error) {
        console.log(error)
    }
}

const MostrarInventario = () => {
    inventarioDisponible.innerHTML = '';
    
    if (!inventario || inventario.length === 0) {
        inventarioDisponible.innerHTML = '<tr><td colspan="7" class="text-center">No hay productos disponibles</td></tr>';
        return;
    }
    
    inventario.forEach(producto => {
        const fila = document.createElement('tr');
        fila.innerHTML = `
            <td>
                <input type="checkbox" class="form-check-input producto-check" 
                       data-id="${producto.id_inventario}">
            </td>
            <td>${producto.nombre_marca}</td>
            <td>${producto.nombre_modelo}</td>
            <td>${producto.estado_celular}</td>
            <td>Q. ${parseFloat(producto.precio_venta).toFixed(2)}</td>
            <td>${producto.imei || 'Sin IMEI'}</td>
            <td>
                <button type="button" class="btn btn-sm btn-primary agregar-btn" 
                        data-id="${producto.id_inventario}" disabled>
                    Agregar
                </button>
            </td>
        `;
        inventarioDisponible.appendChild(fila);
    });

    AgregarEventosInventario();
}

const AgregarEventosInventario = () => {
    document.querySelectorAll('.producto-check').forEach(check => {
        check.addEventListener('change', function() {
            const id = this.dataset.id;
            const agregarBtn = document.querySelector(`[data-id="${id}"].agregar-btn`);
            
            if (this.checked) {
                agregarBtn.disabled = false;
            } else {
                agregarBtn.disabled = true;
            }
        });
    });

    document.querySelectorAll('.agregar-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            AgregarAlCarrito(id);
        });
    });
}

const AgregarAlCarrito = (inventarioId) => {
    const producto = inventario.find(p => p.id_inventario == inventarioId);
    
    // Verificar si ya está en el carrito
    const existe = carrito.findIndex(item => item.id_inventario == inventarioId);
    
    if (existe !== -1) {
        Swal.fire({
            position: "center",
            icon: "warning",
            title: "PRODUCTO YA AGREGADO",
            text: "Este producto ya está en el carrito",
            showConfirmButton: true,
        });
        return;
    }

    carrito.push({
        id_inventario: inventarioId,
        marca: producto.nombre_marca,
        modelo: producto.nombre_modelo,
        estado: producto.estado_celular,
        precio: producto.precio_venta,
        cantidad: 1,
        subtotal: producto.precio_venta,
        imei: producto.imei || 'Sin IMEI'
    });

    ActualizarCarrito();
    
    // Mostrar botón guardar
    BtnGuardar.style.display = 'inline-block';
    
    // Desmarcar checkbox
    document.querySelector(`[data-id="${inventarioId}"].producto-check`).checked = false;
    document.querySelector(`[data-id="${inventarioId}"].agregar-btn`).disabled = true;
}

const ActualizarCarrito = () => {
    carritoItems.innerHTML = '';
    let subtotal = 0;

    carrito.forEach((item, index) => {
        subtotal += parseFloat(item.subtotal);
        
        const fila = document.createElement('tr');
        fila.innerHTML = `
            <td>${item.marca} ${item.modelo}</td>
            <td>${item.estado}</td>
            <td>Q. ${parseFloat(item.precio).toFixed(2)}</td>
            <td>
                <input type="number" class="form-control form-control-sm" 
                       value="${item.cantidad}" min="1" max="1" readonly>
            </td>
            <td>Q. ${parseFloat(item.subtotal).toFixed(2)}</td>
            <td>${item.imei}</td>
            <td>
                <button type="button" class="btn btn-sm btn-danger" 
                        onclick="QuitarDelCarrito(${index})">
                    Quitar
                </button>
            </td>
        `;
        carritoItems.appendChild(fila);
    });

    const descuento = parseFloat(InputDescuento.value) || 0;
    const total = subtotal - descuento;

    subtotalVenta.textContent = `Q. ${subtotal.toFixed(2)}`;
    descuentoVenta.textContent = `Q. ${descuento.toFixed(2)}`;
    totalVenta.textContent = `Q. ${total.toFixed(2)}`;

    if (carrito.length > 0) {
        seccionCarrito.style.display = 'block';
    } else {
        seccionCarrito.style.display = 'none';
        BtnGuardar.style.display = 'none';
    }
}

window.QuitarDelCarrito = (index) => {
    carrito.splice(index, 1);
    ActualizarCarrito();
}

const limpiarTodo = () => {
    FormVentas.reset();
    carrito = [];
    inventario = [];
    seccionInventario.style.display = 'none';
    seccionCarrito.style.display = 'none';
    BtnGuardar.style.display = 'none';
    BtnModificar.classList.add('d-none');
    ActualizarCarrito();
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
    const url = `/base_login/ventas/obtenerUsuariosAPI`;
    const config = {
        method: 'GET'
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, data } = datos

        if (codigo == 1) {
            SelectUsuario.innerHTML = '<option value="">Seleccione usuario</option>';
            data.forEach(usuario => {
                SelectUsuario.innerHTML += `<option value="${usuario.id_usuario}">${usuario.primer_nombre} ${usuario.primer_apellido}</option>`;
            });
        }

    } catch (error) {
        console.log(error)
    }
}

// Event listener para actualizar total cuando cambia descuento
InputDescuento.addEventListener('input', ActualizarCarrito);

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
            title: 'Cliente', 
            data: null, 
            width: '20%',
            render: (data, type, row) => `${row.cliente_nombre} ${row.cliente_apellido}`
        },
        { 
            title: 'Usuario', 
            data: null, 
            width: '15%',
            render: (data, type, row) => `${row.usuario_nombre} ${row.usuario_apellido}`
        },
        { 
            title: 'Fecha', 
            data: 'fecha_venta', 
            width: '12%'
        },
        { 
            title: 'Total', 
            data: 'total', 
            width: '12%',
            render: (data) => `Q. ${parseFloat(data).toFixed(2)}`
        },
        { 
            title: 'Descuento', 
            data: 'descuento', 
            width: '10%',
            render: (data) => `Q. ${parseFloat(data).toFixed(2)}`
        },
        { 
            title: 'Método Pago', 
            data: 'metodo_pago', 
            width: '10%'
        },
        { 
            title: 'Estado', 
            data: 'estado_venta', 
            width: '10%'
        },
        {
            title: 'Acciones',
            data: 'id_venta',
            searchable: false,
            orderable: false,
            width: '6%',
            render: (data, type, row, meta) => {
                return `
                    <button class='btn btn-info btn-sm' onclick="VerDetalle(${data})" title="Ver Detalle">
                        <i class="bi bi-eye"></i>
                    </button>
                    <button class='btn btn-danger btn-sm' onclick="EliminarVenta(${data})" title="Eliminar">
                        <i class="bi bi-trash"></i>
                    </button>
                `;
            }
        }
    ]
});

window.VerDetalle = async (idVenta) => {
    const url = `/base_login/ventas/obtenerDetalleAPI?id=${idVenta}`;
    const config = {
        method: 'GET'
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();

        if (datos.codigo == 1) {
            const venta = datos.venta;
            const detalles = datos.detalles;

            let contenido = `
                <div class="row mb-3">
                    <div class="col-md-6">Cliente: ${venta.cliente_nombre} ${venta.cliente_apellido}</div>
                    <div class="col-md-6">Vendedor: ${venta.usuario_nombre} ${venta.usuario_apellido}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">Fecha: ${venta.fecha_venta}</div>
                    <div class="col-md-6">Método: ${venta.metodo_pago}</div>
                </div>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Estado</th>
                            <th>Precio</th>
                            <th>Cantidad</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            detalles.forEach(detalle => {
                contenido += `
                    <tr>
                        <td>${detalle.nombre_marca} ${detalle.nombre_modelo}</td>
                        <td>${detalle.estado_celular}</td>
                        <td>Q. ${parseFloat(detalle.precio_unitario).toFixed(2)}</td>
                        <td>${detalle.cantidad}</td>
                        <td>Q. ${parseFloat(detalle.subtotal_detalle).toFixed(2)}</td>
                    </tr>
                `;
            });

            contenido += `
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4">TOTAL:</td>
                            <td>Q. ${parseFloat(venta.total).toFixed(2)}</td>
                        </tr>
                    </tfoot>
                </table>
            `;

            document.getElementById('contenidoDetalleVenta').innerHTML = contenido;
            
            const modal = new bootstrap.Modal(document.getElementById('modalDetalleVenta'));
            modal.show();
        }
    } catch (error) {
        console.log(error);
    }
}

window.EliminarVenta = async (idVenta) => {
    const confirmacion = await Swal.fire({
        title: '¿Está seguro que desea eliminar esta venta?',
        icon: 'warning',
        text: 'Esta acción no se puede deshacer',
        showConfirmButton: true,
        confirmButtonText: 'Sí, Eliminar',
        confirmButtonColor: '#dc3545',
        cancelButtonText: 'No, Cancelar',
        showCancelButton: true
    });

    if (confirmacion.isConfirmed) {
        const url = `/base_login/ventas/eliminarAPI?id=${idVenta}`;
        const config = {
            method: 'GET'
        }

        try {
            const respuesta = await fetch(url, config);
            const datos = await respuesta.json();

            if (datos.codigo == 1) {
                await Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "Éxito",
                    text: datos.mensaje,
                    showConfirmButton: true,
                });
                BuscarVentas();
            } else {
                await Swal.fire({
                    position: "center",
                    icon: "error",
                    title: "Error",
                    text: datos.mensaje,
                    showConfirmButton: true,
                });
            }
        } catch (error) {
            console.log(error);
        }
    }
}

// Event Listeners
FormVentas.addEventListener('submit', GuardarVenta);
BtnCancelar.addEventListener('click', limpiarTodo);
BtnBuscar.addEventListener('click', BuscarVentas);
BtnCargarInventario.addEventListener('click', CargarInventario);

// Inicialización
CargarClientes();
CargarUsuarios();
BuscarVentas();