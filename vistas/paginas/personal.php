<section class="main-contenedor">

    <h2>Personal</h2>
    <br>
    <article id="subcontenedor">
    <?php
        if (isset($_GET['opciones'])) {
            if ($_GET['opciones'] === 'alta')
                include 'vistas/paginas/personal-form-alta.php';
            if ($_GET['opciones'] === 'listar')
                include 'vistas/paginas/personal-listar.php';
            if ($_GET['opciones'] === 'detalles')
                include 'vistas/paginas/personal-detalles-editar.php';
        } else
            include 'vistas/paginas/personal-listar.php';
    ?>
    </article>
    
</section>

<script type="module" src="vistas/js/paginas/personal.js"></script>