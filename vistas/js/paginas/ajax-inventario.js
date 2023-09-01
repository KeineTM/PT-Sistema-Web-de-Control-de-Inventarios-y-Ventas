import { metodosModal } from "../modal.js";


/** Método que recibe un JSON con el listado de la tabla categorías y la etiqueta SELECT */
const cargarOptionsCategorias = (selectHTML, listaCategoriasJSON) => {
    selectHTML.innerHTML = "<option disabled selected>Categorías...</option>";

    for (const registro of listaCategoriasJSON) { // Recorre el array para entrar a cada registro (objeto tipo json)
        const nuevaOpcion = document.createElement('option');  // Crea una nueva option
        nuevaOpcion.classList.add('mayusculas');
        nuevaOpcion.setAttribute("estado", registro['estado']); // Incluye a un atributo el estado de la categoría (0|1)
        nuevaOpcion.value = registro['categoria_id']; // Asigna como value la llave primaria del registro
        nuevaOpcion.textContent = registro['categoria']; // Nombre de la categoria
        selectHTML.appendChild(nuevaOpcion); // Agrega la opción en la etiqueta select
    }
};

/** API FETCH para la recuperación y listado de categorías asíncronas */
const recuperarCategorias = (selectCategorias) => {
    fetch('controlador/ctrlInventario.php?funcion=listar-categorias') // Esta página de PHP se ejecuta y el resultado o respuesta (un echo) es el que regresa a este método
    .then(response => response.json()) // Aquí se recibe un JSON
    .then(data => {
        cargarOptionsCategorias(selectCategorias, data); // Carga la etiqueta select con el json recuperado
    }).catch(error => {
        console.error('Error:', error); // Devuelve el error si ocurrió uno
    });
};

/** API FETCH para la recuperación y listado de categorías asíncronas */
const recuperarCategoriasActivas = (selectCategorias, preseleccion="") => {
    fetch('controlador/ctrlInventario.php?funcion=listar-categorias-activas') 
    .then(response => response.json())
    .then(data => {
        cargarOptionsCategorias(selectCategorias, data);
        // Control de la categoría si se está recuperando un registro de la DB para el formulario de edición
        if(preseleccion !== "") selectCategorias.value = preseleccion;
    }).catch(error => {
        console.error('Error:', error);
    });
};


/** API FETCH para el registro de categorías asíncronas */
const registrarCategoria = (categoria, selectCategorias, aletaHTML) => {
    // Almacenamiento de los campos del formulario en un FormData
    const formData = new FormData();
    formData.append('categoria-txt', categoria.value);

    // Uso de Fetch para el paso del formulario a la página PHP por POST
    fetch('controlador/ctrlInventario.php?funcion=registrar-categoria', {
        method: 'POST',
        body: formData
    }).then(response => response.text() // Recuperación de la respuesta del servidor en texto plano
    ).then(data => {
        aletaHTML.style.visibility = 'visible';
        aletaHTML.innerText = data; // Impresión en pantalla de la respuesta del registro
        categoria.value = ''; // Limpia el campo
        if (selectCategorias != undefined)
        recuperarCategoriasActivas(selectCategorias); // RECARGA LA LISTA DE OPCIONES CON OTRO AJAX del archivo inventario-listar-categorias-asincrono.js

    }).catch(error => {
        console.error('Error:', error);
    });
}

/** API FETCH para el registro de categorías asíncronas */
const editarCategoria = (formulario, selectCategorias, alertaHTML) => {
    const formData = new FormData(formulario);

    fetch('controlador/ctrlInventario.php?funcion=editar-categoria', {
        method: 'POST',
        body: formData
    }).then(response => response.text()
    ).then(data => {
        alertaHTML.style.visibility = 'visible';
        alertaHTML.innerText = data;
        if (selectCategorias != undefined) // Mientras exista el select donde se cargan las categorías
        recuperarCategorias(selectCategorias);

        metodosModal.desplegarModal(document.getElementById('modal'));
        metodosModal.construirModalMensajeResultado(document.getElementById('modal'), data);
    }).catch(error => {
        console.error('Error:', error);
    });
}

/** API FETCH para el registro de productos */
const registrarProducto = (formulario) => {
    const formData = new FormData(formulario);

    fetch('controlador/ctrlInventario.php?funcion=registrar-producto', {
        method: 'POST',
        body: formData
    }).then(response => response.text()
    ).then(data => {
        const alertaHTML = document.getElementById('alerta-formulario');
        alertaHTML.style.visibility = 'visible';
        alertaHTML.innerHTML = data;
        
        metodosModal.desplegarModal(document.getElementById('modal'));
        metodosModal.construirModalMensajeResultado(document.getElementById('modal'), data);

        if(data === 'Registro exitoso.') {
            document.getElementById('formulario-alta-producto').reset();
        }
    }).catch(error => {
        console.error('Error:', error);
    });
}

/** API FETCH para el registro de productos */
const editarProducto = (formulario) => {
    const formData = new FormData(formulario);

    fetch('controlador/ctrlInventario.php?funcion=editar-producto', {
        method: 'POST',
        body: formData
    }).then(response => response.text()
    ).then(data => {
        const alertaHTML = document.getElementById('alerta-formulario');
        alertaHTML.style.visibility = 'visible';
        alertaHTML.innerText = data;

        metodosModal.desplegarModal(document.getElementById('modal'));
        metodosModal.construirModalMensajeResultado(document.getElementById('modal'), data);

    }).catch(error => {
        console.error('Error:', error);
    });
}


export const metodosAJAX = {
    recuperarCategorias,
    recuperarCategoriasActivas,
    cargarOptionsCategorias,
    registrarCategoria,
    editarCategoria,
    registrarProducto,
    editarProducto
}