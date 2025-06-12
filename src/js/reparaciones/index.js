//import { Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import { validarFormulario } from '../funciones';

// Verificar que los elementos existan antes de usarlos
const FormLogin = document.getElementById('FormLogin');
const BtnIniciarSesion = document.getElementById('BtnIniciarSesion');
const InputDpi = document.getElementById('dpi');

// Verificar que todos los elementos existan
if (!FormLogin || !BtnIniciarSesion || !InputDpi) {
    console.error('Elementos del formulario no encontrados:', {
        FormLogin: !!FormLogin,
        BtnIniciarSesion: !!BtnIniciarSesion,
        InputDpi: !!InputDpi
    });
}

// Validación en tiempo real del DPI
const ValidarDPI = () => {
    if (!InputDpi) return;
    
    const dpi = InputDpi.value.trim();
    
    // Remover caracteres no numéricos
    const dpiLimpio = dpi.replace(/\D/g, '');
    InputDpi.value = dpiLimpio;
    
    if (dpiLimpio.length === 0) {
        InputDpi.classList.remove('is-valid', 'is-invalid');
    } else if (dpiLimpio.length !== 13) {
        InputDpi.classList.remove('is-valid');
        InputDpi.classList.add('is-invalid');
    } else {
        InputDpi.classList.remove('is-invalid');
        InputDpi.classList.add('is-valid');
    }
}

const login = async (e) => {
    e.preventDefault();
    
    if (!BtnIniciarSesion) {
        console.error('Botón de iniciar sesión no encontrado');
        return;
    }
    
    BtnIniciarSesion.disabled = true;

    // Validar formulario
    if (!validarFormulario(FormLogin, [''])) {
        Swal.fire({
            title: "Campos vacíos",
            text: "Debe llenar todos los campos",
            icon: "info",
            confirmButtonColor: '#3085d6'
        });
        BtnIniciarSesion.disabled = false;
        return;
    }

    // Validar DPI específicamente
    const dpiInput = document.getElementById('dpi');
    if (!dpiInput) {
        console.error('Campo DPI no encontrado');
        BtnIniciarSesion.disabled = false;
        return;
    }
    
    const dpi = dpiInput.value.trim();
    if (dpi.length !== 13 || !/^\d{13}$/.test(dpi)) {
        Swal.fire({
            title: "DPI inválido",
            text: "El DPI debe tener exactamente 13 dígitos numéricos",
            icon: "warning",
            confirmButtonColor: '#f39c12'
        });
        BtnIniciarSesion.disabled = false;
        return;
    }

    try {
        const body = new FormData(FormLogin);
        const url = '/base_login/login/loginAPI';
        const config = {
            method: 'POST',
            body
        };

        console.log('Enviando petición a:', url);
        
        const respuesta = await fetch(url, config);
        
        // Verificar si la respuesta es JSON válido
        const contentType = respuesta.headers.get("content-type");
        if (!contentType || !contentType.includes("application/json")) {
            const textResponse = await respuesta.text();
            console.error('Respuesta no es JSON:', textResponse);
            throw new Error('El servidor no devolvió una respuesta JSON válida');
        }
        
        const data = await respuesta.json();
        console.log('Respuesta del servidor:', data);
        
        const { codigo, mensaje, usuario } = data;

        if (codigo == 1) {
            await Swal.fire({
                title: '¡Bienvenido!',
                text: `${mensaje}. Hola ${usuario || ''}`,
                icon: 'success',
                showConfirmButton: true,
                timer: 2000,
                timerProgressBar: true,
                background: '#f8f9fa',
                confirmButtonColor: '#28a745'
            });

            FormLogin.reset();
            
            // Redirigir a la página principal
            location.href = '/base_login/';
        } else {
            Swal.fire({
                title: '¡Error de autenticación!',
                text: mensaje,
                icon: 'error',
                showConfirmButton: true,
                confirmButtonColor: '#dc3545',
                background: '#f8f9fa'
            });
        }

    } catch (error) {
        console.error('Error en login:', error);
        Swal.fire({
            title: 'Error de conexión',
            text: 'No se pudo conectar con el servidor. Intente nuevamente.',
            icon: 'error',
            confirmButtonColor: '#dc3545'
        });
    }

    BtnIniciarSesion.disabled = false;
}

// Event listeners con verificación
if (FormLogin) {
    FormLogin.addEventListener('submit', login);
}

if (InputDpi) {
    InputDpi.addEventListener('input', ValidarDPI);
    
    // Prevenir caracteres no numéricos en el campo DPI
    InputDpi.addEventListener('keypress', (e) => {
        // Permitir solo números, backspace, delete, tab, escape, enter
        const allowedKeys = [8, 9, 27, 13, 46];
        const isNumber = (e.which >= 48 && e.which <= 57);
        const isAllowedKey = allowedKeys.includes(e.which);
        
        if (!isNumber && !isAllowedKey) {
            e.preventDefault();
        }
    });
}