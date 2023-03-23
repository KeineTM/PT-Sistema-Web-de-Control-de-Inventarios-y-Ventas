<section class="contenedor-login">
    <?php
        # Evalúa si existe una llave 'error' en el query string del url
        if(isset($_GET["error"])) {
            if($_GET["error"] === "0") # De existir imprime el mensaje correspondiente
                echo '<center style="color:#ff7899;">No ingresó los datos necesarios.</br></br>
                    Intente nuevamente.</center></br></br>';
            else if($_GET["error"] === "1")
                echo '<center style="color:#ff7899;">Los datos escritos son incorrectos.</br></br>
                    Intente nuevamente.</center></br></br>';
        }
    ?>
    <form class="login-form" method="post" id="form-login">
        <label for="usuario-txt">Nombre de usuario:</label>
        <input class="login-campo" type="text" name="login-usuario" id="login-usuario">
        <span class="alerta" id="alerta-usuario">Debe llenar este campo</span>

        <label for="password">Contraseña:</label>
        <div class="contenedor-password">
            <input class="login-campo" type="password" name="login-pass" id="login-pass">
            <img class="ojito-pass" src="vistas/img/eye.svg" alt="Icono ver" id="ojito-pass" title="Ver contraseña">
        </div>
        <span class="alerta" id="alerta-password">Debe llenar este campo</span>

        <div class="botones-contenedor">
            <button class="boton" type="button" id="btn-enviar"><div class="boton-interior-blanco"><img class="icono-login" src="vistas/img/right-to-bracket.svg" alt="Iniciar sesión"></div></button>
        </div>
        
        <?php
            # Con este código se envían los datos del formulario a ser procesados por el método de Login
            ControladorUsuarios::ctrlLoginUsuarios();
        ?>
        
    </form>
</section>

<!-- Script de validación del formulario de inicio de sesión -->
<script src="vistas/js/paginas/login-validacion.js"></script>