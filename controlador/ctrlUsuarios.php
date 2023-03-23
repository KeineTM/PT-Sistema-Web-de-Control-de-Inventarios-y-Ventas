<?php
    /**  Esta clase gestiona todas las actividades que se relacionen con los usuarios del sistema */
    class ControladorUsuarios {
        public $usuario_id;
        public $nombre;
        public $apellido_paterno;
        public $apellido_materno;
        public $telefono;
        public $rfc;
        public $email;
        public $password;
        public $notas;
        public $estado;
        public $tipo_usuario;

        # Métodos constructores
        public function __construct($usuario_id, $nombre, $apellido_paterno, $apellido_materno, $telefono, $rfc, $email, $password, $notas, $estado, $tipo_usuario) {
            $this->usuario_id = $usuario_id;
            $this->nombre = $nombre;
            $this->apellido_paterno = $apellido_paterno;
            $this->apellido_materno = $apellido_materno;
            $this->telefono = $telefono;
            $this->rfc = $rfc;
            $this->email = $email;
            $this->password = $password;
            $this->notas = $notas;
            $this->estado = $estado;
            $this->tipo_usuario = $tipo_usuario;
        }
        
        /** Método que recupera toda la información de la tabla usuario. */
        static public function ctrlConsultarUsuarios() {
            $modelo = new ModeloUsuarios;
            return $modelo -> read();
        }

        /** Método que recupera toda la información de un usuario existente */
        static public function ctrlConsultarUsuarioID($usuario_id) {
            $modelo = new ModeloUsuarios;
            return $modelo -> read($usuario_id);
        }

        /** Método que recupera toda la información de un usuario existente y activo con sólo un fragmento de información para iniciar sesión */
        static public function ctrlConsultarUsuarioLogin($usuario_id) {
            $modelo = new ModeloUsuarios;
            return $modelo -> readLogin($usuario_id);
        }

        /**
         * Método que verifica y valida las credenciales de un usuario que solicita ingreso al sistema.
         * Si hay coincidencia en el usuario procede a verificar la contraseña con el hash. 
         * Si la verificación es exitosa envía al usuario a la página de inicio del sistema.
         * De lo contrario lo regresa a la página de login con un mensaje que indica lo sucedido.
         */
        static public function ctrlLoginUsuarios() {
            if(isset($_POST["login-usuario"])) {
                if($_POST['login-usuario'] && $_POST['login-pass']) { // Valida que existan datos
                    $usuario_id = $_POST['login-usuario'];
                    $password = $_POST['login-pass'];

                    // Si no existe en la tabla devuelve null
                    $consultaUsuario = self::ctrlConsultarUsuarioLogin($usuario_id);
                    
                    if($consultaUsuario && ControladorSeguridad::ctrlValidarPassword("$password", $consultaUsuario[0][0]['password'])) {
                        # Declaración de variables de sesión
                        $_SESSION['validarSesion'] = true; // Indica que existe una sesión
                        # Para acceder al contenido de la consulta es necesario apuntar al índice del array [$registros][$resultado(fetchAll)][campo]
                        $_SESSION['idUsuarioSesion'] = $consultaUsuario[0][0]['usuario_id'];
                        $_SESSION['nombreUsuarioSesion'] = $consultaUsuario[0][0]['nombre_completo'];
                        $_SESSION['tipoUsuarioSesion'] = $consultaUsuario[0][0]['tipo_usuario'];

                        header("Location: index.php?pagina=inicio-usuario");

                    } else 
                        header("Location: index.php?pagina=login&error=1");
                } else
                    header("Location: index.php?pagina=login&error=0");
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