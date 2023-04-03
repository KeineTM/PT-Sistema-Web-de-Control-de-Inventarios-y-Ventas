<section class="main-contenedor">
    <h2>Inventario</h2>
    <!-- Lista de opciones -->
    <ul class="inventario-lista">
        <li class="inventario-lista__opcion"><a class="boton-main" id="abrir__alta-inventario">Formulario de Alta</a></li>
        <li class="inventario-lista__opcion"><a class="boton-main">Tabla de Productos</a></li>
        <li class="inventario-lista__opcion"><a class="boton-main">Vista de Catálogo</a></li>
    </ul>

    <!-- Ventana modal para el formulario de alta de productos -->
    <div class="modal" id="modal__alta-inventario">
        <div class="modal__contenedor">
            <img class="modal__icono" src="vistas/img/file-invoice.svg" alt="Formulario">
            <h2>Formulario de alta de producto</h2>
            <p>Los campos marcados con * son necesarios.</p>
            <form class="formulario" action="post" id="formulario-alta-producto">
                <input type="text" class="campo requerido" placeholder="ID del producto" maxlength="20" id="idProducto-txt" name="idProducto-txt">
                <input type="text" class="campo requerido" placeholder="Nombre" maxlength="80" id="nombreProducto-txt" name="nombreProducto-txt">
                <fieldset class="formulario__fieldset">
                    <select class="campo" splaceholder="seleccionar" id="categoriaProducto-txt" name="categoriaProducto-txt">
                        <option disabled selected>Categorías...</option>
                        <!-- Por medio de la interfaz fetch API de Javascript se cargan las categorías -->
                    </select>
                    <button class="boton redondo" id="btnAgregarCategoria"><img class="icono" src="vistas/img/plus.svg" alt="Agregar"></button>
                </fieldset>
                <textarea class="campo" placeholder="Descripción" rows="3" cols="50" maxlength="500" id="descripcionProducto-txt" name="descripcionProducto-txt"></textarea>
                <input type="number" class="campo  requerido" placeholder="Unidades" id="unidadesProducto-txt" name="unidadesProducto-txt">
                <input type="number" class="campo" placeholder="Unidades mínimas" id="unidadesMinimasProducto-txt" name="unidadesMinimasProducto-txt">
                <input type="number" step="any" class="campo" placeholder="Precio de compra" id="precioCompraProducto-txt" name="precioCompraProducto-txt">
                <input type="number" step="any" class="campo  requerido" placeholder="Precio de venta" id="precioVentaProducto-txt" name="precioVentaProducto-txt">
                <input type="number" step="any" class="campo" placeholder="Precio de mayoreo" id="precioMayoreoProducto-txt" name="precioMayoreoProducto-txt">
                <label for="fechaCaducidad-txt">Fecha de caducidad</label>
                <input type="date" class="campo" placeholder="Fecha de caducidad" id="caducidadProducto-txt" name="caducidadProducto-txt">
                <label for="imagenProducto-txt">URL de la foto</label>
                <input type="text" class="campo" placeholder="Imagen" maxlength="250" id="imagenProducto-txt" name="imagenProducto-txt">
                
                <button class="boton-registrar boton" id="btnRegistrarProducto">Registrar</button>
                <div class="formulario__botones-contenedor">
                    <button class="boton" type="reset"><div class="boton-interior-blanco">Limpiar</div></button>
                    <button class="boton" id="btnCerrarModal"><div class="boton-interior-blanco">Cancelar</div></button>
                </div>
            </form>
            
        </div>
    </div> 
    <!-- Fin del modal formulario de alta de producto -->

    <!-- Modal alta de categoría -->
    <!-- El registro de categorías se realiza sin recargar por medio de fetch API y recargando la lista de selección de categorías en el formulario de alta de producto -->
    <div class="modal" id="modal__alta-categoria">
        <div class="modal__contenedor">
            <form action="post" class="formulario" id="formulario-alta-categoria">
                <h3>Agregar categoría</h3>
                <input class="campo" type="text" placeholder="Categoria" id="categoria-txt" name="categoria-txt">
                <button class="boton-registrar boton" id="btnRegistrarCategoria">Agregar</button>
                <button class="boton" id="btnCerrarMiniModal"><div class="boton-interior-blanco">Cancelar</div></button>
            </form>
        </div>
    </div>
    <!-- Fin del modal formulario de alta de categoria -->

</section>

<!-- Scripts de JavaScript -->

<script src="vistas/js/paginas/inventario-listar-categorias-asincrono.js"></script>
<script type="module" src="vistas/js/paginas/inventario.js"></script>