import Swal from 'sweetalert2';
import { validarFormulario } from '../funciones';

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
        const body = new FormData(FormLogin);
        const url = '/base_login/login'; 
        const config = {
            method: 'POST',
            body
        };

        const respuesta = await fetch(url, config);
        const data = await respuesta.json();
        const { codigo, mensaje, detalle } = data;

        if (codigo == 1) {
            await Swal.fire({
                title: 'Exito',
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

    } catch (error) {
        console.log(error);
    }

    BtnIniciar.disabled = false;
}

const logout = async () => {
    try {
        const confirmacion = await Swal.fire({
            title: '¿Cerrar sesión?',
            text: "¿Estás seguro que deseas cerrar la sesión?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, cerrar',
            cancelButtonText: 'Cancelar',
            background: '#e0f7fa',
            customClass: {
                title: 'custom-title-class',
                text: 'custom-text-class'
            }
        });

        if (confirmacion.isConfirmed) {
            await Swal.fire({
                title: 'Cerrando sesión',
                text: 'Redirigiendo al login...',
                icon: 'success',
                showConfirmButton: false,
                timer: 1000,
                timerProgressBar: true,
                background: '#e0f7fa',
                customClass: {
                    title: 'custom-title-class',
                    text: 'custom-text-class'
                }
            });

            location.href = '/base_login/logout';
        }

    } catch (error) {
        console.log(error);
    }
}

FormLogin.addEventListener('submit', login);
window.logout = logout;