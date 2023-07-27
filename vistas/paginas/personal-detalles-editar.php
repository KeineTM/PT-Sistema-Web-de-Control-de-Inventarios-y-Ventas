<?php
if (!isset($_GET['id'])) {
    echo '<p class="destacado">No se ha selecionado ningún empleado...</p>';
    return;
}

$id = $_GET['id'];

# Recuperación de datos
$consulta = ControladorUsuarios::ctrlConsultarUsuarioID($id);

# Valida el resultado de la consulta
# Si no es una lista es porque retornó un error
# Si es una lista vacía es porque no encontró coincidencias
if (!is_array($consulta) || sizeof($consulta) === 0) {
    echo '<p class="alerta-roja">Ocurrió un error: El usuario no existe o hay un problema con la base de datos</p>';
    die();
}

$nombre_completo = $consulta[0]['nombre'] . " " . $consulta[0]['apellido_paterno'] . " " . $consulta[0]['apellido_materno'];

ControladorUsuarios::ctrlEditarUsuario($id);
?>
<span class="formulario__encabezado">
    <img class="formulario__icono" src="vistas/img/file-invoice.svg" alt="Formulario">
    <h2>Formulario de edición para <?= $consulta[0]['usuario_id'] . ':<br>' . $nombre_completo ?></h2>
    <span class="alerta" id="alerta-formulario"></span>
</span>

<!-- Mensaje de estado de la operación -->
<div id="alerta-formulario" class=<?php
    if (isset($_GET['estado'])) {
        if ($_GET['estado'] === 'exito') {
        ?> "alerta-verde">Usuario editado con éxito
<?php } else
        if ($_GET['estado'] === 'error') { ?>
    "alerta-roja">Ocurrió un error, intente nuevamente
<?php }
    } else {
    echo 'hidden >';
} ?>
</div>
<!-- -------------------------------------------- -->

<form class="formulario" method="post" id="formulario-editar">
    <fieldset class="formulario__fieldset">
        <input type="hidden" name="usuario_id-txt" value="<?= $consulta[0]['usuario_id'] ?>" required>

        <label for="nombre-txt">Nombre:</label>
        <input type="text" class="campo requerido" placeholder="Nombre" name="nombre-txt" autocomplete="off" data-form="nombre" minlength="3" maxlength="80" required value=<?= $consulta[0]['nombre'] ?>>

        <label for="apellido_paterno-txt">Apellido Paterno:</label>
        <input type="text" class="campo requerido" placeholder="Apellido" name="apellido_paterno-txt" autocomplete="off" data-form="apellido_paterno" minlength="3" maxlength="80" required value=<?= $consulta[0]['apellido_paterno'] ?>>

        <label for="apellido_materno-txt">Apellido Materno:</label>
        <input type="text" class="campo" placeholder="Apellido" name="apellido_materno-txt" autocomplete="off" data-form="apellido_materno" maxlength="80" value=<?= $consulta[0]['apellido_materno'] ?> required>

        <label for="telefono-txt">Teléfono:</label>
        <input type="number" step="any" class="campo requerido" placeholder="1234567890" name="telefono-txt" autocomplete="off" data-form="telefono" minlength="10" maxlength="10" required value=<?= $consulta[0]['telefono'] ?>>

        <label for="rfc-txt">RFC:</label>
        <input type="text" class="campo mayusculas requerido" placeholder="ABCD123456EF0" name="rfc-txt" autocomplete="off" data-form="rfc" minlength="13" maxlength="13" required value="<?= $consulta[0]['rfc'] ?>">

        <label for="email-txt">Email:</label>
        <input type="email" name="email-txt" autocomplete="off" class="campo" data-form="email" placeholder="direccion@email.com" maxlength="150" value=<?= $consulta[0]['email'] ?>>
    </fieldset>

    <fieldset class="formulario__fieldset">
        <label for="password-txt">Nueva contraseña:</label>
        <input type="password" name="password-txt" autocomplete="off" class="campo" data-form="password" placeholder="Contraseña" minlength="8" maxlength="20">

        <label for="password_2-txt">Repita la contraseña:</label>
        <input type="password" name="password_2-txt" autocomplete="off" class="campo" placeholder="Repita su contraseña" minlength="8" maxlength="20">
        <span class="alerta" id="alerta-password"></span>

        <label for="notas-txt">Notas:</label>
        <textarea name="notas-txt" class="campo requerido" autocomplete="off" cols="30" rows="3" data-form="notas" placeholder="Notas..." maxlength="250" required><?= $consulta[0]['notas'] ?></textarea>

        <fieldset class="fieldset__envoltura">
            <legend>Tipo de usuario</legend>
            <input type="radio" name="tipo_usuario-txt" value="2" required data-form="tipo_usuario" <?php if($consulta[0]['tipo_usuario'] === 'Empleado') echo 'checked' ?>>
            <label for="tipo_usuario-txt">Empleado</label>
            <br>
            <input type="radio" name="tipo_usuario-txt" value="1" required data-form="tipo_usuario" <?php if ($consulta[0]['tipo_usuario'] === 'Administrador') echo 'checked' ?>>
            <label for="tipo_usuario-txt">Administrador</label>
        </fieldset>

        <fieldset class="fieldset__envoltura">
            <legend>Estado</legend>
            <input type="radio" name="estado-txt" value="1" required data-form="estado" <?php if($consulta[0]['estado']) echo 'checked' ?>>
            <label for="estado-txt">Activo</label>
            <br>
            <input type="radio" name="estado-txt" value="0" required data-form="estado" <?php if (!$consulta[0]['estado']) echo 'checked' ?>>
            <label for="estado-txt">Inactivo</label>
        </fieldset>
        <button type="submit" class="boton-form enviar" id="btnEditar">Editar</button>
    </fieldset>
</form>