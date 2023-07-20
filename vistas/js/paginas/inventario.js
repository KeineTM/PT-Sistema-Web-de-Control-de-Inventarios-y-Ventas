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

/** Método que recorre todas las etiquetas input para validar su contenido */
const validar = (campos, etiquetaHTML, lista) => {
    campos.forEach(campo => {
        campo.style.background = 'var(--color-blanco)'; // Reestablece el color del campo
        if(metodosValidacion.validarCampoProductos(campo) !== null){ // Si detecta un error
            campo.style.background = 'var(--color-crema)'; // Resalta el campo
            lista.push("<br>" + metodosValidacion.validarCampoProductos(campo)); // Almacena el mensaje asociado
        }
    });
    etiquetaHTML.style.visibility = 'visible';
    etiquetaHTML.innerHTML = lista;
}

// FORMULARIO DE ALTA: Validaciones y eventos
if(formularioAlta !== null) {
    const contenedorMiniFormularioCategoria = document.getElementById('modal__mini-formulario');
    const btnAbrirFormularioCategoria = document.getElementById('btnAgregarCategoria');
    const btnCerrar = document.getElementById("btnCerrar");
    const btnRegistrarProducto = document.getElementById("btnRegistrarProducto");

    // Control de cierre del formulario
    btnCerrar.addEventListener("click", () => {
        contenedor.innerHTML = "";
    });

    // Control del mini formulario de categorías:
    btnAbrirFormularioCategoria.addEventListener("click", () => {
        metodosModal.desplegarModal(contenedorMiniFormularioCategoria);
        construirFormularioAltaCategoria(contenedorMiniFormularioCategoria);
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
// FORMULAIRO DE EDICIÓN: Validaciones y eventos
// -------------------------------------------------------------------------------------------------------
const formularioEdicion = document.getElementById('formulario-edicion-producto');

if(formularioEdicion !== null) {
    // Variables del contenedor:
    const contenedorMiniFormularioCategoria = document.getElementById('modal__mini-formulario');
    const btnAbrirFormularioCategoria = document.getElementById('btnAgregarCategoria');
    const btnEditarProducto = document.getElementById("btnEditarProducto");

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

        (listaErrores.length === 0) // Si no hay errores
            ? metodosAJAX.editarProducto(formularioEdicion)
            : console.log('Los datos no son válidos');
    });
}

// -------------------------------------------------------------------------------------------------------
// BARRA DE BÚSQUEDA: Validaciones y eventos
// -------------------------------------------------------------------------------------------------------
const btnBuscarProductos = document.getElementById('btnBuscarProducto');
const campoBuscarProducto = document.getElementById('buscarProducto-txt');
const alertaBuscar = document.getElementById('alertaBuscar');

// -------------------------------------------------------------------------------------------------------
// Recuperación por medio de AJAX y listado de productos de la BD en tarjetas por páginas
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
                    <li>Código: ${producto['producto_id']}</li>
                    <li>Categoría: ${producto['categoria']}</li>
                    <li id="unidades_producto"></li>
                    <li>Precio de venta: $${producto['precio_venta']}</li>
                    <li><a href="index.php?pagina=inventario&opciones=detalles&id=${producto['producto_id']}">Ver detalles y editar</a></li>
                </ul>
            </span>`;

        tarjeta.innerHTML = contenido; // Agrega el contenido de la tj

        // Lógica para incluir las unidades
        tarjeta.querySelector("#unidades_producto").innerHTML = (producto['unidades'] < 1)
            ? `Unidades: Agotado`
            : `Unidades: ${producto['unidades']}`;
    
        contenedor.appendChild(tarjeta);
    });
}

/** Método que recupera con AJAX los registros de los productos en BD */
const recuperarProductos = (contenedorHTML, palabraClave='') => {
    contenedorHTML.innerText = 'Buscando...';

    if(palabraClave !== '') {
        const formData = new FormData();
        formData.append('buscarProducto-txt', palabraClave);

        fetch('controlador/ctrlInventario.php?funcion=listar-productos', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            contenedorHTML.innerText = '';
            if(data == '')
                contenedorHTML.innerText = `No hay coincidencias para '${palabraClave}'`;
            else 
                crearListaProductos(contenedorHTML, data);
        }).catch(error => {
            console.error('Error:', error);
        });
    } else {
        fetch('controlador/ctrlInventario.php?funcion=listar-productos')
        .then(response => response.json())
        .then(data => {
            contenedorHTML.innerText = '';
            crearListaProductos(contenedorHTML, data);
        }).catch(error => {
            console.error('Error:', error);
        });
    }
}

btnBuscarProductos.addEventListener('click', () => {
    event.preventDefault();
    alertaBuscar.innerText = ""
    alertaBuscar.style.visibility = 'hidden';

    if(campoBuscarProducto.value.length !== 0 && campoBuscarProducto.value.length < 80) {
        contenedor.innerHTML = ""; // Limpia el contenedor antes de crear la tabla
        let contenedorProductos = document.createElement('section');
        contenedorProductos.classList.add('contenedor-productos');
        contenedor.appendChild(contenedorProductos);
        recuperarProductos(contenedorProductos, campoBuscarProducto.value); // AJAX
    } else {
        alertaBuscar.innerText = "Debe ingresar una palabra clave"
        alertaBuscar.style.visibility = 'visible';
    }
    
});