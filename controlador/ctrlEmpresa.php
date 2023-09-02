<?php
class ControladorEmpresa {
    private $rfc;
    private $razon_social;
    private $nombre_tienda;
    private $descripcion;
    private $calle;
    private $numero;
    private $codigo_postal;
    private $telefono;
    private $email;
    private $logo;

    private $estado;
    private $ciudad;

    public function __construct($rfc, $razon_social, $nombre_tienda, $descripcion, $calle, $numero, $codigo_postal, $telefono, $email, $logo) {
        $this->rfc = $rfc;
        $this->razon_social = $razon_social;
        $this->nombre_tienda = $nombre_tienda;
        $this->descripcion = $descripcion;
        $this->calle = $calle;
        $this->numero = $numero;
        $this->codigo_postal = $codigo_postal;
        $this->telefono = $telefono;
        $this->email = $email;
        $this->logo = $logo;
    }

    public static function ctrlLeer() {
        $modelo_consulta = new ModeloEmpresa;
        return $modelo_consulta -> mdlLeer();
    }

    /** Método de validación que de encontrar un error lo retorna dentro de un array
     * En caso de no encontrar errores, retorna null;
     */
    private function validarDatos() {
        $listaDeErrores = [];

        if(strlen($this->rfc) !== 13) array_push($listaDeErrores, 'El RFC debe tener 13 caracteres');
        if(!preg_match('/^([a-z]{3,4})(\d{2})(\d{2})(\d{2})([0-9a-z]{3})$/i', $this->rfc)) array_push($listaDeErrores, 'El RFC no coincide con un formato esperado');

        if(strlen($this->razon_social) < 3 && strlen($this->razon_social) > 200) array_push($listaDeErrores, 'La razón social debe contener de 3 a 200 letras');
        
        if(strlen($this->nombre_tienda) < 3 && strlen($this->nombre_tienda) > 200) array_push($listaDeErrores, 'El nombre de la tienda debe contener de 3 a 200 letras');

        if(strlen($this->descripcion) < 3 && strlen($this->descripcion) > 250) array_push($listaDeErrores, 'La descripción debe contener de 3 a 250 letras');

        if(strlen($this->calle) < 3 && strlen($this->calle) > 150) array_push($listaDeErrores, 'La calle debe contener de 3 a 150 letras');

        if(strlen($this->numero) < 1 && strlen($this->numero) > 6) array_push($listaDeErrores, 'El número debe contener de 1 a 6 cifras');
        if(!preg_match('/^([0-9]+)$/', $this->numero)) array_push($listaDeErrores, 'El número sólo admite números');
        if($this->numero < 0) array_push($listaDeErrores, 'El número debe ser un entero positivo');

        if(strlen($this->codigo_postal) !== 5) array_push($listaDeErrores, 'El código postal debe contener 5 cifras');
        if(!preg_match('/^([0-9]+)$/', $this->codigo_postal)) array_push($listaDeErrores, 'El código postal sólo admite números');

        if(strlen($this->telefono) !== 10) array_push($listaDeErrores, 'El número de teléfono debe tener 10 cifras');
        if(!preg_match('/^([0-9]+){10}$/', $this->telefono)) array_push($listaDeErrores, 'El número de teléfono solo acepta números');
        
        if(strlen($this->email) > 150) array_push($listaDeErrores, 'El email no puede tener mas de 150 letras');
        if(!preg_match('/^\w+([.-_+]?\w+)*@\w+([.-]?\w+)*(\.\w{2,10})+$/', $this->email)) array_push($listaDeErrores, 'El email debe contener un @ y un dominio. Ej: tienda@gobokids.com');

        if(strlen($this->logo) > 250) array_push($listaDeErrores, 'El logo no puede tener mas de 250 caracteres');
        if(!preg_match('/^[^\s]{0,250}\.(jpg|JPG|png|PNG|jpeg|JPEG|webp|WEBP|svg)$/', $this->logo)) array_push($listaDeErrores, 'El URL del logo sólo admite extensiones extensiones .jpg, .jpeg, .png, .gif, .webp o .svg');

        return (count($listaDeErrores) > 0)
            ? $listaDeErrores
            : null;
    }

    /** Método para editar los datos de la empresa.
     * En caso de ejecutarse con éxito retorna true, de lo contrario retorna un string con el error.
     */
    private function ctrlEditar($id_original) {
        $listaDatos = [
            $this->rfc,
            $this->razon_social,
            $this->nombre_tienda,
            $this->descripcion,
            $this->calle,
            $this->numero,
            $this->codigo_postal,
            $this->telefono,
            $this->email,
            $this->logo,
            $id_original # Corresponde al id o llave primaria original
        ];
        $modelo_consulta = new ModeloEmpresa();
        return $modelo_consulta -> mdlEditar($listaDatos);
    }

    /** Método que recibe el formulario de datos de la empresa para validarlos y,
     * de acuerdo con el resultado, ejecutar los cambios o rechazarlos informando al usuario.
     */
    public static function editarEmpresa() {
        if(!isset($_POST['rfc_original-txt'])) return;

        $rfc_original = $_POST['rfc_original-txt'];
        $rfc = $_POST['rfc_nuevo-txt'];
        $razon_social = $_POST['razon_social-txt'];
        $nombre_tienda = $_POST['nombre_tienda-txt'];
        $descripcion = $_POST['descripcion-txt'];
        $calle = $_POST['calle-txt'];
        $numero = $_POST['numero-txt'];
        $codigo_postal = $_POST['codigo_postal-txt'];
        $telefono = $_POST['telefono-txt'];
        $email = $_POST['email-txt'];
        $logo = $_POST['logo-txt'];

        $empresa = new ControladorEmpresa($rfc, $razon_social, $nombre_tienda, $descripcion, $calle, $numero, $codigo_postal, $telefono, $email, $logo);
        # Validación
        $resultado_validacion = $empresa -> validarDatos();

        if($resultado_validacion !== null) {
            echo 'Servidor: <br>';
            foreach($resultado_validacion as $error) {
                echo ($error . '<br>');
            }
            exit;
        } else {
            $resultado_registro = $empresa -> ctrlEditar($rfc_original);

            if($resultado_registro === true) { # Registro exitoso
                echo '<script type="text/javascript">
                window.location.href = "index.php?pagina=empresa&opciones=editar&estado=exito";
                </script>';
                exit;
            } else {
                echo '<div id="alerta-formulario" class=alerta-roja>' . $resultado_registro . '</div>';
                exit;
            }
        }
    }

    // CRUD PARA REDES SOCIALES
    public static function registrarRedSocial() {
        if(!isset($_POST['red_nombre-txt']) || !isset($_POST['red_url-txt'])) {
            return;
        }
        
        $red_nombre = $_POST['red_nombre-txt'];
        $red_url = $_POST['red_url-txt'];

        $listaDeErrores = [];
        if(strlen($red_nombre) < 1 || strlen($red_nombre) > 150) array_push($listaDeErrores, 'El nombre de la red social debe tener entre 1 y 150 caracteres.');
        if(strlen($red_url) < 10 || strlen($red_url) > 200) array_push($listaDeErrores, 'La URL debe tener entre 10 y 200 caracteres.');

        if(count($listaDeErrores) > 0) {
            echo 'Servidor: <br>';
            foreach($listaDeErrores as $error) {
                echo ($error . '<br>');
            }
            exit;
        } else {
            $listaDatos = [$red_nombre, $red_url];

            $consulta_modelo = new ModeloEmpresa();
            $resultado_registro = $consulta_modelo->mdlRegistrarRedSocial($listaDatos);

            if($resultado_registro === true) { # Registro exitoso
                echo '<div id="alerta-formulario" class=alerta-verde>Registro exitoso</div>';
                echo '<script type="text/javascript">
                window.location.href = "index.php?pagina=empresa&opciones=editar&estado=exito";
                </script>';
                exit();
            } else {
                echo '<div id="alerta-formulario" class=alerta-roja>' . $resultado_registro . '</div>';
                exit;
            }
        }
    }

    public static function leerRedSocial($id = '') {
        $modelo_consulta = new ModeloEmpresa;
        return $modelo_consulta -> mdlLeerRedSocial($id);
    }

    public static function editarRedSocial() {
        if(!isset($_POST["red_id-txt"]) ||
        !isset($_POST["red_nombre_editar-txt"]) ||
        !isset($_POST["red_url_editar-txt"])) return;

        $red_nombre = $_POST['red_nombre_editar-txt'];
        $red_url = $_POST['red_url_editar-txt'];
        $red_id = $_POST['red_id-txt'];

        $listaDeErrores = [];
        if(strlen($red_nombre) < 1 || strlen($red_nombre) > 150) array_push($listaDeErrores, 'El nombre de la red social debe tener entre 1 y 150 caracteres.');
        if(strlen($red_url) < 10 || strlen($red_url) > 200) array_push($listaDeErrores, 'La URL debe tener entre 10 y 200 caracteres.');
        if(strlen($red_id) < 1) array_push($listaDeErrores, 'Debe ingresar un ID.');
        if($red_id < 1 || !preg_match('/^([0-9]+)$/', $red_id)) array_push($listaDeErrores, 'Debe ingresar un ID válido');

        if(count($listaDeErrores) > 0) {
            echo 'Servidor: <br>';
            foreach($listaDeErrores as $error) {
                echo ($error . '<br>');
            }
            exit;
        } else {
            $listaDatos = [$red_nombre, $red_url, $red_id];

            $consulta_modelo = new ModeloEmpresa();
            $resultado_registro = $consulta_modelo->mdlEditarRedSocial($listaDatos);

            if($resultado_registro === true) { # Registro exitoso
                echo '<div id="alerta-formulario" class=alerta-verde>Registro exitoso</div>';
                echo '<script type="text/javascript">
                window.location.href = "index.php?pagina=empresa&opciones=editar&estado=exito";
                </script>';
                exit;
            } else {
                echo '<div id="alerta-formulario" class=alerta-roja>' . $resultado_registro . '</div>';
                exit;
            }
        }
    }

    public static function borrarRedSocial() {
        if(!isset($_GET['borrar'])) return;

        $red_id = $_GET['borrar'];

        $listaDeErrores = [];
        if(strlen($red_id) < 1) array_push($listaDeErrores, 'Debe ingresar un ID');
        if($red_id < 1 || !preg_match('/^([0-9]+)$/', $red_id)) array_push($listaDeErrores, 'Debe ingresar un ID válido');
        
        if(count($listaDeErrores) > 0) {
            echo 'Servidor: <br>';
            foreach($listaDeErrores as $error) {
                echo ($error . '<br>');
            }
            exit;
        } else {
            $listaDatos = [$red_id];
            
            $modelo_consulta = new ModeloEmpresa;
            $resultado_registro = $modelo_consulta -> mdlBorrarRedSocial($listaDatos);

            if($resultado_registro === true) { # Registro exitoso
                echo '<div id="alerta-formulario" class=alerta-verde>Eliminación completa</div>';
                echo '<script type="text/javascript">
                window.location.href = "index.php?pagina=empresa&opciones=editar&estado=exito";
                </script>';
                exit;
            } else {
                echo '<div id="alerta-formulario" class=alerta-roja>' . $resultado_registro . '</div>';
                exit;
            }
        }
    }
}