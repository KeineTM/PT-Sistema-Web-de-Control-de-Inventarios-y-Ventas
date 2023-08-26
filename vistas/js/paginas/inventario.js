// -------------------------------------------------------------------------------------------------------
// Manejo de los Modales para crear y desplegar el Formulario de alta de producto y de alta de categorías
// -------------------------------------------------------------------------------------------------------
import { metodosModal } from "../modal.js";
import { metodosValidacion } from "../validacion.js";
import { metodosAJAX } from "./ajax-inventario.js";

const formularioAlta = document.getElementById('formulario-alta-producto');
const contenedor = document.getElementById('subcontenedor');
const selectCategorias = document.getElementById("categoriaProducto-txt");
const formularioAltaCategoria = document.getElementById('formulario-alta-categoria');
const formularioEdicionCategoria = document.getElementById('formulario-edicion-categoria');

const campoCodigoDeBarras = document.querySelector('[data-form="productoID"]');

/** Método para prevenir el 'Enter' del lector de código de barras */
if(campoCodigoDeBarras !== null) {
    campoCodigoDeBarras.addEventListener('keydown', (event) => {
        if(event.key == "Enter") {
            event.preventDefault();
            const campoNombreProducto = document.querySelector('[data-form="nombreProducto"]');
            campoNombreProducto.focus();
            return false;
        }
    });
}

/** Método que recorre todas las etiquetas input para validar su contenido */
const validar = (campos, alertaHTML, listaErrores) => {
    campos.forEach(campo => {
        campo.style.background = 'var(--color-blanco)'; // Reestablece el color del campo

        const resultado_validacion = metodosValidacion.validarCampoProductos(campo);

        if(resultado_validacion !== null){ // Si detecta un error
            campo.style.background = 'var(--color-crema)'; // Resalta el campo
            listaErrores.push(resultado_validacion); // Almacena el mensaje asociado
        }
    });
    alertaHTML.style.visibility = 'visible';
    alertaHTML.innerHTML = listaErrores.join('<br>');
}


// -------------------------------------------------------------------------------------------------------
// FORMULARIO DE ALTA Y EDICIÓN DE CATEGORÍAS
// -------------------------------------------------------------------------------------------------------
const construirFormularioAltaCategoria = (contenedor) => {
    const formularioAltaCategoria = 
        `<div class="modal__contenedor" id="contenedorFormularioCategoria">
            <span class="formulario__encabezado">
                <img class="formulario__icono" src="vistas/img/file-invoice.svg" alt="Formulario">
                <h2>Formulario de Registro de categoría</h2>
                <span class="alerta" id="alerta-categoria"></span>
            </span>
            <form action="post" class="formulario" id="formulario-alta-categoria">
                <h3>Agregar categoría</h3>
                <input class="campo" type="text" placeholder="Categoria" id="categoria-txt" name="categoria-txt" autocomplete="off" minlength="3" maxlength="50" required>
                <button class="boton-form otro" id="btnCerrarMiniModal">Cancelar</button>
                <button class="boton-form enviar" id="btnRegistrarCategoria">Agregar</button>
            </form>
        </div>`;

    contenedor.innerHTML = "";
    contenedor.innerHTML = formularioAltaCategoria;
    document.getElementById('categoria-txt').focus();

    // Control de cierre de la ventana:
    const contenedorFormularioCategoria = document.getElementById('contenedorFormularioCategoria');
    const btnCerrarModal = document.getElementById('btnCerrarMiniModal');
    
    btnCerrarModal.addEventListener("click", () => {
        metodosModal.cerrarModal(contenedorFormularioCategoria);
    });

    // Registro de categoría nueva
    const btnRegistrarCategoria = document.getElementById('btnRegistrarCategoria');
    const categoriaTxt = document.getElementById('categoria-txt');
    const alertaCategoria = document.getElementById('alerta-categoria');
    
    btnRegistrarCategoria.addEventListener('click', () => {
        event.preventDefault();
        alertaCategoria.style.visibility = 'hidden';
        alertaCategoria.innerHTML = '';
        if(categoriaTxt.value.length > 3 && categoriaTxt.value.length <= 50)
            metodosAJAX.registrarCategoria(categoriaTxt, selectCategorias, alertaCategoria);
        else {
            alertaCategoria.style.visibility = 'visible';
            alertaCategoria.innerHTML = 'Ingrese un nombre para la categoría de 4 a 50 letras';
        }
    });
}

if(formularioAltaCategoria !== null) {
    // Registro de categoría nueva
    const btnRegistrarCategoria = document.getElementById('btnRegistrarCategoria');
    const categoriaTxt = document.getElementById('categoria-txt');
    const alertaCategoria = document.getElementById('alerta-categoria');
    
    btnRegistrarCategoria.addEventListener('click', () => {
        event.preventDefault();
        alertaCategoria.style.visibility = 'hidden';
        alertaCategoria.innerHTML = '';
        if(categoriaTxt.value.length > 3 && categoriaTxt.value.length <= 50) {
            alertaCategoria.style.visibility = 'hidden';
            metodosAJAX.registrarCategoria(categoriaTxt, selectCategorias, alertaCategoria);
        } else {
            alertaCategoria.style.visibility = 'visible';
            alertaCategoria.innerHTML = 'Ingrese un nombre para la categoría de 4 a 50 letras';
        }
    });
}

if(formularioEdicionCategoria !== null) {
    // Carga de lista de categorías
    const selectCategorias = document.getElementById("categoriaProducto-txt");
    const categoriaEditarTxt = document.getElementById('categoria_editar-txt');
    const radioEstadoActivo = document.getElementById('estado-activo');
    const radioEstadoInactivo = document.getElementById('estado-inactivo');

    // AJAX de categorías
    metodosAJAX.recuperarCategorias(selectCategorias);

    // Precarga el formulario con los datos recuperados desde el método anterior
    selectCategorias.addEventListener('change', () => {
        categoriaEditarTxt.value = selectCategorias.options[selectCategorias.selectedIndex].text;
        
        // Precargar radio button del estado:
        (selectCategorias.options[selectCategorias.selectedIndex].getAttribute('estado') == 1)
            ? radioEstadoActivo.checked = true
            : radioEstadoInactivo.checked = true;
    });

    // Edición de categoría
    const btnEditarCategoria = document.getElementById('btnEditarCategoria');
    const alertaCategoria = document.getElementById('alerta-edicion-categoria');
    const formulario = document.getElementById('formulario-edicion-categoria');
    
    btnEditarCategoria.addEventListener('click', () => {
        event.preventDefault();
        alertaCategoria.style.visibility = 'hidden';
        alertaCategoria.innerHTML = '';

        // Validaciones
        if(selectCategorias.selectedIndex > 0 &&
            categoriaEditarTxt.value.length > 3 && 
            categoriaEditarTxt.value.length <= 50) {

            if(radioEstadoInactivo.checked === true && radioEstadoActivo.checked === true || 
                radioEstadoInactivo.checked == false && radioEstadoActivo.checked == false) {
                alertaCategoria.style.visibility = 'visible';
                alertaCategoria.innerHTML = 'Seleccione una opción: Activo o Dar de baja';
            } else
                metodosAJAX.editarCategoria(formulario, selectCategorias, alertaCategoria);
        }
        else if (selectCategorias.selectedIndex == 0) {
            alertaCategoria.style.visibility = 'visible';
            alertaCategoria.innerHTML = 'Seleccione una categoría';
        } else if(categoriaEditarTxt.value.length < 3 || categoriaEditarTxt.value.length >= 50) {
            alertaCategoria.style.visibility = 'visible';
            alertaCategoria.innerHTML = 'Ingrese un nombre para la categoría de 4 a 50 letras';
        }

        categoriaEditarTxt.value =''
    });
}


// -------------------------------------------------------------------------------------------------------
// FORMULARIO DE ALTA: Validaciones y eventos
// -------------------------------------------------------------------------------------------------------
if(formularioAlta !== null) {
    const contenedorMiniFormularioCategoria = document.getElementById('modal');
    const btnAbrirFormularioCategoria = document.getElementById('btnAgregarCategoria');
    const btnRegistrarProducto = document.getElementById("btnRegistrarProducto");
    const campos = document.querySelectorAll('[data-form]');
    const alertaHTML = document.getElementById('alerta-formulario');
    const campoPrecioCompra = document.querySelector('[data-form="precioCompra"]');
    const campoPrecioVenta = document.querySelector('[data-form="precioVenta"]');

    // Control del mini formulario de categorías:
    btnAbrirFormularioCategoria.addEventListener("click", () => {
        metodosModal.desplegarModal(contenedorMiniFormularioCategoria);
        construirFormularioAltaCategoria(contenedorMiniFormularioCategoria);
    });

    

    // Registro de producto
    btnRegistrarProducto.addEventListener("click", (event) => {
        event.preventDefault();
        let listaErrores = []; // Lista que almacenará los errores detectados

        validar(campos, alertaHTML, listaErrores);

        if(listaErrores.length === 0) {// Si no hay errores 
            if(parseInt(campoPrecioVenta.value) < parseInt(campoPrecioCompra.value)) {
                if(confirm("Alerta: El precio de venta es menor al precio de compra. ¿Desea continuar con estos datos?") === true)
                    metodosAJAX.registrarProducto(formularioAlta)
            } else
                metodosAJAX.registrarProducto(formularioAlta)
        } else {
            metodosModal.desplegarModal(contenedorMiniFormularioCategoria);
            metodosModal.construirModalAlerta(contenedorMiniFormularioCategoria, listaErrores);
        }
    });
}

// -------------------------------------------------------------------------------------------------------
// FORMULAIRO DE EDICIÓN: Validaciones y eventos
// -------------------------------------------------------------------------------------------------------
const formularioEdicion = document.getElementById('formulario-edicion-producto');

if(formularioEdicion !== null) {
    // Variables del contenedor:
    const contenedorMiniFormularioCategoria = document.getElementById('modal');
    const btnAbrirFormularioCategoria = document.getElementById('btnAgregarCategoria');
    const btnEditarProducto = document.getElementById("btnEditarProducto");
    const campoPrecioCompra = document.querySelector('[data-form="precioCompra"]');
    const campoPrecioVenta = document.querySelector('[data-form="precioVenta"]');

    // Control del mini formulario de categorías:
    btnAbrirFormularioCategoria.addEventListener("click", () => {
        metodosModal.desplegarModal(contenedorMiniFormularioCategoria);
        construirFormularioAltaCategoria(contenedorMiniFormularioCategoria);
    });

    const campos = document.querySelectorAll('[data-form]');
    const alertaHTML = document.getElementById('alerta-formulario');

    // Registro de producto
    btnEditarProducto.addEventListener("click", () => {
        event.preventDefault();
        let listaErrores = []; // Lista que almacenará los errores detectados
        
        validar(campos, alertaHTML, listaErrores); // Método que retorna los errores encontrados

        if(listaErrores.length === 0) { // Si no hay errores
            if(parseInt(campoPrecioVenta.value) < parseInt(campoPrecioCompra.value)) {
                if(confirm("Alerta: El precio de venta es menor al precio de compra. ¿Desea continuar con estos datos?") === true)
                    metodosAJAX.editarProducto(formularioEdicion);
            } else
                metodosAJAX.editarProducto(formularioEdicion);
        }else {
            metodosModal.desplegarModal(contenedorMiniFormularioCategoria);
            metodosModal.construirModalAlerta(contenedorMiniFormularioCategoria, listaErrores);
        }
    });
}

// -------------------------------------------------------------------------------------------------------
// BARRA DE BÚSQUEDA: Validaciones y eventos
// -------------------------------------------------------------------------------------------------------
const btnBuscarProductos = document.getElementById('btnBuscarProducto');
const formularioBusqueda = document.querySelector('#barra-busqueda');
const campoBuscarProducto = document.getElementById('buscarProducto-txt');
const alertaBuscar = document.getElementById('alertaBuscar');

// -------------------------------------------------------------------------------------------------------
// Recuperación y listado de productos de la BD en tarjetas por páginas
// -------------------------------------------------------------------------------------------------------
if(btnBuscarProductos !== null) {
    btnBuscarProductos.addEventListener('click', () => {
        event.preventDefault();
        alertaBuscar.innerText = ""
        alertaBuscar.style.visibility = 'hidden';
    
        if(campoBuscarProducto.value.length !== 0 && campoBuscarProducto.value.length < 80) {
            formularioBusqueda.submit();
        } else {
            alertaBuscar.innerText = "Debe ingresar una palabra clave"
            alertaBuscar.style.visibility = 'visible';
        }
    });
}