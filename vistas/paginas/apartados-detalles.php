<?php
if(!isset($_GET['folio'])) {
    echo '<p class="destacado">No se ha selecionado ningún folio...</p>';
    return;
}

$folio = $_GET['folio'];
$tipo_operacion = 'apartados';
$total_abonado = 0;

# Recuperación de datos
$consulta = ControladorOperaciones::ctrlLeer($folio);
$consulta_abonos = ControladorOperaciones::ctrlLeerAbonos($folio);

# Valida el resultado de la consulta
# Si no es una lista es porque retornó un error
# Si es una lista vacía es porque no encontró coincidencias
if (!is_array($consulta) || sizeof($consulta) === 0) {
    echo '<p class="alerta-roja">Ocurrió un error: El folio no existe o hay un problema con la base de datos</p>';
    die();
}

ControladorOperaciones::ctrlEliminar($tipo_operacion);
?>
<span class="formulario__encabezado">
    <img class="formulario__icono" src="vistas/img/file-invoice.svg">
    <h2>Detalles del Folio: <?= $folio ?></h2>
</span>
<p>Puede hacer clic sobre el nombre del cliente para ver sus detalles.</p>
<br>
<section class="detalles-ticket">
    <fieldset class="fieldset__envoltura">
        <legend>Productos incluidos</legend>
        <?php foreach ($consulta as $fila) { ?>
            <fieldset class="fieldset__envoltura" id="<?= $fila['producto_id'] ?>">
                <legend class="resaltado"><?= $fila['nombre'] ?></legend>
                <ul class="formulario__fieldset-2-columnas">
                    <li>Código: <?= $fila['producto_id'] ?></li>
                    <li>Cantidad: <?= $fila['unidades'] ?></li>
                    <li>Precio: $<?= $fila['precio_venta'] ?></li>
                    <li>Total: $<?= $fila['total_acumulado'] ?></li>
                </ul>
            </fieldset>
        <?php } ?>
    </fieldset>

    <fieldset class="fieldset__envoltura">
        <legend>Lista de Abonos</legend>
        <?php for($i = 0; $i < count($consulta_abonos); $i++) { 
            $total_abonado += $consulta_abonos[$i]['abono'] #SUMA DE ABONOS ?>
            <fieldset class="fieldset__envoltura">
                <legend class="resaltado">Abono # <?= $i+1 ?></legend>
                <ul>
                    <li>Fecha: <?= $consulta_abonos[$i]['fecha'] ?></li>
                    <li>Monto abonado: $<?= $consulta_abonos[$i]['abono'] ?></li>
                    <li>Método de pago: <?= $consulta_abonos[$i]['metodo'] ?></li>
                    <li>Empleado: <?= $consulta_abonos[$i]['nombre_completo'] ?></li>
                </ul>
            </fieldset>
        <?php } ?>
    </fieldset>

    <fieldset class="fieldset__envoltura formulario__fieldset-2-columnas">
        <legend>Datos de facturación</legend>

        <span>Subtotal</span>
        <span>$<?= $consulta[0]['subtotal'] ?></span>

        <span>Descuento</span>
        <span>$<?= ($consulta[0]['descuento']) ? $consulta[0]['descuento'] : number_format(0, 2) ?></span>

        <span>Total</span>
        <span>$<?= $consulta[0]['total'] ?></span>

        <span>Notas</span>
        <span><?= $consulta[0]['notas'] ?></span>

        <span>Tipo de operación</span>
        <span><?php 
        if($consulta[0]['tipo_operacion'] === 'VE') echo 'Venta';
        if($consulta[0]['tipo_operacion'] === 'AP') echo 'Apartado';
        if($consulta[0]['tipo_operacion'] === 'DE') echo 'Devolución'; 
        ?></span>

        <span>Estado</span>
        <span class="resaltado"><?php echo ($consulta[0]['estado']) ?'Pagado' :'Pendiente'  ?></span>

        <span>Cliente</span>
        <a class="texto-rosa" href="index.php?pagina=directorio&opciones=detalles&id=<?=$consulta_abonos[0]['contacto_id']?>" ><?= $consulta_abonos[0]['nombre_completo_cliente'] ?></a>

        <p class="destacado resaltado">Total abonado: </p>
        <p class="destacado resaltado">$<?= $total_abonado #RESULTADO DE LOS ABONOS ?></p>
        
        <?php if(!$consulta[0]['estado']) { # Si el estado es 0 = Pendiente ?>
            <p class="destacado resaltado">Saldo pendiente:</p>
            <p class="destacado resaltado">$<?= $consulta[0]['total'] - $total_abonado ?></p>
        <?php } ?>
        
    </fieldset>
</section>

<h2>Abonar al apartado:</h2>
<span class="texto-rosa" id="error-abono"></span>

<?php if(!$consulta[0]['estado']) { ?>
    <form method="post" class="formulario" id="formulario-abonar">
        <input name="folio-txt" type="hidden" value="<?=$folio?>" required readonly>
        <input name="saldo_pendiente" type="hidden" id="saldo_pendiente" value='<?= $consulta[0]['total'] - $total_abonado ?>'>
        
        <label for="abono_nuevo-txt">Monto a abonar $:</label>
        <input type="text" class="campo destacado requerido" autocomplete="off" type="number" placeholder="0.00" name="abono_nuevo-txt" step="any" min="1" max="9999" required  data-form="abono_nuevo">

        <label for="restante">Restante $:</label>
        <input type="text" class="sin-borde" name="restante" id="restante" disabled>

        <button type="submit" class="boton-form enviar" id="btnAbonar">Abonar al Apartado</button>
    </form>
<?php } ?>

<form method="post" id="formulario-eliminar-operacion">
    <input name="folio-txt" type="hidden" value="<?=$folio?>" required readonly>
    <button type="submit" class="boton-form otro" id="btnEliminar">Cancelar Apartado</button>
</form>
