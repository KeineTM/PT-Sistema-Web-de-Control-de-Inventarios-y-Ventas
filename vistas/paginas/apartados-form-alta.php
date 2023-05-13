<?php
# Si no existe se crea una variable de sesión que incluye los datos de la operación
if (!isset($_SESSION['carrito-apartado-' . $_SESSION['idUsuarioSesion']])) $_SESSION['carrito-apartado-' . $_SESSION['idUsuarioSesion']] = [];
$nombre_carrito = 'carrito-apartado-' . $_SESSION['idUsuarioSesion'];
$tipo_operacion = 'apartados';
$descuento = 0;
$totalFinal = 0;

ControladorOperaciones::agregarAlCarrito($nombre_carrito, $tipo_operacion);
ControladorOperaciones::sumarDelCarrito($nombre_carrito, $tipo_operacion);
ControladorOperaciones::restarDelCarrito($nombre_carrito, $tipo_operacion);
ControladorOperaciones::quitarDelCarrito($nombre_carrito, $tipo_operacion);
ControladorOperaciones::btnVaciarCarrito($nombre_carrito, $tipo_operacion);
# PONER CREACIÖN DE APARTADO
?>
<span class="formulario__encabezado">
    <img class="formulario__icono" src="vistas/img/file-invoice.svg" alt="Formulario">
    <h2>Formulario de Apartado</h2>
</span>

<!-- Mensaje de estado de la operación -->
<div id="alerta-formulario" class=<?php
    if (isset($_GET['estado'])) {
        if ($_GET['estado'] === 'creada') { ?> "alerta-verde">Apartado Creado
<?php } else if ($_GET['estado'] === 'cancelada') { ?>
    "alerta-roja">Apartado Cancelado
<?php } else if ($_GET['estado'] === 'maximo') { ?>
    "alerta-verde">Ya se agregó el máximo de unidades existentes
<?php } else if ($_GET['estado'] === 'no-existe') { ?>
    "alerta-roja">El Código del producto no es válido o no existe
<?php } else if ($_GET['estado'] === 'agotado') { ?>
    "alerta-roja">¡Producto Agotado!
<?php } else if ($_GET['estado'] === 'eliminado') { ?>
    "alerta-roja">Se eliminó un producto del carrito
<?php } else if ($_GET['estado'] === 'error-total') { ?>
    "alerta-roja">El total del apartado no puede ser 0
<?php } else if ($_GET['estado'] === 'error') { ?>
    "alerta-roja">Ocurrió un error, cancele y vuelva a intentarlo
<?php } else if ($_GET['estado'] === 'error-abono') { ?>
    "alerta-roja">El abono no es válido
<?php } else if ($_GET['estado'] === 'error-carrito') { ?>
    "alerta-roja">El carrito está vacio
<?php } else if ($_GET['estado'] === 'incompleto') { ?>
    "alerta-roja">Algunos datos están incompletos o no son válidos
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
                        <li>Caducidad: <span> <?= ($producto->caducidad) ?$producto->caducidad : 'No aplica' ?></span></li>
                        <li>Precio unitario: <span> $<?= number_format($producto->precioVenta, 2) ?></span></li>
                        <li>Subtotal: <span> $<?= number_format($producto->total, 2) ?></span></li>
                    </ul>
                </span>
                <span class="contenedor-boton">
                    <a class="rojo redondo" data-restar="<?= $indice ?>" href="index.php?pagina=apartados&opciones=alta&restar=<?= $indice ?>">-</a>
                    <span class="destacado texto-centrado"><?= $producto->cantidad ?>
                    <a class="verde redondo" data-agregar="<?= $indice ?>" href="index.php?pagina=apartados&opciones=alta&sumar=<?= $indice ?>">+</a>
                    <a class="gris redondo" data-quitar="<?= $indice ?>" href="index.php?pagina=apartados&opciones=alta&quitar=<?= $indice ?>"><img id="icono-quitar" src="vistas/img/trash-can.svg" alt="Quitar"></a>
                </span>
            </div>
        </div>
    <?php } ?>
</section>
<!-- -------------------------------------------- -->

<!-- Formulario de registro de la operación -->
<form method="post" id="form-alta">
    <fieldset class="fieldset__envoltura form__operaciones">
        <fieldset class="contenedor-campos-operaciones">
            <!-- TOTAL FINAL -->
            <h3 class="destacado-mas">Total: </h3>
            <h3 class="destacado-mas">$
                <?php
                $totalFinal -= $descuento;

                if ($totalFinal >= 0)
                    echo number_format($totalFinal, 2);
                else
                    echo 'Error: Cancele e intente crear un nuevo apartado';
                ?>
            </h3>
            <!-- ----------- -->

            <label for="abono-txt" class="destacado">Abono:</label>
            <input class="campo destacado requerido" autocomplete="off" type="number" placeholder="0.00" name="abono-txt" step="any" min="1" max="9999" required  data-form="abono">

            <label for="restante-txt" class="destacado">Restante:</label>
            <input class="campo no-borde" autocomplete="off" type="number" name="restante-txt" step="any" min="1" max="9999" disabled  data-form="restante">
        </fieldset>
        
        <fieldset class="contenedor-campos-operaciones">
            <label for="metodo-pago-txt">Método de pago:</label>
            <select class="campo" name="metodo-pago-txt" required  data-form="metodo-pago">
                <option value="1" selected>Efectivo</option>
                <option value="2">Transferencia</option>
            </select>

            <label for="notas-txt">Nombre del cliente:</label>
            <textarea class="campo mayusculas requerido" autocomplete="off" name="notas-txt" cols="20" rows="2" maxlength="250" data-form="notas" required></textarea>

            <input name="total-txt" type="hidden" value="<?= $totalFinal; ?>"  data-form="total">
        </fieldset>
    </fieldset>

    <!--<fieldset class="fieldset__envoltura formulario__fieldset-2-columnas">
        <legend>Datos del cliente</legend>
        
        <label for="cliente_nombre-txt">Nombre:</label>
        <input class="campo" type="text" name="cliente_nombre-txt" autocomplete="off" minlength="3" maxlength="80" required>

        <label for="cliente_apellido1-txt">Apellido 1:</label>
        <input class="campo" type="text" name="cliente_apellido1-txt" autocomplete="off" minlength="3" maxlength="80" required>

        <label for="cliente_apellido2-txt">Apellido 2:</label>
        <input class="campo" type="text" name="cliente_apellido2-txt" autocomplete="off" minlength="3" maxlength="80">

        <label for="cliente-telefono-txt">Teléfono:</label>
        <input class="campo" type="tel" name="cliente-telefono-txt" autocomplete="off" maxlength="10" pattern="[0-9]{10}">
    </fieldset>-->

    <br>
    <div class="formulario__botones-contenedor">
        <button type="submit" class="boton-form enviar" id="btnRegistrar">Terminar Apartado</button>
        <span></span>
        <a class="boton-form otro" id="btnCancelar" href="index.php?pagina=apartados&opciones=alta&vaciar=true">Cancelar</a>
    </div>
</form>