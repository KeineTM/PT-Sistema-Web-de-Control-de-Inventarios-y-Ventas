<section class="main-contenedor">

    <h2>Ventas</h2>
    <!-- Submenú -->
    <ul class="main-menu destacado">
        <li class="main-menu__opcion"><a class="boton-main" href="index.php?pagina=ventas&opciones=alta">Formulario de Venta</a></li>
        <li class="main-menu__opcion"><a class="boton-main" href="index.php?pagina=ventas&opciones=listar&tiempo=dia">Ventas del día</a></li>
        <li class="main-menu__opcion"><a class="boton-main" href="index.php?pagina=ventas&opciones=listar&tiempo=semana">Ventas de la semana</a></li>
        <li class="main-menu__opcion">
            <form class="boton-main" id="barra-busqueda">
                <input type="number" step="any" class="campo" name="buscarOperacion-txt" autocomplete="off" id="buscarOperacion-txt" placeholder="Buscar..." maxlength="18" min='1' required>
                <input type="hidden" name="tipoOperacion-txt" value="VE">
                <button class="boton enviar" id="btnBuscarOperacion"><img src="vistas/img/magnifying-glass.svg" alt=""></button>
            </form>
            <span class="alerta" id="alertaBuscar"></span>
        </li>
    </ul>

    <article id="subcontenedor">
    <?php
        if (isset($_GET['opciones'])) {
            if ($_GET['opciones'] === 'alta')
                include 'vistas/paginas/ventas-form-alta.php';
            if ($_GET['opciones'] === 'listar')
                include 'vistas/paginas/ventas-listar.php';
            if ($_GET['opciones'] === 'detalles')
                include 'vistas/paginas/ventas-detalles.php';
            if($_GET['opciones'] === 'exito')
                echo '<p class="alerta-verde">Eliminación exitosa</p>';
            if($_GET['opciones'] === 'error')
                echo '<p class="alerta-roja">Ocurrió un error</p>';
        } else
            include 'vistas/paginas/ventas-form-alta.php';
    ?>
    </article>

</section>

<script src="vistas/js/paginas/ventas.js"></script>