import Swal from 'sweetalert2';
import { validarFormulario } from '../funciones';
//import { Dropdown } from "bootstrap";

const FormLogin = document.getElementById('FormLogin');
const BtnIniciar = document.getElementById('BtnIniciar');

const login = async (e) => {
    e.preventDefault();
    BtnIniciar.disabled = true;

    if (!validarFormulario(FormLogin, [''])) {
        Swal.fire({
            title: "Campos vacíos",
            text: "Debe llenar todos los campos",
            icon: "info"
        });
        BtnIniciar.disabled = false;
        return;
    }

    try {
        //PREPARAR DATOS
        const body = new FormData(FormLogin);
        const url = '/base_login/login';  
        const config = {
            method: 'POST',
            body
        };
        //ENVIAR PETICION AJAX
        const respuesta = await fetch(url, config);
        const data = await respuesta.json();
        const { codigo, mensaje } = data;

        //MANEJAR LA RESPUESTA
        if (codigo == 1) {
            await Swal.fire({
                title: 'Éxito',
                text: mensaje,
                icon: 'success',
                showConfirmButton: true,
                timer: 1500,
                timerProgressBar: false,
                background: '#e0f7fa',
                customClass: {
                    title: 'custom-title-class',
                    text: 'custom-text-class'
                }
            });

            FormLogin.reset();
            location.href = '/base_login/inicio';
            //ERROR DE LOGIN
        } else {
            Swal.fire({
                title: '¡Error!',
                text: mensaje,
                icon: 'warning',
                showConfirmButton: true,
                timer: 1500,
                timerProgressBar: false,
                background: '#e0f7fa',
                customClass: {
                    title: 'custom-title-class',
                    text: 'custom-text-class'
                }
            });
        }
        //ERROR DE CONEXION
    } catch (error) {
        console.log(error);
        Swal.fire({
            title: 'Error de conexión',
            text: 'No se pudo conectar con el servidor',
            icon: 'error'
        });
    }

    BtnIniciar.disabled = false;
}







// const logout = async () => {
//     try {
//         const response = await fetch('/base_login/logout', {
//             method: 'POST',
//             headers: {
//                 'Content-Type': 'application/json',
//                 'X-Requested-With': 'XMLHttpRequest'
//             }
//         });

//         const result = await response.json();

//         if (result.codigo === 1) {
//             // Mostrar mensaje de éxito
//             Swal.fire({
//                 title: '¡Sesión cerrada!',
//                 text: result.mensaje,
//                 icon: 'success',
//                 timer: 1500,
//                 showConfirmButton: false
//             }).then(() => {
//                 // Redirigir al login
//                 window.location.href = '/base_login/login';
//             });
//         } else {
//             Swal.fire({
//                 title: 'Error',
//                 text: result.mensaje,
//                 icon: 'error'
//             });
//         }
//     } catch (error) {
//         console.error('Error al cerrar sesión:', error);
//         Swal.fire({
//             title: 'Error',
//             text: 'Error de conexión al cerrar sesión',
//             icon: 'error'
//         });
//     }
// };

// // Función para confirmar antes de cerrar sesión
// const confirmarLogout = () => {
//     Swal.fire({
//         title: '¿Cerrar sesión?',
//         text: '¿Estás seguro de que quieres cerrar tu sesión?',
//         icon: 'question',
//         showCancelButton: true,
//         confirmButtonColor: '#3085d6',
//         cancelButtonColor: '#d33',
//         confirmButtonText: 'Sí, cerrar sesión',
//         cancelButtonText: 'Cancelar'
//     }).then((result) => {
//         if (result.isConfirmed) {
//             logout();
//         }
//     });
// };

FormLogin.addEventListener('submit', login);