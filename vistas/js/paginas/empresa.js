import { metodosValidacion } from "../validacion.js";
import { metodosModal } from "../modal.js";

/** Método que recorre todas las etiquetas input para validar su contenido */
const validar = (campos, alertaHTML, listaErrores) => {
    campos.forEach(campo => {
        campo.style.background = 'var(--color-blanco)'; // Reestablece el color del campo

        const resultado_validacion = metodosValidacion.validarCampoEmpresa(campo);

        if(resultado_validacion !== null){ // Si detecta un error
            campo.style.background = 'var(--color-crema)'; // Resalta el campo
            listaErrores.push(resultado_validacion); // Almacena el mensaje asociado
        }
    });
    alertaHTML.style.visibility = 'visible';
    alertaHTML.innerHTML = listaErrores.join('<br>');
}

const validarRedes = (campos, alertaHTML, listaErrores) => {
    campos.forEach(campo => {
        campo.style.background = 'var(--color-blanco)'; // Reestablece el color del campo

        const resultado_validacion = metodosValidacion.validarCampoRedesSociales(campo);

        if(resultado_validacion !== null){ // Si detecta un error
            campo.style.background = 'var(--color-crema)'; // Resalta el campo
            listaErrores.push(resultado_validacion); // Almacena el mensaje asociado
        }
    });
    alertaHTML.style.visibility = 'visible';
    alertaHTML.innerHTML = listaErrores.join('<br>');
}

const formulario = document.querySelector('#formulario-empresa');

if(formulario !== null) {
    const alertaHTML = document.querySelector('#alerta-formulario');
    const listaCamposEmpresa = document.querySelectorAll('[data-form]');
    const campoTelefono = document.querySelector('[data-form=telefono]');
    const btnEnviar = document.querySelector('#btnEditar');

    campoTelefono.addEventListener('input', () => {
        if(campoTelefono.value.length > 10) {
            campoTelefono.value = campoTelefono.value.slice(0,10);
        }
    });

    btnEnviar.addEventListener('click', (event) => {
        event.preventDefault();
        const listaErrores = [];
        
        validar(listaCamposEmpresa, alertaHTML, listaErrores);

        if(listaErrores.length === 0) {
            formulario.submit();
        } else {
            metodosModal.desplegarModal(document.getElementById('modal'));
            metodosModal.construirModalAlerta(document.getElementById('modal'), listaErrores);
        }
    });


    /* Métodos para la asociación de eventos a cada formulario de edición de cada red social encontrada */
    const formulariosEdicionRedes = document.querySelectorAll('[data-idform]');
    const btnsEditarRedes = document.querySelectorAll('[data-btn]');

    if (formulariosEdicionRedes !== null) {
        for(let i = 0; i < formulariosEdicionRedes.length; i++) {
            const camposDeEsteFormulario = formulariosEdicionRedes[i].querySelectorAll('[data-red]');

            btnsEditarRedes[i].addEventListener('click', (event) => {
                event.preventDefault();
                const listaErrores = [];

                validarRedes(camposDeEsteFormulario, alertaHTML, listaErrores);
        
                if(listaErrores.length === 0) {
                    formulariosEdicionRedes[i].submit();
                } else {
                    metodosModal.desplegarModal(document.getElementById('modal'));
                    metodosModal.construirModalAlerta(document.getElementById('modal'), listaErrores);
                }
            });
        }
    }
    

    const formularioAgregarNuevaRed = document.querySelector('#formulario-red-nueva');
    if(formularioAgregarNuevaRed !== null) {
        const campos = formularioAgregarNuevaRed.querySelectorAll('[data-red]');
        const btnEnviarNuevaRed = document.querySelector('#btnAgregarRed');

        btnEnviarNuevaRed.addEventListener('click', (event) => {
            event.preventDefault();
            const listaErrores = [];

            validarRedes(campos, alertaHTML, listaErrores);
    
            if(listaErrores.length === 0) {
                formularioAgregarNuevaRed.submit();
            } else {
                metodosModal.desplegarModal(document.getElementById('modal'));
                metodosModal.construirModalAlerta(document.getElementById('modal'), listaErrores);
            }
        });
    }
}