import Swal from "sweetalert2";


const FormRoles = document.getElementById('FormRoles');
const BtnGuardar = document.getElementById('BtnGuardar');
const BtnModificar = document.getElementById('BtnModificar');
const BtnLimpiar = document.getElementById('BtnLimpiar');
const BtnBuscar = document.getElementById('BtnBuscar');
const seccionTabla = document.getElementById('seccionTabla');
const bodyRoles = document.getElementById('bodyRoles');


const GuardarRol = async (event) => {
    event.preventDefault(); 

    const formData = new FormData(FormRoles);
    
    try {
        
        const respuesta = await fetch('/base_login/roles/guardarAPI', {
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
            BuscarRoles();
        } else {
         
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


const BuscarRoles = async () => {
    try {
        const respuesta = await fetch('/base_login/roles/buscarAPI');
        const datos = await respuesta.json();

        if (datos.codigo == 1) {
        
            bodyRoles.innerHTML = '';

            
            datos.data.forEach((rol, index) => {
                bodyRoles.innerHTML += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${rol.nombre_rol}</td>
                        <td>${rol.nombre_corto}</td>
                        <td>${rol.descripcion || 'Sin descripción'}</td>
                        <td>${rol.fecha_creacion || 'Sin fecha'}</td>
                        <td>
                            <button class="btn btn-warning btn-sm me-1" 
                                    onclick="EditarRol(${rol.id_rol}, '${rol.nombre_rol}', '${rol.nombre_corto}', '${rol.descripcion}')">
                                <i class="bi bi-pencil"></i> Editar
                            </button>
                            <button class="btn btn-danger btn-sm" 
                                    onclick="EliminarRol(${rol.id_rol}, '${rol.nombre_rol}')">
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
            text: "No se pudieron cargar los roles",
        });
    }
}


const MostrarTabla = () => {
    if (seccionTabla.style.display === 'none') {
        seccionTabla.style.display = 'block';
        BuscarRoles(); 
    } else {
        seccionTabla.style.display = 'none';
    }
}


const LimpiarFormulario = () => {
    FormRoles.reset();
    BtnGuardar.classList.remove('d-none');
    BtnModificar.classList.add('d-none');
}


window.EditarRol = (id, nombre, nombreCorto, descripcion) => {

    document.getElementById('id_rol').value = id;
    document.getElementById('nombre_rol').value = nombre;
    document.getElementById('nombre_corto').value = nombreCorto;
    document.getElementById('descripcion').value = descripcion;

   
    BtnGuardar.classList.add('d-none');
    BtnModificar.classList.remove('d-none');


    window.scrollTo({ top: 0, behavior: 'smooth' });
}


const ModificarRol = async () => {
    const formData = new FormData(FormRoles);
    
    try {
        const respuesta = await fetch('/base_login/roles/modificarAPI', {
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
            BuscarRoles();
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

window.EliminarRol = async (id, nombre) => {
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
            const respuesta = await fetch(`/base_login/roles/eliminarAPI?id=${id}`);
            const datos = await respuesta.json();

            if (datos.codigo == 1) {
                Swal.fire({
                    icon: "success",
                    title: "¡Eliminado!",
                    text: datos.mensaje,
                });
                BuscarRoles();
            }

        } catch (error) {
            console.log(error);
        }
    }
}


const capitalizarNombre = () => {
    const nombreInput = document.getElementById('nombre_rol');
    nombreInput.addEventListener('input', function() {
        const words = this.value.split(' ');
        const capitalizedWords = words.map(word => {
            return word.charAt(0).toUpperCase() + word.slice(1).toLowerCase();
        });
        this.value = capitalizedWords.join(' ');
    });
}


const mayusculasCorto = () => {
    const cortoInput = document.getElementById('nombre_corto');
    cortoInput.addEventListener('input', function() {
        this.value = this.value.toUpperCase();
    });
}


capitalizarNombre();
mayusculasCorto();


FormRoles.addEventListener('submit', GuardarRol);
BtnLimpiar.addEventListener('click', LimpiarFormulario);
BtnModificar.addEventListener('click', ModificarRol);
BtnBuscar.addEventListener('click', MostrarTabla);