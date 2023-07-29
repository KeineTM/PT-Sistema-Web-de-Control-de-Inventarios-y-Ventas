<section class="main-contenedor">
    <article id="subcontenedor">
    <?php
        if (isset($_GET['opciones'])) {
            if ($_GET['opciones'] === 'editar')
                include 'vistas/paginas/empresa-form-editar.php';
        } else
            include 'vistas/paginas/empresa-informacion.php';
    ?>
    </article>
    
</section>

<script type="module" src="vistas/js/paginas/personal.js"></script>