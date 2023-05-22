import { metodosValidacion } from "../validacion.js";

// Validaciones en formularios de alta y edición
/** Método que recorre todas las etiquetas input para validar su contenido */
const validar = (campos, alertaHTML, listaErrores) => {
    campos.forEach(campo => {
        campo.style.background = 'var(--color-blanco)'; // Reestablece el color del campo
        if(metodosValidacion.validarCampoDirectorio(campo) !== null){ // Si detecta un error
            campo.style.background = 'var(--color-crema)'; // Resalta el campo
            listaErrores.push("<br>" + metodosValidacion.validarCampoDirectorio(campo)); // Almacena el mensaje asociado
        }
    });
    alertaHTML.style.visibility = 'visible';
    alertaHTML.innerHTML = listaErrores;
}

// Aplica tanto para formulario de alta como de edición
const formulario = document.querySelector('#formulario-directorio')

if(formulario !== null) {
    const alertaHTML = document.querySelector('#alerta-formulario');
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

        (listaErrores.length === 0)
            ? formulario.submit()
            : console.log('No pasó la validación');
    });
}


//----------------- AJAX búsqueda y despliegue de un contacto ------------------
const contenedorHTML = document.querySelector('#subcontenedor');
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

const listarResultado = (contenedorHTML, data) => {
    contenedorHTML.innerHTML = '';
    const tabla = document.createElement('table');
    tabla.classList.add('tabla');
    
    const contenido =
        `<thead>
            <tr>
                <th>Nombre</th>
                <th>Teléfono</th>
                <th>Notas</th>
                <th>Tipo</th>
            </tr>
        </thead>
        <tbody id='datos-tabla'></tbody>`;

    tabla.innerHTML = contenido;
    const bodyTabla = tabla.querySelector('#datos-tabla');

    //Control de contenido
    data.forEach(contacto => {
        const fila = document.createElement('tr');
        const contenidoFila = `<td>${contacto['nombre']} ${contacto['apellido_paterno']} ${contacto['apellido_materno']}</td>
        <td><a class="texto-rosa" href="index.php?pagina=directorio&opciones=detalles&id=${contacto['contacto_id']}">${contacto['contacto_id']}<br>Detalles</a></td>
        <td>${contacto['notas']}</td>
        <td>${contacto['tipo_contacto']}</td>`;
        fila.innerHTML = contenidoFila;
        bodyTabla.appendChild(fila);
    });

    contenedorHTML.appendChild(tabla);
}

const buscarContactoAJAX = (formularioBusqueda, contenedorHTML) => {
    const formData = new FormData(formularioBusqueda);

    contenedorHTML.innerText = "Buscando...";

    fetch('controlador/ctrlContactos.php?funcion=buscar' , {
        method: 'POST',
        body: formData
    }).then(response => response.json())
    .then(data => {
        if(data.length !== 0) {
            if(data === false) 
                contenedorHTML.innerText = data; // El servidor devolvió un error
            else
                listarResultado(contenedorHTML, data); // Devolvió una coincidencia
        } else contenedorHTML.innerText = "No hay coincidencias...";
    }).catch(error => {
        console.log('Error: ', error);
    })
}

btnBuscar.addEventListener('click', (event) => {
    event.preventDefault();
    alertaHTML.innerHTML = '';

    let validacionResultado = validarBusqueda(campoBuscar.value);
    if(validacionResultado === true) {
        buscarContactoAJAX(formularioBusqueda, contenedorHTML);
    } else {
        alertaHTML.style.visibility = 'visible';
        alertaHTML.innerText = validacionResultado;
    }
});

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