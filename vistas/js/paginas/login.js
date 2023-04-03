import { metodosValidacion } from "../validacion.js";

let campoUsuario = document.getElementById('login-usuario');
let campoPassword = document.getElementById('login-pass');
let alertaUsuario = document.getElementById('alerta-usuario');
let alertaPassword = document.getElementById('alerta-password');
let btnEnviar = document.getElementById('btn-enviar');
let formularioLogin = document.getElementById('form-login');
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
iconoOjito.addEventListener('click', verOcultarPassword);

campoPassword.addEventListener('keyup', (event) => {
    // Envía la validación de formulario con 'Enter'
    if(event.keyCode === 13) {
        let listaCamposObligatorios = [campoUsuario, campoPassword];
        if(metodosValidacion.validarLlenadoFormulario(listaCamposObligatorios)) {
            formularioLogin.submit();
        }
    }
});

btnEnviar.addEventListener('click', (event) => {
    event.preventDefault();
    let listaCamposObligatorios = [campoUsuario, campoPassword];
    if(metodosValidacion.validarLlenadoFormulario(listaCamposObligatorios)) {
        formularioLogin.submit();
    }
});