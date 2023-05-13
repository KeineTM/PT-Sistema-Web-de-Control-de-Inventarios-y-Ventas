const contenedorHTML = document.querySelector('#subcontenedor');
const formularioBusqueda = document.querySelector('#barra-busqueda');
const campoBuscar = document.querySelector('#buscarOperacion-txt');
const btnBuscar = document.querySelector('#btnBuscarOperacion');
const alertaHTML = document.querySelector('#alertaBuscar');

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
        if(!regex.test(campo)) return 'Sólo se aceptan números';
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
    let lista_productos = [];
    data.forEach(producto => {
        lista_productos.push(`<li>${producto['unidades']} x ${producto['nombre']}</li>`);
    })
    document.querySelector('.celda__lista').innerHTML = lista_productos.join('');

    let fecha = new Date(data[0]['fecha']);
    let hora = fecha.getHours();
    let minutos = fecha.getMinutes();
    let ampm = hora >= 12 ? 'pm' : 'am';
    hora = hora % 12;
    hora = hora ? hora : 12;
    let dia = fecha.getDate();
    let mes = fecha.getMonth() + 1;
    let anio = fecha.getFullYear();
    let fechaFormateada = `${hora}:${minutos}${ampm} ${dia.toString().padStart(2, '0')}/${mes.toString().padStart(2, '0')}/${anio.toString().slice(2)}`;
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
    alertaHTML.innerText = '';
    
    let validacionResultado = validarCampo(campoBuscar.value);

    if(validacionResultado !== true) {
        alertaHTML.style.visibility = 'visible';
        alertaHTML.innerText = validacionResultado;
    } else {
        bucarOperacionAJAX(formularioBusqueda, contenedorHTML);
    }
});


//----------------- Evento del botón de eliminación del registro que solicida confirmación ------------------
if(document.querySelector('#formulario-eliminar-operacion')) {
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

//----------------- ------------------------ --------------------
//----------------- JS del módulo de Operaciones ------------------
const campoDescuento = document.querySelector('[data-form=descuento]');
const campoTotal = document.querySelector('[data-form=total]');
const campoDescuentoAplicado = document.querySelector('[name=descuento-aplicado]');
const lblTotal = document.querySelector('#lbl-total');
const lblDescuentoAplicado = document.querySelector('#lbl-descuento-aplicado');

// Evalúa que exista el campo de descuento en el módulo: Ventas y Apartados
// Para calcular e imprimir en pantalla el total con el descuento aplicado
if(campoDescuento !== null) {
    let resultadoResta;
    campoDescuento.addEventListener('keyup', () => {
        resultadoResta = campoTotal.value - campoDescuento.value;
        if(campoTotal.value <= 0) {
            lblTotal.style.textDecoration = 'none';
            campoDescuentoAplicado.value = '';
            campoDescuentoAplicado.placeholder = 'No hay productos';
        } else if(campoDescuento.value.length < 1) {
            lblTotal.style.textDecoration = 'none';
            lblDescuentoAplicado.innerText = '';
            campoDescuentoAplicado.placeholder = '';
            campoDescuentoAplicado.value = '';
        } else if(resultadoResta < 0 || campoDescuento.value < 0 ||campoDescuento.value > campoTotal.value/2) {
            lblTotal.style.textDecoration = 'none';
            campoDescuentoAplicado.value = '';
            campoDescuentoAplicado.placeholder = 'Descuento no válido';
        } else {
            lblTotal.style.textDecoration = 'line-through';
            lblDescuentoAplicado.innerText = 'Con Descuento:';
            campoDescuentoAplicado.value = resultadoResta.toFixed(2);
        }
    });
}

//----------------- ------------------------ --------------------
//----------------- JS del módulo de APARTADOS ------------------
const campoMontoAbonado = document.querySelector('[data-form=abono]');
const campoTotalRestante = document.querySelector('[data-form=restante]');
const porcentajeDeAbonoSugerido = 0.3; // Regla del negocio para realizar apartados: 30%

// Evalúa que exista un campo de abonos: Apartados
// Para calcular e imprimir en pantalla el total restante después del abono
if(campoMontoAbonado !== null) {
    let minimoDeAbono = campoTotal.value * porcentajeDeAbonoSugerido;
    let resultadoResta;

    campoMontoAbonado.placeholder = 'Sugerido = ' + Math.ceil(minimoDeAbono);

    campoMontoAbonado.addEventListener('keyup', () => {
        resultadoResta = campoTotal.value - campoMontoAbonado.value;
        if(campoTotal.value <= 0) {
            campoTotalRestante.value = '';
            campoTotalRestante.placeholder = 'No hay productos';
        } else if(resultadoResta < 0) {
            campoTotalRestante.value = '';
            campoTotalRestante.placeholder = 'Error: Abono muy alto';
        } else if(campoMontoAbonado.value < 0) {
            campoTotalRestante.value = '';
            campoTotalRestante.placeholder = 'Error: Número negativo';
        } else {
            campoTotalRestante.value = resultadoResta.toFixed(2);
        }
    });
}