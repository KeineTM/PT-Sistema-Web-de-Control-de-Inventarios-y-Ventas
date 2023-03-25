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

/*******************************************************
 * API FETCH para la recuperación y listado de categorías asíncronas
 ******************************************************/
const recuperarCategoriasAsincrono = (selectCategorias) => {
    fetch('vistas/paginas/inventario-listar-categorias.php')
    .then(response => response.json()) // Aquí se recibe un JSON
    .then(data => {
        cargarOptions(selectCategorias, data);
    }).catch(error => {
        console.error('Error:', error);
    });
};

recuperarCategoriasAsincrono(selectCategorias);