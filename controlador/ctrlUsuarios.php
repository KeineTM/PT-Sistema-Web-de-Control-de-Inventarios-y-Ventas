<?php
    /** 
     * Esta clase gestiona todas las actividades que se relacionen con los usuarios del sistema
     */
    class ControladorUsuarios {
        /**
         * Método que ejecuta una consulta para recuperar los campos usuario_id, password, tipo_usuario y nombre_completo de un usuario.
         */
        static public function ctrlConsultarUsuarios($usuarioID) {
            return ModeloUsuarios::mdlConsultarUsuarios($usuarioID);
        }

        /**
         * Método que verifica y valida las credenciales de un usuario que solicita ingreso al sistema.
         * Si hay coincidencia en el usuario procede a verificar la contraseña con el hash. 
         * Si la verificación es exitosa envía al usuario a la página de inicio del sistema.
         * De lo contrario lo regresa a la página de login con un mensaje que indica lo sucedido.
         */
        static public function ctrlLoginUsuarios() {
            if(isset($_POST["login-usuario"])) {
                $usuarioID = $_POST['login-usuario'];
                $password = $_POST['login-pass'];

                $consultaUsuario = ModeloUsuarios::mdlConsultarUsuarios($usuarioID);
                
                if($consultaUsuario && ControladorSeguridad::ctrlValidarPassword($password, $consultaUsuario['password'])) {
                    # Declaración de variables de sesión
                    $_SESSION['validarSesion'] = true; // Indica que existe una sesión
                    $_SESSION['idUsuarioSesion'] = $consultaUsuario['usuario_id']; // Mantiene el ID del usuario

                    header("Location: index.php?pagina=inicio-usuario");
                } else 
                    header("Location: index.php?pagina=login&error=true");
            }
        }

        /**
         * Cerrar sesión
         */
        static public function ctrlLogoutUsuarios() {
            session_destroy();
        }
    }
?>