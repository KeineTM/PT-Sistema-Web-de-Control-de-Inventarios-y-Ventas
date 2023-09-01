<?php
$fecha_min = date("Y-m-d");
$fecha_max = date("Y-m-d", strtotime("+5 year", strtotime($fecha_min)));
$lista_categorias = ControladorProductos::ctrlCategoriasActivas();
?>
<span class="formulario__encabezado">
    <img class="formulario__icono" src="vistas/img/file-invoice.svg" alt="Formulario">
    <h2>Formulario de alta de producto</h2>
    <span class="" id="alerta-formulario"></span>
</span>

<form class="formulario" action="post" id="formulario-alta-producto">
    <!-- 1/2 -->
    <fieldset class="formulario__fieldset">
        <label for="idProducto-txt">Código o Folio:</label>
        <input type="text" class="campo requerido mayusculas" placeholder="ABC12345678910" name="idProducto-txt" id="idProducto-txt" autocomplete="off" data-form="productoID" maxlength="20" pattern="^[a-zA-Z0-9]{1,20}$" autofocus required>
        <span class="texto-rosa" id="alerta-valida_ID"></span>

        <label for="nombreProducto-txt">Nombre del producto:</label>
        <input type="text" class="campo requerido" placeholder="Nombre" name="nombreProducto-txt" id="nombreProducto-txt" autocomplete="off" data-form='nombreProducto' maxlength="80" minlength="4" required>

        <fieldset class="formulario__fieldset-categorias">
            <select class="campo mayusculas" id="categoriaProducto-txt" name="categoriaProducto-txt" autocomplete="off" data-form="categoriaID" required>
                <option disabled selected>SELECCIONE CATEGORÍA...</option>
                <?php
                foreach($lista_categorias as $categoria) { ?>
                <option class="mayusculas" value="<?= $categoria['categoria_id'] ?>">
                    <?= $categoria['categoria'] ?>
                </option>'
                <?php } ?>
            </select>
            <button class="boton redondo" id="btnAgregarCategoria"><img class="icono" src="vistas/img/plus.svg" alt="Agregar"></button>
        </fieldset>

        <label for="descripcionProducto-txt">Descripción:</label>
        <textarea class="campo" placeholder="Descripción" rows="3" cols="50" name="descripcionProducto-txt" id="descripcionProducto-txt" autocomplete="off" data-form="descripcion" maxlength="400"></textarea>

        <fieldset class="formulario__fieldset-2-columnas">
            <label for="unidadesProducto-txt">Unidades:</label>
            <input type="number" class="campo  requerido" placeholder="001" name="unidadesProducto-txt" id="unidadesProducto-txt" autocomplete="off" data-form="unidades" min="1" maxlength="4" required>

            <label for="unidadesMinimasProducto-txt">Unidades mínimas:</label>
            <input type="number" class="campo" placeholder="0" name="unidadesMinimasProducto-txt" id="unidadesMinimasProducto-txt" autocomplete="off" data-form="unidadesMinimas" min="0" max="9999">
        </fieldset>
    </fieldset>

    <!-- 2/2 -->
    <fieldset class="formulario__fieldset">
        <fieldset class="formulario__fieldset-2-columnas">
            <label for="precioCompraProducto-txt">Precio de compra:</label>
            <input type="number" step="any" class="campo" placeholder="0.00" name="precioCompraProducto-txt" id="precioCompraProducto-txt" autocomplete="off" data-form="precioCompra" min="0" max="9999">

            <label for="precioVentaProducto-txt">Precio de venta:</label>
            <input type="number" step="any" class="campo  requerido" placeholder="0.00" name="precioVentaProducto-txt" id="precioVentaProducto-txt" autocomplete="off" data-form="precioVenta" min="0" max="9999" required>

            <label for="caducidadProducto-txt">Fecha de caducidad</label>
            <input type="date" class="campo" placeholder="Fecha de caducidad" name="caducidadProducto-txt" id="caducidadProducto-txt" autocomplete="off" data-form="caducidad" min="<?= $fecha_min ?>" max="<?= $fecha_max ?>" maxlength="8">
        </fieldset>

        <label for="imagenProducto-txt">URL de la foto:</label>
        <input type="text" class="campo" placeholder="direccion.jpg" name="imagenProducto-txt" id="imagenProducto-txt" autocomplete="off" data-form="imagenURL" maxlength="250" pattern="^[^\s]{0,250}\.(jpg|JPG|png|PNG|jpeg|JPEG|webp|WEBP)$">

        <div class="formulario__botones-contenedor">
            <button type="submit" class="boton-form enviar" id="btnRegistrarProducto">Registrar</button>
            <button class="boton-form otro" type="reset">Limpiar</button>
        </div>
    <fieldset>
</form>