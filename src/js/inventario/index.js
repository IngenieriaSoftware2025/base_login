//import { Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import { validarFormulario } from '../funciones';
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";

const FormInventario = document.getElementById('FormInventario');
const BtnGuardar = document.getElementById('BtnGuardar');
const BtnModificar = document.getElementById('BtnModificar');
const BtnLimpiar = document.getElementById('BtnLimpiar');
const BtnBuscar = document.getElementById('BtnBuscar');
const SelectMarca = document.getElementById('id_marca');
const SelectModelo = document.getElementById('id_modelo');

const GuardarInventario = async (event) => {
    event.preventDefault();
    BtnGuardar.disabled = true;

    if (!validarFormulario(FormInventario, ['id_inventario'])) {
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

    const body = new FormData(FormInventario);
    const url = '/base_login/inventario/guardarAPI';
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
            BuscarInventario();
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

const BuscarInventario = async () => {
    const url = `/base_login/inventario/buscarAPI`;
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

const CargarMarcas = async () => {
    const url = `/base_login/inventario/obtenerMarcasAPI`;
    const config = {
        method: 'GET'
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, data } = datos

        if (codigo == 1) {
            SelectMarca.innerHTML = '<option value="">-- Seleccione una marca --</option>';
            data.forEach(marca => {
                SelectMarca.innerHTML += `<option value="${marca.id_marca}">${marca.nombre_marca}</option>`;
            });
        }

    } catch (error) {
        console.log(error)
    }
}

const CargarModelos = async (id_marca) => {
    const url = `/base_login/inventario/obtenerModelosAPI?id_marca=${id_marca}`;
    const config = {
        method: 'GET'
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, data } = datos

        if (codigo == 1) {
            SelectModelo.innerHTML = '<option value="">-- Seleccione un modelo --</option>';
            data.forEach(modelo => {
                SelectModelo.innerHTML += `<option value="${modelo.id_modelo}">${modelo.nombre_modelo}</option>`;
            });
        } else {
            SelectModelo.innerHTML = '<option value="">-- No hay modelos --</option>';
        }

    } catch (error) {
        console.log(error)
        SelectModelo.innerHTML = '<option value="">-- Error al cargar modelos --</option>';
    }
}

// Event listener para cambio de marca
SelectMarca.addEventListener('change', (event) => {
    const id_marca = event.target.value;
    
    if (id_marca) {
        CargarModelos(id_marca);
    } else {
        SelectModelo.innerHTML = '<option value="">-- Seleccione un modelo --</option>';
    }
});

const datatable = new DataTable('#TableInventario', {
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
            data: 'id_inventario',
            width: '5%',
            render: (data, type, row, meta) => meta.row + 1
        },
        { 
            title: 'Marca', 
            data: 'nombre_marca', 
            width: '15%' 
        },
        { 
            title: 'Modelo', 
            data: 'nombre_modelo', 
            width: '15%' 
        },
        { 
            title: 'IMEI', 
            data: 'imei', 
            width: '15%' 
        },
        { 
            title: 'Estado Celular', 
            data: 'estado_celular', 
            width: '10%',
            render: (data) => {
                const badges = {
                    'nuevo': '<span class="badge bg-success">Nuevo</span>',
                    'usado': '<span class="badge bg-warning">Usado</span>',
                    'dañado': '<span class="badge bg-danger">Dañado</span>'
                };
                return badges[data] || data;
            }
        },
        { 
            title: 'Precio Compra', 
            data: 'precio_compra', 
            width: '10%',
            render: (data) => `Q. ${parseFloat(data).toFixed(2)}`
        },
        { 
            title: 'Precio Venta', 
            data: 'precio_venta', 
            width: '10%',
            render: (data) => `Q. ${parseFloat(data).toFixed(2)}`
        },
        { 
            title: 'Estado Inventario', 
            data: 'estado_inventario', 
            width: '10%',
            render: (data) => {
                const badges = {
                    'disponible': '<span class="badge bg-primary">Disponible</span>',
                    'vendido': '<span class="badge bg-secondary">Vendido</span>',
                    'en_reparacion': '<span class="badge bg-info">En Reparación</span>'
                };
                return badges[data] || data;
            }
        },
        {
            title: 'Acciones',
            data: 'id_inventario',
            searchable: false,
            orderable: false,
            width: '10%',
            render: (data, type, row, meta) => {
                return `
                 <div class='d-flex justify-content-center'>
                     <button class='btn btn-warning modificar mx-1 btn-sm' 
                         data-id="${data}" 
                         data-modelo="${row.id_modelo}"  
                         data-imei="${row.imei}"  
                         data-estado-celular="${row.estado_celular}"  
                         data-precio-compra="${row.precio_compra}"  
                         data-precio-venta="${row.precio_venta}"  
                         data-estado-inventario="${row.estado_inventario}">
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

    document.getElementById('id_inventario').value = datos.id;
    document.getElementById('id_modelo').value = datos.modelo;
    document.getElementById('imei').value = datos.imei;
    document.getElementById('estado_celular').value = datos.estadoCelular;
    document.getElementById('precio_compra').value = datos.precioCompra;
    document.getElementById('precio_venta').value = datos.precioVenta;
    document.getElementById('estado_inventario').value = datos.estadoInventario;

    BtnGuardar.classList.add('d-none');
    BtnModificar.classList.remove('d-none');

    window.scrollTo({ top: 0 });
}

const limpiarTodo = () => {
    FormInventario.reset();
    SelectModelo.innerHTML = '<option value="">-- Seleccione un modelo --</option>';
    BtnGuardar.classList.remove('d-none');
    BtnModificar.classList.add('d-none');
}

const ModificarInventario = async (event) => {
    event.preventDefault();
    BtnModificar.disabled = true;

    if (!validarFormulario(FormInventario, [])) {
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

    const body = new FormData(FormInventario);
    const url = '/base_login/inventario/modificarAPI';
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
            BuscarInventario();
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

const EliminarInventario = async (e) => {
    const idInventario = e.currentTarget.dataset.id

    const AlertaConfirmarEliminar = await Swal.fire({
        position: "center",
        icon: "question",
        title: "¿Desea eliminar este inventario?",
        text: 'Esta acción no se puede deshacer',
        showConfirmButton: true,
        confirmButtonText: 'Sí, Eliminar',
        confirmButtonColor: '#dc3545',
        cancelButtonText: 'No, Cancelar',
        showCancelButton: true
    });

    if (AlertaConfirmarEliminar.isConfirmed) {
        const url = `/base_login/inventario/eliminarAPI?id=${idInventario}`;
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
                
                BuscarInventario();
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

// Cargar datos al iniciar
CargarMarcas();
BuscarInventario();

// Event listeners
datatable.on('click', '.eliminar', EliminarInventario);
datatable.on('click', '.modificar', llenarFormulario);
FormInventario.addEventListener('submit', GuardarInventario);
BtnLimpiar.addEventListener('click', limpiarTodo);
BtnModificar.addEventListener('click', ModificarInventario);
BtnBuscar.addEventListener('click', BuscarInventario);