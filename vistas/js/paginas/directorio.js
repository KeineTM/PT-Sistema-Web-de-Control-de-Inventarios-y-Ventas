import { metodosValidacion } from "../validacion.js";
import { metodosModal } from "../modal.js";

const contactoID = document.querySelector('#contacto_id-txt');

// Validaciones en formularios de alta y edición
/** Método que recorre todas las etiquetas input para validar su contenido */
const validar = (campos, alertaHTML, listaErrores) => {
    campos.forEach(campo => {
        campo.style.background = 'var(--color-blanco)'; // Reestablece el color del campo

        const resultado_validacion = metodosValidacion.validarCampoDirectorio(campo);

        if(resultado_validacion !== null){ // Si detecta un error
            campo.style.background = 'var(--color-crema)'; // Resalta el campo
            listaErrores.push(resultado_validacion); // Almacena el mensaje asociado
        }
    });
    alertaHTML.style.visibility = 'visible';
    alertaHTML.innerHTML = listaErrores.join('<br>');
}

// Código para ejecutar una consulta asincrónica que valida que el código de barras no esté repetido
if(contactoID !== null) {
    const aletaHTML_validacion = document.querySelector('#alerta-valida_ID');
    const url = window.location.search;
    const urlParametros = new URLSearchParams(url);
    const contactoIDOriginal = urlParametros.get('id');

    const validarExistenciaAJAX = () => {
        aletaHTML_validacion.style.visibility = 'hidden';
        aletaHTML_validacion.innerText = '';

        const formData = new FormData();
        formData.append('contacto_id-txt', contactoID.value);
        
        if(contactoIDOriginal !== null) {
            formData.append('contacto_id_original-txt', contactoIDOriginal);
        }

        // Uso de Fetch para el paso del parámetro a la página PHP por POST
        fetch('controlador/ctrlContactos.php?funcion=validar-existencia', {
            method: 'POST',
            body: formData
        }).then(response => response.text() // Recuperación de la respuesta del servidor en texto plano
        ).then(data => {
            if(data !== '') {
                aletaHTML_validacion.style.visibility = 'visible';
                aletaHTML_validacion.innerText = data; // Impresión en pantalla de la respuesta del registro
                metodosModal.desplegarModal(document.getElementById('modal'));
                metodosModal.construirModalMensajeResultado(document.getElementById('modal'), data);
            } 
            
        }).catch(error => {
            console.error('Error:', error);
        });
    }

    contactoID.addEventListener('blur', validarExistenciaAJAX);
}

// Aplica tanto para formulario de alta como de edición
const formulario = document.querySelector('#formulario-directorio')

if(formulario !== null) {
    const listaCampos = document.querySelectorAll('[data-form]');
    const campoTelefono = document.querySelector('[data-form=contacto_id]');
    const btnEnviar = document.querySelector('#btnRegistrar');

    campoTelefono.addEventListener('input', () => {
        if(campoTelefono.value.length > 10) {
            campoTelefono.value = campoTelefono.value.slice(0,10);
        }
    });

    btnEnviar.addEventListener('click', (event) => {
        event.preventDefault();
        const listaErrores = [];
        
        validar(listaCampos, alertaHTML, listaErrores);

        if(listaErrores.length === 0) {
            formulario.submit()
        } else {
            metodosModal.desplegarModal(document.getElementById('modal'));
            metodosModal.construirModalAlerta(document.getElementById('modal'), listaErrores);
        }
    });
}


//----------------- AJAX búsqueda y despliegue de un contacto ------------------
const formularioBusqueda = document.querySelector('#barra-busqueda');
const campoBuscar = document.querySelector('[name=buscarContacto-txt]');
const btnBuscar = document.querySelector('#btnBuscar');
const alertaHTML = document.querySelector('#alertaBuscar');

/**
 * Método que valida el campo de búsqueda
 * @param {DOM} campo 
 * @returns Mensaje en caso de error o true en caso de validación correcta
 */
const validarBusqueda = (campo) => {
    if(campo.length < 3 ||
        campo < 0 ||
        campo.length > 240 ||
        (/\d/.test(campo.trim()))) {
        if(campo.length < 3) return 'La palabra clave para buscar debe tener mínimo 3 letras o 10 números';
        if(campo < 0) return 'Búsqueda no válida';
        if(campo.length > 240) return 'No debe sobrepasar 240 letras';

        if(/\d/.test(campo.trim())) // Sí la cadena contiene números:
            if(campo.length !== 10) return 'Un número de teléfono debe tener 10 números'
            else return true;
    } else
        return true;
}

if(btnBuscar !== null) {
    btnBuscar.addEventListener('click', (event) => {
    event.preventDefault();
    alertaHTML.innerHTML = '';

    let validacionResultado = validarBusqueda(campoBuscar.value);
    if(validacionResultado === true) {
        formularioBusqueda.submit();
    } else {
        alertaHTML.style.visibility = 'visible';
        alertaHTML.innerText = validacionResultado;
    }
});
}

//-------------------- Botón eliminar contacto ------------------
const formularioEliminarContacto = document.querySelector('#formulario-eliminar-contacto');
if(formularioEliminarContacto !== null) {
    const btnEliminar = document.querySelector('#btnEliminar');

    btnEliminar.addEventListener('click', (event)=> {
        event.preventDefault();

        const respuesta = confirm('Confirme la elminación del contacto');
        (respuesta === true)
            ? formularioEliminarContacto.submit()
            : console.log('Se rechazó la eliminación');
    });
    
}

// ------------------------------------------------------------------------------------------
// Métoso de ordenamiento para la lista de contactos
// ------------------------------------------------------------------------------------------
const selectFiltrar = document.querySelector('#lista-filtrar-txt');

if(selectFiltrar !== null) {
    selectFiltrar.addEventListener('change', (event) => {
        event.preventDefault();
        switch(selectFiltrar.value) {
            case 'Clientes':
                window.location.href = "index.php?pagina=directorio&opciones=listar&ordenar=clientes";
                break;
            case 'Proveedores':
                window.location.href = "index.php?pagina=directorio&opciones=listar&ordenar=proveedores";
                break;
            case 'Servicios':
                window.location.href = "index.php?pagina=directorio&opciones=listar&ordenar=servicios";
                break;
            default:
                window.location.href = "index.php?pagina=directorio&opciones=listar";
                break;
        }
    });
}