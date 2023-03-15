<?php
    // Esta página no se muestra, es totalmente back-end
    $usuarioID = $_POST['usuario-txt'];
    $password = $_POST['password-txt'];

    echo ctrlUsuarios::ctrValidarUsuario($usuarioID);

    

    #if($usuarioID == "Jessy" && $password == "qwerty") {
    #    header("Location: index.php?pagina=inicio-usuario");
    #}
    #else
    #    header("Location: index.php?pagina=login&error=true");

    // Primero debe validar que no exista una sesión ya activa.
    // Lo de arriba se sustituye por la conexión y consultas a la BD
    // Lo siguiente es evaluar si los datos coinciden
    // Si coinciden se inicia una sesión 
    // evalua el tipo de usuario y se redirige a la página principal (index) correspondiente
    // Si no coinciden se carga el mensaje correspondiente.
?>