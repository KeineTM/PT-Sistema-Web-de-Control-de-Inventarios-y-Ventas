<section class="main-contenedor">

    <h2>Inventario</h2>
    <!-- Lista de opciones -->
    <ul class="inventario-menu">
        <li class="inventario-menu__opcion"><a class="boton-main" id="abrir__alta-inventario">Formulario de Alta</a></li>
        <li class="inventario-menu__opcion"><a class="boton-main" id="abrir__tabla-productos">Lista de Productos</a></li>
        <li class="inventario-menu__opcion"><a class="boton-main"><input type="text" class="campo" name="buscar-producto" id="buscar-producto" placeholder="Buscar un producto"></a></li>
    </ul>

    <article id="subcontenedor"><!-- Aquí se carga contenido con JS --></article>
    <!-- Modal alta de categoría -->
    <section class="modal" id="modal__mini-formulario"></section>

</section>

<!-- Scripts de JavaScript -->
<script type="module" src="vistas/js/paginas/inventario.js"></script>