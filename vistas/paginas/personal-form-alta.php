<?php 
ControladorUsuarios::ctrlCrearUsuario();
?>
<span class="formulario__encabezado">
    <img class="formulario__icono" src="vistas/img/file-invoice.svg" alt="Formulario">
    <h2>Formulario de alta de Empleado</h2>
    <span class="" id="alerta-formulario"></span>
</span>

<!-- Mensaje de estado de la operación -->
<div id="alerta-formulario" class=
<?php
    if (isset($_GET['estado'])) {
        if ($_GET['estado'] === 'exito') { 
    ?> "alerta-verde">Empleado registrado
    <?php } else
                if ($_GET['estado'] === 'incompleto') { ?>
        "alerta-roja">No se han completado los datos obligatorios
    <?php } else
                if ($_GET['estado'] === 'formato') { ?>
        "alerta-roja">Algunos datos no son válidos
    <?php } else
                if ($_GET['estado'] === 'error') { ?>
        "alerta-roja">Ocurrió un error, intente nuevamente
    <?php }
    } else {
        echo 'hidden >';
    }?>
</div>
<!-- -------------------------------------------- -->

<form class="formulario" method="post" id="formulario-personal">
    <fieldset class="formulario__fieldset">
        <label for="usuario_id-txt">ID de Usuario generado automáticamente:</label>
        <input type="text" class="login-campo sin-borde" name="usuario_id-txt" id="usuario_id-txt" autocomplete="off" minlength="3" maxlength="80" required disabled>

        <label for="nombre-txt">Nombre:</label>
        <input type="text" class="campo requerido" placeholder="Nombre" name="nombre-txt" id="nombre-txt" autocomplete="off" data-form="nombre" minlength="3" maxlength="80" required>

        <label for="apellido_paterno-txt">Apellido Paterno:</label>
        <input type="text" class="campo requerido" placeholder="Apellido paterno" name="apellido_paterno-txt" id="apellido_paterno-txt" autocomplete="off" data-form="apellido_paterno" minlength="3" maxlength="80" required>
    
        <label for="apellido_materno-txt">Apellido Materno:</label>
        <input type="text" class="campo requerido" placeholder="Apellido materno" name="apellido_materno-txt" id="apellido_materno-txt" autocomplete="off" data-form="apellido_materno" maxlength="80">

        <label for="telefono-txt">Teléfono:</label>
        <input type="number" step="any" class="campo requerido" placeholder="1234567890" name="telefono-txt" id="telefono-txt" autocomplete="off" data-form="telefono" minlength="10" maxlength="10" required>

        <label for="rfc-txt">RFC:</label>
        <input type="text" class="campo mayusculas requerido" placeholder="ABCD123456EF0" name="rfc-txt" id="rfc-txt" autocomplete="off" data-form="rfc" minlength="13" maxlength="13" required>

    </fieldset>

    <fieldset class="formulario__fieldset">
        <label for="email-txt">Email:</label>
        <input type="email" name="email-txt" id="email-txt" autocomplete="off" class="campo" data-form="email" placeholder="direccion@email.com" maxlength="150">

        <label for="password-txt">Contraseña de usuario:</label>
        <input type="password" name="password-txt" id="password-txt" autocomplete="off" class="campo requerido" data-form="password" placeholder="Contraseñ@_1" minlength="8" maxlength="20" required>

        <label for="password_2-txt">Repita la contraseña:</label>
        <input type="password" name="password_2-txt" id="password_2-txt" autocomplete="off" class="campo requerido" placeholder="Repita su contraseña" minlength="8" maxlength="20" required>
        <span class="alerta" id="alerta-password"></span>

        <label for="notas-txt">Notas:</label>
        <textarea name="notas-txt" id="notas-txt" class="campo" autocomplete="off" cols="30" rows="3" data-form="notas" placeholder="Notas..." maxlength="250"></textarea>

        <div class="formulario__botones-contenedor">
            <button type="submit" class="boton-form enviar" id="btnRegistrar">Registrar</button>
            <input type="reset" class="boton-form otro" id="btnCerrar" value="Limpiar">
        </div>
    </fieldset>
</form>