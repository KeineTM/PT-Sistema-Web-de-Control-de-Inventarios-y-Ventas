<?php
# Si no existe se crea una variable de sesión que incluye los datos de la operación
if (!isset($_SESSION['carrito-devolucion-'.$_SESSION['idUsuarioSesion']])) $_SESSION['carrito-devolucion-'.$_SESSION['idUsuarioSesion']] = [];
$nombre_carrito = 'carrito-devolucion-'.$_SESSION['idUsuarioSesion'];
$tipo_operacion = 'devoluciones';
$totalFinal = 0;

ControladorOperaciones::agregarAlCarrito($nombre_carrito, $tipo_operacion);
ControladorOperaciones::sumarDelCarrito($nombre_carrito, $tipo_operacion);
ControladorOperaciones::restarDelCarrito($nombre_carrito, $tipo_operacion);
ControladorOperaciones::quitarDelCarrito($nombre_carrito, $tipo_operacion);
ControladorOperaciones::btnVaciarCarrito($nombre_carrito, $tipo_operacion);
ControladorOperaciones::ctrlCrearDevolucion($nombre_carrito, $tipo_operacion);
?>    
<span class="destacado">
    <img class="formulario__icono" src="vistas/img/circle-exclamation.svg" alt="Alerta">
    Sólo se aceptan devoluciones hasta 1 día después de la compra
</span>
<span class="formulario__encabezado">
    <img class="formulario__icono" src="vistas/img/file-invoice.svg" alt="Formulario">
    <h2>Formulario de Devolución</h2>
</span>
<!-- Mensaje de estado de la operación -->
<div id="alerta-formulario" class=<?php
    if (isset($_GET['estado'])) {
        if ($_GET['estado'] === 'creada') { ?> "alerta-verde">Devolución Creada
    <?php } else
                if ($_GET['estado'] === 'cancelada') { ?>
        "alerta-roja">Devolución Cancelada
    <?php } else
                if ($_GET['estado'] === 'no-existe') { ?>
        "alerta-roja">El Código del producto no es válido o no existe
    <?php } else
                if ($_GET['estado'] === 'eliminado') { ?>
        "alerta-roja">Se eliminó un producto del carrito
    <?php } else
                if ($_GET['estado'] === 'error-total') { ?>
        "alerta-roja">El total de la devolución no puede ser 0
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
    <label for="idProducto-txt">Código de barras:</label>
    <div class="una-linea">
        <img class="formulario__icono" src="/vistas/img/barcode.svg" alt="Código-ícono">
        <input class="campo destacado" autocomplete="off" type="text" name="idProducto-txt" id="idProducto-txt" maxlength="20" pattern="^[a-zA-Z0-9]{1,20}$" autofocus required>    
    </div>
    <button type="submit" class="boton-form enviar" id="btnAgregarAlCarrito">+</button>
</form>
<br>
<!-- -------------------------------------------- -->

<!-- Lista de productos agregados -->
<section id="productos-agregados">
    <?php
    # Por cada producto ingresado en el carrito, crea una tarjeta y suma el total a la operación
    foreach ($_SESSION[$nombre_carrito] as $indice => $producto) {
        # Calcula el total sumando el precio acumulado de cada producto incluído
        $totalFinal += $producto->total;
    ?>
        <div class="tarjeta-operacion">
            <h3 class="destacado"><?= $producto->nombre ?></h3>
            <div>
                <span>
                    <img src="<?= $producto->foto_url ?>" alt="Imagen <?= $producto->nombre ?>">
                    <ul>
                        <li>Folio: <span> <?= $producto->producto_id ?></span></li>
                        <li>Categoría: <span> <?= $producto->categoria_id ?></span></li>
                        <li>Existencias: <span> <?= $producto->unidades ?></span></li>
                        <li>Precio unitario: <span> $<?= number_format($producto->precioVenta, 2) ?></span></li>
                        <li>Subtotal: <span> $<?= number_format($producto->total, 2) ?></span></li>
                    </ul>
                </span>
                <span class="contenedor-boton">
                    <a class="rojo redondo" data-restar="<?=$indice?>" href="index.php?pagina=devoluciones&opciones=alta&restar=<?=$indice?>">-</a>
                    Cantidad: <p class="destacado"><?= $producto->cantidad ?></p>
                    <a class="verde redondo" data-agregar="<?=$indice?>" href="index.php?pagina=devoluciones&opciones=alta&sumar=<?=$indice?>">+</a>
                    <a class="gris redondo" data-quitar="<?=$indice?>"  href="index.php?pagina=devoluciones&opciones=alta&quitar=<?=$indice?>"><img id="icono-quitar" src="vistas/img/trash-can.svg" alt="Quitar"></a>
                </span>
            </div>
        </div>
    <?php } ?>
</section>
<!-- -------------------------------------------- -->

<!-- Formulario de registro de la operación -->
<form method="post" id="form-devolucion">
    <fieldset class="fieldset__envoltura form__operaciones">
        <fieldset class="contenedor-campos-operaciones">
            <!-- TOTAL FINAL -->
            <h3 class="destacado-mas">Total a devolver:</h3>
            <h3 class="destacado-mas" id="lbl-total">$
                <?php
                if ($totalFinal >= 0)
                    echo number_format($totalFinal, 2);
                else
                    echo 'Error: Cancele e intente crear una nueva devolución';
                ?>
            </h3>
            <!-- ----------- -->
        </fieldset>
        
        <fieldset class="contenedor-campos-operaciones">
            <label for="metodo-pago-txt">Método de pago:</label>
            <select class="campo" name="metodo-pago-txt" id="metodo-pago-txt" required  data-form="metodo-pago">
                <option value="1" selected>Efectivo</option>
                <option value="2">Transferencia</option>
            </select>

            <label for="notas-txt">Motivo:</label>
            <textarea class="campo" autocomplete="off" name="notas-txt" id="notas-txt" cols="20" rows="2" maxlength="250"  data-form="notas" required placeholder="Razón de devolución..."></textarea>

            <input name="total-txt" type="hidden" value="<?= $totalFinal; ?>"  data-form="total">
        </fieldset>
    </fieldset>
    
    <br>
    <div class="formulario__botones-contenedor">
        <button type="submit" class="boton-form enviar" id="btnRegistrar">Crear Devolución</button>
        <span></span>
        <a class="boton-form otro" id="btnCancelar" href="index.php?pagina=devoluciones&opciones=alta&vaciar=true">Cancelar</a>
    </div>
</form>