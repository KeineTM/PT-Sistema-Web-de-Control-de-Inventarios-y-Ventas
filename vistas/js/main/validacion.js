let campoUsuario = document.getElementById('usuario-txt');
let campoPassword = document.getElementById('password-txt');
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

    if(validacion)
        formulario.submit();
}

campoUsuario.addEventListener('blur', () => mostrarAlerta(campoUsuario, alertaUsuario));

campoPassword.addEventListener('blur', () => mostrarAlerta(campoPassword, alertaPassword));

btnEnviar.addEventListener('click', validarVacio);