<?php
if(!isset($_GET['folio'])) {
    echo '<p class="destacado">No se ha selecionado ningún folio...</p>';
    return;
}

$folio = $_GET['folio'];
$tipo_operacion = 'ventas';

# Recuperación de datos
$consulta = ControladorOperaciones::ctrlLeer($folio);

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

<section class="detalles-ticket">
    <fieldset class="fieldset__envoltura">
        <legend>Productos incluidos</legend>
        <?php foreach ($consulta as $fila) { ?>
            <fieldset class="fieldset__envoltura" id="<?= $fila['producto_id'] ?>">
                <legend><?= $fila['nombre'] ?></legend>
                <ul class="formulario__fieldset-2-columnas">
                    <li>Código: <?= $fila['producto_id'] ?></li>
                    <li>Cantidad: <?= $fila['unidades'] ?></li>
                    <li>Precio: $<?= $fila['precio_venta'] ?></li>
                    <li>Total: $<?= $fila['total_acumulado'] ?></li>
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

        <span>Método de pago</span>
        <span><?= $consulta[0]['metodo'] ?></span>

        <span>Fecha y hora</span>
        <span><?= $consulta[0]['fecha'] ?></span>

        <span>Tipo de operación</span>
        <span><?php 
        if($consulta[0]['tipo_operacion'] === 'VE') echo 'Venta';
        if($consulta[0]['tipo_operacion'] === 'AP') echo 'Apartado';
        if($consulta[0]['tipo_operacion'] === 'DE') echo 'Devolución'; 
        ?></span>

        <?php if(isset($consulta[0]['cliente_id'])) { ?>
        <span>Cliente</span>
        <span><?= $consulta[0]['nombre_completo_cliente'] ?></span>
        <?php } ?>

        <span>Empleado</span>
        <span><?= $consulta[0]['nombre_completo'] ?></span>

        <span>Empleado ID</span>
        <span><?= $consulta[0]['empleado_id'] ?></span>
        
    </fieldset>
</section>
<?php if($_SESSION['tipoUsuarioSesion'] === 'Administrador') { ?>
    <form method="post" id="formulario-eliminar-operacion">
        <input name="folio-txt" type="hidden" value="<?=$folio?>" required readonly>
        <button type="submit" class="boton-form otro" id="btnEliminar">Eliminar Ticket</button>
    </form>
<?php } ?>