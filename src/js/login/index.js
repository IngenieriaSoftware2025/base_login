import Swal from 'sweetalert2';
import { validarFormulario } from '../funciones';

const FormLogin = document.getElementById('FormLogin');
const BtnIniciarSesion = document.getElementById('BtnIniciarSesion');

const login = async (e) => {
    e.preventDefault();

    BtnIniciarSesion.disabled = true;

    if (!validarFormulario(FormLogin, [''])) {
        Swal.fire({
            title: "Campos vacíos",
            text: "Debe llenar todos los campos",
            icon: "info"
        });
        BtnIniciarSesion.disabled = false;
        return;
    }

    try {
        const body = new FormData(FormLogin);
        const url = '/base_login/API/login';

        const config = {
            method: 'POST',
            body
        };

        const respuesta = await fetch(url, config);
        const data = await respuesta.json();
        const { codigo, mensaje } = data;

        if (codigo == 1) {
            await Swal.fire({
                title: 'Éxito',
                text: mensaje,
                icon: 'success',
                showConfirmButton: true,
                timer: 1500,
                timerProgressBar: false,
                background: '#e0f7fa'
            });

            FormLogin.reset();
            location.href = '/base_login/inicio'
        } else {
            Swal.fire({
                title: '¡Error!',
                text: mensaje,
                icon: 'warning',
                showConfirmButton: true,
                timer: 1500,
                timerProgressBar: false,
                background: '#e0f7fa'
            });
        }

    } catch (error) {
        console.log(error);
        Swal.fire({
            title: 'Error de conexión',
            text: 'No se pudo conectar con el servidor',
            icon: 'error'
        });
    }

    BtnIniciarSesion.disabled = false;
};

FormLogin.addEventListener('submit', login);