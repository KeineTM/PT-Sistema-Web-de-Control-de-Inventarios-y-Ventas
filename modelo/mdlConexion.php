<?php 
/**
 * ModeloConexion es una clase abstracta que define los métodos para establecer conexión con la base de datos, 
 * así como lo métodos que se emplean para realizar el CRUD sobre ella según lo requiera cada clase hija. 
 * Las clases hijas serán todas aquellas que necesiten establecer conexiones a la base de datos.
 */
abstract class ModeloConexion {
    private static $db_host = "localhost";
    private static $db_usuario = "root"; // HAY QUE CAMBIARLO PARA EL ADMINISTRADOR Y EL EMPLEADO
    private static $db_password = "";
    protected $db_nombre;
    private $conexion;
    protected $consulta;
    protected $registros = array();

    /** Método que inicia conexión con la base de datos */
    private function conectar() {
        # Empleo de Objetos de Datos PHP o PDO para crear conexiones seguras
        try {
            # Sintaxis $mbd = new PDO('mysql:host=localhost;dbname=prueba', $usuario, $contraseña);
            $this->conexion = new PDO(
                "mysql:host=".self::$db_host. 
                ";dbname=".$this->db_nombre, 
                self::$db_usuario, 
                self::$db_password);
        } catch(PDOException $error) {
            die($error->getMessage());
        }
    }

    /**  Método que cierra la conexión a base de datos */
    private function cerrar() {
        $this->conexion = null;
    }

    /**
     * Método para ejecutar consultas que realizan cambios en la base de datos sin devolver datos.
     * Estas son: Create, Update y Delete.
     */
    protected function consultaCUD () {
        $this->conectar(); # Conecta
        $this->conexion->prepare($this->consulta);
        $this->conexion -> execute(); # Ejecuta
        $this->cerrar(); # Cierra
    }

    /** Método para ejecutar consultas que recuperan información de la base de datos. */
    protected function consultaRead ($id = '') {
        $this->conectar(); # Conecta
        $resultado = $this->conexion -> prepare($this->consulta); 
        
        if($id != '') { # Si contiene un parámetro este se liga para evitar SQLinjection
            $resultado -> bindValue(1, $id, PDO::PARAM_STR);
        }

        $resultado -> execute(); # Ejecuta
        while($this->registros[] = $resultado -> fetchAll(PDO::FETCH_ASSOC)); # Recupera datos
        $resultado = null; # Limpia memoria
        $this->cerrar(); # Cierra

        return $this->registros;
    }

    # Métodos abstractos CRUD
    abstract protected function create();
    abstract protected function read();
    abstract protected function update();
    abstract protected function delete();
}
?>