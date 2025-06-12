import Swal from "sweetalert2";

// 1. OBTENER LOS ELEMENTOS DEL HTML
const FormPermisos = document.getElementById('FormPermisos');
const BtnGuardar = document.getElementById('BtnGuardar');
const BtnModificar = document.getElementById('BtnModificar');
const BtnLimpiar = document.getElementById('BtnLimpiar');
const BtnBuscar = document.getElementById('BtnBuscar');
const seccionTabla = document.getElementById('seccionTabla');
const bodyPermisos = document.getElementById('bodyPermisos');

// 2. FUNCIÓN PARA GUARDAR PERMISO
const GuardarPermiso = async (event) => {
    event.preventDefault(); // Evita que se recargue la página

    // Obtener los datos del formulario
    const formData = new FormData(FormPermisos);
    
    try {
        // Enviar datos al servidor
        const respuesta = await fetch('/base_login/permisos/guardarAPI', {
            method: 'POST',
            body: formData
        });

        const datos = await respuesta.json();

        if (datos.codigo == 1) {
            // Si todo salió bien
            Swal.fire({
                icon: "success",
                title: "¡Éxito!",
                text: datos.mensaje,
            });
            LimpiarFormulario();
            BuscarPermisos();
        } else {
            // Si hubo error
            Swal.fire({
                icon: "error",
                title: "Error",
                text: datos.mensaje,
            });
        }

    } catch (error) {
        console.log(error);
        Swal.fire({
            icon: "error",
            title: "Error",
            text: "No se pudo conectar con el servidor",
        });
    }
}

// 3. FUNCIÓN PARA BUSCAR PERMISOS
const BuscarPermisos = async () => {
    try {
        const respuesta = await fetch('/base_login/permisos/buscarAPI');
        const datos = await respuesta.json();

        if (datos.codigo == 1) {
            // Limpiar la tabla
            bodyPermisos.innerHTML = '';

            // Llenar la tabla con los datos
            datos.data.forEach((permiso, index) => {
                bodyPermisos.innerHTML += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${permiso.nombre_permiso}</td>
                        <td>${permiso.descripcion}</td>
                        <td>${permiso.fecha_creacion || 'Sin fecha'}</td>
                        <td>
                            <button class="btn btn-warning btn-sm me-1" 
                                    onclick="EditarPermiso(${permiso.id_permiso}, '${permiso.nombre_permiso}', '${permiso.descripcion}')">
                                <i class="bi bi-pencil"></i> Editar
                            </button>
                            <button class="btn btn-danger btn-sm" 
                                    onclick="EliminarPermiso(${permiso.id_permiso}, '${permiso.nombre_permiso}')">
                                <i class="bi bi-trash"></i> Eliminar
                            </button>
                        </td>
                    </tr>
                `;
            });
        }

    } catch (error) {
        console.log(error);
        Swal.fire({
            icon: "error",
            title: "Error",
            text: "No se pudieron cargar los permisos",
        });
    }
}

// 4. FUNCIÓN PARA MOSTRAR/OCULTAR TABLA
const MostrarTabla = () => {
    if (seccionTabla.style.display === 'none') {
        seccionTabla.style.display = 'block';
        BuscarPermisos();
    } else {
        seccionTabla.style.display = 'none';
    }
}

// 5. FUNCIÓN PARA LIMPIAR FORMULARIO
const LimpiarFormulario = () => {
    FormPermisos.reset();
    BtnGuardar.classList.remove('d-none');
    BtnModificar.classList.add('d-none');
}

// 6. FUNCIÓN PARA EDITAR PERMISO (GLOBAL)
window.EditarPermiso = (id, nombre, descripcion) => {
    // Llenar el formulario con los datos
    document.getElementById('id_permiso').value = id;
    document.getElementById('nombre_permiso').value = nombre;
    document.getElementById('descripcion').value = descripcion;

    // Cambiar los botones
    BtnGuardar.classList.add('d-none');
    BtnModificar.classList.remove('d-none');

    // Ir arriba de la página
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// 7. FUNCIÓN PARA MODIFICAR PERMISO
const ModificarPermiso = async () => {
    const formData = new FormData(FormPermisos);
    
    try {
        const respuesta = await fetch('/base_login/permisos/modificarAPI', {
            method: 'POST',
            body: formData
        });

        const datos = await respuesta.json();

        if (datos.codigo == 1) {
            Swal.fire({
                icon: "success",
                title: "¡Éxito!",
                text: datos.mensaje,
            });
            LimpiarFormulario();
            BuscarPermisos();
        } else {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: datos.mensaje,
            });
        }

    } catch (error) {
        console.log(error);
    }
}

// 8. FUNCIÓN PARA ELIMINAR PERMISO (GLOBAL)
window.EliminarPermiso = async (id, nombre) => {
    const confirmacion = await Swal.fire({
        title: `¿Eliminar "${nombre}"?`,
        text: "Esta acción no se puede deshacer",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar"
    });

    if (confirmacion.isConfirmed) {
        try {
            const respuesta = await fetch(`/base_login/permisos/eliminarAPI?id=${id}`);
            const datos = await respuesta.json();

            if (datos.codigo == 1) {
                Swal.fire({
                    icon: "success",
                    title: "¡Eliminado!",
                    text: datos.mensaje,
                });
                BuscarPermisos();
            }

        } catch (error) {
            console.log(error);
        }
    }
}

// 9. CONECTAR LAS FUNCIONES CON LOS BOTONES
FormPermisos.addEventListener('submit', GuardarPermiso);
BtnLimpiar.addEventListener('click', LimpiarFormulario);
BtnModificar.addEventListener('click', ModificarPermiso);
BtnBuscar.addEventListener('click', MostrarTabla);