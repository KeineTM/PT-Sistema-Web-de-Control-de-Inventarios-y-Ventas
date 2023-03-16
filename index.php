<!--
    En esta página de php se llaman a todos los archivos del controlador y modelo del sistema.
    Gracias a ello, es posible emplear sus métodos en cada página que se cargue desde este punto.
-->
<?php
    require 'controlador/ctrlPlantilla.php';
    require 'controlador/ctrlUsuarios.php';
    require 'controlador/ctrlSeguridad.php';

    require 'modelo/mdlUsuarios.php';

    // Instancia la clase
    $plantilla = new ControladorPlantilla();
    // Ejecuta el método que trae la vista de la plantilla.php
    $plantilla->ctrPlantilla();
?>