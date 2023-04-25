<section class="main-contenedor">

    <h2>Ventas</h2>
    <!-- Lista de opciones -->
    <ul class="main-menu">
        <li class="main-menu__opcion"><a class="boton-main" href="index.php?pagina=ventas&opciones=alta">Formulario de Venta</a></li>
        <li class="main-menu__opcion"><a class="boton-main" href="index.php?pagina=ventas&opciones=listar&tiempo=dia">Ventas del día</a></li>
        <li class="main-menu__opcion"><a class="boton-main" href="index.php?pagina=ventas&opciones=listar&tiempo=semana">Ventas de la semana</a></li>
        <li class="main-menu__opcion">
            <form class="boton-main" id="barra-busqueda">
                <input type="text" class="campo" name="buscarOperacion-txt" id="buscarOperacion-txt" placeholder="Buscar..." maxlength="80" required>
                <button class="boton enviar" id="btnBuscarOperacion"><img src="vistas/img/magnifying-glass.svg" alt=""></button>
            </form>
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