import { Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import DataTable from "datatables.net-bs5";
import { validarFormulario } from "../funciones";
import { lenguaje } from "../lenguaje";

const FormInventario = document.getElementById("FormInventario");
const BtnGuardar = document.getElementById("BtnGuardar");
const BtnModificar = document.getElementById("BtnModificar");
const BtnLimpiar = document.getElementById("BtnLimpiar");
const SelectMarca = document.getElementById("id_marca");

const Datosdelatabla = new DataTable("#TableInventario", {
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
      data: "id_inventario",
      width: "5%",
      render: (data, type, row, meta) => meta.row + 1,
    },
    { title: "Marca", data: "marca_nombre", width: "15%" },
    { 
      title: "Estado Dispositivo", 
      data: "estado_dispositivo", 
      width: "10%",
      render: (data) => {
        const badges = {
          'NUEVO': '<span class="badge bg-success">NUEVO</span>',
          'USADO': '<span class="badge bg-warning">USADO</span>',
          'REPARADO': '<span class="badge bg-info">REPARADO</span>'
        };
        return badges[data] || data;
      }
    },
    { 
      title: "Estado Inventario", 
      data: "estado_inventario", 
      width: "10%",
      render: (data) => {
        const badges = {
          'DISPONIBLE': '<span class="badge bg-primary">DISPONIBLE</span>',
          'VENDIDO': '<span class="badge bg-danger">VENDIDO</span>',
          'EN_REPARACION': '<span class="badge bg-secondary">EN REPARACIÓN</span>'
        };
        return badges[data] || data;
      }
    },
    { 
      title: "Número Serie", 
      data: "numero_serie", 
      width: "10%",
      render: (data) => data || 'N/A'
    },
    { 
      title: "Stock", 
      data: "stock_disponible", 
      width: "8%",
      render: (data) => `<span class="badge bg-dark">${data}</span>`
    },
    { 
      title: "Precio Compra", 
      data: "precio_compra", 
      width: "10%",
      render: (data) => `Q ${parseFloat(data).toFixed(2)}`
    },
    { 
      title: "Precio Venta", 
      data: "precio_venta", 
      width: "10%",
      render: (data) => `Q ${parseFloat(data).toFixed(2)}`
    },
    { 
      title: "Fecha Ingreso", 
      data: "fecha_ingreso", 
      width: "10%", 
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
      data: "id_inventario",
      searchable: false,
      orderable: false,
      width: "12%",
      render: (data, type, row, meta) => {
        return `
                <div class='d-flex flex-column align-items-center'>
                    <button class='btn btn-warning modificar btn-sm mb-1' 
                        data-id_inventario="${data}" 
                        data-id_marca="${row.id_marca}"
                        data-estado_dispositivo="${row.estado_dispositivo}"
                        data-estado_inventario="${row.estado_inventario}"
                        data-numero_serie="${row.numero_serie || ''}"
                        data-precio_compra="${row.precio_compra}"
                        data-precio_venta="${row.precio_venta}"
                        data-stock_disponible="${row.stock_disponible}"
                        data-observaciones="${row.observaciones || ''}"
                        title="Modificar">
                        <i class='bi bi-pencil-square'></i>
                    </button>
                    <button class='btn btn-danger eliminar btn-sm' 
                        data-id_inventario="${data}"
                        title="Eliminar">
                        <i class="bi bi-trash3"></i>
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

  if (!validarFormulario(FormInventario, ["id_inventario", "observaciones", "numero_serie"])) {
    Swal.fire({
      position: "center",
      icon: "error",
      title: "Campos obligatorios",
      text: "Por favor, complete todos los campos obligatorios.",
      showConfirmButton: true,
    });
    BtnGuardar.disabled = false;
    return;
  }

  const body = new FormData(FormInventario);
  const url = "/base_login/inventario/guardarAPI";
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
        text: "Producto agregado al inventario correctamente",
        timer: 2000,
      });

      limpiarFormulario();
      buscarAPI();
    } else {
      Swal.fire({
        position: "center",
        icon: "error",
        title: "Error",
        text: mensaje,
        showConfirmButton: true,
      });
    }
  } catch (error) {
    console.log(error);
  }
  BtnGuardar.disabled = false;
};

const buscarAPI = async () => {
  const url = "/base_login/inventario/buscarAPI";
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
    }
  } catch (error) {
    console.log(error);
  }
};

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
        SelectMarca.innerHTML += `<option value="${marca.id_marca}">${marca.marca_nombre}</option>`;
      });
    }

  } catch (error) {
    console.log(error)
  }
}

const llenarFormulario = (e) => {
  const datos = e.currentTarget.dataset;
  document.getElementById("id_inventario").value = datos.id_inventario;
  document.getElementById("id_marca").value = datos.id_marca;
  document.getElementById("estado_dispositivo").value = datos.estado_dispositivo;
  document.getElementById("estado_inventario").value = datos.estado_inventario;
  document.getElementById("numero_serie").value = datos.numero_serie;
  document.getElementById("precio_compra").value = datos.precio_compra;
  document.getElementById("precio_venta").value = datos.precio_venta;
  document.getElementById("stock_disponible").value = datos.stock_disponible;
  document.getElementById("observaciones").value = datos.observaciones;

  BtnGuardar.classList.add("d-none");
  BtnModificar.classList.remove("d-none");

  window.scrollTo({
    top: 0,
  });
};

const limpiarFormulario = () => {
  FormInventario.reset();
  BtnGuardar.classList.remove("d-none");
  BtnModificar.classList.add("d-none");
};

const modificarAPI = async (e) => {
  e.preventDefault();
  BtnModificar.disabled = true;

  if (!validarFormulario(FormInventario, ["id_inventario", "observaciones", "numero_serie"])) {
    Swal.fire({
      position: "center",
      icon: "error",
      title: "Campos obligatorios",
      text: "Por favor, complete todos los campos obligatorios.",
      showConfirmButton: true,
    });
    BtnModificar.disabled = false;
    return;
  }

  const body = new FormData(FormInventario);
  const url = "/base_login/inventario/modificarAPI";
  const config = {
    method: "POST",
    body,
  };
  try {
    const respuesta = await fetch(url, config);
    const datos = await respuesta.json();

    const { codigo, mensaje } = datos;

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
        icon: "error",
        title: "error",
        text: mensaje,
        showConfirmButton: true,
      });
    }
  } catch (error) {
    console.log(error);
  }
  BtnModificar.disabled = false;
};

const eliminarAPI = async (e) => {
    const idInventario = e.currentTarget.dataset.id_inventario

    const alertaConfirmaEliminar = await Swal.fire({
        position: "center",
        icon: "question",
        title: "¿Desea eliminar este producto del inventario?",
        text: 'Esta acción cambiará el estado del producto a inactivo',
        showConfirmButton: true,
        confirmButtonText: 'Sí, Eliminar',
        confirmButtonColor: '#dc3545',
        cancelButtonText: 'No, Cancelar',
        showCancelButton: true
    });

   if (!alertaConfirmaEliminar.isConfirmed) return;

    try {
        const respuesta = await fetch(`/base_login/inventario/eliminarAPI?id=${idInventario}`, {
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

// Inicializar
CargarMarcas();
buscarAPI();

// Event Listeners
Datosdelatabla.on("click", ".eliminar", eliminarAPI);
Datosdelatabla.on("click", ".modificar", llenarFormulario);
FormInventario.addEventListener("submit", guardarAPI);
BtnLimpiar.addEventListener("click", limpiarFormulario);
BtnModificar.addEventListener("click", modificarAPI);