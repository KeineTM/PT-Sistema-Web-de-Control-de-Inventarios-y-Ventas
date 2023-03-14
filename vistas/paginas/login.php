<?php
    # Evalúa si existe una llave 'error' en el query string deñ url
    if(isset($_GET["error"])) {
        if($_GET["error"] == "true") { # De existir imprime el mensaje correspondiente
            echo '<center style="color:red;">Los datos escritos no coinciden.</br></br>
                Intente nuevamente.</center></br></br></br></br>';
        }
    }
?>

<form class="login-form" action="index.php?pagina=validacion-login" method="post" id="form-login">
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