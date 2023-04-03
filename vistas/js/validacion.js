/**
 * Método para evaluar si un campo se ha llenado, retorna true si la cadena tiene datos.
 * @param {*} input 
 * @returns 
 */
const validarCampoVacio = (input) => {
    return (input.length !== 0)
        ? true
        : false
};

/**
 * Método para evaluar un número entero.
 * @param {*} input 
 * @returns 
 */
const validarNumeroEntero = (input) => {
    return Number.isInteger(input)
};

/**
 * Método para evaluar un número con hasta 3 cifras luego del punto.
 * @param {*} input 
 * @returns 
 */
const validarNumeroDecimal = (input) => {
    let regex = new RegExp(/^\d*(\.\d{1})?\d{0,1}$/);
    return (regex.test(input))
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
    validarLlenadoFormulario,
    validarNumeroEntero,
    validarNumeroDecimal
}