<?php
    $usuario = $_POST['usuario-txt'];
    $password = $_POST['password-txt'];

    if($usuario == "Jessy" && $password == "qwerty")
        echo 'Los datos coinciden';
    else
        echo 'Los datos no coinciden';

    // Primero debe validar que no exista una sesión ya activa.
    // Lo de arriba se sustituye por la conexión y consultas a la BD
    // Lo siguiente es evaluar si los datos coinciden
    // Si coinciden se inicia una sesión 
    // evalua el tipo de usuario y se redirige a la página principal (index) correspondiente
    // Si no coinciden se carga el mensaje correspondiente.
?>