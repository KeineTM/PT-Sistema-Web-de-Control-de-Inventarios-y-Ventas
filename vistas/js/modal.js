/**
 * Con este método se despliega una ventana modal, 
 * es necesario que se defina el id del elemento HTML que se mostrará
 * @param {*} event 
 * @param {node} modal 
 */
const desplegarModal = (modal) => {
    //event.preventDefault();
    modal.classList.toggle('mostrar-modal');
};

/**
 * Método que detecta el elemento desde el cual se hace click.
 * Parte de un diseño estricto de la ventana modal para poder cerrarla.
 * Así esta función cierra cualquier ventana modal siempre y cuando todos los botones de cierre 
 * tengan el id = 'btnCerrarModal'.
 * @param {} event 
 */
const cerrarModal = (contenedor) => {
    event.preventDefault();
    contenedor.parentNode.classList.toggle("mostrar-modal");
    contenedor.parentNode.innerHTML = "";
};

const irArriba = () => {
    window.scrollTo({
        top: 0,
        behavior: "smooth"
    });
}

/** Constructor de modal para mostrar alertas. Recibe un contenedor HTML donde se alojará la ventana
 * y el mensaje que imprimirá al usuario como string o como lista de strings */
const construirModalAlerta = (contenedor, mensaje) => {
    const modalAlerta = 
        `<div class="modal__contenedor" id="contenedorModalAlerta">
            <span class="formulario__encabezado">
                <img class="formulario__icono" src="vistas/img/triangle-exclamation.svg" alt="Formulario">
                <h2 class="destacado">Se han encontrado los siguientes errores:</h2>
            </span>
            <ol class="lista-ordenada contenido-centrado" id="contenedorMensaje">
            </ol>
            <button class="boton otro" id="btnCerrarMiniModal">Cerrar</button>
        </div>`;

    contenedor.innerHTML = "";
    contenedor.innerHTML = modalAlerta;

    // Control de cierre de la ventana:
    const contenedorModalAlerta = document.getElementById('contenedorModalAlerta');
    const btnCerrarModal = document.getElementById('btnCerrarMiniModal');
    
    btnCerrarModal.addEventListener("click", () => {
        metodosModal.cerrarModal(contenedorModalAlerta);
    });

    // Impresión de mensaje
    const contenedorMensaje = document.getElementById('contenedorMensaje');

    if(Array.isArray(mensaje)) {
        let listado = [];
        mensaje.forEach(error => {
            listado.push('<li>'+ error +'</li>')
        });
        contenedorMensaje.innerHTML = listado.join('');
    } else {
        contenedorMensaje.innerHTML = mensaje;
    }
    
    irArriba();
}

const construirModalMensajeResultado = (contenedor, mensaje) => {
    const modal = 
        `<div class="modal__contenedor" id="contenedorModalAlerta">
            <span class="formulario__encabezado">
                <img class="formulario__icono" src="vistas/img/circle-check.svg" alt="icono">
                <h2 class="destacado" id="contenedorRespuesta">Resultado: </h2>
            </span>
            <p class="texto-centrado">${mensaje}</p>
            <button class="boton otro" id="btnCerrarMiniModal">Cerrar</button>
        </div>`;

    contenedor.innerHTML = "";
    contenedor.innerHTML = modal;

    // Control de cierre de la ventana:
    const contenedorModalAlerta = document.getElementById('contenedorModalAlerta');
    const btnCerrarModal = document.getElementById('btnCerrarMiniModal');
    
    btnCerrarModal.addEventListener("click", () => {
        metodosModal.cerrarModal(contenedorModalAlerta);
    });
}

export const metodosModal = {
    desplegarModal,
    cerrarModal,
    construirModalAlerta,
    construirModalMensajeResultado
}