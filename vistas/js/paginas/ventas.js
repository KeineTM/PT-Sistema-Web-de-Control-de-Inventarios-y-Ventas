const btnEliminar = document.getElementById('btnEliminar');

btnEliminar.addEventListener('click', () => {
    const confirmacion = confirm("¿Desea eliminar la información de esta operación?");
    
    (confirmacion === true)
        ? console.log('Confirmó el envío del formulario')
        : console.log('Canceló el envío del formulario');
});