// -------------------------------------------------------------------------------------------------------
// Manejo de validación de los campos en los formularios del inventario
// -------------------------------------------------------------------------------------------------------
import { metodosValidacion } from "../validacion.js";

// Variables del formulario de alta de producto
const formularioAltaProducto = document.getElementById('formulario-alta-producto');
const productoID = document.getElementById('idProducto-txt');
const nombre = document.getElementById('nombreProducto-txt');
const categoria = document.getElementById('categoriaProducto-txt'); // NOTA: Es parte de otra tabla
const descripcion = document.getElementById('descripcionProducto-txt'); // No requerido
const unidades = document.getElementById('unidadesProducto-txt');
const unidadesMinimas = document.getElementById('unidadesMinimasProducto-txt'); // No requerido
const precioCompra = document.getElementById('precioCompraProducto-txt'); // No requerido
const precioVenta = document.getElementById('precioVentaProducto-txt');
const precioMayoreo = document.getElementById('precio|MayoreoProducto-txt'); // No requerido
const caducidad = document.getElementById('caducidadProducto-txt'); // No requerido -- NOTA: Es parte de otra tabla
const imagenURL = document.getElementById('imagenProducto-txt'); // No requerido
const btnRegistrarProducto = document.getElementById('btnRegistrarProducto');

btnRegistrarProducto.addEventListener('click', (event) => {
    event.preventDefault();
    let listaCamposObligatorios = [productoID, nombre, unidades, precioVenta];
    if(metodosValidacion.validarLlenadoFormulario(listaCamposObligatorios)) {
        formularioAltaProducto.submit();
    }
});


// -------------------------------------------------------------------------------------------------------
// Manejo de los Modales para el Formulario de alta de producto y de alta de categorías
// -------------------------------------------------------------------------------------------------------
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


// -------------------------------------------------------------------------------------------------------
// Carga de la lista de categorías mediante el API fetch de JS
// -------------------------------------------------------------------------------------------------------
selectCategorias = document.getElementById("categoriaProducto-txt");

/** Método que recibe una array de objetos con información de la tabla */
const cargarOptions = (selectCategorias, listaCategorias) => {
    selectCategorias.innerHTML = "<option disabled selected>Categorías...</option>";

    for (const registro of listaCategorias) { // Recorremos el array para entrar a cada registro (tipo object)
        const nuevaOpcion = document.createElement('option');  // Creamos una nueva option
        nuevaOpcion.value = registro['categoria_id']; // Se le asigna de value el valor de PK en tabla
        nuevaOpcion.textContent = registro['categoria']; // Se incluye en texto el nombre de la categoria
        selectCategorias.appendChild(nuevaOpcion); // Se agrega a la etiqueta select
    }
};

/** API FETCH para la recuperación y listado de categorías asíncronas */
const recuperarCategoriasAsincrono = (selectCategorias) => {
    fetch('vistas/paginas/inventario-listar-categorias.php') // Esta página de PHP se ejecuta y el resultado o respuesta (un echo) es el que regresa a este método
    .then(response => response.json()) // Aquí se recibe un JSON
    .then(data => {
        cargarOptions(selectCategorias, data);
    }).catch(error => {
        console.error('Error:', error);
    });
};

recuperarCategoriasAsincrono(selectCategorias);


// -------------------------------------------------------------------------------------------------------
// Registro de categoría en base de datos mediante el API fetch de JS
// -------------------------------------------------------------------------------------------------------
const btnRegistrarCategoria = document.getElementById("btnRegistrarCategoria");

/** API FETCH para el registro de categorías asíncronas */
const registrarCategoriaAsincrono = () => {
    // Almacenamiento de los campos del formulario en un FormData
    const formData = new FormData();
    formData.append('categoria-txt', nuevaCategoria.value);

    // Uso de Fetch para el paso del formulario a la página PHP por POST
    fetch('vistas/paginas/inventario-registrar-categoria.php', {
        method: 'POST',
        body: formData
    }).then(response => response.text() // Recuperación de la respuesta del servidor con text()
    ).then(data => {
        alert(data); // Impresión de en pantalla de la respuesta
        nuevaCategoria.value = ''; // Limpia el campo
        recuperarCategoriasAsincrono(selectCategorias); // RECARGA LA LISTA DE OPCIONES CON OTRO AJAX del archivo inventario-listar-categorias-asincrono.js
    }).catch(error => {
        console.error('Error:', error);
    });
}

btnRegistrarCategoria.addEventListener('click', (event) => {
    event.preventDefault();
    if(nuevaCategoria.value.length > 0) {
        registrarCategoriaAsincrono();
    } else 
        alert("Debe ingresar una categoría.");
})
