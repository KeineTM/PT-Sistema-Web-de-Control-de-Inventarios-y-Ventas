<?php
    /** 
     * Controlador de la plantilla principal
     * Esta clase recibe las peticiones desde el index.php
     * Gestiona la navegación en el sitio web
     */
    class ControladorPlantilla {

        /** 
         * Método que llama a plantilla.php
         */
        public function ctrPlantilla() {
            include 'vistas/plantilla.php';
        }

        /**
         * Método para asignar título a la página
         */
        static public function ctrlTitulo() {
            if(isset($_GET["pagina"])) {
                return $_GET["pagina"]." - Globo Kids";
            } else "Sistema Globo Kids";
        }

        /** 
         * Método para cargar el contenido que corresponde con el nombre de la página
         */
        static public function ctrlContenido() {
            if(isset($_GET["pagina"])) {
                if ($_GET["pagina"] == "inicio-usuario" ||
                    $_GET["pagina"] == "ventas" ||
                    $_GET["pagina"] == "apartados" ||
                    $_GET["pagina"] == "devoluciones" ||
                    $_GET["pagina"] == "inventario" ||
                    $_GET["pagina"] == "directorio" ||
                    $_GET["pagina"] == "reportes" ||
                    $_GET["pagina"] == "personal" ||
                    $_GET["pagina"] == "empresa" ||
                    $_GET["pagina"] == "salir" ) {
                    include "vistas/paginas/".$_GET["pagina"].".php";
                }
            }else {
                include "vistas/paginas/inicio-usuario.php";
            }
        }
    }
?>