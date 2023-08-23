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
            else if($_GET["error"] === "2")
                echo '<center style="color:#ff7899;">La sesión ha expirado.</center></br></br>';
            else if($_GET["error"] === "recaptcha")
                echo '<center style="color:#ff7899;">No se ha aprobado el reCAPTCHA.</center></br></br>';
                
        }

        # Con este código se envían los datos del formulario a ser procesados por el método de Login
        ControladorUsuarios::ctrlLoginUsuarios();

        $claves = ControladorSeguridad::getClavesReCAPTCHA();
    ?>

    <form class="login-form" method="post" id="form-login">
        <label for="usuario-txt">Nombre de usuario:</label>
        <input class="login-campo" type="text" name="login-usuario" id="usuario-txt">
        <span class="alerta" id="alerta-usuario">Debe llenar este campo</span>

        <label for="password">Contraseña:</label>
        <div class="contenedor-password">
            <input class="login-campo" type="password" name="login-pass" id="password">
            <img class="ojito-pass" src="vistas/img/eye.svg" alt="Icono ver" id="ojito-pass" title="Ver contraseña">
        </div>
        <span class="alerta" id="alerta-password">Debe llenar este campo</span>

        <div class="botones-contenedor">
            <button class="boton" type="button" id="btn-enviar" disabled><div class="boton-interior-blanco"><img class="icono-login" src="vistas/img/right-to-bracket.svg" alt="Iniciar sesión"></div></button>
        </div>

        <input type="hidden" name="token" id="token">

    </form>
</section>

<!-- Script de validación del formulario de inicio de sesión -->
<script type="module" src="vistas/js/paginas/login.js"></script>
<!-- Script para el API de Google reCAPTCHA de forma asíncrona para evitar bloqueo de la página en caso de error -->
<script src="https://www.google.com/recaptcha/api.js?render=<?php echo $claves['publica'] ?>"></script>
<script>
    // Objeto grecaptcha generado por el API de Google
    grecaptcha.ready(() => {
        grecaptcha.execute('<?php echo $claves['publica'] ?>', {
            action: 'formularioLogin'
        }).then(function(token) {
            let recaptchaResponse = document.getElementById('token');
            recaptchaResponse.value = token;
            document.getElementById('btn-enviar').disabled = false;
        });
    });
</script>