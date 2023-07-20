<section class="main-contenedor">
    <ul class="main-menu destacado">
        <li class="main-menu__opcion"><a class="boton-main" href="index.php?pagina=personal&opciones=alta">Registrar Empleado</a></li>
        <li class="main-menu__opcion"><a class="boton-main" href="index.php?pagina=personal&opciones=listar">Lista de Empleados</a></li>
        
        <li class="main-menu__opcion">
            <form class="boton-main" id="barra-busqueda">
                <input type="text" class="campo" name="buscarEmpleado-txt" autocomplete="off" placeholder="Buscar..." minlength="3" maxlength="240" required>
                <button class="boton enviar" id="btnBuscar"><img src="vistas/img/magnifying-glass.svg" alt=""></button>
            </form>
            <span class="alerta" id="alertaBuscar"></span>
        </li>
    </ul>

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