<section class="main-contenedor">

    <h2>Ventas</h2>
    <!-- Lista de opciones -->
    <ul class="main-menu">
        <li class="main-menu__opcion"><a class="boton-main" href="index.php?pagina=ventas&opciones=alta">Formulario de Venta</a></li>
        <li class="main-menu__opcion"><a class="boton-main" href="index.php?pagina=ventas&opciones=listar">Lista de Ventas de la última semana</a></li>
        <li class="main-menu__opcion">
            <form class="boton-main" id="barra-busqueda">
                <input type="text" class="campo" name="buscarVenta-txt" id="buscarVenta-txt" placeholder="Buscar..." maxlength="80" required>
                <button class="boton enviar" id="btnBuscarVenta"><img src="vistas/img/magnifying-glass.svg" alt=""></button>
            </form>
        </li>
    </ul>

    <article id="subcontenedor">
    <!-- Aquí se carga contenido con JS o las páginas con PHP -->
    <?php
        if (isset($_GET['opciones'])) {
            if ($_GET['opciones'] === 'alta')
                include 'vistas/paginas/ventas-form-alta.php';
            if ($_GET['opciones'] === 'listar')
                include 'vistas/paginas/ventas-listar.php';
            if ($_GET['opciones'] === 'editar')
                include 'vistas/paginas/ventas-form-editar.php';
        } else
            include 'vistas/paginas/ventas-form-alta.php';
    ?>
    </article>

</section>

<script type="module" src="vistas/js/paginas/ventas.js"></script>