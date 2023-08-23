<?php 
ControladorContactos::crearContacto();
?>
<span class="formulario__encabezado">
    <img class="formulario__icono" src="vistas/img/file-invoice.svg" alt="Formulario">
    <h2>Formulario de alta de Contacto</h2>
    <span class="alerta" id="alerta-formulario"></span>
</span>

<!-- Mensaje de estado de la operación -->
<div id="alerta-formulario" class=
<?php
    if (isset($_GET['estado'])) {
        if ($_GET['estado'] === 'exito') { 
    ?> "alerta-verde">Contacto registrado
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

<form class="formulario" method="post" id="formulario-directorio">
    <fieldset class="formulario__fieldset">
        <label for="nombre-txt">Nombre:</label>
        <input type="text" class="campo requerido" placeholder="Nombre" name="nombre-txt" id="nombre-txt" autocomplete="off" data-form="nombre" minlength="3" maxlength="80" required>

        <label for="apellido_paterno-txt">Apellido Paterno:</label>
        <input type="text" class="campo requerido" placeholder="Apellido" name="apellido_paterno-txt" id="apellido_paterno-txt" autocomplete="off" data-form="apellido_paterno" minlength="3" maxlength="80" required>
    
        <label for="apellido_materno-txt">Apellido Materno:</label>
        <input type="text" class="campo" placeholder="Apellido" name="apellido_materno-txt" id="apellido_materno-txt" autocomplete="off" data-form="apellido_materno" maxlength="80">

        <label for="contacto_id-txt">Teléfono:</label>
        <input type="number" step="any" class="campo requerido" placeholder="1234567890" name="contacto_id-txt" id="contacto_id-txt" autocomplete="off" data-form="contacto_id" minlength="10" maxlength="10" required>

        <label for="email-txt">Email:</label>
        <input type="email" name="email-txt" id="email-txt" autocomplete="off" class="campo" data-form="email" placeholder="direccion@email.com" maxlength="150">
    </fieldset>

    <fieldset class="formulario__fieldset">
        <label for="notas-txt">notas:</label>
        <textarea name="notas-txt" id="notas-txt" class="campo requerido" autocomplete="off" cols="30" rows="3" data-form="notas" placeholder="Contador, Pizzería Frogs, Zaragoza..." maxlength="250" required></textarea>

        <fieldset class="fieldset__envoltura formulario__fieldset-2-columnas">
            <legend>Tipo de contacto</legend>
            <label for="tipo_contacto_2-txt">Cliente</label>
            <input type="radio" name="tipo_contacto-txt" id="tipo_contacto_2-txt" value="2" required checked data-form="tipo_id">
            <label for="tipo_contacto_1-txt">Proveedor</label>
            <input type="radio" name="tipo_contacto-txt" id="tipo_contacto_1-txt" value="1" required data-form="tipo_id">
            <label for="tipo_contacto_3-txt">Servicios</label>
            <input type="radio" name="tipo_contacto-txt" id="tipo_contacto_3-txt" value="3" required data-form="tipo_id">
        </fieldset>

        <div class="formulario__botones-contenedor">
            <button type="submit" class="boton-form enviar" id="btnRegistrar">Registrar</button>
            <input type="reset" class="boton-form otro" id="btnCerrar" value="Limpiar">
        </div>
    </fieldset>
</form>