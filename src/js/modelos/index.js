import { Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import { validarFormulario } from '../funciones';
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";

const FormModelos = document.getElementById('FormModelos');
const BtnGuardar = document.getElementById('BtnGuardar');
const BtnModificar = document.getElementById('BtnModificar');
const BtnLimpiar = document.getElementById('BtnLimpiar');
const BtnBuscar = document.getElementById('BtnBuscar');
const SelectMarca = document.getElementById('id_marca');

const GuardarModelo = async (event) => {
    event.preventDefault();
    BtnGuardar.disabled = true;

    if (!validarFormulario(FormModelos, ['id_modelo', 'color'])) {
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

    const body = new FormData(FormModelos);
    const url = '/base_login/modelos/guardarAPI';
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
            BuscarModelos();
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

const BuscarModelos = async () => {
    const url = `/base_login/modelos/buscarAPI`;
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
    const url = `/base_login/modelos/obtenerMarcasAPI`;
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

const datatable = new DataTable('#TableModelos', {
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
            data: 'id_modelo',
            width: '5%',
            render: (data, type, row, meta) => meta.row + 1
        },
        { 
            title: 'Marca', 
            data: 'nombre_marca', 
            width: '25%' 
        },
        { 
            title: 'Modelo', 
            data: 'nombre_modelo', 
            width: '30%' 
        },
        { 
            title: 'Color', 
            data: 'color', 
            width: '15%',
            render: (data) => data || 'Sin especificar'
        },
        { 
            title: 'Fecha Creación', 
            data: 'fecha_creacion', 
            width: '15%',
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
            data: 'id_modelo',
            searchable: false,
            orderable: false,
            width: '10%',
            render: (data, type, row, meta) => {
                return `
                 <div class='d-flex justify-content-center'>
                     <button class='btn btn-warning modificar mx-1 btn-sm' 
                         data-id="${data}" 
                         data-marca="${row.id_marca}"  
                         data-nombre="${row.nombre_modelo}"  
                         data-color="${row.color || ''}">
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

    document.getElementById('id_modelo').value = datos.id;
    document.getElementById('id_marca').value = datos.marca;
    document.getElementById('nombre_modelo').value = datos.nombre;
    document.getElementById('color').value = datos.color;

    BtnGuardar.classList.add('d-none');
    BtnModificar.classList.remove('d-none');

    window.scrollTo({ top: 0 });
}

const limpiarTodo = () => {
    FormModelos.reset();
    BtnGuardar.classList.remove('d-none');
    BtnModificar.classList.add('d-none');
}

const ModificarModelo = async (event) => {
    event.preventDefault();
    BtnModificar.disabled = true;

    if (!validarFormulario(FormModelos, ['color'])) {
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

    const body = new FormData(FormModelos);
    const url = '/base_login/modelos/modificarAPI';
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
            BuscarModelos();
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

const EliminarModelo = async (e) => {
    const idModelo = e.currentTarget.dataset.id

    const AlertaConfirmarEliminar = await Swal.fire({
        position: "center",
        icon: "question",
        title: "¿Desea eliminar este modelo?",
        text: 'Esta acción no se puede deshacer',
        showConfirmButton: true,
        confirmButtonText: 'Sí, Eliminar',
        confirmButtonColor: '#dc3545',
        cancelButtonText: 'No, Cancelar',
        showCancelButton: true
    });

    if (AlertaConfirmarEliminar.isConfirmed) {
        const url = `/base_login/modelos/eliminarAPI?id=${idModelo}`;
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
                
                BuscarModelos();
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
BuscarModelos();

// Event listeners
datatable.on('click', '.eliminar', EliminarModelo);
datatable.on('click', '.modificar', llenarFormulario);
FormModelos.addEventListener('submit', GuardarModelo);
BtnLimpiar.addEventListener('click', limpiarTodo);
BtnModificar.addEventListener('click', ModificarModelo);
BtnBuscar.addEventListener('click', BuscarModelos);