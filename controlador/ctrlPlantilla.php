<!-- Controlador de la plantilla principal
Esta clase recibe las peticiones desde el index.php -->
<?php
    class ControladorPlantilla {
        // Método que llama a plantilla.php
        public function ctrPlantilla() {
            include 'vistas/plantilla.php';
        }
    }
?>