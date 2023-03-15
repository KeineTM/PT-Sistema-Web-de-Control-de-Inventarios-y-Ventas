<?php
    // Importa los controladores y modelos del sitio web para ser usados por las páginas que se invoquen desde este archivo
    require 'controlador/ctrlPlantilla.php';
    require 'controlador/ctrlUsuarios.php';
    require 'controlador/ctrlSeguridad.php';

    require 'modelo/mdlUsuarios.php';


    // Instancia la clase
    $plantilla = new ControladorPlantilla();
    // Ejecuta el método que trae la vista de la plantilla.php
    $plantilla->ctrPlantilla();
?>