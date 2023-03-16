let campoUsuario = document.getElementById('login-usuario');
let campoPassword = document.getElementById('login-pass');
let alertaUsuario = document.getElementById('alerta-usuario');
let alertaPassword = document.getElementById('alerta-password');
let btnEnviar = document.getElementById('btn-enviar');
let formulario = document.getElementById('form-login');

const mostrarAlerta = (campo, alerta) => {
    (!campo.value)
        ? alerta.style.visibility = 'visible'
        : alerta.style.visibility = 'hidden'
}

const validarVacio = () => {
    let validacion = true;
    
    if(!campoUsuario.value) {
        alert('El nombre de usuario no puede quedar vacío.');
        campoUsuario.focus();
        validacion = false;
    } else if(!campoPassword.value) {
        alert('La contraseña no puede quedar vacía.');
        campoPassword.focus();
        validacion = false;
    }     

    // Al pasar exitosamente el pequeño bucle de validación se manda el contenido del formulario al archivo objetivo: usuario-validacion.php
    if(validacion)
        formulario.submit();
}

// Valida vacío en los campos
campoUsuario.addEventListener('blur', () => mostrarAlerta(campoUsuario, alertaUsuario));
campoPassword.addEventListener('blur', () => mostrarAlerta(campoPassword, alertaPassword));

// Ejecuta la validación en caso de presionar 'Enter' desde el campo de contraseña
campoPassword.addEventListener('keyup', (event) => {
    if(event.keyCode === 13) {
        validarVacio();
    }
});

// Ejecuta la validación 
btnEnviar.addEventListener('click', validarVacio);