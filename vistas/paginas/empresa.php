<section class="main-contenedor">
    <h2>Empresa</h2>
    <br>
    <article id="subcontenedor">
    <?php
        if (isset($_GET['opciones'])) {
            if ($_GET['opciones'] === 'editar') {

                # Evalúa que un usuario Administrador sea quien solicita acceso a este formulario
                # De no serlo, es redireccionado a la página de inicio
                if ($_SESSION['tipoUsuarioSesion'] !== 'Administrador') {
                ?>
                <script>
                    location.href ='index.php?pagina=inicio-usuario';
                </script>
                <?php
                    die();

                } else
                    # Si es válido, continúa y despliega el formulario
                    include 'vistas/paginas/empresa-form-editar.php';
            }
                
        } else
            include 'vistas/paginas/empresa-informacion.php';
    ?>
    </article>
    
</section>

<script type="module" src="vistas/js/paginas/personal.js"></script>