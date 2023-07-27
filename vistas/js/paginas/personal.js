import { metodosValidacion } from "../validacion.js";

const formularioAltaPersonal = document.querySelector('#formulario-personal');
const formularioEditarPersonal = document.querySelector('#formulario-editar');

/** Método que recorre todas las etiquetas input para validar su contenido */
const validar = (campos, alertaHTML, listaErrores) => {
    campos.forEach(campo => {
        campo.style.background = 'var(--color-blanco)'; // Reestablece el color del campo
        
        const resultado_validacion = new Array(metodosValidacion.validarCampoPersonal(campo));
        
        if(metodosValidacion.validarCampoPersonal(campo) !== null){ // Si detecta un error
            campo.style.background = 'var(--color-crema)'; // Resalta el campo
            listaErrores.push('<br>' + resultado_validacion); // Almacena el mensaje asociado
        }
    });
    alertaHTML.style.visibility = 'visible';
    alertaHTML.innerHTML = listaErrores.join('');
}

// ------------------------------------------------------------------------------------------
// JS DEL FORMULARIO DE ALTA DE PERSONAL
// ------------------------------------------------------------------------------------------
if(formularioAltaPersonal !== null) {
    const campos = document.querySelectorAll('[data-form]');
    const alertaHTML = document.querySelector('#alerta-formulario');
    const btnEnviar = document.querySelector('#btnRegistrar');
    const campoTelefono = document.querySelector('[data-form=telefono]');
    const campoUsuarioID = document.querySelector('[name=usuario_id-txt]');
    const campoRFC = document.querySelector('[data-form=rfc]');
    const campoPassword = document.querySelector('[data-form=password]');
    const campoPasswordRepetida = document.querySelector('[name=password_2-txt]');
    const alertaPassword = document.querySelector('#alerta-password');

    // Limitadores de caracteres
    campoTelefono.addEventListener('input', () => {
        if(campoTelefono.value.length > 10) {
            campoTelefono.value = campoTelefono.value.slice(0,10);
        }
    });

    campoRFC.addEventListener('input', () => {
        if(campoRFC.value.length > 13) {
            campoRFC.value = campoRFC.value.slice(0,13);
        }
    });

    // Asignación de nombre de usuario
    campoRFC.addEventListener('input', () => {
        campoUsuarioID.value = campoRFC.value.slice(0,6);
    });

    // Comparación de contraseñas
    campoPasswordRepetida.addEventListener('keyup', () => {
        if(campoPasswordRepetida.value !== campoPassword.value) {
            alertaPassword.style.visibility = 'visible';
            alertaPassword.innerHTML = 'Las contraseñas no coinciden';
        } 
        if(campoPasswordRepetida.value === campoPassword.value) {
            alertaPassword.innerHTML = '';
            alertaPassword.style.visibility = 'hidden';
        }
    });

    btnEnviar.addEventListener('click', (event) => {
        event.preventDefault();
        const listaErrores = [];
        
        validar(campos, alertaHTML, listaErrores);

        (listaErrores.length === 0)
            ? formularioAltaPersonal.submit()
            : console.log('No pasó la validación');
    });
}

// ------------------------------------------------------------------------------------------
// JS DEL FORMULARIO DE EDICIÓN DE PERSONAL
// ------------------------------------------------------------------------------------------
if(formularioEditarPersonal !== null) {
    const campos = document.querySelectorAll('[data-form]');
    const alertaHTML = document.querySelector('#alerta-formulario');
    const btnEnviar = document.querySelector('#btnEditar');
    const campoTelefono = document.querySelector('[data-form=telefono]');
    const campoUsuarioID = document.querySelector('[name=usuario_id-txt]');
    const campoRFC = document.querySelector('[data-form=rfc]');
    const campoPassword = document.querySelector('[data-form=password]');
    const campoPasswordRepetida = document.querySelector('[name=password_2-txt]');
    const alertaPassword = document.querySelector('#alerta-password');

    // Limitadores de caracteres
    campoTelefono.addEventListener('input', () => {
        if(campoTelefono.value.length > 10) {
            campoTelefono.value = campoTelefono.value.slice(0,10);
        }
    });

    campoRFC.addEventListener('input', () => {
        if(campoRFC.value.length > 13) {
            campoRFC.value = campoRFC.value.slice(0,13);
        }
    });

    // Asignación de nombre de usuario
    campoRFC.addEventListener('input', () => {
        campoUsuarioID.value = campoRFC.value.slice(0,6);
    });

    // Comparación de contraseñas
    campoPasswordRepetida.addEventListener('keyup', () => {
        if(campoPasswordRepetida.value !== campoPassword.value) {
            alertaPassword.style.visibility = 'visible';
            alertaPassword.innerHTML = 'Las contraseñas no coinciden';
        } 
        if(campoPasswordRepetida.value === campoPassword.value) {
            alertaPassword.innerHTML = '';
            alertaPassword.style.visibility = 'hidden';
        }
    });

    btnEnviar.addEventListener('click', (event) => {
        event.preventDefault();
        const listaErrores = [];
        
        validar(campos, alertaHTML, listaErrores);

        console.log(listaErrores);
        (listaErrores.length === 0)
            ? formularioEditarPersonal.submit()
            : console.log('No pasó la validación');
    });
}