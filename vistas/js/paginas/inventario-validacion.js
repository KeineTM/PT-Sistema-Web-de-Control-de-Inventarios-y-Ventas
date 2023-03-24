import { metodosValidacion } from "../validacion.js"; 

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

const formularioAltaCategoria = document.getElementById("formulario-alta-categoria");
const nuevaCategoria = document.getElementById("categoria-txt");
const btnRegistrarCategoria = document.getElementById("btnRegistrarCategoria");

btnRegistrarProducto.addEventListener('click', (event) => {
    let listaCamposObligatorios = [productoID, nombre, categoria, unidades, precioVenta];
    metodosValidacion.validarLlenadoFormulario(event, listaCamposObligatorios, formularioAltaProducto);
});

/**
 * API FETCH para el registro de categorías asíncronas
 
btnRegistrarCategoria.addEventListener('click', (event) => {
    event.preventDefault();

    // Almacenamiento de los campos del formulario en un FormData
    const formData = new FormData();
    formData.append('categoria-txt', nuevaCategoria.value);

    // Uso de Fetch para el paso del formulario a la página PHP
    fetch('vistas/paginas/inventario-registrar-categoria.php', {
        method: 'POST',
        body: formData
    }).then(response => {
        console.log(formData.get('categoria-txt'));
    }).then(data => {
        //...
    }).catch(error => {
        console.error('Error:', error);
    });
})*/