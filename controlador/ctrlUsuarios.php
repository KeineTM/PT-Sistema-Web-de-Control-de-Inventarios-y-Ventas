<?php
    /**  Esta clase gestiona todas las actividades que se relacionen con los usuarios del sistema */
    class ControladorUsuarios {
        private $usuario_id;
        private $nombre;
        private $apellido_paterno;
        private $apellido_materno;
        private $telefono;
        private $rfc;
        private $email;
        private $password;
        private $notas;
        private $estado;
        private $tipo_usuario;

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

        /** Método de validación que de encontrar un error lo retorna dentro de un array
         * En caso de no encontrar errores, retorna null;
         */
        private function validarDatos($formulario) {
            $listaDeErrores = [];

            if(strlen($this->nombre) < 3 && strlen($this->nombre) > 80) array_push($listaDeErrores, 'El nombre debe contener de 3 a 80 letras');
            if(strlen($this->apellido_paterno) < 3 && strlen($this->apellido_paterno) > 80) array_push($listaDeErrores, 'El apellido paterno debe contener de 3 a 80 letras');
            if(strlen($this->apellido_materno) < 3 && strlen($this->apellido_materno) > 80) array_push($listaDeErrores, 'El apellido materno debe contener de 3 a 80 letras');
            
            if(strlen($this->telefono) !== 10) array_push($listaDeErrores, 'El número de telefono debe tener 10 numeros');
            if(!preg_match('/^([0-9]+){10}$/', $this->telefono)) array_push($listaDeErrores, 'El número de telefono solo acepta numeros');
            
            if(strlen($this->rfc) !== 13) array_push($listaDeErrores, 'El RFC debe tener 13 caracteres');
            if(!preg_match('/^([a-z]{3,4})(\d{2})(\d{2})(\d{2})([0-9a-z]{3})$/i', $this->rfc)) array_push($listaDeErrores, 'El RFC no coincide con un formato esperado');

            if(strlen($this->notas) > 0) # Valida sólo en caso de que se haya recibido un dato
                if(strlen($this->notas) > 250) array_push($listaDeErrores, 'Las notas no pueden tener mas de 250 letras');
            
                
            if(strlen($this->email) > 0) { # Valida sólo en caso de que se haya recibido un dato
                if(strlen($this->email) > 150) array_push($listaDeErrores, 'El email no puede tener mas de 150 letras');
                if(!preg_match('/^\w+([.-_+]?\w+)*@\w+([.-]?\w+)*(\.\w{2,10})+$/', $this->email)) array_push($listaDeErrores, 'El email debe contener un @ y un dominio. Ej: tienda@gobokids.com');
            }

            if($formulario !== 'alta') {
                if(strlen($this->password) > 0) {
                    if(strlen($this->password) < 8) array_push($listaDeErrores, 'Debe indicar una contraseña de 8 a 20 caracteres');
                if(!preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[@¡!¿?\-_ñÑ%])[A-Za-z\d@¡!¿?\-_ñÑ%]{8,20}$/', $this->password)) array_push($listaDeErrores, 'La contraseña debe tener, por lo menos: 1 mayúscula, 1 número y 1 caracter especial');
                }
            } else {
                if(strlen($this->password) < 8) array_push($listaDeErrores, 'Debe indicar una contraseña de 8 a 20 caracteres');
                if(!preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[@¡!¿?\-_ñÑ%])[A-Za-z\d@¡!¿?\-_ñÑ%]{8,20}$/', $this->password)) array_push($listaDeErrores, 'La contraseña debe tener, por lo menos: 1 mayúscula, 1 número y 1 caracter especial');
            }

            return (count($listaDeErrores) > 0)
                ? $listaDeErrores
                : null;
        }

        /** Método para registrar un usuario en la base de datos. En este método se encripta la contraseña */
        private function ctrlRegistrar() {
            // Encriptación de contraseña
            $this->password = ControladorSeguridad::ctrlEncriptarPassword($this->password);
            $listaDatos = [
                $this->usuario_id,
                $this->nombre,
                $this->apellido_paterno,
                $this->apellido_materno,
                $this->telefono,
                $this->rfc,
                $this->email,
                $this->password,
                $this->notas,
                $this->estado,
                $this->tipo_usuario
            ];
    
            $modelo_consulta = new ModeloUsuarios();
            return $modelo_consulta -> mdlRegistrar($listaDatos);
        }

        /** Método que recibe el formulario y procesa los datos para registrar un usuario en caso de ser válido.
         * Puede recibir un parámetro numérico tipo_usuario que puede ser 1 = Administrador o 2 = Empleado
         */
        static public function ctrlCrearUsuario($tipo_usuario = 2) {
            if(!isset($_POST['rfc-txt'])) return;

            $nuevo_usuario = new ControladorUsuarios(
                substr($_POST['rfc-txt'], 0, 6),
                $_POST['nombre-txt'],
                $_POST['apellido_paterno-txt'],
                $_POST['apellido_materno-txt'],
                $_POST['telefono-txt'],
                $_POST['rfc-txt'],
                $_POST['email-txt'],
                $_POST['password-txt'],
                $_POST['notas-txt'],
                true,
                $tipo_usuario
            );

            # Validación
            $resultado_validacion = $nuevo_usuario -> validarDatos('alta');

            if($resultado_validacion !== null) {
                foreach($resultado_validacion as $error)
                echo ('Servidor: ' . $error . '<br>');
            } else {
                $resultado_registro = $nuevo_usuario -> ctrlRegistrar();

                if($resultado_registro === true) { # Registro exitoso
                    echo '<div id="alerta-formulario" class=alerta-verde>Registro de usuario ' . $_POST['nombre-txt'] . ' ' . $_POST['apellido_paterno-txt'] . ' con folio: "' . substr($_POST['rfc-txt'], 0, 6) . '" exitoso</div>';
                } else {
                    echo '<div id="alerta-formulario" class=alerta-roja>Error: Registro duplicado</div>';
                    exit;
                }
            }
        }

        private function ctrlEditar($id_original) {
            // Encriptación de contraseña
            $this->password = ControladorSeguridad::ctrlEncriptarPassword($this->password);
            $listaDatos = [
                $this->usuario_id,
                $this->nombre,
                $this->apellido_paterno,
                $this->apellido_materno,
                $this->telefono,
                $this->rfc,
                $this->email,
                $this->password,
                $this->notas,
                $this->estado,
                $this->tipo_usuario,
                $id_original # Corresponde al id o llave primaria original
            ];
            $modelo_consulta = new ModeloUsuarios();
            return $modelo_consulta -> update($listaDatos);
        }

        static public function ctrlEditarUsuario() {
            if(!isset($_POST['usuario_id-txt'])) return;

            $usuario_id = $_POST['usuario_id-txt'];

            $usuario = new ControladorUsuarios(
                substr($_POST['rfc-txt'], 0, 6),
                $_POST['nombre-txt'],
                $_POST['apellido_paterno-txt'],
                $_POST['apellido_materno-txt'],
                $_POST['telefono-txt'],
                $_POST['rfc-txt'],
                $_POST['email-txt'],
                $_POST['password-txt'],
                $_POST['notas-txt'],
                $_POST['estado-txt'],
                $_POST['tipo_usuario-txt']
            );

            # Validación
            $resultado_validacion = $usuario -> validarDatos('edicion');

            if($resultado_validacion !== null) {
                foreach($resultado_validacion as $error)
                echo ('<p class="texto-rosa">Servidor: ' . $error . '</p>');
            } else {
                $resultado_registro = $usuario -> ctrlEditar($usuario_id);

                if($resultado_registro === true) { # Registro exitoso
                    echo '<div id="alerta-formulario" class=alerta-verde>Edición de usuario ' . $_POST['nombre-txt'] . ' ' . $_POST['apellido_paterno-txt'] . ' con folio: "' . substr($_POST['rfc-txt'], 0, 6) . '" exitosa</div>';
                } else {
                    echo '<div id="alerta-formulario" class=alerta-roja>Servidor: Error - Intente nuevamente</div>';
                    exit;
                }
            }
        }
        
        /** Método que recupera toda la información de la tabla usuario. */
        static public function ctrlConsultarUsuarios() {
            $modelo = new ModeloUsuarios;
            return $modelo -> read();
        }

        /** Método que recupera toda la información de un usuario existente */
        static public function ctrlConsultarUsuarioID($usuario_id) {
            if(strlen($usuario_id) > 0) {
                $modelo = new ModeloUsuarios;
                return $modelo -> read($usuario_id);
            } else {
                return "Error. No se han recibido los datos.";
            }
        }

        /**
         * Método que verifica y valida las credenciales de un usuario que solicita ingreso al sistema.
         * Si hay coincidencia en el usuario procede a verificar la contraseña con el hash. 
         * Si la verificación es exitosa envía al usuario a la página de inicio del sistema.
         * De lo contrario lo regresa a la página de login con un mensaje que indica lo sucedido.
         */
        static public function ctrlLoginUsuarios() {  
            if(isset($_POST["login-usuario"])) {
                if(ControladorSeguridad::ctrlEvaluarReCAPTCHA($_POST['token'])) {
                    if(strlen($_POST['login-usuario']) > 0 && strlen($_POST['login-pass']) > 0) { // Valida que existan datos
                        $usuario_id = $_POST['login-usuario'];
                        $password = $_POST['login-pass'];
    
                        // Si no existe en la tabla devuelve null
                        $modelo = new ModeloUsuarios;
                        $consultaUsuario = $modelo -> readLogin($usuario_id);
                        
                        if($consultaUsuario && ControladorSeguridad::ctrlValidarPassword($password, $consultaUsuario[0]['password'])) {
                            # Declaración de variables de sesión
                            $_SESSION['validarSesion'] = true; // Indica que existe una sesión
                            # Para acceder al contenido de la consulta es necesario apuntar al índice del array $registros[fila][campo]
                            $_SESSION['idUsuarioSesion'] = $consultaUsuario[0]['usuario_id'];
                            $_SESSION['nombreUsuarioSesion'] = $consultaUsuario[0]['nombre_completo'];
                            $_SESSION['tipoUsuarioSesion'] = $consultaUsuario[0]['tipo_usuario'];
    
                            header("Location: index.php?pagina=inicio-usuario");
                            die();
                        
                        } else {
                            header("Location: index.php?pagina=login&error=1");
                            die();
                        }
                    } else {
                        header("Location: index.php?pagina=login&error=0");
                        die();
                    }
                } else {
                    header("Location: index.php?pagina=login&error=recaptcha");
                    die();
                }
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