<?php
    # Evalúa si existe una llave 'error' en el query string deñ url
    if(isset($_GET["error"])) {
        if($_GET["error"] == "true") { # De existir imprime el mensaje correspondiente
            echo '<center style="color:red;">Los datos escritos son incorrectos.</br></br>
                Intente nuevamente.</center></br></br></br></br>';
        }
    }
?>

<form class="login-form" method="post" id="form-login">
    <label for="usuario-txt">Nombre de usuario:</label>
    <input class="campo" type="text" name="login-usuario" id="login-usuario">
    <span class="alerta" id="alerta-usuario">Debe llenar este campo</span>

    <label for="password">Contraseña:</label>
    <input class="campo" type="password" name="login-pass" id="login-pass">
    <span class="alerta" id="alerta-password">Debe llenar este campo</span>

    <button class="boton" type="button" id="btn-enviar">Entrar</button>
    <button class="boton" type="reset">Limpiar</button>

    <?php
        # Con este código se envían los datos del formulario a ser procesados por el método de Login
        ControladorUsuarios::ctrlLoginUsuarios();
    ?>

</form>

<!-- Script de validación del formulario de inicio de sesión -->
<script src="vistas/js/paginas/validacion-login.js"></script>