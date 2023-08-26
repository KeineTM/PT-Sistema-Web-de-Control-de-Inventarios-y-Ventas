<?php
if (!isset($_GET['id'])) {
    echo '<p class="destacado">No se ha selecionado ningún cliente...</p>';
    return;
}

$contacto_id = $_GET['id'];

# Recuperación de datos
$consulta = ControladorContactos::ctrlLeer($contacto_id);

# Valida el resultado de la consulta
# Si no es una lista es porque retornó un error
# Si es una lista vacía es porque no encontró coincidencias
if (!is_array($consulta) || sizeof($consulta) === 0) {
    echo '<p class="alerta-roja">Ocurrió un error: El cliente no existe o hay un problema con la base de datos</p>';
    die();
}

$nombre_completo = $consulta[0]['nombre'] . " " . $consulta[0]['apellido_paterno'] . " " . $consulta[0]['apellido_materno'];

ControladorContactos::editarContacto();
# Eliminación del contacto en BD
ControladorContactos::eliminarContacto();
?>
<span class="formulario__encabezado">
    <img class="formulario__icono" src="vistas/img/file-invoice.svg" alt="Formulario">
    <h2>Formulario de edición para <?= $nombre_completo ?></h2>
    <span class="alerta" id="alerta-formulario"></span>
</span>

<!-- Mensaje de estado de la operación -->
<div id="alerta-formulario" class=<?php
    if (isset($_GET['estado'])) {
        if ($_GET['estado'] === 'exito') {
        ?> "alerta-verde">Contacto editado con éxito
<?php } else
        if ($_GET['estado'] === 'error') { ?>
    "alerta-roja">Ocurrió un error, intente nuevamente
<?php }
    } else {
    echo 'hidden >';
} ?>
</div>
<!-- -------------------------------------------- -->

<form class="formulario" method="post" id="formulario-directorio">
    <fieldset class="formulario__fieldset">
        <label for="nombre-txt">Nombre:</label>
        <input type="text" class="campo requerido" placeholder="Nombre" name="nombre-txt" id="nombre-txt" autocomplete="off" data-form="nombre" minlength="3" maxlength="80" required value=<?= $consulta[0]['nombre'] ?>>

        <label for="apellido_paterno-txt">Apellido Paterno:</label>
        <input type="text" class="campo requerido" placeholder="Apellido" name="apellido_paterno-txt" id="apellido_paterno-txt" autocomplete="off" data-form="apellido_paterno" minlength="3" maxlength="80" required value=<?= $consulta[0]['apellido_paterno'] ?>>

        <label for="apellido_materno-txt">Apellido Materno:</label>
        <input type="text" class="campo" placeholder="Apellido" name="apellido_materno-txt" id="apellido_materno-txt" autocomplete="off" data-form="apellido_materno" maxlength="80" value=<?= $consulta[0]['apellido_materno'] ?>>

        <label for="contacto_id_nuevo-txt">Teléfono:</label>
        <input type="number" step="any" class="campo requerido" placeholder="1234567890" name="contacto_id_nuevo-txt" id="contacto_id_nuevo-txt" autocomplete="off" data-form="contacto_id" minlength="10" maxlength="10" required value=<?= $consulta[0]['contacto_id'] ?>>

        <label for="email-txt">Email:</label>
        <input type="email" name="email-txt" id="email-txt" autocomplete="off" class="campo" data-form="email" placeholder="direccion@email.com" maxlength="150" value=<?= $consulta[0]['email'] ?>>
    </fieldset>

    <fieldset class="formulario__fieldset">
        <label for="notas-txt">Notas:</label>
        <textarea name="notas-txt" id="notas-txt" class="campo requerido" autocomplete="off" cols="30" rows="3" data-form="notas" placeholder="Contador, Pizzería Frogs, Zaragoza..." maxlength="250" required><?= $consulta[0]['notas'] ?></textarea>

        <fieldset class="fieldset__envoltura formulario__fieldset-2-columnas">
            <legend>Tipo de contacto</legend>

            <label for="tipo_contacto_2-txt">Cliente</label>
            <input type="radio" name="tipo_contacto-txt" id="tipo_contacto_2-txt" value="2" required data-form="tipo_id" <?php if ($consulta[0]['tipo_id'] === 2) echo 'checked' ?>>

            <label for="tipo_contacto_1-txt">Proveedor</label>
            <input type="radio" name="tipo_contacto-txt" id="tipo_contacto_1-txt" value="1" required data-form="tipo_id" <?php if ($consulta[0]['tipo_id'] === 1) echo 'checked' ?>>

            <label for="tipo_contacto_3-txt">Servicios</label>
            <input type="radio" name="tipo_contacto-txt" id="tipo_contacto_3-txt" value="3" required data-form="tipo_id" <?php if ($consulta[0]['tipo_id'] === 3) echo 'checked' ?>>
        </fieldset>

        <input name="contacto_id_original-txt" type="hidden" value=<?= $consulta[0]['contacto_id'] ?> required readonly>
        
        <fieldset class="formulario__botones-contenedor">
            <button type="submit" class="boton-form enviar" id="btnRegistrar">Editar</button>
            <button type="submit" class="boton-form otro" id="btnEliminar">Eliminar</button>
        </fieldset>

    </fieldset>
</form>
<?php # --------- Control: Sólo el administrador puede borrar operaciones de la BD
if ($_SESSION['tipoUsuarioSesion'] === 'Administrador') { ?>
    <form method="post" id="formulario-eliminar-contacto">
        <input name="contacto_id_eliminar-txt" type="hidden" value=<?= $consulta[0]['contacto_id'] ?> required>
    </form>
<?php } ?>