<form class="login-form" action="modulos/main/validacion-login.php" method="post" id="form-login">
    <label for="usuario-txt">Nombre de usuario:</label>
    <input class="campo" type="text" name="usuario-txt" id="usuario-txt">
    <span class="alerta" id="alerta-usuario">Debe llenar este campo</span>

    <label for="password">Contraseña:</label>
    <input class="campo" type="password" name="password-txt" id="password-txt">
    <span class="alerta" id="alerta-password">Debe llenar este campo</span>

    <button class="boton" type="button" id="btn-enviar"><span>Enviar</span></button>
    <button class="boton" type="reset"><span>Limpiar</span></button>
</form>