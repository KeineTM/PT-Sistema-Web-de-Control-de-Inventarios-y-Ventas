// Variables del minimodal de alta de categoría
const nuevaCategoria = document.getElementById("categoria-txt");
const btnRegistrarCategoria = document.getElementById("btnRegistrarCategoria");

/*******************************************************
 * API FETCH para el registro de categorías asíncronas
 ******************************************************/
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