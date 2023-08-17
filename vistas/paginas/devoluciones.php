<section class="main-contenedor">

    <h2>Devoluciones</h2>
    <br>
    <article id="subcontenedor">
    <?php
        if (isset($_GET['opciones'])) {
            if ($_GET['opciones'] === 'alta')
                include 'vistas/paginas/devoluciones-form-alta.php';
            if ($_GET['opciones'] === 'listar')
                include 'vistas/paginas/devoluciones-listar.php';
            if ($_GET['opciones'] === 'detalles')
                include 'vistas/paginas/devoluciones-detalles.php';
            if($_GET['opciones'] === 'exito')
                echo '<p class="alerta-verde">Eliminación exitosa</p>';
            if($_GET['opciones'] === 'error')
                echo '<p class="alerta-roja">Ocurrió un error</p>';
        } else
            include 'vistas/paginas/devoluciones-form-alta.php';
    ?>
    </article>

</section>

<script src="vistas/js/paginas/ventas.js"></script>