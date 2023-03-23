const btnAbrirAlta = document.getElementById("abrir__alta-inventario")
const btnCerrarModal = document.getElementById("btnCerrarModal");
const btnCerrarMiniModal = document.getElementById("btnCerrarMiniModal");

const btnAgregarCategoria = document.getElementById('btnAgregarCategoria');

/*******************************************************************************************/
/**
 * Aqui se detecta el elemento desde el cual se hace click.
 * Parte de un diseño estricto de la ventana modal para poder cerrarla.
 * Así esta función cierra cualquier ventana modal siempre y cuando todos los botones de cierre 
 * tengan el id = 'btnCerrarModal'.
 * @param {} event 
 */
const cerrarModalFormulario = (event) => {
    event.preventDefault();
    const contenedorBotones = event.target.parentNode;
    const contenedorFormulario = contenedorBotones.parentNode;
    const contenedorModal1 = contenedorFormulario.parentNode;
    const contenedorModal2 = contenedorModal1.parentNode;
    contenedorModal2.parentNode.classList.toggle("mostrar-modal");
}

btnCerrarModal.addEventListener("click", cerrarModalFormulario);

const cerrarModalMiniFormulario = (event) => {
    event.preventDefault();
    const contenedorFormulario = event.target.parentNode;
    const contenedorModal1 = contenedorFormulario.parentNode;
    const contenedorModal2 = contenedorModal1.parentNode;
    contenedorModal2.parentNode.classList.toggle("mostrar-modal");
}

btnCerrarMiniModal.addEventListener("click", cerrarModalMiniFormulario);

/*******************************************************************************************/
/**
 * Con este método se despliega una ventana modal, 
 * es necesario que se defina el id del elemento HTML que se mostrará
 * @param {*} event 
 * @param {node} modal 
 */
const desplegarModal = (modal) => {
    event.preventDefault();
    modal.classList.toggle('mostrar-modal');
}

btnAbrirAlta.addEventListener("click", () => {
    desplegarModal(document.getElementById("modal__alta-inventario"));
});

btnAgregarCategoria.addEventListener('click', () => {
    desplegarModal(document.getElementById("modal__alta-categoria"));
});

