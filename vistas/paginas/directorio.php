<section class="main-contenedor">
    <ul class="main-menu destacado">
        <li class="main-menu__opcion"><a class="boton-main" href="index.php?pagina=directorio&opciones=alta">Registrar Contacto</a></li>
        <li class="main-menu__opcion"><a class="boton-main" href="index.php?pagina=directorio&opciones=listar">Lista de Contactos</a></li>
        
        <li class="main-menu__opcion">
            <form class="boton-main" id="barra-busqueda">
                <input type="text" class="campo" name="buscarContacto-txt" autocomplete="off" placeholder="Teléfono o Nombre" minlength="3" maxlength="240" required>
                <button class="boton enviar" id="btnBuscar"><img src="vistas/img/magnifying-glass.svg" alt=""></button>
            </form>
            <span class="alerta" id="alertaBuscar"></span>
        </li>
    </ul>

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
        } else
            include 'vistas/paginas/directorio-form-alta.php';
    ?>
    </article>
    
</section>

<script type="module" src="vistas/js/paginas/directorio.js"></script>