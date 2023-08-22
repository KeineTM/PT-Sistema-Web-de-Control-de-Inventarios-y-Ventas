//--------------------- Validación de Login -----------------------------

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
//--------------------------------------------------
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
        formato: 'Las unidades sólo pueden ser números enteros'
    },
    unidadesMinimas: {
        limiteMin: 'Las unidades mínimas deben ser mayores de 0',
        limiteMax: 'Las unidades mínimas deben ser menores de 9999',
        formato: 'Las unidades mínimas sólo pueden ser números enteros'
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
const validarCampoProductos = (campo) => {
    // Recupera la etiqueta del formulario de acuerdo con el data-form="tipoDeInput" para saber qué campo es.
    let dataform = campo.dataset.form;
    let regex;
    
    // Evalua la validez de la entrada y genera el mensaje de error correspondiente si es oportuno.
    switch(dataform) {
        case 'productoID': // Requerido
            regex = /^[a-zA-Z0-9]{1,20}$/;
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
            regex = /^([0-9])*$/; // Valida sólo números
            if(campo.value.length === 0 ||
                campo.value < 1 ||
                campo.value > 9999 ||
                !regex.test(campo.value)) {
                
                if(campo.value.length === 0) return mostrarMensajeDeError(dataform, 'vacio');
                if(campo.value < 0) return mostrarMensajeDeError(dataform, 'limiteMin');
                if(campo.value > 9999) return mostrarMensajeDeError(dataform, 'limiteMax');
                if(!regex.test(campo.value)) return mostrarMensajeDeError(dataform, 'formato');
            } else
                return null;
        break;
        case 'unidadesMinimas': // Sólo se evalúa si existe un dato
            regex = /^([0-9])*$/;
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
                regex = /^[0-9]+(\.[0-9]{1,2})?/; // Valida sólo números con hasta 2 decimales
                
                if(campo.value < 0) return mostrarMensajeDeError(dataform, 'limiteMin');
                if(campo.value > 9999) return mostrarMensajeDeError(dataform, 'limiteMax');
                if(campo.value.length > 7) return 'El precio de compra no puede superar las 7 cifras';
                if(!regex.test(campo.value)) return mostrarMensajeDeError(dataform, 'formato');
                return null;
            } else
                return null;
        break;
        case 'precioVenta': // Requerido
            regex = /^[0-9]+(\.[0-9]{1,2})?/;
            if(campo.value.length === 0 ||
                campo.value < 1 ||
                campo.value > 9999 ||
                campo.value.length > 7 ||
                !regex.test(campo.value)) {
                
                if(campo.value.length === 0) return mostrarMensajeDeError(dataform, 'vacio');
                if(campo.value.length > 7) return 'El precio de venta no puede superar las 7 cifras';
                if(campo.value < 1) return mostrarMensajeDeError(dataform, 'limiteMin');
                if(campo.value > 9999) return mostrarMensajeDeError(dataform, 'limiteMax');
                if(!regex.test(campo.value)) return mostrarMensajeDeError(dataform, 'formato');
            } else
                return null;
        break;
        case 'precioMayoreo': // Sólo se evalúa si existe un dato
            if(campo.value.length !== 0) {
                regex = /^[0-9]+(\.[0-9]{1,2})?/;
                
                if(campo.value < 0) return mostrarMensajeDeError(dataform, 'limiteMin');
                if(campo.value > 9999) return mostrarMensajeDeError(dataform, 'limiteMax');
                if(campo.value.length > 7) return 'El precio de mayoreo no puede superar las 7 cifras';
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
                    let fechaIngresada = new Date(campo.value);
                    let fechaMin = new Date();
                    let fechaMax = new Date();
                    fechaMax = fechaMax.setFullYear(fechaMin.getFullYear() + 5);
                    fechaMin.setHours(0, 0, 0, 0);
                    if(fechaIngresada.toLocaleString('en-US', { timeZone: 'America/Mexico_City' }) < fechaMin) return mostrarMensajeDeError(dataform, 'limiteMin');
                    if(fechaIngresada.toLocaleString('en-US', { timeZone: 'America/Mexico_City' }) > fechaMax) return mostrarMensajeDeError(dataform, 'limiteMax');
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

/** Método que contiene las validaciones para el formulario de contactos en el módulo de directorio */
const validarCampoDirectorio = (campo) => {
    let dataform = campo.dataset.form;
    let regex;

    switch(dataform) {
        case 'nombre':
            regex = /^[a-zA-Z áéíóúÁÉÍÓÚñÑ]{2,80}$/;
            if(campo.value.length < 0) return 'El nombre no debe quedar vacío';
            if(campo.value.length < 3) return 'El nombre no debe tener menos de 3 letras';
            if(campo.value.length > 80) return 'El nombre no debe tener más de 80 letras';
            if(!regex.test(campo.value)) return 'El nombre sólo acepta letras';
            return null;
        case 'apellido_paterno':
            regex = /^[a-zA-Z áéíóúÁÉÍÓÚñÑ]{2,80}$/;
            if(campo.value.length < 0) return 'El apellido paterno no debe quedar vacío';
            if(campo.value.length < 3) return 'El apellido paterno no debe tener menos de 3 letras';
            if(campo.value.length > 80) return 'El apellido paterno no debe tener más de 80 letras';
            if(!regex.test(campo.value)) return 'El apellido paterno sólo acepta letras';
            return null;
        case 'apellido_materno':
            if(campo.value.length !== 0) {
                regex = /^[a-zA-Z áéíóúÁÉÍÓÚñÑ]{2,80}$/;
                if(campo.value.length < 3) return 'El apellido materno no debe tener menos de 3 letras';
                if(campo.value.length > 80) return 'El apellido materno no debe tener más de 80 letras';
                if(!regex.test(campo.value)) return 'El apellido materno sólo acepta letras';
            }
            return null;
        case 'contacto_id': // Número de teléfono
            regex = /^([0-9]+){10}$/;
            if(campo.value.length < 0) return 'El número de teléfono no debe quedar vacío';
            if(campo.value.length != 10) return 'El número de teléfono debe tener 10 números';
            if(!regex.test(campo.value)) return 'El número de teléfono sólo acepta números';
            return null;
        case 'notas': // Dirección
            if(campo.value.length < 0) return 'Las notas no deben quedar vacía';
            if(campo.value.length < 5) return 'Las notas no deben tener menos de 5 letras';
            if(campo.value.length > 240) return 'Las notas no deben tener más de 240 letras';
            return null;
        case 'email':
            if(campo.value.length !== 0) {
                regex =  /^\w+([.-_+]?\w+)*@\w+([.-]?\w+)*(\.\w{2,10})+$/;
                if(campo.value.length > 150) return 'El email no debe tener más de 150 letras';
                if(!regex.test(campo.value)) return 'El email debe contener un @ y un dominio. Ej: tienda@gobokids.com';
            }
            return null;
        case 'tipo_id':
            if(campo.value.length < 0) return 'Debe seleccionar un tipo para el contacto';
            return null;
    }
}

/** Método que contiene las validaciones para el formulario de contactos en el módulo de directorio */
const validarCampoPersonal = (campo, formulario) => {
    let dataform = campo.dataset.form;
    let regex;

    switch(dataform) {
        case 'nombre':
            regex = /^[a-zA-Z áéíóúÁÉÍÓÚñÑ]{2,80}$/;
            if(campo.value.length < 1) return 'El nombre no debe quedar vacío.';
            if(campo.value.length < 3) return 'El nombre no debe tener menos de 3 letras.';
            if(campo.value.length > 80) return 'El nombre no debe tener más de 80 letras.';
            if(!regex.test(campo.value)) return 'El nombre sólo acepta letras.';
            return null;
        case 'apellido_paterno':
            regex = /^[a-zA-Z áéíóúÁÉÍÓÚñÑ]{2,80}$/;
            if(campo.value.length < 1) return 'El apellido paterno no debe quedar vacío.';
            if(campo.value.length < 3) return 'El apellido paterno no debe tener menos de 3 letras.';
            if(campo.value.length > 80) return 'El apellido paterno no debe tener más de 80 letras.';
            if(!regex.test(campo.value)) return 'El apellido paterno sólo acepta letras.';
            return null;
        case 'apellido_materno':
            regex = /^[a-zA-Z áéíóúÁÉÍÓÚñÑ]{2,80}$/;
            if(campo.value.length < 1) return 'El apellido materno no debe quedar vacío.';
            if(campo.value.length < 3) return 'El apellido materno no debe tener menos de 3 letras.';
            if(campo.value.length > 80) return 'El apellido materno no debe tener más de 80 letras.';
            if(!regex.test(campo.value)) return 'El apellido materno sólo acepta letras.';
            return null;
        case 'telefono':
            regex = /^([0-9]+){10}$/;
            if(campo.value.length < 1) return 'El número de teléfono no debe quedar vacío.';
            if(campo.value.length != 10) return 'El número de teléfono debe tener 10 números.';
            if(!regex.test(campo.value)) return 'El número de teléfono sólo acepta números.';
            return null;
        case 'rfc':
            regex = /^([a-z]{3,4})(\d{2})(\d{2})(\d{2})([0-9a-z]{3})$/i;
            if(campo.value.length < 1) return 'El RFC no debe quedar vacío.';
            if(campo.value.length != 13) return 'El RFC debe tener 13 caracteres.';
            if(!regex.test(campo.value)) return 'El formato ingresado no corresponde a un RFC válido.';
            return null;
        case 'notas':
            if(campo.value.length !== 0) {
                if(campo.value.length < 5) return 'Las notas no deben tener menos de 5 letras.';
                if(campo.value.length > 240) return 'Las notas no deben tener más de 240 letras.';
            }
            return null;
        case 'email':
            if(campo.value.length !== 0) {
                regex =  /^\w+([.-_+]?\w+)*@\w+([.-]?\w+)*(\.\w{2,10})+$/;
                if(campo.value.length > 150) return 'El email no debe tener más de 150 letras';
                if(!regex.test(campo.value)) return 'El email debe contener un @ y un dominio. Ej: tienda@gobokids.com';
            }
            return null;
        case 'password':
            if(formulario === 'edicion') {                
                if(campo.value.length !== 0) {
                    regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@¡!¿?\-_ñÑ%])[A-Za-z\d@¡!¿?\-_ñÑ%]{8,20}$/;
                    if(campo.value.length < 8 || campo.value.length > 20) return 'La contraseña debe tener de 8 a 20 caracteres.';
                    if(!regex.test(campo.value)) return 'La contraseña debe tener por lo menos: 1 mayúscula, 1 minúscula, 1 número y 1 caracter especial  (@, ¡, !, ¿, ?, -, _ o %)';
                }
            } 
            
            if(formulario === 'alta') {
                regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@¡!¿?\-_ñÑ%])[A-Za-z\d@¡!¿?\-_ñÑ%]{8,20}$/;
                if(campo.value.length === 0) return 'La contraseña no puede quedar vacía.';
                if(campo.value.length < 8 || campo.value.length > 20 || campo.value.length === 0) return 'La contraseña debe tener de 8 a 20 caracteres.';
                if(!regex.test(campo.value)) return 'La contraseña debe tener por lo menos: 1 mayúscula, 1 minúscula, 1 número y 1 caracter especial  (@, ¡, !, ¿, ?, -, _ o %)';
            }
            return null;
        default:
            return null;
    }
}

export const metodosValidacion = {
    validarCampoProductos,
    validarCampoDirectorio,
    validarCampoPersonal,
    validarLlenadoFormulario
}