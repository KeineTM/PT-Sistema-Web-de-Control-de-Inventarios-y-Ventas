// -------------------------------------------------------------------------------------------------------
// Manejo de los Modales para crear y desplegar el Formulario de alta de producto y de alta de categorías
// -------------------------------------------------------------------------------------------------------
import { metodosModal } from "../modal.js";
import { metodosValidacion } from "../validacion.js";
import { metodosAJAX } from "./ajax-inventario.js";
//import { metodosValidacion } from "../validacion.js";

const btnAbrirAlta = document.getElementById("abrir__alta-inventario");
const contenedor = document.getElementById("subcontenedor");

/** Método que recorre todas las etiquetas input para validar su contenido */
const validar = (campos, etiquetaHTML, lista) => {
    campos.forEach(campo => {
        campo.style.background = 'var(--color-blanco)'; // Reestablece el color del campo
        if(metodosValidacion.validarCampo(campo) !== null){ // Si detecta un error
            campo.style.background = 'var(--color-crema)'; // Resalta el campo
            lista.push("<br>" + metodosValidacion.validarCampo(campo)); // Almacena el mensaje asociado
        }
    });
    etiquetaHTML.style.visibility = 'visible';
    etiquetaHTML.innerHTML = lista;
}

const construirFormularioCategoria = (contenedor) => {
    const formularioAltaCategoria = 
        `<div class="modal__contenedor" id="contenedorFormularioCategoria">
            <span class="formulario__encabezado">
                <img class="formulario__icono" src="vistas/img/file-invoice.svg" alt="Formulario">
                <h2>Formulario de registro de categoría</h2>
                <span class="alerta" id="alerta-formulario"></span>
            </span>
            <form action="post" class="formulario" id="formulario-alta-categoria">
                <h3>Agregar categoría</h3>
                <input class="campo" type="text" placeholder="Categoria" maxlength="100" id="categoria-txt" name="categoria-txt" maxlength="50" required>
                <button class="boton-registrar boton" id="btnRegistrarCategoria">Agregar</button>
                <button class="boton" id="btnCerrarMiniModal"><div class="boton-interior-blanco">Cancelar</div></button>
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
    const selectCategorias = document.getElementById("categoriaProducto-txt");
    
    btnRegistrarCategoria.addEventListener('click', (event) => {
        if(categoriaTxt.value.length > 0 && !categoriaTxt.validity.valueMissing && !categoriaTxt.validity.tooLong) {
            categoriaTxt.setCustomValidity("");
            metodosAJAX.registrarCategoria(categoriaTxt, selectCategorias);
            //metodosModal.cerrarModal(contenedorFormularioCategoria);
        } else if(categoriaTxt.validity.valueMissing) {
            categoriaTxt.setCustomValidity("Debe ingresar una categoría");
        }
    });
}

const construirFormularioAlta = () => {
    // Establecimiento de fechas mínimas y máximas para el formulario:
    let fechaMin = new Date();
    let fechaMax = new Date();
    fechaMax = fechaMax.setFullYear(fechaMin.getFullYear() + 5);
    // Conversión a formato yyyy-mm-dd
    let fechaMinISO = new Date(fechaMin).toISOString().slice(0, 10); 
    let fechaMaxISO = new Date(fechaMax).toISOString().slice(0, 10);

    // Definición del formulario en HTML
    const formularioAltaProducto = 
    `<span class="formulario__encabezado">
        <img class="formulario__icono" src="vistas/img/file-invoice.svg" alt="Formulario">
        <h2>Formulario de alta de producto</h2>
        <span class="alerta" id="alerta-formulario"></span>
    </span>
    
    <form class="formulario" action="post" id="formulario-alta-producto">
        <!-- 1/2 -->
        <fieldset  class="formulario__fieldset">
            <label for="idProducto-txt">Código o Folio:</label>
            <input type="text" class="campo requerido" placeholder="ID del producto" name="idProducto-txt" data-form="productoID" maxlength="20" pattern="^[a-zA-Z0-9]{1,20}$" required>
                    
            <label for="nombreProducto-txt">Nombre del producto:</label>
            <input type="text" class="campo requerido" placeholder="Nombre" name="nombreProducto-txt" data-form='nombreProducto' maxlength="80" minlength="4" required>

            <fieldset class="formulario__fieldset-categorias">
                <select class="campo" id="categoriaProducto-txt" name="categoriaProducto-txt" data-form="categoriaID" required>
                    <option disabled selected>Categorías:</option>
                </select>
                <button class="boton redondo" id="btnAgregarCategoria"><img class="icono" src="vistas/img/plus.svg" alt="Agregar"></button>
            </fieldset>

            <label for="descripcionProducto-txt">Descripción:</label>
            <textarea class="campo" placeholder="Descripción" rows="3" cols="50" name="descripcionProducto-txt" data-form="descripcion" maxlength="400"></textarea>
        
            <fieldset class="formulario__fieldset-2-columnas">
                <label for="unidadesProducto-txt">Unidades:</label>
                <input type="number" class="campo  requerido" placeholder="001" name="unidadesProducto-txt" data-form="unidades" min="1" maxlength="4" required>
                    
                <label for="unidadesMinimasProducto-txt">Unidades mínimas:</label>
                <input type="number" class="campo" placeholder="0" name="unidadesMinimasProducto-txt" data-form="unidadesMinimas" min="0" max="9999">
            </fieldset>
        </fieldset>

        <!-- 2/2 -->
        <fieldset class="formulario__fieldset">
            <fieldset class="formulario__fieldset-2-columnas">
                <label for="precioCompraProducto-txt">Precio de compra:</label>
                <input type="number" step="any" class="campo" placeholder="0.00" name="precioCompraProducto-txt" data-form="precioCompra" min="0" max="9999">

                <label for="precioVentaProducto-txt">Precio de venta:</label>
                <input type="number" step="any" class="campo  requerido" placeholder="0.00" name="precioVentaProducto-txt" data-form="precioVenta" min="0" max="9999" required>

                <label for="precioMayoreoProducto-txt">Precio de venta al mayoreo:</label>
                <input type="number" step="any" class="campo" placeholder="0.00" name="precioMayoreoProducto-txt" data-form="precioMayoreo" min="0" max="9999">

                <label for="fechaCaducidad-txt">Fecha de caducidad</label>
                <input type="date" class="campo" placeholder="Fecha de caducidad" name="caducidadProducto-txt" data-form="caducidad"
                min='${fechaMinISO}'
                max='${fechaMaxISO}'
                maxlength="8">
            </fieldset>

            <label for="imagenProducto-txt">URL de la foto:</label>
            <input type="text" class="campo" placeholder="direccion.jpg" name="imagenProducto-txt" data-form="imagenURL" maxlength="250" pattern="^[^\s]{0,250}\.(jpg|JPG|png|PNG|jpeg|JPEG|webp|WEBP)$">
                
            <div class="formulario__botones-contenedor">
                <button class="boton-form enviar" id="btnRegistrarProducto">Registrar</button>
                <button class="boton-form otro" type="reset">Limpiar</button>
                <button class="boton-form otro" id="btnCerrar">Cancelar</button>
            </div>
        <fieldset>
    </form>`;

    contenedor.innerHTML = ""; // Se vacía el contenedor para evitar duplicaciones
    contenedor.innerHTML = formularioAltaProducto; // Se llena el contenedor

    // Variables del contenedor:
    const contenedorMiniFormularioCategoria = document.getElementById('modal__mini-formulario');
    const formularioAlta = document.getElementById('formulario-alta-producto');
    const selectCategorias = document.getElementById("categoriaProducto-txt");
    const btnAbrirFormularioCategoria = document.getElementById('btnAgregarCategoria');
    const btnCerrar = document.getElementById("btnCerrar");
    const btnRegistrarProducto = document.getElementById("btnRegistrarProducto");

    // Carga de la lista de categorías:
    metodosAJAX.recuperarCategorias(selectCategorias);

    // Control de cierre del formulario
    btnCerrar.addEventListener("click", () => {
        contenedor.innerHTML = "";
    });

    // Control del mini formulario de categorías:
    btnAbrirFormularioCategoria.addEventListener("click", () => {
        metodosModal.desplegarModal(contenedorMiniFormularioCategoria);
        construirFormularioCategoria(contenedorMiniFormularioCategoria);
    });

    const campos = document.querySelectorAll('[data-form]');
    const alertaHTML = document.getElementById('alerta-formulario');

    // Registro de producto
    btnRegistrarProducto.addEventListener("click", (event) => {
        event.preventDefault();
        let listaErrores = []; // Lista que almacenará los errores detectados

        validar(campos, alertaHTML, listaErrores);

        (listaErrores.length === 0) // Si no hay errores
            ? metodosAJAX.registrarProducto(formularioAlta) //console.log('Los datos son válidos')
            : console.log('Los datos no son válidos');
    });
}

btnAbrirAlta.addEventListener("click", construirFormularioAlta);


// -------------------------------------------------------------------------------------------------------
// Formulario de edición de producto que se despliega desde una tj de producto precargando los datos almacenados en BD
// -------------------------------------------------------------------------------------------------------
/** Método de contrucción del formulario de edición que incluye la recarga de la información recuperada de BD */
const construirFormularioEdicion = (producto_id, nombre, categoria_id, descripcion, unidades, 
    unidades_minimas, precio_compra, precio_venta, precio_mayoreo, foto_url, caducidad, estado) => {
    // Establecimiento de fechas mínimas y máximas para el formulario:
    let fechaMin = new Date();
    let fechaMax = new Date();
    fechaMax = fechaMax.setFullYear(fechaMin.getFullYear() + 5);
    // Conversión a formato yyyy-mm-dd
    let fechaMinISO = new Date(fechaMin).toISOString().slice(0, 10); 
    let fechaMaxISO = new Date(fechaMax).toISOString().slice(0, 10);

    // Definición del formulario en HTML
    const formularioEdicionProducto = 
    `<span class="formulario__encabezado">
        <img class="formulario__icono" src="vistas/img/file-invoice.svg" alt="Formulario">
        <h2>Formulario de edición para el producto: ${producto_id}</h2>
        <span class="alerta" id="alerta-formulario"></span>
    </span>
    
    <form class="formulario" action="post" id="formulario-edicion-producto">
        <!-- 1/2 -->
        <fieldset  class="formulario__fieldset">
            <label for="idProducto-txt">Código o Folio:</label>
            <input type="text" class="campo requerido" placeholder="ID del producto" name="idProducto-txt" data-form="productoID" maxlength="20" pattern="^[a-zA-Z0-9]{1,20}$" required value="${producto_id}">
                    
            <label for="nombreProducto-txt">Nombre del producto:</label>
            <input type="text" class="campo requerido" placeholder="Nombre" name="nombreProducto-txt" data-form='nombreProducto' maxlength="80" minlength="4" required value="${nombre}">

            <fieldset class="formulario__fieldset-categorias">
                <select class="campo" id="categoriaProducto-txt" name="categoriaProducto-txt" data-form="categoriaID" required></select>
                <button class="boton redondo" id="btnAgregarCategoria"><img class="icono" src="vistas/img/plus.svg" alt="Agregar"></button>
            </fieldset>

            <label for="descripcionProducto-txt">Descripción:</label>
            <textarea class="campo" placeholder="Descripción" rows="3" cols="50" name="descripcionProducto-txt" data-form="descripcion" maxlength="400">${descripcion}</textarea>
        
            <fieldset class="formulario__fieldset-2-columnas">
                <label for="unidadesProducto-txt">Unidades:</label>
                <input type="number" class="campo  requerido" placeholder="001" name="unidadesProducto-txt" data-form="unidades" min="1" maxlength="4" required value="${unidades}">
                    
                <label for="unidadesMinimasProducto-txt">Unidades mínimas:</label>
                <input type="number" class="campo" placeholder="0" name="unidadesMinimasProducto-txt" data-form="unidadesMinimas" min="0" max="9999" value="${unidades_minimas}">
            </fieldset>
        </fieldset>

        <!-- 2/2 -->
        <fieldset class="formulario__fieldset">
            <fieldset class="formulario__fieldset-2-columnas">
                <label for="precioCompraProducto-txt">Precio de compra:</label>
                <input type="number" step="any" class="campo" placeholder="0.00" name="precioCompraProducto-txt" data-form="precioCompra" min="0" max="9999" value="${precio_compra}">

                <label for="precioVentaProducto-txt">Precio de venta:</label>
                <input type="number" step="any" class="campo  requerido" placeholder="0.00" name="precioVentaProducto-txt" data-form="precioVenta" min="0" max="9999" required value="${precio_venta}">

                <label for="precioMayoreoProducto-txt">Precio de venta al mayoreo:</label>
                <input type="number" step="any" class="campo" placeholder="0.00" name="precioMayoreoProducto-txt" data-form="precioMayoreo" min="0" max="9999" value="${precio_mayoreo}">

                <label for="fechaCaducidad-txt">Fecha de caducidad</label>
                <input type="date" class="campo" placeholder="Fecha de caducidad" name="caducidadProducto-txt" data-form="caducidad"
                min='${fechaMinISO}'
                max='${fechaMaxISO}'
                maxlength="8">
            </fieldset>

            <label for="imagenProducto-txt">URL de la foto:</label>
            <input type="text" class="campo" placeholder="direccion.jpg" name="imagenProducto-txt" data-form="imagenURL" maxlength="250" value="${foto_url}">
            
            <label>Estado del producto:</label>
            <fieldset class="formulario__fieldset-2-columnas">
                <label for="estadoProducto-txt">Activo</label>
                <input type="radio" id='estado-activo' name="estadoProducto-txt" value="1" data-form="estado" required>
                <label for="estadoProducto-txt">Dar de baja</label>
                <input type="radio" id='estado-inactivo' name="estadoProducto-txt" value="0" data-form="estado" required>
            </fieldset>

            <div class="formulario__botones-contenedor">
                <button class="boton-form enviar" id="btnEditarProducto">Editar</button>
                <button class="boton-form otro" id="btnCerrar">Cancelar</button>
            </div>
        <fieldset>
    </form>`;

    contenedor.innerHTML = ""; // Se vacía el contenedor para evitar duplicaciones
    contenedor.innerHTML = formularioEdicionProducto; // Se llena el contenedor

    // Control de fecha de caducidad
    if (caducidad !== '0000-00-00')
        contenedor.querySelector('[data-form=caducidad]').value = caducidad;
    // Control estado del producto
    (estado === 1)
        ? document.getElementById('estado-activo').checked = true
        : document.getElementById('estado-inactivo').checked = true;

    // Variables del contenedor:
    const contenedorMiniFormularioCategoria = document.getElementById('modal__mini-formulario');
    const formularioEdicion = document.getElementById('formulario-edicion-producto');
    const selectCategorias = document.getElementById("categoriaProducto-txt");
    const btnAbrirFormularioCategoria = document.getElementById('btnAgregarCategoria');
    const btnCerrar = document.getElementById("btnCerrar");
    const btnEditarProducto = document.getElementById("btnEditarProducto");

    // Carga de la lista de categorías:
    metodosAJAX.recuperarCategorias(selectCategorias, categoria_id);

    // Control de cierre del formulario
    btnCerrar.addEventListener("click", () => {
        contenedor.innerHTML = "";
    });

    // Control del mini formulario de categorías:
    btnAbrirFormularioCategoria.addEventListener("click", () => {
        metodosModal.desplegarModal(contenedorMiniFormularioCategoria);
        construirFormularioCategoria(contenedorMiniFormularioCategoria);
    });

    const campos = document.querySelectorAll('[data-form]');
    const alertaHTML = document.getElementById('alerta-formulario');

    // Registro de producto
    btnEditarProducto.addEventListener("click", () => {
        event.preventDefault();
        console.log('click ' + nombre)
        let listaErrores = []; // Lista que almacenará los errores detectados

        validar(campos, alertaHTML, listaErrores);

        (listaErrores.length === 0) // Si no hay errores
            ? metodosAJAX.editarProducto(formularioEdicion)
            : console.log('Los datos no son válidos');
    });
}

// -------------------------------------------------------------------------------------------------------
// Recuperación por medio de AJAX y listado de productos de la BD en tarjetas // PENDIENTE LA PAGINACIÓN
// -------------------------------------------------------------------------------------------------------
/** Método que recibe un contenedor HTML y una lista JSON, construye una lista de productos y los incluye en el contenedor */
const crearListaProductos = (contenedor, listaProductosJSON) => {
    listaProductosJSON.forEach(producto => {
        const tarjeta = document.createElement('span');
        tarjeta.classList.add('tarjeta-producto');

        const contenido = 
            `<img src="${producto['foto_url']}" alt="Imagen ${producto['nombre']}">
            <span>
                <h3>${producto['nombre']}</h3>
                <ul>
                    <li>${producto['producto_id']}</li>
                    <li>Categoría: ${producto['categoria']}</li>
                    <li>Precio de venta: $${producto['precio_venta']}</li>
                    <li><a data-edit>Ver detalles y editar</a></li>
                </ul>
            </span>`;

        tarjeta.innerHTML = contenido; // Agrega el contenido de la tj
        
        // Recupera el data del botón de la tarjeta para crearle un evento personalizado con el id del producto
        const btnAbirEdicion = tarjeta.querySelector('[data-edit]');
        btnAbirEdicion.addEventListener('click', () => {
            // Abre un formulario de edición específico para ese id de producto:
            construirFormularioEdicion(
                producto['producto_id'],
                producto['nombre'],
                producto['categoria_id'],
                producto['descripcion'],
                producto['unidades'],
                producto['unidades_minimas'],
                producto['precio_compra'],
                producto['precio_venta'],
                producto['precio_mayoreo'],
                producto['foto_url'],
                producto['caducidad'],
                producto['estado'],
                );
        });

        contenedor.appendChild(tarjeta);
    });
}

/** Método que recupera con AJAX los registros de la tabla de productos */
const recuperarProductos = (contenedorHTML) => {
    fetch('controlador/ctrlInventario.php?funcion=tabla-productos')
    .then(response => response.json())
    .then(data => {
        crearListaProductos(contenedorHTML, data);
    }).catch(error => {
        console.error('Error:', error);
    });
}

const btnListarProductos = document.getElementById("abrir__tabla-productos");

btnListarProductos.addEventListener('click', () => {
    contenedor.innerHTML = ""; // Limpia el contenedor antes de crear la tabla
    let contenedorProductos = document.createElement('div');
    contenedorProductos.classList.add('contenedor-productos');
    contenedor.appendChild(contenedorProductos);
    recuperarProductos(contenedorProductos); // AJAX
});

