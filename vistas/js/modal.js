/**
 * Con este método se despliega una ventana modal, 
 * es necesario que se defina el id del elemento HTML que se mostrará
 * @param {*} event 
 * @param {node} modal 
 */
const desplegarModal = (modal) => {
    event.preventDefault();
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

export const metodosModal = {
    desplegarModal,
    cerrarModal
}