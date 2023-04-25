<?php
if (!isset($_GET['folio'])) {
    echo '<p class="destacado">No se ha selecionado ningún folio...</p>';
    return;
}

$folio = $_GET['folio'];

# Recuperación de datos
$consulta = ControladorOperaciones::ctrlLeer($folio);

# Valida el resultado de la consulta
# Si no es una lista es porque retornó un error
# Si es una lista vacía es porque no encontró coincidencias
if (!is_array($consulta) || sizeof($consulta) === 0) {
    echo '<p class="alerta-roja">Ocurrió un error: El folio no existe o hay un problema con la base de datos</p>';
    die();
}

?>

<span class="formulario__encabezado">
    <img class="formulario__icono" src="vistas/img/file-invoice.svg">
    <h2>Formulario de Edición: <?= $folio ?></h2>
    <span class="alerta" id="alerta-edicion-categoria"></span>
</span>

<form action="post" class="formulario" id="formulario-edicion-venta">
    <label class="destacado" for="operacion_id-txt">Folio:</label>
    <input class="no-focus destacado" type="number" step="any" value="<?= $consulta[0]['operacion_id'] ?>" name="operacion_id-txt" data-form="folio" required readonly>

    <fieldset class="fieldset__envoltura">
        <legend>Productos incluidos</legend>
        <?php foreach ($consulta as $fila) { ?>
            <fieldset class="fieldset__envoltura formulario__fieldset-2-columnas">
                <legend><?=$fila['nombre']?></legend>
                <p>Código: <?=$fila['producto_id']?></p>
                <p>Cantidad: <?=$fila['unidades']?></p>
                <p>Precio: $ <?=$fila['precio_venta']?></p>
                <p>Total: $ <?=$fila['total_acumulado']?></p>
            </fieldset>
            <!--<input class="campo" type="text" name="producto_id-txt" autocomplete="off" data-form="producto_id-txt">-->
        <?php } ?>
    </fieldset>

    <fieldset>
        <label for="subtotal-txt">Subtotal:</label>
        <input class="campo" type="text" name="subtotal-txt" autocomplete="off" value="<?= $consulta[0]['subtotal'] ?>" data-form="subtotal" min='0' max='99999' required>

        <label for="descuento-txt">Descuento:</label>
        <input class="campo" type="text" name="descuento-txt" autocomplete="off" value="<?= ($consulta[0]['descuento']) ? $consulta[0]['descuento'] : number_format(0, 2) ?>" data-form="descuento" min='0' max='99999' required>

        <label for="total-txt">Total:</label>
        <input class="campo" type="text" name="total-txt" autocomplete="off" value="<?= $consulta[0]['total'] ?>" data-form="total" min='0' max='99999' required>

        <label for="notas-txt">Notas:</label>
        <textarea class="campo" name="notas-txt" autocomplete="off" cols="30" rows="2" data-form="notas" maxlength="250"></textarea>

        <label for="metodo-pago-txt">Método de pago:</label>
        <select class="campo" name="metodo-pago-txt" data-form="metodo-pago" required>
            <?php ?>
            <option value="1">Efectivo</option>
            <option value="2">Transferencia</option>
        </select>

        <label for="fecha-txt">Fecha:</label>
        <input class="campo" type="datetime" name="fecha-txt" value="<?=$consulta[0]['fecha']?>" data-form="fecha" required>

        <fieldset class="fieldset__envoltura">
            <legend>Datos del empleado</legend>
            <p>Nombre: <span class="texto-rosa"><?=$consulta[0]['nombre_completo']?></span></p>

            <label for="empleado_id-txt">Usuario:</label>
            <input class="campo mayusculas" type="text" name="empleado_id-txt" value="<?=$consulta[0]['empleado_id']?>" minlength="6" maxlength="6" required>
        </fieldset>
        
    </fieldset>

    <button class="boton-form enviar" id="btnEditar">Editar</button>
    <button class="boton-form otro" id="btnEliminar">Eliminar</button>
    <button class="boton-form otro" id="btnCerrar">Cancelar</button>
</form>`