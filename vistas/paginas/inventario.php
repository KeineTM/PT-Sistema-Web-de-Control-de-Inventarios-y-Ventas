<section class="main-contenedor">

    <h2>Inventario</h2>
    <!-- Lista de opciones -->
    <ul class="main-menu destacado">
        <li class="main-menu__opcion"><a class="boton-main" href="index.php?pagina=inventario&opciones=alta">Registrar Producto</a></li>
        <li class="main-menu__opcion"><a class="boton-main" href="index.php?pagina=inventario&opciones=listar&pag=1">Lista de Productos</a></li>
        <li class="main-menu__opcion">
            <form class="boton-main" id="barra-busqueda">
                <input type="text" class="campo" name="buscarProducto-txt" autocomplete="off" id="buscarProducto-txt" placeholder="Buscar..." maxlength="80" min='3' required>
                <button class="boton enviar" id="btnBuscarProducto"><img src="vistas/img/magnifying-glass.svg" alt="Buscar"></button>
            </form>
            <span class="alerta" id="alertaBuscar"></span>
        </li>
        <li class="main-menu__opcion"><a class="boton-main" href="index.php?pagina=inventario&opciones=categoria">Categorías</a></li>
    </ul>

    <article id="subcontenedor">
    <?php
        if (isset($_GET['opciones'])) {
            if ($_GET['opciones'] === 'alta')
                include 'vistas/paginas/inventario-form-alta.php';
            if ($_GET['opciones'] === 'listar')
                include 'vistas/paginas/inventario-listar.php';
            if ($_GET['opciones'] === 'detalles')
                include 'vistas/paginas/inventario-detalles-editar.php';
            if ($_GET['opciones'] === 'categoria')
                include 'vistas/paginas/inventario-categoria.php';
        } else
            include 'vistas/paginas/inventario-form-alta.php';
    ?>
    </article>
    
    <section class="modal" id="modal__mini-formulario"><!-- Modal alta de categoría --></section>

</section>

<!-- Scripts de JavaScript -->
<script type="module" src="vistas/js/paginas/inventario.js"></script>