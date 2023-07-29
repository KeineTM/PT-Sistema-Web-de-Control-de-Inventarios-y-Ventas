<?php
    # En esta página de php se llaman a todos los archivos del controlador y modelo del sistema.
    # Gracias a ello, es posible emplear sus métodos en cada página que se cargue desde este punto.
    require 'controlador/ctrlPlantilla.php';
    require 'controlador/ctrlUsuarios.php';
    require 'controlador/ctrlSeguridad.php';
    require 'controlador/ctrlInventario.php';
    require 'controlador/ctrlOperaciones.php';
    require 'controlador/ctrlContactos.php';
    require 'controlador/ctrlEmpresa.php';

    require 'modelo/mdlUsuarios.php';
    require 'modelo/mdlInventario.php';
    require 'modelo/mdlOperaciones.php';
    require 'modelo/mdlContactos.php';
    require 'modelo/mdlEmpresa.php';

    # Instancia la clase
    $plantilla = new ControladorPlantilla();
    # Ejecuta el método que trae la vista de la plantilla.php
    $plantilla->ctrPlantilla();
?>