<section class="main-contenedor">

    <h2>Ventas</h2>
    <br>
    <article id="subcontenedor">
    <?php
        if (isset($_GET['opciones'])) {
            if ($_GET['opciones'] === 'alta')
                include 'vistas/paginas/ventas-form-alta.php';
            if ($_GET['opciones'] === 'listar')
                include 'vistas/paginas/ventas-listar.php';
            if ($_GET['opciones'] === 'detalles')
                include 'vistas/paginas/ventas-detalles.php';
            if ($_GET['opciones'] === 'catalogo')
                include 'vistas/paginas/ventas-catalogo.php';
            if ($_GET['opciones'] === 'buscar-productos')
                include 'vistas/paginas/ventas-buscar-productos.php';
            if ($_GET['opciones'] === 'buscar-folio')
                include 'vistas/paginas/buscar-folio.php';
            if ($_GET['opciones'] === 'buscar')
                include 'vistas/paginas/ventas-busqueda.php';
            if($_GET['opciones'] === 'exito')
                echo '<p class="alerta-verde">Eliminación exitosa</p>';
            if($_GET['opciones'] === 'error')
                echo '<p class="alerta-roja">Ocurrió un error</p>';
        } else
            include 'vistas/paginas/ventas-form-alta.php';
    ?>
    </article>

</section>

<script type="module" src="vistas/js/paginas/ventas.js"></script>