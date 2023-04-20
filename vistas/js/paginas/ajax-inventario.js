/** Método que recibe un JSON con el listado de la tabla categorías y la etiqueta SELECT */
const cargarOptionsCategorias = (selectHTML, listaCategoriasJSON) => {
    selectHTML.innerHTML = "<option disabled selected>Categorías...</option>";

    for (const registro of listaCategoriasJSON) { // Recorre el array para entrar a cada registro (objeto tipo json)
        const nuevaOpcion = document.createElement('option');  // Crea una nueva option
        nuevaOpcion.value = registro['categoria_id']; // Asigna como value la llave primaria del registro
        nuevaOpcion.textContent = registro['categoria']; // Nombre de la categoria
        selectHTML.appendChild(nuevaOpcion); // Agrega la opción en la etiqueta select
    }
};

/** API FETCH para la recuperación y listado de categorías asíncronas */
const recuperarCategorias = (selectCategorias, preseleccion="") => {
    fetch('controlador/ctrlInventario.php?funcion=listar-categorias') // Esta página de PHP se ejecuta y el resultado o respuesta (un echo) es el que regresa a este método
    .then(response => response.json()) // Aquí se recibe un JSON
    .then(data => {
        cargarOptionsCategorias(selectCategorias, data); // Carga la etiqueta select con el json recuperado
        // Control de la categoría si se está recuperando un registro de la DB
        if(preseleccion !== "") selectCategorias.value = preseleccion;
    }).catch(error => {
        console.error('Error:', error); // Devuelve el error si ocurrió uno
    });
};

/** API FETCH para el registro de categorías asíncronas */
const registrarCategoria = (categoria, selectCategorias) => {
    // Almacenamiento de los campos del formulario en un FormData
    const formData = new FormData();
    formData.append('categoria-txt', categoria.value);

    // Uso de Fetch para el paso del formulario a la página PHP por POST
    fetch('controlador/ctrlInventario.php?funcion=registrar-categoria', {
        method: 'POST',
        body: formData
    }).then(response => response.text() // Recuperación de la respuesta del servidor en texto plano
    ).then(data => {
        alert(data); // Impresión en pantalla de la respuesta del registro
        categoria.value = ''; // Limpia el campo
        recuperarCategorias(selectCategorias); // RECARGA LA LISTA DE OPCIONES CON OTRO AJAX del archivo inventario-listar-categorias-asincrono.js
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
        alertaHTML.innerText = data;
        console.log(data);
        if(data === 'Registro exitoso.') {
            document.getElementById('formulario-alta-producto').reset();
            alert(data);
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
    }).catch(error => {
        console.error('Error:', error);
    });
}

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



export const metodosAJAX = {
    recuperarCategorias,
    cargarOptionsCategorias,
    registrarCategoria,
    registrarProducto,
    editarProducto
}