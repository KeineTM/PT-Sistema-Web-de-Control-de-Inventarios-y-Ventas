import { metodosModal } from "../modal.js";

const nuevaCategoria = document.getElementById("categoria-txt");
const btnFormularioCategoria = document.getElementById('btnAgregarCategoria');
const btnAbrirAlta = document.getElementById("abrir__alta-inventario");
const btnCerrarModal = document.getElementById("btnCerrarModal");
const btnCerrarMiniModal = document.getElementById("btnCerrarMiniModal");

btnAbrirAlta.addEventListener("click", () => {
    metodosModal.desplegarModal(document.getElementById("modal__alta-inventario"));
});

btnFormularioCategoria.addEventListener('click', () => {
    metodosModal.desplegarModal(document.getElementById("modal__alta-categoria"));
    nuevaCategoria.focus();
});

btnCerrarMiniModal.addEventListener("click", metodosModal.cerrarModalMiniFormulario);
btnCerrarModal.addEventListener("click", metodosModal.cerrarModalFormulario);