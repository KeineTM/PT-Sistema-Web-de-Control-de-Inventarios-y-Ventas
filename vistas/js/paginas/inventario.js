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
const selectCategorias = document.getElementById("categoriaProducto-txt");

/** Método que recibe un JSON con el listado de la tabla categorías y el DOM de la etiqueta SELECT */
const cargarOptions = (selectCategorias, listaCategoriasJSON) => {
    selectCategorias.innerHTML = "<option disabled selected>Categorías...</option>";

    for (const registro of listaCategoriasJSON) { // Recorre el array para entrar a cada registro (objeto tipo json)
        const nuevaOpcion = document.createElement('option');  // Crea una nueva option
        nuevaOpcion.value = registro['categoria_id']; // Asigna como value la llave primaria del registro
        nuevaOpcion.textContent = registro['categoria']; // Nombre de la categoria
        selectCategorias.appendChild(nuevaOpcion); // Agrega la opción en la etiqueta select
    }
};

/** API FETCH para la recuperación y listado de categorías asíncronas */
const recuperarCategoriasAsincrono = (selectCategorias) => {
    fetch('controlador/ctrlInventario.php?funcion=listar-categorias') // Esta página de PHP se ejecuta y el resultado o respuesta (un echo) es el que regresa a este método
    .then(response => response.json()) // Aquí se recibe un JSON
    .then(data => {
        cargarOptions(selectCategorias, data); // Carga la etiqueta select con el json recuperado
    }).catch(error => {
        console.error('Error:', error); // Devuelve el error si ocurrió uno
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
    fetch('controlador/ctrlInventario.php?funcion=registrar-categoria', {
        method: 'POST',
        body: formData
    }).then(response => response.text() // Recuperación de la respuesta del servidor en texto plano
    ).then(data => {
        alert(data); // Impresión en pantalla de la respuesta del registro
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



// -------------------------------------------------------------------------------------------------------
// Registro de producto en base de datos mediante el API fetch de JS
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

/** API FETCH para el registro de productos */
const registrarProductoAsincrono = () => {
    const formData = new FormData(formularioAltaProducto);

    fetch('controlador/ctrlInventario.php?funcion=registrar-producto', {
        method: 'POST',
        body: formData
    }).then(response => response.text()
    ).then(data => {
        alert(data);
    }).catch(error => {
        console.error('Error:', error);
    });
}

btnRegistrarProducto.addEventListener('click', (event) => {
    event.preventDefault();
    let listaCamposObligatorios = [productoID, nombre, unidades, precioVenta];
    if(metodosValidacion.validarLlenadoFormulario(listaCamposObligatorios)) {
        registrarProductoAsincrono();
    }
});


// -------------------------------------------------------------------------------------------------------
// Tabla de productos
// -------------------------------------------------------------------------------------------------------
const btnAbrirTablaProductos = document.getElementById("abrir__tabla-productos");
const contenedorTabla = document.getElementById("tabla-contenedor");

/** Método que construye y llena la tabla de productos */
const crearTablaProductos = (contenedor, listaProductosJSON) => {
    const tabla = document.createElement('table'); // Tabla
    tabla.classList.add('tabla');

    const thead =
        `<thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Categoría</th>
                <th>Descripción</th>
                <th>Existencia</th>
                <th>Unidades Mín</th>
                <th>P Compra</th>
                <th>P Venta</th>
                <th>P Mayoreo</th>
                <th>Estado</th>
                <th>Foto</th>
                <th>Caducidad</th>
            </tr>
        </thead>`;
    const tbody = document.createElement('tbody');
    tabla.innerHTML= thead; // Agrega código HTML al objeto HTML
    tabla.appendChild(tbody); // Agrega un objeto HTML a otro objeto HTML

    // Recorrido del archivo JSON para crear filas por cada registro y celdas con los valores
    listaProductosJSON.forEach(producto => {
        const fila = document.createElement('tr'); // Crea fila

        // Celdas
        const celdaID = document.createElement('td');
        celdaID.innerText = producto['producto_id'];
        fila.appendChild(celdaID);

        const celdaNombre = document.createElement('td');
        celdaNombre.innerText = producto['nombre'];
        fila.appendChild(celdaNombre);

        const celdaCategoria = document.createElement('td');
        celdaCategoria.innerText = producto['categoria'];
        fila.appendChild(celdaCategoria);

        const celdaDescripcion = document.createElement('td');
        celdaDescripcion.innerText = producto['descripcion'];
        fila.appendChild(celdaDescripcion);

        const celdaUnidades = document.createElement('td');
        celdaUnidades.innerText = producto['unidades'];
        fila.appendChild(celdaUnidades);

        const celdaUnidadesMin = document.createElement('td');
        celdaUnidadesMin.innerText = producto['unidades_minimas'];
        fila.appendChild(celdaUnidadesMin);

        const celdaPrecioCompra = document.createElement('td');
        celdaPrecioCompra.innerText = producto['precio_compra'];
        fila.appendChild(celdaPrecioCompra);

        const celdaPrecioVenta = document.createElement('td');
        celdaPrecioVenta.innerText = `$${producto['precio_venta']}`;
        fila.appendChild(celdaPrecioVenta);

        const celdaPrecioMayoreo = document.createElement('td');
        celdaPrecioMayoreo.innerText = producto['precio_mayoreo'];
        fila.appendChild(celdaPrecioMayoreo);

        const celdaEstado = document.createElement('td');
        celdaEstado.innerText = (producto['estado'])
                                ? 'Activo'
                                : 'Baja';
        fila.appendChild(celdaEstado);

        const celdaFotoURL = document.createElement('td');
        celdaFotoURL.innerHTML = `<img src="${producto['foto_url']}">`;
        fila.appendChild(celdaFotoURL);

        const celdaCaducidad = document.createElement('td');
        celdaCaducidad.innerText = producto['caducidad'];
        fila.appendChild(celdaCaducidad);
        
        tbody.appendChild(fila); // Agrega fila al cuerpo de la tabla
    });

    contenedor.appendChild(tabla);
}

const recuperarProductosAsincrono = () => {
    fetch('controlador/ctrlInventario.php?funcion=tabla-productos')
    .then(response => response.json())
    .then(data => {
        crearTablaProductos(contenedorTabla, data); // Construye la tabla
    }).catch(error => {
        console.error('Error:', error);
    });
}

btnAbrirTablaProductos.addEventListener('click', () => {
    contenedorTabla.innerHTML = ""; // Limpia el contenedor antes de crear la tabla
    recuperarProductosAsincrono();
});
