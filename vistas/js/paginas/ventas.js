import { metodosModal } from "../modal.js";

const campoBuscar = document.querySelector('#buscarOperacion-txt');
const btnBuscar = document.querySelector('#btnBuscarOperacion');
const formularioBusquedaProducto = document.querySelector('#barra-busqueda-producto');
const campoBuscarProducto = document.querySelector('#buscarProducto-txt');
const btnBuscarProducto = document.querySelector('#btnBuscarProductos');
const alertaHTML = document.querySelector('#alertaBuscar');
const btnRegistrar = document.querySelector('#btnRegistrar')

//----------------- ------------------------ --------------------
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

// Busqueda de una operación
if(btnBuscar !== null) {
    btnBuscar.addEventListener('click', (event) => {
        event.preventDefault();
        alertaHTML.innerText = '';
        
        let validacionResultado = validarCampo(campoBuscar.value);
    
        if(validacionResultado !== true) {
            alertaHTML.style.visibility = 'visible';
            alertaHTML.innerText = validacionResultado;
        } else {
            document.querySelector('#barra-busqueda').submit();
        }
    });
}

//----------------- ------------------------ --------------------
//----------------- Evento del botón de eliminación del registro que solicita confirmación ------------------
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
const formularioDeVenta = document.querySelector('#form-alta');
const campoTotal = document.querySelector('[data-form=total]');

// Evalúa que exista el campo de descuento en el módulo: Ventas y Apartados
// Para calcular e imprimir en pantalla el total con el descuento aplicado
if(formularioDeVenta !== null) {
    const campoDescuento = document.querySelector('[data-form=descuento]');
    const campoDescuentoAplicado = document.querySelector('[name=descuento-aplicado]');
    const lblDescuentoAplicado = document.querySelector('#lbl-descuento-aplicado');
    const lblTotal = document.querySelector('#lbl-total');

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

    const validarFormulario = () => {
        const listaErrores = [];
        if(parseFloat(campoTotal.value) <= parseFloat(campoDescuento.value)) listaErrores.push('El descuento es mayor o igual al total de la venta.');
        if(parseFloat(campoDescuento.value) > (parseFloat(campoTotal.value)/2)) listaErrores.push('El descuento es mayor al 50% de la venta.');
        if(parseFloat(campoTotal.value) <= 0) listaErrores.push('El carrito está vacío.');
        if(parseFloat(campoDescuento.value) < 0) listaErrores.push('El descuento no debe ser negativo.');
        return listaErrores;
    }

    btnRegistrar.addEventListener('click', (event) => {
        event.preventDefault();
        const listaErrores = validarFormulario();

        if(listaErrores.length === 0) {
            formularioDeVenta.submit();
        } else {
            metodosModal.desplegarModal(document.getElementById('modal'));
            metodosModal.construirModalAlerta(document.getElementById('modal'), listaErrores);
        }
    });
}



//----------------- ------------------------ --------------------
//----------------- JS del módulo de APARTADOS ------------------
const formularioDeApartados = document.querySelector('#form-apartado');
const formularioAbonoNuevo = document.querySelector('#formulario-abonar');

if(formularioDeApartados !== null || formularioAbonoNuevo !== null) {
    const campoMontoAbonado = document.querySelector('[data-form=abono]');
    const campoTotalRestante = document.querySelector('[data-form=restante]');
    const porcentajeDeAbonoSugerido = 0.3; // Regla del negocio para realizar apartados: 30%
    const campoMontoAbonadoNuevo = document.querySelector('[data-form=abono_nuevo]');
    const btnAbonar = document.querySelector('#btnAbonar');
    const errorAbono = document.querySelector('#error-abono');
    const saldo_pendiente = document.querySelector('#saldo_pendiente');

    
    // Evalúa que exista un campo de abonos: Apartados
    // Para calcular e imprimir en pantalla el total restante después del abono
    if(campoMontoAbonado !== null) {
        let minimoDeAbono = campoTotal.value * porcentajeDeAbonoSugerido;
        let resultadoResta;
    
        campoMontoAbonado.placeholder = 'Sugerido: $' + Math.ceil(minimoDeAbono);
    
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

        //----------------- Comprobación de que el número de teléfono del cliente existe ------------------
        const campoTelefono = document.querySelector('[name=cliente_id-txt]');
        
        if(campoTelefono !== null) {
            campoTelefono.addEventListener('input', () => {
                if(campoTelefono.value.length > 10) {
                    campoTelefono.value = campoTelefono.value.slice(0,10);
                }
            });
        }

        const validarFormulario = () => {
            const listaErrores = [];
            resultadoResta = campoTotal.value - campoMontoAbonado.value;
            if(parseFloat(campoTotal.value) <= 0) 
                listaErrores.push('El carrito está vacío.');
            else {
                if(resultadoResta < 0) listaErrores.push('El abono no debe ser mayor al total del apartado.')
                if(campoMontoAbonado.value < 0) listaErrores.push("El abono debe ser mayor a 0.");
                if(campoMontoAbonado.value.length === 0) listaErrores.push("Debe ingresar un abono.");
                if(campoTelefono.value.length <= 0 && campoTelefono.value.length < 10) listaErrores.push("Debe ingresar un número de teléfono de 10 dígitos.");
            }
            
            return listaErrores;
        }

        btnRegistrar.addEventListener('click', (event) => {
            event.preventDefault();
            const listaErrores = validarFormulario();
    
            if(listaErrores.length === 0) {
                formularioDeApartados.submit();
            } else {
                metodosModal.desplegarModal(document.getElementById('modal'));
                metodosModal.construirModalAlerta(document.getElementById('modal'), listaErrores);
            }
        });

    }

    
    /** Validación del campo de abono: */ 
    const validarAbono = (campo, saldo_pendiente) => {
        if(campo.value.length > 0) {
            let regex = new RegExp('^[0-9]+(\\.[0-9]{1,2})?$');
            if(campo.value <= 0 ||
                campo.value > 9999 ||
                !regex.test(campo.value) ||
                parseFloat(campo.value) > parseFloat(saldo_pendiente.value)) {

                if(campo.value <= 0) return 'Sólo se aceptan números mayores a 0.'
                if(campo.value > 9999) return 'Sólo se aceptan números menores a 9999.'
                if(!regex.test(campo.value)) return 'Sólo se aceptan números con un máximo de 2 decimales.'
                if(parseFloat(campo.value) > parseFloat(saldo_pendiente.value)) return 'El monto abonado es mayor que la deuda.'

            } else return false;
        } else return 'No ha ingresado una cantidad a abonar.'; 
    }
    
    // Evalúa la existencia del campo para el nuevo abono y realiza las operaciones de cálculo de nuevo total 
    // y las restricciones necesarias
    if(campoMontoAbonadoNuevo !== null) {
        const campoRestante = document.querySelector('#restante');
    
        campoRestante.value = saldo_pendiente.value;
    
        campoMontoAbonadoNuevo.addEventListener('keyup', () => {
            const validacion = validarAbono(campoMontoAbonadoNuevo, saldo_pendiente);
            // Si hay error en el monto abonado:
            if(validacion !== false) {
                campoRestante.value = saldo_pendiente.value;
                errorAbono.innerText = validacion;
            } else {
                errorAbono.innerText = '';
                if(saldo_pendiente.value - campoMontoAbonadoNuevo.value < 0) {
                    campoRestante.value = '';
                    errorAbono.innerText = 'El abono es mayor a la deuda.'
                } else {
                    campoRestante.value = saldo_pendiente.value - campoMontoAbonadoNuevo.value; 
                }
            }
        });
    }
    
    if(formularioAbonoNuevo !== null) {
        btnAbonar.addEventListener('click', (event) => {
            event.preventDefault();
            let listaErrores = validarAbono(campoMontoAbonadoNuevo, saldo_pendiente);

            if(listaErrores !== false) {
                metodosModal.desplegarModal(document.getElementById('modal'));
                metodosModal.construirModalAlerta(document.getElementById('modal'), listaErrores);
            } else {
                errorAbono.innerText = '';
                formularioAbonoNuevo.submit();
            }
        });
    }
}

//-----------------//-----------------//-----------------
//----------------- Búsqueda de productos ------------------
if(btnBuscarProducto !== null) {
    btnBuscarProducto.addEventListener('click', () => {
        event.preventDefault();
        alertaBuscar.innerText = ""
        alertaBuscar.style.visibility = 'hidden';
    
        if(campoBuscarProducto.value.length !== 0 && campoBuscarProducto.value.length < 80) {
            formularioBusquedaProducto.submit();
        } else {
            alertaBuscar.innerText = "Debe ingresar una palabra clave"
            alertaBuscar.style.visibility = 'visible';
        }  
    });
}

//----------------- ------------------------ --------------------
//----------------- JS del módulo de DEVOLUCIONES ------------------
const formularioDeDevolucion = document.querySelector('#form-devolucion');

if(formularioDeDevolucion !== null) {
    const campoMotivoDeDevolucion = document.querySelector('#notas-txt');
    
    const validarFormulario = () => {
        const listaErrores = [];
        if(parseFloat(campoTotal.value) <= 0) listaErrores.push('El carrito está vacío.');
        else if(campoMotivoDeDevolucion.value.length === 0) listaErrores.push('Debe ingresar un motivo para la devolución.');
        return listaErrores;
    }

    btnRegistrar.addEventListener('click', (event) => {
        event.preventDefault();
        const listaErrores = validarFormulario();

        if(listaErrores.length === 0) {
            formularioDeDevolucion.submit();
        } else {
            metodosModal.desplegarModal(document.getElementById('modal'));
            metodosModal.construirModalAlerta(document.getElementById('modal'), listaErrores);
        }
    });
}