<?php
    // Importa la clase ControladorPlantilla
    require 'controlador/ctrlPlantilla.php';
    // Instancia la clase
    $plantilla = new ControladorPlantilla();
    // Ejecuta el método que trae la vista de la plantilla.php
    $plantilla->ctrPlantilla();
?>