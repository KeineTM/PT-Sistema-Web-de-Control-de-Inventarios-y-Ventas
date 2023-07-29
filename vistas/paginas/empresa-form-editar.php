<?php
$consulta = ControladorEmpresa::ctrlLeer();
#$consulta_redes = ControladorEmpresa::ctrlLeerRedesSociales();
?>

<span class="formulario__encabezado">
    <img class="formulario__icono" src="vistas/img/file-invoice.svg" alt="Formulario">
    <h2>Editar datos de la Tienda</h2>
    <span class="alerta" id="alerta-formulario"></span>
</span>

<form class="formulario" method="post" id="formulario-empresa">
    <fieldset class="formulario__fieldset">
        <input type="hidden" name="rfc-txt" value="<?= $consulta[0]['rfc'] ?>">

        <label for="rfc_nuevo-txt">RFC:</label>
        <input type="text" class="campo mayusculas requerido" placeholder="ABCD123456EF0" name="rfc_nuevo-txt" autocomplete="off" data-form="rfc" minlength="13" maxlength="13" value="<?= $consulta[0]['rfc'] ?>" required>

        <label for="razon_social-txt">Razón Social:</label>
        <input type="text" class="campo requerido" placeholder="Razón social..." name="razon_social-txt" autocomplete="off" data-form="razon_social" minlength="3" maxlength="200" value="<?= $consulta[0]['razon_social'] ?>" required>

        <label for="nombre_tienda-txt">Nombre de la Tienda:</label>
        <input type="text" class="campo requerido" placeholder="Nombre..." name="nombre_tienda-txt" autocomplete="off" data-form="nombre_tienda" minlength="3" maxlength="200" value="<?= $consulta[0]['nombre_tienda'] ?>" required>

        <label for="descripcion-txt">Descripción:</label>
        <textarea name="descripcion-txt" class="campo requerido" autocomplete="off" cols="30" rows="3" data-form="descripcion" placeholder="Descripción..." maxlength="250"><?= $consulta[0]['descripcion'] ?></textarea>
        
        <label for="telefono-txt">Teléfono:</label>
        <input type="number" step="any" class="campo requerido" placeholder="1234567890" name="telefono-txt" autocomplete="off" data-form="telefono" minlength="10" maxlength="10" value="<?= $consulta[0]['telefono'] ?>" required>

        <label for="email-txt">Email:</label>
        <input type="email" name="email-txt" autocomplete="off" class="campo" data-form="email" placeholder="direccion@email.com" maxlength="200" value="<?= $consulta[0]['email'] ?>" required>

        <label for="logo-txt">URL del logo:</label>
        <input type="text" class="campo requerido" placeholder="url.jpg" name="logo-txt" autocomplete="off" data-form="logo" maxlength="250" pattern="^[^\s]{0,250}\.(jpg|JPG|png|PNG|jpeg|JPEG|webp|WEBP)$" value="<?= $consulta[0]['logo'] ?>" required>
    </fieldset>

    <fieldset class="formulario__fieldset">
        <fieldset class="fieldset__envoltura">
            <legend>Dirección</legend>
            <label for="calle-txt">Calle:</label>
            <input type="text" class="campo requerido" placeholder="Calle..." name="calle-txt" autocomplete="off" data-form="calle" minlength="3" maxlength="150" value="<?= $consulta[0]['calle'] ?>" required>

            <label for="numero-txt">Número:</label>
            <input type="number" class="campo  requerido" placeholder="101" name="numero-txt" autocomplete="off" data-form="numero" min="1" maxlength="6" value="<?= $consulta[0]['numero'] ?>" required>
            
            <label for="cp-txt">Código Postal:</label>
            <input type="number" class="campo  requerido" placeholder="000001" name="cp-txt" autocomplete="off" data-form="cp" min="5" maxlength="5" value="<?= $consulta[0]['codigo_postal'] ?>" required>

            <label for="ciudad-txt">Ciudad:</label>
            <input type="text" class="campo" value="<?= $consulta[0]['ciudad'] ?>" disabled>

            <label for="estado-txt">Estado:</label>
            <input type="text" class="campo" value="<?= $consulta[0]['estado'] ?>" disabled>
        </fieldset>

        <fieldset class="fieldset__envoltura">
            <legend>Redes Sociales</legend>
            <label for="">AQUI VA UN FOR PARA CADA RED REGISTRADA</label>
            <br>
            <label for="red_nombre_ID-txt">Nombre:</label>
            <input type="text" class="campo" placeholder="FaceBook, Twitter, etc..." name="red_nombre_ID-txt" autocomplete="off" data-form="red_nombre_ID" minlength="2" maxlength="150" required>

            <label for="red_url_ID-txt">URL:</label>
            <input type="text" class="campo" placeholder="https://..." name="red_url_ID-txt" autocomplete="off" data-form="red_url_ID" minlength="10" maxlength="200" required>
        </fieldset>
    </fieldset>
    <button class="boton-form enviar" id="btnEditar">Enviar cambios</button>
</form>
<br>
<br>

<form method="post" id="formulario-redes" class="formulario">
    <fieldset class="fieldset__envoltura">
        <legend>Agregar nueva red social</legend>
        <label for="red_nombre-txt">Nombre:</label>
        <input type="text" class="campo" placeholder="FaceBook, Twitter, etc..." name="red_nombre-txt" autocomplete="off" data-form="red_nombre" minlength="2" maxlength="150" required>

        <label for="red_url-txt">URL:</label>
        <input type="text" class="campo" placeholder="https://..." name="red_url-txt" autocomplete="off" data-form="red_url" minlength="10" maxlength="200" required>
        <br>
        <br>
        <button class="boton-form enviar" id="btnAgregarRed">Agregar</button>
    </fieldset>
</form>