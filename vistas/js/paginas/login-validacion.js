let campoUsuario = document.getElementById('login-usuario');
let campoPassword = document.getElementById('login-pass');
let alertaUsuario = document.getElementById('alerta-usuario');
let alertaPassword = document.getElementById('alerta-password');
let btnEnviar = document.getElementById('btn-enviar');
let formulario = document.getElementById('form-login');
let iconoOjito = document.getElementById('ojito-pass');

/**
 * Método que muestra la alerta en el campo correspondiente.
 * @param {*} campo 
 * @param {*} alerta 
 */
const mostrarAlerta = (campo, alerta) => {
    (!campo.value)
        ? alerta.style.visibility = 'visible'
        : alerta.style.visibility = 'hidden'
}

/**
 * Método que evalúa si el campo está vacío para mandar la alerta correspondiente
 */
const validarVacio = () => {
    let validacion = true;
    
    if(campoUsuario.value.lenght === 0) {
        alert('El nombre de usuario no puede quedar vacío.');
        campoUsuario.focus();
        validacion = false;
    } else if(!campoPassword.value.lenght === 0) {
        alert('La contraseña no puede quedar vacía.');
        campoPassword.focus();
        validacion = false;
    }     

    // Manda el contenido del formulario al archivo objetivo: usuario-validacion.php
    if(validacion)
        formulario.submit();
}

/**
 * Método para alternar entre ver y ocultar el campo de la contraseña
 */
const verOcultarPassword = () => {
    if(campoPassword.type == 'password') {
        campoPassword.type = 'text';
        iconoOjito.src = "vistas/img/eye-slash.svg";
        iconoOjito.alt= "Icono ocultar";
        iconoOjito.title = "Ocultar contraseña";
    } else {
        campoPassword.type = 'password';
        iconoOjito.src = "vistas/img/eye.svg";
        iconoOjito.alt= "Icono ver";
        iconoOjito.title = "Ver contraseña";
    }
}

campoUsuario.addEventListener('blur', () => mostrarAlerta(campoUsuario, alertaUsuario));
campoPassword.addEventListener('blur', () => mostrarAlerta(campoPassword, alertaPassword));

campoPassword.addEventListener('keyup', (event) => {
    // Envía la validación de formulario con 'Enter'
    if(event.keyCode === 13) {
        validarVacio();
    }
});

btnEnviar.addEventListener('click', validarVacio);
iconoOjito.addEventListener('click', verOcultarPassword);