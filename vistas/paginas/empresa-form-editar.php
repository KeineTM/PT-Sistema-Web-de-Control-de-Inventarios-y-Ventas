<?php
$consulta = ControladorEmpresa::ctrlLeer();
$consulta_redes = ControladorEmpresa::leerRedSocial();

ControladorEmpresa::editarEmpresa();

ControladorEmpresa::registrarRedSocial();
ControladorEmpresa::editarRedSocial();
ControladorEmpresa::borrarRedSocial();
?>

<span class="formulario__encabezado">
    <img class="formulario__icono" src="vistas/img/file-invoice.svg" alt="Formulario">
    <h2>Editar datos de la Tienda</h2>
    <span class="alerta" id="alerta-formulario"></span>
</span>

<form class="formulario" method="post" id="formulario-empresa">
    <fieldset class="formulario__fieldset">
        <input type="hidden" name="rfc_original-txt" value="<?= $consulta[0]['rfc'] ?>">

        <label for="rfc_nuevo-txt">RFC:</label>
        <input type="text" class="campo mayusculas requerido" placeholder="ABCD123456EF0" name="rfc_nuevo-txt" id="rfc_nuevo-txt" autocomplete="off" data-form="rfc" minlength="13" maxlength="13" value="<?= $consulta[0]['rfc'] ?>" required>

        <label for="razon_social-txt">Razón Social:</label>
        <input type="text" class="campo requerido" placeholder="Razón social..." name="razon_social-txt" id="razon_social-txt" autocomplete="off" data-form="razon_social" minlength="3" maxlength="200" value="<?= $consulta[0]['razon_social'] ?>" required>

        <label for="nombre_tienda-txt">Nombre de la Tienda:</label>
        <input type="text" class="campo requerido" placeholder="Nombre..." name="nombre_tienda-txt" id="nombre_tienda-txt" autocomplete="off" data-form="nombre_tienda" minlength="3" maxlength="200" value="<?= $consulta[0]['nombre_tienda'] ?>" required>

        <label for="descripcion-txt">Descripción:</label>
        <textarea name="descripcion-txt" id="descripcion-txt" class="campo requerido" autocomplete="off" cols="30" rows="3" data-form="descripcion" placeholder="Descripción..." maxlength="250"><?= $consulta[0]['descripcion'] ?></textarea>

        <label for="telefono-txt">Teléfono:</label>
        <input type="number" step="any" class="campo requerido" placeholder="1234567890" name="telefono-txt" id="telefono-txt" autocomplete="off" data-form="telefono" minlength="10" maxlength="10" value="<?= $consulta[0]['telefono'] ?>" required>

        <label for="email-txt">Email:</label>
        <input type="email" name="email-txt" id="email-txt" autocomplete="off" class="campo" data-form="email" placeholder="direccion@email.com" maxlength="200" value="<?= $consulta[0]['email'] ?>" required>

        <label for="logo-txt">URL del logo:</label>
        <input type="text" class="campo requerido" placeholder="url.jpg" name="logo-txt" id="logo-txt" autocomplete="off" data-form="logo" maxlength="250" pattern="^[^\s]{0,250}\.(jpg|JPG|png|PNG|jpeg|JPEG|webp|WEBP|svg)$" value="<?= $consulta[0]['logo'] ?>" required>
    </fieldset>

    <fieldset class="fieldset__envoltura">
        <legend>Dirección</legend>
        <label for="calle-txt">Calle:</label>
        <input type="text" class="campo requerido" placeholder="Calle..." name="calle-txt" id="calle-txt" autocomplete="off" data-form="calle" minlength="3" maxlength="150" value="<?= $consulta[0]['calle'] ?>" required>

        <label for="numero-txt">Número:</label>
        <input type="number" class="campo  requerido" placeholder="101" name="numero-txt" id="numero-txt" autocomplete="off" data-form="numero" min="1" maxlength="6" value="<?= $consulta[0]['numero'] ?>" required>

        <label for="codigo_postal-txt">Código Postal:</label>
        <input type="number" class="campo  requerido" placeholder="000001" name="codigo_postal-txt" id="codigo_postal-txt" autocomplete="off" data-form="cp" min="5" maxlength="5" value="<?= $consulta[0]['codigo_postal'] ?>" disabled required>

        <label for="ciudad-txt">Ciudad:</label>
        <input type="text" class="campo" id="ciudad-txt" value="<?= $consulta[0]['ciudad'] ?>" disabled>

        <label for="estado-txt">Estado:</label>
        <input type="text" class="campo" id="estado-txt" value="<?= $consulta[0]['estado'] ?>" disabled>
    </fieldset>
    <button class="boton-form enviar" id="btnEditar">Enviar cambios</button>
</form>
<br><br>

<h2>Editar Redes Sociales Registradas</h2>
<div class="una-dos-tres-columnas">
<?php 
$contador = 0;
foreach ($consulta_redes as $red) {?>
    <form method="post" id="formulario-redes-editar" data-idform="<?= $contador ?>">
        <fieldset class="fieldset__envoltura">
            <input type="hidden" name="red_id-txt" value="<?= $red['red_id'] ?>" required>
            <label for="red_nombre_editar-txt">Nombre:</label>
            <input type="text" class="campo" placeholder="FaceBook, Twitter, etc..." name="red_nombre_editar-txt" id="red_nombre_editar-txt" autocomplete="off" data-red="red_nombre" minlength="2" maxlength="150" value="<?= $red['nombre_red'] ?>" required>

            <label for="red_url_editar-txt">URL:</label>
            <input type="text" class="campo" placeholder="https://..." name="red_url_editar-txt" id="red_url_editar-txt" autocomplete="off" data-red="red_url" minlength="10" maxlength="200" value="<?= $red['url'] ?>" required>
            <br><br>

            <fieldset class="formulario__fieldset-2-columnas">
                <a class="texto-rosa texto-centrado" id="btnBorrarRed" href="index.php?pagina=empresa&opciones=editar&borrar=<?= $red['red_id'] ?>">Borrar</a>
                <input type="submit" class="texto-verde destacar" id="btnEditarRed" data-btn="<?= $contador ?>" value="Editar"/>
            </fieldset>
        </fieldset>
    </form>
<?php $contador ++; } ?>
</div>

<br><br>

<h2>Agregar Nueva Red Social</h2>
<form method="post" id="formulario-red-nueva" class="formulario">
    <fieldset class="fieldset__envoltura">
        <label for="red_nombre-txt">Nombre:</label>
        <input type="text" class="campo" placeholder="FaceBook, Twitter, etc..." name="red_nombre-txt" id="red_nombre-txt" autocomplete="off" data-red="red_nombre" minlength="1" maxlength="150" required>
        <label for="red_url-txt">URL:</label>
        <input type="text" class="campo" placeholder="https://..." name="red_url-txt" id="red_url-txt" autocomplete="off" data-red="red_url" minlength="10" maxlength="200" required>
        <br><br>

        <fieldset class="formulario__fieldset-2-columnas">
            <input type="reset" class="boton-form otro" value="Limpiar"/>
            <button class="boton-form enviar" id="btnAgregarRed">Agregar</button>
        </fieldset>
    </fieldset>
</form>

<script type="module" src="vistas/js/paginas/empresa.js"></script>