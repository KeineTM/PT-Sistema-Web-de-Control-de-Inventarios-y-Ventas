<?php
class ControladorContactos {
    private $contacto_id; # Num de teléfono
    private $nombre;
    private $apellido_paterno;
    private $apellido_materno;
    private $email;
    private $notas; # Dirección en clientes con apartados
    private $tipo_id;

    public function __construct($contacto_id, $nombre, $apellido_paterno, $apellido_materno, $email, $notas, $tipo_id) {
        $this->contacto_id = $contacto_id; # Num de teléfono
        $this->nombre = $nombre;
        $this->apellido_paterno = $apellido_paterno;
        $this->apellido_materno = $apellido_materno;
        $this->email = $email;
        $this->notas = $notas; # Dirección en clientes con apartados
        $this->tipo_id = $tipo_id;
    }

    /** Método de validación que de encontrar un error lo retorna dentro de un array
     * En caso de no encontrar errores, retorna null;
     */
    private function validarDatos() {
        $listaDeErrores = [];

        if(strlen($this->contacto_id) !== 10) array_push($listaDeErrores, 'El numero de telefono debe tener 10 numeros');
        if(!preg_match('/^([0-9]+){10}$/', $this->contacto_id)) array_push($listaDeErrores, 'El numero de telefono solo acepta numeros');
        if(strlen($this->nombre) < 3 && strlen($this->nombre) > 80) array_push($listaDeErrores, 'El nombre debe contener de 3 a 80 letras');
        if(strlen($this->apellido_paterno) < 3 && strlen($this->apellido_paterno) > 80) array_push($listaDeErrores, 'El apellido paterno debe contener de 3 a 80 letras');
        
        if(strlen($this->apellido_materno) > 0) # Valida sólo en caso de que se haya recibido un dato
            if(strlen($this->apellido_materno) > 80) array_push($listaDeErrores, 'El apellido materno no puede tener más de 80 letras');
        
        if(strlen($this->notas) > 0) # Valida sólo en caso de que se haya recibido un dato
            if(strlen($this->notas) > 250) array_push($listaDeErrores, 'Las notas no pueden tener mas de 250 letras');
        
        if(strlen($this->email) > 0) { # Valida sólo en caso de que se haya recibido un dato
            if(strlen($this->email) > 150) array_push($listaDeErrores, 'El email no puede tener mas de 150 letras');
            if(!preg_match('/^\w+([.-_+]?\w+)*@\w+([.-]?\w+)*(\.\w{2,10})+$/', $this->email)) array_push($listaDeErrores, 'El email debe contener un @ y un dominio. Ej: tienda@gobokids.com');
        }

        return (count($listaDeErrores) > 0)
            ? $listaDeErrores
            : null;
    }

    /** Método para registrar un contacto.
     * En caso de ejecutarse con éxito retorna true, de lo contrario retorna un string con el error.
     */
    private function ctrlRegistrar() {
        $listaDatos = [
            $this->contacto_id,
            $this->nombre,
            $this->apellido_paterno,
            $this->apellido_materno,
            $this->email,
            $this->notas,
            $this->tipo_id
        ];

        $modelo_consulta = new ModeloContactos();
        return $modelo_consulta -> mdlRegistrar($listaDatos);
    }

    /** Método para recuperar un registro de contacto.
     * Si se indica un ID retorna máximo una coincidencia, 
     * mientras que si no se indica, retorna todos los registros de la tabla.
     */
    public static function ctrlLeer($id='') {
        $modelo_consulta = new ModeloContactos();
        return $modelo_consulta -> mdlLeer($id);
    }

    /** Método para buscar por palabra clave en los campos de nombre y apellidos */
    public static function ctlBuscarEnFullText($palabra_clave) {
        $modelo_consulta = new ModeloContactos();
        return $modelo_consulta -> mdlBuscarEnFullText($palabra_clave);
    }

    /** Método para registrar un contacto.
     * En caso de ejecutarse con éxito retorna true, de lo contrario retorna un string con el error.
     */
    private function ctrlEditar($id_original) {
        $listaDatos = [
            $this->contacto_id,
            $this->nombre,
            $this->apellido_paterno,
            $this->apellido_materno,
            $this->email,
            $this->notas,
            $this->tipo_id,
            $id_original # Corresponde al id o llave primaria original
        ];
        $modelo_consulta = new ModeloContactos();
        return $modelo_consulta -> mdlEditar($listaDatos);
    }

    public static function ctrlEliminar($id) {
        $modelo_consulta = new ModeloContactos();
        return $modelo_consulta -> mdlEliminar($id);
    }

    #---------------- Métodos para el módulo de Directorio --------------
    public static function crearContacto() {
        if(!isset($_POST['contacto_id-txt'])) return;

        $contacto_id = $_POST['contacto_id-txt']; # Num de teléfono
        $nombre = $_POST['nombre-txt'];
        $apellido_paterno = $_POST['apellido_paterno-txt'];
        $apellido_materno = $_POST['apellido_materno-txt'];
        $email = $_POST['email-txt'];
        $notas = $_POST['notas-txt'];
        $tipo_id = $_POST['tipo_contacto-txt'];

        $nuevo_contacto = new ControladorContactos($contacto_id, $nombre, $apellido_paterno, $apellido_materno, $email, $notas, $tipo_id);
        # Validación
        $resultado_validacion = $nuevo_contacto -> validarDatos();

        if($resultado_validacion !== null) {
            echo ($resultado_validacion);
        } else {
            $resultado_registro = $nuevo_contacto -> ctrlRegistrar();

            if($resultado_registro === true) { # Registro exitoso
                echo '<div id="alerta-formulario" class=alerta-verde>Registro de ' . $nombre . ' exitoso</div>';
            } else {
                echo '<div id="alerta-formulario" class=alerta-roja>' . $resultado_registro . '</div>';
                exit;
            }
        } 
    }

    public static function editarContacto() {
        if(!isset($_POST['contacto_id_original-txt'])) return;

        $contacto_id = $_POST['contacto_id_nuevo-txt']; # Num de teléfono
        $nombre = $_POST['nombre-txt'];
        $apellido_paterno = $_POST['apellido_paterno-txt'];
        $apellido_materno = $_POST['apellido_materno-txt'];
        $email = $_POST['email-txt'];
        $notas = $_POST['notas-txt'];
        $tipo_id = $_POST['tipo_contacto-txt'];

        $contacto_id_original = $_POST['contacto_id_original-txt']; # Para ejecutar la consulta sobre el id en tabla

        $contacto = new ControladorContactos($contacto_id, $nombre, $apellido_paterno, $apellido_materno, $email, $notas, $tipo_id);
        # Validación
        $resultado_validacion = $contacto -> validarDatos();

        if($resultado_validacion !== null) {
            echo '<div id="alerta-formulario" class=alerta-roja>';
            foreach($resultado_validacion as $error) {
                echo $error . '<br>';
            }
            echo '</div>';
        } else {
            $resultado_edicion = $contacto -> ctrlEditar($contacto_id_original);

            if($resultado_edicion === true) { # Edición exitosa, recarga presentando los datos nuevos
                echo '<script type="text/javascript">
                window.location.href = "index.php?pagina=directorio&opciones=detalles&id=' . $contacto_id .'&estado=exito";
                </script>';
                exit;
            } else { # Despliega el mensaje correspondiente
                echo '<div id="alerta-formulario" class=alerta-roja>' . $resultado_edicion . '</div>';
            }
        }
    }

    public static function eliminarContacto() {
        if(!isset($_POST['contacto_id_eliminar-txt'])) return;

        $contacto_id = $_POST['contacto_id_eliminar-txt'];

        $resultado = self::ctrlEliminar($contacto_id);

        if($resultado === true) {
            echo '<div id="alerta-formulario" class=alerta-roja>Eliminación exitosa</div>';
        } else { # Despliega el mensaje correspondiente
            echo '<div id="alerta-formulario" class=alerta-roja>' . $resultado . '</div>';
        }
    }
}

if(isset($_GET['funcion'])) {
    require_once '../modelo/mdlContactos.php'; # Recordar llamar en estos casos al modelo

    if($_GET['funcion'] === 'buscar') {
        if(!isset($_POST['buscarContacto-txt'])) exit();

        $palabra_clave = $_POST['buscarContacto-txt'];

        if(strlen($palabra_clave) < 3 || strlen($palabra_clave) > 240) {
            echo 'Servidor: La búsqueda debe contener de 3 a 240 letras';
            die();
        }

        $consulta = ControladorContactos::ctlBuscarEnFullText($palabra_clave);

        if(is_array($consulta))
            echo json_encode($consulta);
        else 
            echo false;
    }
}