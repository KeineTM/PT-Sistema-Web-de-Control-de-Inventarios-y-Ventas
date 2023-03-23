let contenedorMenu = document.getElementById("menu-contenedor");
let btnAbrirMenu = document.getElementById("btn-abrir-menu");

const desplegarMenu = () => {
    contenedorMenu.classList.toggle('abrir-menu');
}

btnAbrirMenu.addEventListener('click', desplegarMenu);