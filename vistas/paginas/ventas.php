<section class="main-contenedor">

    <h2>Ventas</h2>
        <!-- Lista de opciones -->
        <ul class="main-menu">
            <li class="main-menu__opcion"><a class="boton-main" id="abrir__alta-venta">Formulario de Venta</a></li>
            <li class="main-menu__opcion"><a class="boton-main" id="abrir__lista-ventas">Lista de Ventas de la última semana</a></li>
            <li class="main-menu__opcion">
                <form class="boton-main" id="barra-busqueda">
                    <input type="text" class="campo" name="buscarVenta-txt" id="buscarVenta-txt" placeholder="Buscar..." maxlength="80" required>
                    <button class="boton enviar" id="btnBuscarVenta"><img src="vistas/img/magnifying-glass.svg" alt=""></button>
                    <span class="alerta" id="alertaBuscar"></span>
                </form>
            </li>
        </ul>

</section>

<script type="module" src="vistas/js/paginas/ventas.js"></script>