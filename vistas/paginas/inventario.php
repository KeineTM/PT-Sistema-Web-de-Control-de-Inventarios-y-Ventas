<section class="main-contenedor">

    <h2>Inventario</h2>
    <!-- Lista de opciones -->
    <ul class="main-menu">
        <li class="main-menu__opcion"><a class="boton-main" id="abrir__alta-inventario">Formulario de Alta</a></li>
        <li class="main-menu__opcion"><a class="boton-main" id="abrir__tabla-productos">Lista de Productos</a></li>
        <li class="main-menu__opcion">
            <form class="boton-main" id="barra-busqueda">
                <input type="text" class="campo" name="buscarProducto-txt" id="buscarProducto-txt" placeholder="Buscar por Nombre" maxlength="80" required>
                <button class="boton enviar" id="btnBuscarProducto"><img src="vistas/img/magnifying-glass.svg" alt=""></button>
                <span class="alerta" id="alertaBuscar"></span>
            </form>
        </li>
    </ul>

    <article id="subcontenedor"><!-- Aquí se carga contenido con JS --></article>
    <!-- Modal alta de categoría -->
    <section class="modal" id="modal__mini-formulario"></section>

</section>

<!-- Scripts de JavaScript -->
<script type="module" src="vistas/js/paginas/inventario.js"></script>