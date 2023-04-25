const contenedorHTML = document.getElementById('subcontenedor');
const formularioBusqueda = document.getElementById('barra-busqueda');
const campoBuscar = document.getElementById('buscarOperacion-txt');
const btnBuscar = document.getElementById('btnBuscarOperacion');

//----------------- AJAX búsqueda y despliegue de una operación ------------------
/**
 * Método que valida el campo de búsqueda
 * @param {*} campo 
 * @returns Mensaje en caso de error o true en caso de validación correcta
 */
const validarCampo = (campo) => {
    let regex = new RegExp('^([0-9])*$');
    if(campo.length === 0 ||
        !regex.test(campo) ||
        campo < 0 ||
        campo.length > 18) {
        
        if(campo.length === 0) return 'Escriba un folio para buscar';
        if(!regex.test(campo)) return 'Sólo acepta números';
        if(campo < 0) return 'No puede ser menor que 0';
        if(campo.length > 18) return 'No debe sobrepasar 18 números';

    } else
        return true;
}

const listarResultado = (contenedorHTML, data) => {
    contenedorHTML.innerHTML = '';
    const tabla = document.createElement('table');
    tabla.classList.add('tabla');
    const contenido =
        `<thead>
            <tr>
                <th>Folio</th>
                <th>Productos<br>incluidos</th>
                <th>Subtotal</th>
                <th>Descuento</th>
                <th>Total</th>
                <th>Notas</th>
                <th>Método<br>de<br>pago</th>
                <th>Fecha<br>y<br>hora</th>
                <th>Empleado</th>
            </tr>
        </thead>
        <tbody>
        <tr>
            <td><a class="texto-rosa" href="index.php?pagina=ventas&opciones=detalles&folio=${data[0]['operacion_id']}">${parseInt(data[0]['operacion_id'])}<br>Detalles</a></td>
            <td>
                <ol class="celda__lista"></ol>
            </td>
            <td>$${data[0]['subtotal']}</td>
            <td id="descuento">$</td>
            <td>$${data[0]['total']}</td>
            <td id="notas"></td>
            <td>${data[0]['metodo']}</td>
            <td id="fecha"></td>
            <td>${data[0]['nombre_completo']}</td>
        </tr>
        </tbody>`;

    tabla.innerHTML = contenido;
    contenedorHTML.appendChild(tabla);

    //Control de contenido
    (data[0]['descuento'] === null)
        ? document.getElementById('descuento').innerText = '$0.00'
        : document.getElementById('descuento').innerText = `$${data[0]['descuento']}`;
    (data[0]['notas'] === null)
        ? document.getElementById('notas').innerText = ''
        : document.getElementById('notas').innerText = `$${data[0]['notas']}`;
    
    let fecha = new Date(data[0]['fecha']);
    let hora = fecha.getHours();
    let minutos = fecha.getMinutes();
    let ampm = hora >= 12 ? 'pm' : 'am';
    hora = hora % 12;
    hora = hora ? hora : 12;
    let dia = fecha.getDate();
    let mes = fecha.getMonth() + 1;
    let anio = fecha.getFullYear();
    let fechaFormateada = `${hora}:${minutos}${ampm} ${dia}/${mes}/${anio.toString().slice(2)}`;
    document.getElementById('fecha').innerText = fechaFormateada;
}

const bucarOperacionAJAX = (formulario, contenedorHTML) => {
    const formData = new FormData(formulario);

    contenedorHTML.innerText = "Buscando...";

    fetch('controlador/ctrlOperaciones.php?funcion=buscar' , {
        method: 'POST',
        body: formData
    }).then(response => response.json())
    .then(data => {
        if(data.length !== 0) {
            if(data === false) 
                contenedorHTML.innerText = data; // El servidor devolvió un error
            else
                listarResultado(contenedorHTML, data); // Devolvió una coincidencia
        } else contenedorHTML.innerText = "No hay coincidencias...";
    }).catch(error => {
        console.log('Error: ', error);
    })
}

btnBuscar.addEventListener('click', (event) => {
    event.preventDefault();
    
    let resultado = validarCampo(campoBuscar.value);

    if(resultado !== true)
        console.log(resultado);
    else {
        bucarOperacionAJAX(formularioBusqueda, contenedorHTML);
    }
});


//----------------- Evento del botón de eliminación del registro que solicida confirmación ------------------
if(document.getElementById('formulario-eliminar-operacion')) {
    const formulario = document.getElementById('formulario-eliminar-operacion');
    const btnEliminar = document.getElementById('btnEliminar');

    btnEliminar.addEventListener('click', (event) => {
        event.preventDefault();
        const confirmacion = confirm("¿Desea eliminar la información de esta operación?");

        (confirmacion === true)
            ? formulario.submit()
            : console.log('Canceló el envío del formulario');
    });
}