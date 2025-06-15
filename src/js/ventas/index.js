import { Modal } from "bootstrap";
import Swal from "sweetalert2";
import { validarFormulario } from '../funciones';
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";

// Elementos del DOM
const FormVentas = document.getElementById('FormVentas');
const selectCliente = document.getElementById('id_cliente');
const selectMetodoPago = document.getElementById('metodo_pago');
const inputDescuento = document.getElementById('descuento');
const BtnCargarProductos = document.getElementById('BtnCargarProductos');
const BtnGuardar = document.getElementById('BtnGuardar');
const BtnModificar = document.getElementById('BtnModificar');
const BtnLimpiar = document.getElementById('BtnLimpiar');
const seccionProductos = document.getElementById('seccionProductos');
const seccionCarrito = document.getElementById('seccionCarrito');
const seccionObservaciones = document.getElementById('seccionObservaciones');
const subtotalVenta = document.getElementById('subtotalVenta');
const descuentoVenta = document.getElementById('descuentoVenta');
const totalVenta = document.getElementById('totalVenta');

// Variables globales
let carrito = [];
let productos = [];
let datatableProductos = null;
let datatableCarrito = null;

// Cargar clientes al inicializar
const CargarClientes = async () => {
    try {
        const respuesta = await fetch('/base_login/ventas/clientesAPI');
        const datos = await respuesta.json();
        
        if (datos.codigo == 1) {
            selectCliente.innerHTML = '<option value="">-- Seleccione un cliente --</option>';
            
            datos.data.forEach(cliente => {
                selectCliente.innerHTML += `
                    <option value="${cliente.id_cliente}">
                        ${cliente.nombres} ${cliente.apellidos}
                    </option>
                `;
            });
        }
    } catch (error) {
        Swal.fire({
            icon: "error",
            title: "Error",
            text: "No se pudieron cargar los clientes"
        });
    }
};

// Cargar productos disponibles
const CargarProductos = async () => {
    if (!selectCliente.value) {
        Swal.fire({
            icon: "warning",
            title: "Cliente requerido",
            text: "Debe seleccionar un cliente primero"
        });
        return;
    }

    try {
        const respuesta = await fetch('/base_login/ventas/productosAPI');
        const datos = await respuesta.json();
        
        if (datos.codigo == 1) {
            productos = datos.data;
            MostrarProductos();
            seccionObservaciones.style.display = 'block';
        } else {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: datos.mensaje || "Error al cargar productos"
            });
        }
    } catch (error) {
        Swal.fire({
            icon: "error",
            title: "Error",
            text: "No se pudieron cargar los productos"
        });
    }
};

// Mostrar productos en la tabla dinámica
const MostrarProductos = () => {
    if (!productos || productos.length === 0) {
        if (datatableProductos) {
            datatableProductos.destroy();
        }
        seccionProductos.style.display = 'none';
        return;
    }

    // Destruir DataTable existente si existe
    if (datatableProductos) {
        datatableProductos.destroy();
    }

    // Crear DataTable para productos disponibles
    datatableProductos = new DataTable('#TableProductosDisponibles', {
        language: lenguaje,
        data: productos,
        pageLength: 5,
        lengthMenu: [5, 10, 15],
        columns: [
            {
                title: 'Seleccionar',
                data: 'id_producto',
                width: '8%',
                orderable: false,
                searchable: false,
                render: (data, type, row) => {
                    return `<input type="checkbox" class="form-check-input producto-check" data-id="${data}">`;
                }
            },
            {
                title: 'Producto',
                data: 'nombre',
                width: '25%',
                render: (data, type, row) => {
                    return `<strong>${data}</strong>`;
                }
            },
            {
                title: 'Precio',
                data: 'precio',
                width: '12%',
                render: (data, type, row) => {
                    return `Q. ${parseFloat(data).toFixed(2)}`;
                }
            },
            {
                title: 'Stock',
                data: 'cantidad',
                width: '10%',
                render: (data, type, row) => {
                    let badgeClass = 'bg-success';
                    if (data <= 0) badgeClass = 'bg-danger';
                    else if (data <= 5) badgeClass = 'bg-warning';
                    
                    return `<span class="badge ${badgeClass}">${data}</span>`;
                }
            },
            {
                title: 'Cantidad',
                data: 'id_producto',
                width: '15%',
                orderable: false,
                searchable: false,
                render: (data, type, row) => {
                    return `<input type="number" class="form-control form-control-sm cantidad-input" 
                           data-id="${data}" min="1" max="${row.cantidad}" value="1" disabled>`;
                }
            },
            {
                title: 'Descripción',
                data: 'descripcion',
                width: '20%',
                render: (data, type, row) => {
                    return data || 'Sin descripción';
                }
            },
            {
                title: 'Acción',
                data: 'id_producto',
                width: '10%',
                orderable: false,
                searchable: false,
                render: (data, type, row) => {
                    return `<button type="button" class="btn btn-sm btn-primary agregar-btn" 
                           data-id="${data}" disabled>
                        <i class="bi bi-plus-circle me-1"></i>Agregar
                    </button>`;
                }
            }
        ]
    });

    seccionProductos.style.display = 'block';
    AgregarEventosProductos();
};

// Agregar eventos a los productos (usando delegación de eventos)
const AgregarEventosProductos = () => {
    // Usar delegación de eventos para checkboxes
    $('#TableProductosDisponibles').on('change', '.producto-check', function() {
        const id = this.dataset.id;
        const fila = $(this).closest('tr');
        const cantidadInput = fila.find('.cantidad-input')[0];
        const agregarBtn = fila.find('.agregar-btn')[0];
        
        if (this.checked) {
            cantidadInput.disabled = false;
            agregarBtn.disabled = false;
        } else {
            cantidadInput.disabled = true;
            agregarBtn.disabled = true;
        }
    });

    // Usar delegación de eventos para botones agregar
    $('#TableProductosDisponibles').on('click', '.agregar-btn', function() {
        const id = this.dataset.id;
        AgregarAlCarrito(id);
    });
};

// Agregar producto al carrito
const AgregarAlCarrito = (productoId) => {
    const producto = productos.find(p => p.id_producto == productoId);
    const fila = $(`#TableProductosDisponibles .agregar-btn[data-id="${productoId}"]`).closest('tr');
    const cantidadInput = fila.find('.cantidad-input')[0];
    const cantidad = parseInt(cantidadInput.value);
    
    if (cantidad > producto.cantidad) {
        Swal.fire({
            icon: "error",
            title: "Stock insuficiente",
            text: `Solo hay ${producto.cantidad} unidades disponibles`
        });
        return;
    }

    const existe = carrito.findIndex(item => item.producto_id == productoId);
    
    if (existe !== -1) {
        carrito[existe].cantidad = cantidad;
        carrito[existe].subtotal = cantidad * producto.precio;
    } else {
        carrito.push({
            producto_id: productoId,
            nombre: producto.nombre,
            descripcion: producto.descripcion,
            precio: producto.precio,
            cantidad: cantidad,
            subtotal: cantidad * producto.precio
        });
    }

    ActualizarCarrito();
    
    // Mostrar botón guardar y limpiar checks
    BtnGuardar.style.display = 'inline-block';
    
    // Limpiar selección en la tabla de productos
    fila.find('.producto-check')[0].checked = false;
    fila.find('.cantidad-input')[0].disabled = true;
    fila.find('.agregar-btn')[0].disabled = true;
};

// Actualizar el carrito con DataTable
const ActualizarCarrito = () => {
    let subtotal = 0;

    // Destruir DataTable existente si existe
    if (datatableCarrito) {
        datatableCarrito.destroy();
    }

    carrito.forEach(item => {
        subtotal += item.subtotal;
    });

    const descuento = parseFloat(inputDescuento.value) || 0;
    const total = subtotal - descuento;

    subtotalVenta.textContent = `Q. ${subtotal.toFixed(2)}`;
    descuentoVenta.textContent = `Q. ${descuento.toFixed(2)}`;
    totalVenta.textContent = `Q. ${total.toFixed(2)}`;

    if (carrito.length > 0) {
        // Crear DataTable para el carrito
        datatableCarrito = new DataTable('#TableCarrito', {
            language: lenguaje,
            data: carrito,
            paging: false,
            searching: false,
            info: false,
            columns: [
                {
                    title: 'Producto',
                    data: 'nombre',
                    width: '35%',
                    render: (data, type, row) => {
                        return `
                            <strong>${data}</strong><br>
                            <small class="text-muted">${row.descripcion || 'Sin descripción'}</small>
                        `;
                    }
                },
                {
                    title: 'Precio Unitario',
                    data: 'precio',
                    width: '15%',
                    render: (data, type, row) => {
                        return `Q. ${parseFloat(data).toFixed(2)}`;
                    }
                },
                {
                    title: 'Cantidad',
                    data: 'cantidad',
                    width: '15%',
                    render: (data, type, row, meta) => {
                        return `<input type="number" class="form-control form-control-sm cantidad-carrito" 
                               value="${data}" min="1" data-index="${meta.row}">`;
                    }
                },
                {
                    title: 'Subtotal',
                    data: 'subtotal',
                    width: '15%',
                    render: (data, type, row) => {
                        return `Q. ${parseFloat(data).toFixed(2)}`;
                    }
                },
                {
                    title: 'Acción',
                    data: null,
                    width: '20%',
                    orderable: false,
                    render: (data, type, row, meta) => {
                        return `
                            <button type="button" class="btn btn-sm btn-danger quitar-carrito" 
                                    data-index="${meta.row}">
                                <i class="bi bi-trash me-1"></i>Quitar
                            </button>
                        `;
                    }
                }
            ]
        });

        // Agregar eventos para cantidad y quitar
        $('#TableCarrito').on('change', '.cantidad-carrito', function() {
            const index = parseInt(this.dataset.index);
            const nuevaCantidad = parseInt(this.value);
            CambiarCantidad(index, nuevaCantidad);
        });

        $('#TableCarrito').on('click', '.quitar-carrito', function() {
            const index = parseInt(this.dataset.index);
            QuitarDelCarrito(index);
        });

        seccionCarrito.style.display = 'block';
    } else {
        seccionCarrito.style.display = 'none';
        BtnGuardar.style.display = 'none';
    }
};

// Cambiar cantidad en el carrito
window.CambiarCantidad = (index, nuevaCantidad) => {
    const item = carrito[index];
    const producto = productos.find(p => p.id_producto == item.producto_id);
    
    if (nuevaCantidad > producto.cantidad) {
        Swal.fire({
            icon: "error",
            title: "Stock insuficiente",
            text: `Solo hay ${producto.cantidad} unidades disponibles`
        });
        ActualizarCarrito();
        return;
    }

    carrito[index].cantidad = parseInt(nuevaCantidad);
    carrito[index].subtotal = parseInt(nuevaCantidad) * item.precio;
    ActualizarCarrito();
};

// Quitar del carrito
window.QuitarDelCarrito = (index) => {
    carrito.splice(index, 1);
    ActualizarCarrito();
};

// Validar descuento
const ValidarDescuento = () => {
    const descuento = parseFloat(inputDescuento.value) || 0;
    
    if (descuento < 0) {
        inputDescuento.value = 0;
        Swal.fire({
            icon: "warning",
            title: "Descuento inválido",
            text: "El descuento no puede ser negativo"
        });
    }
    
    ActualizarCarrito();
};

// Guardar venta
const GuardarVenta = async (event) => {
    event.preventDefault();
    BtnGuardar.disabled = true;

    if (!selectCliente.value) {
        Swal.fire({
            icon: "warning",
            title: "Cliente requerido",
            text: "Debe seleccionar un cliente"
        });
        BtnGuardar.disabled = false;
        return;
    }

    if (carrito.length === 0) {
        Swal.fire({
            icon: "warning",
            title: "Carrito vacío",
            text: "Debe agregar al menos un producto"
        });
        BtnGuardar.disabled = false;
        return;
    }

    const formData = new FormData(FormVentas);
    formData.append('productos', JSON.stringify(carrito));

    try {
        const respuesta = await fetch('/base_login/ventas/guardarAPI', {
            method: 'POST',
            body: formData
        });
        
        const datos = await respuesta.json();

        if (datos.codigo == 1) {
            await Swal.fire({
                icon: "success",
                title: "Éxito",
                text: datos.mensaje,
                showConfirmButton: true
            });

            LimpiarTodo();
            BuscarVentas();
        } else {
            await Swal.fire({
                icon: "error",
                title: "Error",
                text: datos.mensaje
            });
        }
    } catch (error) {
        await Swal.fire({
            icon: "error",
            title: "Error",
            text: "Error de conexión"
        });
    }
    
    BtnGuardar.disabled = false;
};

// Buscar ventas
const BuscarVentas = async () => {
    try {
        const respuesta = await fetch('/base_login/ventas/buscarAPI');
        const datos = await respuesta.json();

        if (datos.codigo == 1) {
            datatable.clear();
            datatable.rows.add(datos.data);
            datatable.draw(false);
        }
    } catch (error) {
        console.error('Error:', error);
    }
};

// Limpiar todo
const LimpiarTodo = () => {
    FormVentas.reset();
    carrito = [];
    productos = [];
    
    // Destruir DataTables si existen
    if (datatableProductos) {
        datatableProductos.destroy();
        datatableProductos = null;
    }
    
    if (datatableCarrito) {
        datatableCarrito.destroy();
        datatableCarrito = null;
    }
    
    seccionProductos.style.display = 'none';
    seccionCarrito.style.display = 'none';
    seccionObservaciones.style.display = 'none';
    BtnGuardar.style.display = 'none';
    BtnModificar.style.display = 'none';
    
    subtotalVenta.textContent = 'Q. 0.00';
    descuentoVenta.textContent = 'Q. 0.00';
    totalVenta.textContent = 'Q. 0.00';
};

// DataTable para ventas
const datatable = new DataTable('#TableVentas', {
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
            title: 'No. Venta', 
            data: 'numero_venta',
            width: '15%'
        },
        { 
            title: 'Cliente', 
            data: 'nombres',
            width: '20%',
            render: (data, type, row) => {
                return `${row.nombres} ${row.apellidos}`;
            }
        },
        { 
            title: 'Fecha', 
            data: 'fecha_venta',
            width: '12%',
            render: (data, type, row) => {
                const fecha = new Date(data);
                return fecha.toLocaleDateString('es-GT');
            }
        },
        { 
            title: 'Método Pago', 
            data: 'metodo_pago',
            width: '12%',
            render: (data, type, row) => {
                const badges = {
                    'efectivo': 'bg-success',
                    'tarjeta': 'bg-primary', 
                    'transferencia': 'bg-info'
                };
                return `<span class="badge ${badges[data] || 'bg-secondary'}">${data.toUpperCase()}</span>`;
            }
        },
        { 
            title: 'Estado', 
            data: 'estado_venta',
            width: '10%',
            render: (data, type, row) => {
                const badges = {
                    'completada': 'bg-success',
                    'pendiente': 'bg-warning',
                    'cancelada': 'bg-danger'
                };
                return `<span class="badge ${badges[data] || 'bg-secondary'}">${data.toUpperCase()}</span>`;
            }
        },
        { 
            title: 'Total', 
            data: 'total',
            width: '12%',
            render: (data, type, row) => {
                return `Q. ${parseFloat(data).toFixed(2)}`;
            }
        },
        {
            title: 'Acciones',
            data: 'id_venta',
            width: '14%',
            searchable: false,
            orderable: false,
            render: (data, type, row, meta) => {
                return `
                 <div class='d-flex justify-content-center'>
                     <button class='btn btn-info btn-sm me-1 ver-detalle' 
                         data-id="${data}"
                         title="Ver detalle">
                         <i class='bi bi-eye'></i>
                     </button>
                     <button class='btn btn-warning btn-sm me-1 modificar' 
                         data-id="${data}"
                         title="Modificar venta">
                         <i class='bi bi-pencil-square'></i>
                     </button>
                     <button class='btn btn-danger btn-sm eliminar' 
                         data-id="${data}"
                         title="Cancelar venta">
                        <i class="bi bi-x-circle"></i>
                     </button>
                 </div>`;
            }
        }
    ]
});

// Ver detalle de venta
const VerDetalle = async (ventaId) => {
    try {
        const respuesta = await fetch(`/base_login/ventas/detalleAPI?id=${ventaId}`);
        const datos = await respuesta.json();

        if (datos.codigo == 1) {
            const venta = datos.venta;
            const detalles = datos.detalles;

            let contenido = `
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>No. Venta:</strong> ${venta.numero_venta}
                    </div>
                    <div class="col-md-6">
                        <strong>Fecha:</strong> ${new Date(venta.fecha_venta).toLocaleDateString('es-GT')}
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Cliente:</strong> ${venta.nombres} ${venta.apellidos}
                    </div>
                    <div class="col-md-6">
                        <strong>Método Pago:</strong> ${venta.metodo_pago.toUpperCase()}
                    </div>
                </div>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            detalles.forEach(detalle => {
                contenido += `
                    <tr>
                        <td>
                            <strong>${detalle.producto_nombre}</strong><br>
                            <small class="text-muted">${detalle.producto_descripcion || 'Sin descripción'}</small>
                        </td>
                        <td>${detalle.cantidad}</td>
                        <td>Q. ${parseFloat(detalle.precio_unitario).toFixed(2)}</td>
                        <td>Q. ${parseFloat(detalle.subtotal).toFixed(2)}</td>
                    </tr>
                `;
            });

            contenido += `
                    </tbody>
                    <tfoot>
                        <tr class="table-secondary">
                            <td colspan="3"><strong>Subtotal:</strong></td>
                            <td><strong>Q. ${parseFloat(venta.subtotal).toFixed(2)}</strong></td>
                        </tr>
                        <tr class="table-secondary">
                            <td colspan="3"><strong>Descuento:</strong></td>
                            <td><strong>Q. ${parseFloat(venta.descuento).toFixed(2)}</strong></td>
                        </tr>
                        <tr class="table-info">
                            <td colspan="3"><strong>TOTAL:</strong></td>
                            <td><strong>Q. ${parseFloat(venta.total).toFixed(2)}</strong></td>
                        </tr>
                    </tfoot>
                </table>
            `;

            if (venta.observaciones) {
                contenido += `
                    <div class="mt-3">
                        <strong>Observaciones:</strong><br>
                        <p class="text-muted">${venta.observaciones}</p>
                    </div>
                `;
            }

            document.getElementById('contenidoDetalleVenta').innerHTML = contenido;
            
            const modal = new Modal(document.getElementById('modalDetalleVenta'));
            modal.show();
        }
    } catch (error) {
        Swal.fire({
            icon: "error",
            title: "Error",
            text: "No se pudo obtener el detalle"
        });
    }
};

// Eventos
BtnCargarProductos.addEventListener('click', CargarProductos);
FormVentas.addEventListener('submit', GuardarVenta);
BtnLimpiar.addEventListener('click', LimpiarTodo);
inputDescuento.addEventListener('change', ValidarDescuento);

datatable.on('click', '.ver-detalle', function() {
    const ventaId = this.dataset.id;
    VerDetalle(ventaId);
});

// Inicializar
CargarClientes();
BuscarVentas();