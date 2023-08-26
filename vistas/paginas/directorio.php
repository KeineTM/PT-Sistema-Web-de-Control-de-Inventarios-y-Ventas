<section class="main-contenedor">
    <h2>Directorio</h2>
    <br>
    
    <article id="subcontenedor">
    <?php
        if (isset($_GET['opciones'])) {
            if ($_GET['opciones'] === 'alta')
                include 'vistas/paginas/directorio-form-alta.php';
            if ($_GET['opciones'] === 'listar')
                include 'vistas/paginas/directorio-listar.php';
            if ($_GET['opciones'] === 'detalles')
                include 'vistas/paginas/directorio-detalles-editar.php';
            if($_GET['opciones'] === 'exito')
                echo '<p class="alerta-verde">Eliminación exitosa</p>';
            if($_GET['opciones'] === 'error')
                echo '<p class="alerta-roja">Ocurrió un error</p>';
            if ($_GET['opciones'] === 'buscar')
                include 'vistas/paginas/directorio-buscar.php';
        } else
            include 'vistas/paginas/directorio-form-alta.php';
    ?>
    </article>
    
</section>

<script type="module" src="vistas/js/paginas/directorio.js"></script>