<section class="main-contenedor">
    
    <h2>Apartados</h2>

    <article id="subcontenedor">
    <?php
        if (isset($_GET['opciones'])) {
            if ($_GET['opciones'] === 'alta')
                include 'vistas/paginas/apartados-form-alta.php';
            if ($_GET['opciones'] === 'listar')
                include 'vistas/paginas/apartados-listar.php';
            if ($_GET['opciones'] === 'detalles')
                include 'vistas/paginas/apartados-detalles.php';
            if($_GET['opciones'] === 'exito')
                echo '<p class="alerta-verde">Eliminación exitosa</p>';
            if($_GET['opciones'] === 'error')
                echo '<p class="alerta-roja">Ocurrió un error</p>';
        } else
            include 'vistas/paginas/apartados-form-alta.php';
    ?>
    </article>

</section>

<script src="vistas/js/paginas/ventas.js"></script>
