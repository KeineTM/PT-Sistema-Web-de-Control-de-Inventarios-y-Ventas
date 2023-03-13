<form class="login-form" action="vistas/paginas/validacion-login.php" method="post" id="form-login">
    <label for="usuario-txt">Nombre de usuario:</label>
    <input class="campo" type="text" name="usuario-txt" id="usuario-txt">
    <span class="alerta" id="alerta-usuario">Debe llenar este campo</span>

    <label for="password">Contraseña:</label>
    <input class="campo" type="password" name="password-txt" id="password-txt">
    <span class="alerta" id="alerta-password">Debe llenar este campo</span>

    <button class="boton" type="button" id="btn-enviar">Entrar</button>
    <button class="boton" type="reset">Limpiar</button>
</form>
<!-- Script de validación del formulario de inicio de sesión -->
<script src="vistas/js/paginas/validacion-login.js"></script>