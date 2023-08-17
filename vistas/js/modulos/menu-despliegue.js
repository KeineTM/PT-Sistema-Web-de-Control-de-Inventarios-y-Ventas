let contenedorMenu = document.getElementById("menu-contenedor");
let btnAbrirMenu = document.getElementById("btn-abrir-menu");

const desplegarMenu = () => {
    contenedorMenu.classList.toggle('abrir-menu');
}

btnAbrirMenu.addEventListener('click', desplegarMenu);

// ------------------------------------------------------
/* Efectos del submenú desplegable
const opcionesDelMenu = document.querySelectorAll('.menu__opciones');

if(opcionesDelMenu !== null) {
    for (const opcion of opcionesDelMenu) {
        opcion.addEventListener('click', () => {
            opcion.classList.toggle('fondo-oscuro');
        });
    }
}*/