<section class="main-contenedor">
    <h2>Inventario</h2>
    <!-- Lista de opciones -->
    <ul class="inventario-lista">
        <li class="inventario-lista__opcion"><a class="boton-main" id="abrir__alta-inventario">Formulario de Alta</a></li>
        <li class="inventario-lista__opcion"><a class="boton-main" id="abrir__tabla-productos">Lista de Productos</a></li>
        <li class="inventario-lista__opcion"><a class="boton-main">Vista de Catálogo</a></li>
    </ul>
    <div id="tabla-contenedor"><!-- Aquí se carga una tabla con JS --></div>

    <!-- Ventana modal para el formulario de alta de productos -->
    
    <!-- Fin del modal formulario de alta de producto -->

    <!-- Modal alta de categoría -->
    <!-- El registro de categorías se realiza sin recargar por medio de fetch API y recargando la lista de selección de categorías en el formulario de alta de producto -->
    <div class="modal" id="modal__alta-categoria">
        <div class="modal__contenedor">
            <form action="post" class="formulario" id="formulario-alta-categoria">
                <h3>Agregar categoría</h3>
                <input class="campo" type="text" placeholder="Categoria" maxlength="100" id="categoria-txt" name="categoria-txt">
                <button class="boton-registrar boton" id="btnRegistrarCategoria">Agregar</button>
                <button class="boton" id="btnCerrarMiniModal"><div class="boton-interior-blanco">Cancelar</div></button>
            </form>
        </div>
    </div>
    <!-- Fin del modal formulario de alta de categoria -->

</section>

<!-- Scripts de JavaScript -->
<script type="module" src="vistas/js/paginas/inventario.js"></script>