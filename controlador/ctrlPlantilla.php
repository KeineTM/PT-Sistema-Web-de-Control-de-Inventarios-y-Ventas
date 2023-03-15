<?php
    # Controlador de la plantilla principal
    # Esta clase recibe las peticiones desde el index.php
    # Gestiona la navegación en el sitio web

    class ControladorPlantilla {

        # Método que llama a plantilla.php
        public function ctrPlantilla() {
            include 'vistas/plantilla.php';
        }

        # Método para asignar título a la página
        static public function ctrlTitulo() {
            if(isset($_GET["pagina"])) {
                if($_GET["pagina"] == "login") {
                    return "Inicio de sesión - Globo Kids";
                }
                if($_GET["pagina"] == "inicio-usuario") {
                    return "Inicio - Globo Kids";
                }
                if($_GET["pagina"] == "ventas") {
                    return "Ventas - Globo Kids";
                }
                // las demás páginas
            }
        }

        # Método que determina que controla la carga del menu
        static public function ctrlMenu() {
            if(isset($_GET["pagina"])) {
                if($_GET["pagina"] != "login" &&
                   $_GET["pagina"] != "usuario-validacion") {
                    include "vistas/modulos/menu.php";
                }
            }
        }

        # Método para cargar el contenido que corresponde con el nombre de la página
        static public function ctrlContenido() {
            if(isset($_GET["pagina"])) {
                if ($_GET["pagina"] == "login" ||
                    $_GET["pagina"] == "usuario-validacion" ||
                    $_GET["pagina"] == "inicio-usuario" ||
                    $_GET["pagina"] == "ventas" ||
                    $_GET["pagina"] == "apartados" ||
                    $_GET["pagina"] == "devoluciones" ||
                    $_GET["pagina"] == "inventario" ||
                    $_GET["pagina"] == "directorio" ||
                    $_GET["pagina"] == "reportes" ||
                    $_GET["pagina"] == "personal" ||
                    $_GET["pagina"] == "empresa"
                    // las demás páginas
                    ) {
                    include "vistas/paginas/".$_GET["pagina"].".php";
                }
            } 
        }
    }
?>