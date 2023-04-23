<span class="formulario__encabezado">
    <img class="formulario__icono" src="vistas/img/file-invoice.svg" alt="Formulario">
    <h2>Formulario de edición para el producto: ${producto_id}</h2>
    <span class="alerta" id="alerta-formulario"></span>
</span>

<form class="formulario" action="post" id="formulario-edicion-producto">
    <!-- 1/2 -->
    <fieldset class="formulario__fieldset">
        <label for="idProducto-txt">Código o Folio:</label>
        <input type="text" class="campo requerido" placeholder="ID del producto" name="idProducto-txt" data-form="productoID" maxlength="20" pattern="^[a-zA-Z0-9]{1,20}$" required value="${producto_id}">

        <label for="nombreProducto-txt">Nombre del producto:</label>
        <input type="text" class="campo requerido" placeholder="Nombre" name="nombreProducto-txt" data-form='nombreProducto' maxlength="80" minlength="4" required value="${nombre}">

        <fieldset class="formulario__fieldset-categorias">
            <select class="campo" id="categoriaProducto-txt" name="categoriaProducto-txt" data-form="categoriaID" required></select>
            <button class="boton redondo" id="btnAgregarCategoria"><img class="icono" src="vistas/img/plus.svg" alt="Agregar"></button>
        </fieldset>

        <label for="descripcionProducto-txt">Descripción:</label>
        <textarea class="campo" placeholder="Descripción" rows="3" cols="50" name="descripcionProducto-txt" data-form="descripcion" maxlength="400"></textarea>

        <fieldset class="formulario__fieldset-2-columnas">
            <label for="unidadesProducto-txt">Unidades:</label>
            <input type="number" class="campo  requerido" placeholder="001" name="unidadesProducto-txt" data-form="unidades" min="1" maxlength="4" required value="${unidades}">

            <label for="unidadesMinimasProducto-txt">Unidades mínimas:</label>
            <input type="number" class="campo" placeholder="0" name="unidadesMinimasProducto-txt" data-form="unidadesMinimas" min="0" max="9999">
        </fieldset>
    </fieldset>

    <!-- 2/2 -->
    <fieldset class="formulario__fieldset">
        <fieldset class="formulario__fieldset-2-columnas">
            <label for="precioCompraProducto-txt">Precio de compra:</label>
            <input type="number" step="any" class="campo" placeholder="0.00" name="precioCompraProducto-txt" data-form="precioCompra" min="0" max="9999">

            <label for="precioVentaProducto-txt">Precio de venta:</label>
            <input type="number" step="any" class="campo  requerido" placeholder="0.00" name="precioVentaProducto-txt" data-form="precioVenta" min="0" max="9999" required value="${precio_venta}">

            <label for="precioMayoreoProducto-txt">Precio de venta al mayoreo:</label>
            <input type="number" step="any" class="campo" placeholder="0.00" name="precioMayoreoProducto-txt" data-form="precioMayoreo" min="0" max="9999">

            <label for="fechaCaducidad-txt">Fecha de caducidad</label>
            <input type="date" class="campo" placeholder="Fecha de caducidad" name="caducidadProducto-txt" data-form="caducidad" maxlength="8">
        </fieldset>

        <label for="imagenProducto-txt">URL de la foto:</label>
        <input type="text" class="campo" placeholder="direccion.jpg" name="imagenProducto-txt" data-form="imagenURL" maxlength="250" value="${foto_url}">

        Para retirar este producto del inventario de venta seleccione la opción 'Dar de baja' y después haga clic en 'Editar'.<br><br>
        Para reintegrarlo, seleccione la opción 'Activo' y después haga clic en 'Editar'.
        <fieldset class="formulario__fieldset-2-columnas">
            <label for="estadoProducto-txt">Activo</label>
            <input type="radio" id='estado-activo' name="estadoProducto-txt" value="1" data-form="estado" required>
            <label for="estadoProducto-txt">Dar de baja</label>
            <input type="radio" id='estado-inactivo' name="estadoProducto-txt" value="0" data-form="estado" required>
        </fieldset>

        <div class="formulario__botones-contenedor">
            <button class="boton-form enviar" id="btnEditarProducto">Editar</button>
            <button class="boton-form otro" id="btnCerrar">Cancelar</button>
        </div>
        <fieldset>
</form>