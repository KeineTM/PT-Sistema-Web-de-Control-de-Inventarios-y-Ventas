const contenedorMenu = document.getElementById("menu-contenedor");
const btnAbrirMenu = document.getElementById("btn-abrir-menu");
const opcionesDelMenu = document.querySelectorAll('[data-option]');

const desplegarMenu = () => {
    contenedorMenu.classList.toggle('abrir-menu');
}

btnAbrirMenu.addEventListener('click', desplegarMenu);

const resaltarModuloActual = (opcionesDelMenu) => {
    const urlActual = window.location.search;
    
    // Detectando el módulo en el que se encuentra el usuario
    opcionesDelMenu.forEach(opcion => {
        if(urlActual.includes('?pagina=' + opcion.dataset.option)) {
            opcion.style.backgroundColor = 'var(--color-rosa-oscuro)';
        }
    });
    
}

resaltarModuloActual(opcionesDelMenu);
