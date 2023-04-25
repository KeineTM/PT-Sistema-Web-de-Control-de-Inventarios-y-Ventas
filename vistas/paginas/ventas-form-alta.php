<?php
# Si no existe se crea una variable de sesión que incluye los datos de la venta
if (!isset($_SESSION['carrito'])) $_SESSION['carrito'] = [];
$descuento = 0;
$totalFinal = 0;

ControladorOperaciones::agregarAlCarrito();
ControladorOperaciones::sumarDelCarrito();
ControladorOperaciones::restarDelCarrito();
ControladorOperaciones::quitarDelCarrito();
ControladorOperaciones::btnVaciarCarrito();
ControladorOperaciones::ctrlCrearVenta();
?>
<span class="formulario__encabezado">
    <img class="formulario__icono" src="vistas/img/file-invoice.svg" alt="Formulario">
    <h2>Formulario de Venta</h2>
</span>

<!-- Mensaje de estado de la venta -->
<div id="alerta-formulario" class=<?php
    if (isset($_GET['estado'])) {
        if ($_GET['estado'] === 'creada') { ?> "alerta-verde">VENTA CREADA
    <?php } else
                if ($_GET['estado'] === 'cancelada') { ?>
        "alerta-roja">VENTA CANCELADA
    <?php } else
                if ($_GET['estado'] === 'maximo') { ?>
        "alerta-verde">Ya se agregó el máximo de unidades existentes
    <?php } else
                if ($_GET['estado'] === 'no-existe') { ?>
        "alerta-roja">El Código del producto no es válido o no existe
    <?php } else
                if ($_GET['estado'] === 'agotado') { ?>
        "alerta-roja">¡Producto Agotado!
    <?php } else
                if ($_GET['estado'] === 'eliminado') { ?>
        "alerta-roja">Se eliminó un producto del carrito
    <?php } else
                if ($_GET['estado'] === 'error-total') { ?>
        "alerta-roja">El total de la venta no puede ser 0
    <?php } else
                if ($_GET['estado'] === 'error-descuento') { ?>
        "alerta-roja">El descuento no es válido
    <?php } else
                if ($_GET['estado'] === 'error-carrito') { ?>
        "alerta-roja">El carrito está vacio
    <?php }
    } else {
        echo 'hidden >';
    }
    ?>
</div>
<!-- -------------------------------------------- -->

<!-- Formulario para agregar productos al carrito -->
<form class="formulario destacado" method="post" id="form-agregar-producto">
    <label for="idProducto-txt">Código del producto:</label>
    <input class="campo destacado" autocomplete="off" type="text" name="idProducto-txt" id="idProducto-txt" maxlength="20" pattern="^[a-zA-Z0-9]{1,20}$" required>
    <button type="submit" class="boton-form enviar" id="btnAgregarAlCarrito">Agregar</button>
</form>
<br>
<!-- -------------------------------------------- -->

<!-- Lista de productos agregados -->
<section id="productos-agregados">
    <?php
    # Por cada producto ingresado en el carrito, crea una tarjeta y suma el total a la venta
    foreach ($_SESSION['carrito'] as $indice => $producto) {
        # Calcula el total sumando el precio acumulado de cada producto incluído
        $totalFinal += $producto->total;
    ?>
        <div class="tarjeta-venta">
            <h3 class="destacado"><?= $producto->nombre ?></h3>
            <div>
                <span>
                    <img src="<?= $producto->foto_url ?>" alt="Imagen <?= $producto->nombre ?>">
                    <ul>
                        <li>Folio: <span> <?= $producto->producto_id ?></span></li>
                        <li>Categoría: <span> <?= $producto->categoria_id ?></span></li>
                        <li>Existencias: <span> <?= $producto->unidades ?></span></li>
                        <li>Caducidad: <span> <?= $producto->caducidad ?></span></li>
                        <li>Precio unitario: <span> $<?= number_format($producto->precioVenta, 2) ?></span></li>
                        <li>Subtotal: <span> $<?= number_format($producto->total, 2) ?></span></li>
                    </ul>
                </span>
                <span class="contenedor-boton">
                    <a class="rojo redondo" data-quitar="<?= $indice ?>" href="index.php?pagina=ventas&opciones=alta&restar=<?= $indice ?>">-</a>
                    Cantidad: <p class="destacado"><?= $producto->cantidad ?></p>
                    <a class="verde redondo" data-agregar="<?= $indice ?>" href="index.php?pagina=ventas&opciones=alta&sumar=<?= $indice ?>">+</a>
                </span>
            </div>
        </div>
    <?php } ?>
</section>
<!-- -------------------------------------------- -->

<!-- Formulario de registro de la venta -->
<form method="post" id="form-alta">
    <fieldset class="resumen-operacion">
        <!-- TOTAL FINAL -->
        <h3 class="destacado-mas">Total: $
        <?php 
            $totalFinal -= $descuento;

            if($totalFinal >= 0)
                echo number_format($totalFinal, 2);
            else
                echo 'Error: Cancele e intente una nueva venta';
        ?>
        </h3>
        <!-- ----------- -->
        <fieldset class="contenedor-campos-operaciones">
            <label for="descuento-txt">Descuento:</label>
            <input class="campo" autocomplete="off" type="number" placeholder="0.00" name="descuento-txt" step="any" min="0" max="9999">
            
            <label for="metodo-pago-txt">Método de pago:</label>
            <select class="campo" name="metodo-pago-txt" required>
                <option value="1" selected>Efectivo</option>
                <option value="2">Transferencia</option>
            </select>

            <label for="notas-txt">Notas:</label>
            <textarea class="campo" name="notas-txt" cols="20" rows="1" maxlength="250"></textarea>

            <input name="total-txt" type="hidden" value="<?= $totalFinal; ?>">
        </fieldset>
    </fieldset>
    
    <br>
    <div class="formulario__botones-contenedor">
        <button type="submit" class="boton-form enviar" id="btnRegistrar">Terminar Venta</button>
        <span></span>
        <a class="boton-form otro" id="btnCancelar" href="index.php?pagina=ventas&opciones=alta&vaciar=true>Cancelar">Cancelar</a>
    </div>
</form>