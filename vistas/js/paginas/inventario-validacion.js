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
    let listaCamposObligatorios = [productoID, nombre, unidades, precioVenta];
    metodosValidacion.validarLlenadoFormulario(event, listaCamposObligatorios, formularioAltaProducto);
});