<section class="main-contenedor">
    <h2>Inventario</h2>
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
</section>

<!-- Scripts de JavaScript -->
<script type="module" src="vistas/js/paginas/inventario.js"></script>