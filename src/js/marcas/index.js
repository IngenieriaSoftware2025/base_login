import { Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import DataTable from "datatables.net-bs5";
import { validarFormulario } from "../funciones";
import { lenguaje } from "../lenguaje";

const FormMarcas = document.getElementById("FormMarcas");
const BtnGuardar = document.getElementById("BtnGuardar");
const BtnModificar = document.getElementById("BtnModificar");
const BtnLimpiar = document.getElementById("BtnLimpiar");

const Datosdelatabla = new DataTable("#TableMarcas", {
  dom: `<"row mt-3 justify-content-between" 
            <"col" l> 
            <"col" B> 
            <"col-3" f>
        >
        t
        <"row mt-3 justify-content-between" 
            <"col-md-3 d-flex align-items-center" i> 
            <"col-md-8 d-flex justify-content-end" p>
        >`,
  language: lenguaje,
  data: [],
  columns: [
    {
      title: "No.",
      data: "id_marca",
      width: "5%",
      render: (data, type, row, meta) => meta.row + 1,
    },
    { title: "Nombre de la Marca", data: "marca_nombre", width: "25%" },
    { 
      title: "Modelo Principal", 
      data: "marca_modelo", 
      width: "20%",
      render: (data) => data || 'N/A'
    },
    { 
      title: "Descripción", 
      data: "marca_descripcion", 
      width: "25%",
      render: (data) => data || 'Sin descripción'
    },
    { 
      title: "Fecha Ingreso", 
      data: "marca_fecha_ingreso", 
      width: "15%", 
      render: (data) => {
        if(data) {
          const fecha = new Date(data);
          return fecha.toLocaleDateString('es-GT');
        }
        return '';
      }
    },
    {
      title: "Acciones",
      data: "id_marca",
      searchable: false,
      orderable: false,
      width: "10%",
      render: (data, type, row, meta) => {
        return `
                <div class='d-flex justify-content-center'>
                    <button class='btn btn-warning modificar mx-1' 
                        data-id_marca="${data}" 
                        data-marca_nombre="${row.marca_nombre}"  
                        data-marca_descripcion="${row.marca_descripcion || ''}"
                        data-marca_modelo="${row.marca_modelo || ''}">
                        <i class='bi bi-pencil-square me-1'></i> Modificar
                    </button>
                    <button class='btn btn-danger eliminar mx-1' 
                        data-id_marca="${data}">
                        <i class="bi bi-trash3 me-1"></i>Eliminar
                    </button>
                </div>
                `;
      },
    },
  ],
});

const guardarAPI = async (e) => {
  e.preventDefault();
  BtnGuardar.disabled = true;

  if (!validarFormulario(FormMarcas, ["id_marca", "marca_descripcion", "marca_modelo"])) {
    Swal.fire({
      position: "center",
      icon: "error",
      title: "Campos obligatorios",
      text: "Por favor, complete el nombre de la marca.",
      showConfirmButton: true,
    });
    BtnGuardar.disabled = false;
    return;
  }

  const body = new FormData(FormMarcas);
  const url = "/base_login/marcas/guardarAPI";
  const config = {
    method: "POST",
    body: body,
  };

  try {
    const respuesta = await fetch(url, config);
    const datos = await respuesta.json();
    console.log(datos);
    const { codigo, mensaje } = datos;

    if (codigo === 1) {
      Swal.fire({
        position: "center",
        icon: "success",
        title: "éxito",
        text: "Marca guardada correctamente",
        timer: 2000,
      });

      limpiarFormulario();
      buscarAPI();
    }
  } catch (error) {
    console.log(error);
  }
  BtnGuardar.disabled = false;
};

const buscarAPI = async () => {
  const url = "/base_login/marcas/buscarAPI";
  const config = {
    method: "GET",
  };
  try {
    const respuesta = await fetch(url, config);
    const datos = await respuesta.json();
    const { codigo, mensaje, data } = datos;

    if (codigo === 1) {
      Swal.fire({
        position: "center",
        icon: "success",
        title: "éxito",
        text: mensaje,
        timer: 2000,
      });

      Datosdelatabla.clear().draw();
      if (data && data.length > 0) {
        Datosdelatabla.rows.add(data).draw();
      }
    } else {
      Swal.fire({
        position: "center",
        icon: "info",
        title: "información",
        text: mensaje,
        timer: 2000,
      });
      return;
    }
  } catch (error) {
    console.log(error);
  }
};

const llenarFormulario = (e) => {
  const datos = e.currentTarget.dataset;
  document.getElementById("id_marca").value = datos.id_marca;
  document.getElementById("marca_nombre").value = datos.marca_nombre;
  document.getElementById("marca_descripcion").value = datos.marca_descripcion;
  document.getElementById("marca_modelo").value = datos.marca_modelo;

  BtnGuardar.classList.add("d-none");
  BtnModificar.classList.remove("d-none");

  window.scrollTo({
    top: 0,
  });
};

const limpiarFormulario = () => {
  FormMarcas.reset();
  BtnGuardar.classList.remove("d-none");
  BtnModificar.classList.add("d-none");
};

const modificarAPI = async (e) => {
  e.preventDefault();
  BtnModificar.disabled = true;

  if (!validarFormulario(FormMarcas, ["id_marca"])) {
    Swal.fire({
      position: "center",
      icon: "error",
      title: "Campos obligatorios",
      text: "Por favor, complete el nombre de la marca.",
      showConfirmButton: true,
    });
    BtnModificar.disabled = false;
    return;
  }

  const body = new FormData(FormMarcas);
  const url = "/base_login/marcas/modificarAPI";
  const config = {
    method: "POST",
    body,
  };
  try {
    const respuesta = await fetch(url, config);
    const datos = await respuesta.json();

    const { codigo, mensaje, detalle } = datos;

    if (codigo === 1) {
      Swal.fire({
        position: "center",
        icon: "success",
        title: "éxito",
        text: mensaje,
        timer: 800,
        showConfirmButton: false,
      });

      limpiarFormulario();
      buscarAPI();
    } else {
      Swal.fire({
        position: "center",
        icon: "info",
        title: "error",
        text: detalle,
        showConfirmButton: false,
        timer: 20000,
      });
    }
  } catch (error) {
    console.log(error);
  }
  BtnModificar.disabled = false;
};

const eliminarAPI = async (e) => {
    const idMarca = e.currentTarget.dataset.id_marca

    const alertaConfirmaEliminar = await Swal.fire({
        position: "center",
        icon: "question",
        title: "¿Desea eliminar esta marca?",
        text: 'Esta acción cambiará el estado de la marca a inactivo',
        showConfirmButton: true,
        confirmButtonText: 'Sí, Eliminar',
        confirmButtonColor: '#dc3545',
        cancelButtonText: 'No, Cancelar',
        showCancelButton: true
    });

   if (!alertaConfirmaEliminar.isConfirmed) return;

    try {
        const respuesta = await fetch(`/base_login/marcas/eliminarAPI?id=${idMarca}`, {
            method: 'GET'
        });

        const datos = await respuesta.json();
        const { codigo, mensaje } = datos;

        if (codigo === 1) {
            await Swal.fire({
                position: "center",
                icon: "success",
                title: "Éxito",
                text: mensaje
            });
            buscarAPI();
        } else {
            await Swal.fire({
                position: "center",
                icon: "error",
                title: "Error",
                text: mensaje
            });
        }

    } catch (error) {
        console.log(error);
    }
};

buscarAPI();
Datosdelatabla.on("click", ".eliminar", eliminarAPI);
Datosdelatabla.on("click", ".modificar", llenarFormulario);
FormMarcas.addEventListener("submit", guardarAPI);
BtnLimpiar.addEventListener("click", limpiarFormulario);
BtnModificar.addEventListener("click", modificarAPI);