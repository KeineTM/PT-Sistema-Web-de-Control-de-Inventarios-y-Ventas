<?php
if(!isset($_GET['id'])) {
    echo '<p class="destacado">No se ha selecionado ningún folio...</p>';
    return;
}

$folio = $_GET['id'];
$consulta = ControladorProductos::ctrlLeerUno($folio);

$fecha_min = date("Y-m-d 00:00:00");
$fecha_max = date("Y-m-d", strtotime("+5 year", strtotime($fecha_min)));

$caducidad = ($consulta[0]['caducidad'] !== null) 
    ? date('Y-m-d', strtotime($consulta[0]['caducidad']))
    : null;

$lista_categorias = ControladorProductos::ctrlCategoriasActivas();
?>
<span class="formulario__encabezado">
    <img class="formulario__icono" src="vistas/img/file-invoice.svg" alt="Formulario">
    <h2>Formulario de edición para el producto: <?= $consulta[0]['producto_id'] ?></h2>
    <span class="" id="alerta-formulario"></span>
</span>

<form class="formulario" action="post" id="formulario-edicion-producto">
    <!-- 1/2 -->
    <fieldset class="formulario__fieldset">
        <label for="idProducto-txt">Código o Folio:</label>
        <input type="text" class="campo requerido mayusculas" placeholder="ID del producto" name="idProducto-txt" id="idProducto-txt" autocomplete="off" data-form="productoID" maxlength="20" pattern="^[a-zA-Z0-9]{1,20}$" required value="<?= $consulta[0]['producto_id'] ?>">
        
        <input type="hidden" name="idProductoOriginal-txt" value="<?= $consulta[0]['producto_id'] ?>" required>

        <label for="nombreProducto-txt">Nombre del producto:</label>
        <input type="text" class="campo requerido" placeholder="Nombre" name="nombreProducto-txt" id="nombreProducto-txt" autocomplete="off" data-form='nombreProducto' maxlength="80" minlength="4" required value="<?= $consulta[0]['nombre'] ?>">

        <fieldset class="formulario__fieldset-categorias">
            <select class="campo mayusculas" id="categoriaProducto-txt" name="categoriaProducto-txt" id="categoriaProducto-txt" data-form="categoriaID" autocomplete="off" required>
                <option disabled selected>SELECCIONE CATEGORÍA...</option>
                <?php
                foreach($lista_categorias as $categoria) { ?>
                <option class="mayusculas" value="<?= $categoria['categoria_id'] ?>" <?php if($consulta[0]['categoria_id'] === $categoria['categoria_id']) echo 'selected'?>>
                    <?= $categoria['categoria'] ?>
                </option>'
                <?php } ?>
            </select>
            <button class="boton redondo" id="btnAgregarCategoria"><img class="icono" src="vistas/img/plus.svg" alt="Agregar"></button>
        </fieldset>

        <label for="descripcionProducto-txt">Descripción:</label>
        <textarea class="campo" placeholder="Descripción" rows="3" cols="50" name="descripcionProducto-txt" id="descripcionProducto-txt" autocomplete="off" data-form="descripcion" maxlength="400"><?= $consulta[0]['descripcion'] ?></textarea>

        <fieldset class="formulario__fieldset-2-columnas">
            <label for="unidadesProducto-txt">Unidades:</label>
            <input type="number" class="campo  requerido" placeholder="001" name="unidadesProducto-txt" id="unidadesProducto-txt" autocomplete="off" data-form="unidades" min="1" maxlength="4" required value="<?= $consulta[0]['unidades'] ?>">

            <label for="unidadesMinimasProducto-txt">Unidades mínimas:</label>
            <input type="number" class="campo" placeholder="0" name="unidadesMinimasProducto-txt" id="unidadesMinimasProducto-txt" autocomplete="off" data-form="unidadesMinimas" min="0" max="9999" value="<?= $consulta[0]['unidades_minimas'] ?>">
        </fieldset>
    </fieldset>

    <!-- 2/2 -->
    <fieldset class="formulario__fieldset">
        <fieldset class="formulario__fieldset-2-columnas">
            <label for="precioCompraProducto-txt">Precio de compra:</label>
            <input type="number" step="any" class="campo" placeholder="0.00" name="precioCompraProducto-txt" id="precioCompraProducto-txt" autocomplete="off" data-form="precioCompra" min="0" max="9999" value="<?= $consulta[0]['precio_compra'] ?>">

            <label for="precioVentaProducto-txt">Precio de venta:</label>
            <input type="number" step="any" class="campo  requerido" placeholder="0.00" name="precioVentaProducto-txt" id="precioVentaProducto-txt" autocomplete="off" data-form="precioVenta" min="0" max="9999" required value="<?= $consulta[0]['precio_venta'] ?>">

            <label for="caducidadProducto-txt">Fecha de caducidad</label>
            <input type="date" class="campo" placeholder="Fecha de caducidad" name="caducidadProducto-txt" id="caducidadProducto-txt" autocomplete="off" data-form="caducidad" min="<?=$fecha_min = date("Y-m-d") ?>" max="<?=$fecha_max?>" maxlength="8" value="<?= $caducidad ?>">
        </fieldset>

        <p>Imagen actual:</p>
        <img class="miniatura" src="<?= $consulta[0]['foto_url'] ?>" alt="Imagen actual del producto">

        <label for="imagenProducto-txt">URL de la foto:</label>
        <input type="text" class="campo" placeholder="direccion.jpg" name="imagenProducto-txt" id="imagenProducto-txt" autocomplete="off" data-form="imagenURL" maxlength="250" value="<?= $consulta[0]['foto_url'] ?>">

        Para retirar este producto del inventario de venta seleccione la opción 'Dar de baja' y después haga clic en 'Editar'.<br><br>
        Para reintegrarlo, seleccione la opción 'Activo' y después haga clic en 'Editar'.
        <fieldset class="formulario__fieldset-2-columnas">
            <label>Activo</label>
            <input type="radio" id='estado-activo' name="estadoProducto-txt" value="1" data-form="estado" required <?php if($consulta[0]['estado'] === 1) echo 'checked' ?>>
            <label>Dar de baja</label>
            <input type="radio" id='estado-inactivo' name="estadoProducto-txt" value="0" data-form="estado" required <?php if($consulta[0]['estado'] !== 1) echo 'checked' ?>>
        </fieldset>

        <div class="formulario__botones-contenedor">
            <button class="boton-form enviar" id="btnEditarProducto">Editar</button>
        </div>
        <fieldset>
</form>