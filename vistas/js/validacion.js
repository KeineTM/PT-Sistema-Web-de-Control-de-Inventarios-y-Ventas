const mensajesDeErrorAltaProducto = {
    productoID: {
        vacio: 'El código del producto no puede quedar vacío',
        formato: 'El código del producto sólo admite números y letras',
        muyLargo: 'El máximo de letras es 20'
    },
    nombreProducto: { 
        vacio: 'El nombre del producto no puede quedar vacío',
        muyCorto: 'El nombre del producto debe ser mayor de 4 letras',
        muyLargo: 'El nombre del producto no puede tener más de 80 letras'
    },
    categoriaID: { 
        vacio: 'Debe seleccionar una categoria',
        muyLargo: 'Seleccione una categoria de la lista'
    },
    descripcion: { muyLargo: 'La descripción no debe sobrepasar las 400 letras' },
    unidades: { 
        vacio: 'Las unidades no pueden quedar vacías',
        limiteMin: 'Las unidades deben ser mayores de 0',
        limiteMax: 'Las unidades deben ser menores de 9999',
        formato: 'Las unidades sólo pueden ser números'
    },
    unidadesMinimas: {
        limiteMin: 'Las unidades mínimas deben ser mayores de 0',
        limiteMax: 'Las unidades mínimas deben ser menores de 9999',
        formato: 'Las unidades mínimas sólo pueden ser números'
    },
    precioCompra: {
        limiteMin: 'El precio de compra debe ser mayor de 0',
        limiteMax: 'El precio de compra debe ser menores de 9999',
        formato: 'El precio de compra sólo admite números con hasta 2 decimales. Ej: 0.50'
    },
    precioVenta: { 
        vacio: 'Debe ingresar un precio de venta válido',
        limiteMin: 'El precio de venta debe ser mayor de 0',
        limiteMax: 'El precio de venta debe ser menores de 9999',
        formato: 'El precio de venta sólo admite números con hasta 2 decimales. Ej: 0.50',
    },
    precioMayoreo: {
        limiteMin: 'El precio de mayoreo debe ser mayor de 0',
        limiteMax: 'El precio de mayoreo debe ser menores de 9999',
        formato: 'El precio de mayoreo sólo admite números con hasta 2 decimales. Ej: 0.50'
    },
    caducidad: { 
        formato: 'El formato de la fecha debe ser dd/mm/aaaa',
        limiteMin: 'La fecha no puede ser anterior al día de hoy',
        limiteMax: 'La fecha no puede ir más allá de 5 años'
    },
    imagenURL: { 
        formato: 'La dirección debe terminar con extensión .jpg, .png o .webp',
        muyLargo: 'La dirección no debe sobrepasar las 250 letras'
    }
}

// Array de los errores existentes
const tipoDeErrores = ['vacio', 'formato', 'muyCorto', 'muyLargo', 'limiteMin', 'limiteMax'];

// Método para seleccionar el mensaje de error adecuado para cada campo evaluado
const mostrarMensajeDeError = (campo, errorEncontrado) => {
    let mensaje = "";
    tipoDeErrores.forEach(error => {
        // Si el error encontrado corresponde con un error de la lista devuelve el mensaje de error correspondiente:
        if(errorEncontrado === error) mensaje = mensajesDeErrorAltaProducto[campo][error];
    });
    return mensaje;
}

/** Método recibe un input y valida su contenido de acuerdo con el tipo de data-form asignado */
const validarCampo = (campo) => {
    // Recupera la etiqueta del formulario de acuerdo con el data-form="tipoDeInput" para saber qué campo es.
    let dataform = campo.dataset.form;
    let regex;
    
    // Evalua la validez de la entrada y genera el mensaje de error correspondiente si es oportuno.
    switch(dataform) {
        case 'productoID': // Requerido
            regex = new RegExp('^[a-zA-Z0-9]{1,20}$');
            if(campo.value.length === 0 ||
                campo.value.length > 20 ||
                !regex.test(campo.value)) {
                
                if(campo.value.length === 0) return mostrarMensajeDeError(dataform, 'vacio');
                if(campo.value.length > 20) return mostrarMensajeDeError(dataform, 'muyLargo');
                if(!regex.test(campo.value)) return mostrarMensajeDeError(dataform, 'formato');
            } else
                return null;
        break;
        case 'nombreProducto': // Requerido
            if(campo.value.length === 0 ||
                campo.value.length > 80 ||
                campo.value.length < 4) {
                
                if(campo.value.length === 0) return mostrarMensajeDeError(dataform, 'vacio');
                if(campo.value.length > 80) return mostrarMensajeDeError(dataform, 'muyLargo');
                if(campo.value.length < 4) return mostrarMensajeDeError(dataform, 'muyCorto');
            } else
                return null;
        break;
        case 'categoriaID': // Requerido
            if(campo.value.length === 0 ||
                campo.value.length > 5) {
                
                if(campo.value.length === 0) return mostrarMensajeDeError(dataform, 'vacio');
                if(campo.value.length > 5) return mostrarMensajeDeError(dataform, 'muyLargo');
            } else
                return null;
        break;
        case 'descripcion': // Sólo se evalúa si existe un dato
            if(campo.value.length !== 0 && campo.value.length > 400) {
                
                return mostrarMensajeDeError(dataform, 'muyLargo');
            } else
                return null;
        break;
        case 'unidades': // Requerido
            regex = new RegExp('^([0-9])*$'); // Valida sólo números
            if(campo.value.length === 0 ||
                campo.value < 1 ||
                campo.value > 9999 ||
                !regex.test(campo.value)) {
                
                if(campo.value.length === 0) return mostrarMensajeDeError(dataform, 'vacio');
                if(campo.value < 1) return mostrarMensajeDeError(dataform, 'limiteMin');
                if(campo.value > 9999) return mostrarMensajeDeError(dataform, 'limiteMax');
                if(!regex.test(campo.value)) return mostrarMensajeDeError(dataform, 'formato');
            } else
                return null;
        break;
        case 'unidadesMinimas': // Sólo se evalúa si existe un dato
            regex = new RegExp('^([0-9])*$');
            if(campo.value.length !== 0) {
                
                if(campo.value < 0) return mostrarMensajeDeError(dataform, 'limiteMin');
                if(campo.value > 9999) return mostrarMensajeDeError(dataform, 'limiteMax');
                if(!regex.test(campo.value)) return mostrarMensajeDeError(dataform, 'formato');
                return null;
            } else
                return null;
        break;
        case 'precioCompra': // Sólo se evalúa si existe un dato
            if(campo.value.length !== 0) {
                regex = new RegExp('^[0-9]+(\\.[0-9]{1,2})?$'); // Valida sólo números con hasta 2 decimales
                
                if(campo.value < 0) return mostrarMensajeDeError(dataform, 'limiteMin');
                if(campo.value > 9999) return mostrarMensajeDeError(dataform, 'limiteMax');
                if(!regex.test(campo.value)) return mostrarMensajeDeError(dataform, 'formato');
                return null;
            } else
                return null;
        break;
        case 'precioVenta': // Requerido
            regex = new RegExp('^[0-9]+(\\.[0-9]{1,2})?$');
            if(campo.value.length === 0 ||
                campo.value < 1 ||
                campo.value > 9999 ||
                !regex.test(campo.value)) {
                
                if(campo.value.length === 0) return mostrarMensajeDeError(dataform, 'vacio');
                if(campo.value < 1) return mostrarMensajeDeError(dataform, 'limiteMin');
                if(campo.value > 9999) return mostrarMensajeDeError(dataform, 'limiteMax');
                if(!regex.test(campo.value)) return mostrarMensajeDeError(dataform, 'formato');
            } else
                return null;
        break;
        case 'precioMayoreo': // Sólo se evalúa si existe un dato
            if(campo.value.length !== 0) {
                regex = new RegExp('^[0-9]+(\\.[0-9]{1,2})?$');
                
                if(campo.value < 0) return mostrarMensajeDeError(dataform, 'limiteMin');
                if(campo.value > 9999) return mostrarMensajeDeError(dataform, 'limiteMax');
                if(!regex.test(campo.value)) return mostrarMensajeDeError(dataform, 'formato');
                return null;
            } else
                return null;
        break;
        case 'caducidad': // Sólo se evalúa si existe un dato
            if(campo.value.length !== 0) {
                // Formato yyyy-mm-dd, además los meses no pueden superar 12 y los días 31
                const FECHA_REGEX = /^\d{4}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])$/;
                if(campo.value.match(FECHA_REGEX)) {
                    // Establecimiento de fechas mínimas y máximas para el formulario:
                    let fechaIngresada = new Date(campo.value)
                    let fechaMin = new Date();
                    let fechaMax = new Date();
                    fechaMax = fechaMax.setFullYear(fechaMin.getFullYear() + 5);
                    if(fechaIngresada < fechaMin) return mostrarMensajeDeError(dataform, 'limiteMin');
                    if(fechaIngresada > fechaMax) return mostrarMensajeDeError(dataform, 'limiteMax');
                    return null;
                } else 
                    return mostrarMensajeDeError(dataform, 'formato');
            } else {
                return null;
            }
        break;
        case 'imagenURL': // Sólo se evalúa si existe un dato
            if(campo.value.length !== 0) {
                
                regex = /\.(jpg|jpeg|png|gif|webp|svg)$/i; // Formato para extensiones de imágenes
                if(!regex.test(campo.value)) return mostrarMensajeDeError(dataform, 'formato');
                if(campo.value.length > 250) return mostrarMensajeDeError(dataform, 'limiteMax');
                else {
                    campo.style.background = 'var(--color-blanco)';
                    return null;
                }
            } else
                return null;
        break;
        default:
            return null;
    }
}

/** Método para evaluar si un campo se ha llenado, retorna true si la cadena tiene datos.*/
const validarCampoVacio = (input) => {
    return (input.length !== 0)
        ? true
        : false
};

/**
 * Método que valida que todos los campos obligatorios del formulario estén llenos, 
 * en caso de no estarlo, colorea el borde de estos en rojo y previene al usuario. 
 * Recibe el evento, la lista de campos a validar y retorna el resultado de la validacion
 * @param {array} listaCamposObligatorios
 */
const validarLlenadoFormulario = (listaCamposObligatorios) => {
    let camposObligatoriosLlenos = 0;
    listaCamposObligatorios.forEach(campo => {
        if (!validarCampoVacio(campo.value)) {
            campo.style.borderColor = "red"
        } else {
            campo.style.borderColor = "gray";
            camposObligatoriosLlenos++;
        }
    });

    if(camposObligatoriosLlenos === listaCamposObligatorios.length)
        return true;
    else {
        alert("Debe llenar los campos marcados con rojo.");
        return false;
    }
}

export const metodosValidacion = {
    validarCampo,
    validarLlenadoFormulario
}