<?php
    if(isset($_SESSION['idUsuarioSesion']))
        $usuarioLogeado = ControladorUsuarios::ctrlConsultarUsuarios($_SESSION['idUsuarioSesion'])
?>
<section class="inicio-bienvenido">
    <p>Bienvenida, <?php echo $usuarioLogeado['nombre_completo'] ?></p>
    <p>Ha iniciado sesión como <?php echo $usuarioLogeado['tipo_usuario'] ?></p>
</section>