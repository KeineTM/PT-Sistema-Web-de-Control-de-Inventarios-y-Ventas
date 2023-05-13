<?php 
/**
 * ModeloConexion es una clase abstracta que define los métodos para establecer conexión con la base de datos, 
 * así como lo métodos que se emplean para realizar el CRUD sobre ella según lo requiera cada clase hija. 
 * Las clases hijas serán todas aquellas que necesiten establecer conexiones a la base de datos.
 */
abstract class ModeloConexion {
    private static $db_host = "localhost";
    private static $db_nombre = "tienda";
    protected $db_usuario; // HAY QUE CAMBIARLO PARA EL ADMINISTRADOR Y EL EMPLEADO
    protected $db_password;
    protected $conexion;
    protected $sentenciaSQL;
    protected $registros = array(); // lista para los registros recuperados o para datos por registrar

    /** Método que inicia conexión con la base de datos */
    protected function abrirConexion() {
        try {
            # Sintaxis $mbd = new PDO('mysql:host=$host;dbname=$bdname', $usuario, $contraseña);
            $this->conexion = new PDO(
                "mysql:host=".self::$db_host. 
                ";dbname=".self::$db_nombre, 
                $this->db_usuario, 
                $this->db_password);

            # Activa el modo de error para capturar los errores que puedan surgir
            $this->conexion -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
        } catch(PDOException $error) {
            die($error->getMessage());
        }
    }

    /**  Método que cierra la conexión a base de datos */
    protected function cerrarConexion() {
        $this->conexion = null;
    }

    /** Método para ejecutar consultas que realizan cambios en la base de datos sin devolver datos: Create, Update y Delete.
     * Si todo sale correcto devuelve true.
     * Si se para el argumento $retornar_id como true, retornará el último ID registrado en caso de éxito
     * En caso de error devuelve una cadena con dicho error
     */
    protected function consultasCUD($retornar_id = false) {
        try {
            $this->abrirConexion(); # Conecta
            $pdo = $this->conexion -> prepare($this->sentenciaSQL); # Crea PDOStatement
            
            # Recorre la lista de datos ligando parámetros a la sentencia SQL:
            for($i = 0; $i < sizeof($this->registros); $i++) {
                $pdo -> bindParam($i+1, $this->registros[$i]);
            }

            $pdo -> execute(); # Ejecuta

            if($retornar_id === true) { # Si se especifica que debe retornar el último ID registrado
                $id = $this->conexion->lastInsertId();
                return $id; # Retorna el ID si se solicita y fue exitoso
            } else
                return true; # Retorna true si fue exitoso

        } catch(PDOException $e) {
            return 'Error: ' .$e->getMessage(); # Si hubo un error lo Retorna
        } finally {
            $pdo = null; # Limpia
            $this->cerrarConexion(); # Cierra
        }
    }

    /** Método para ejecutar consultas que recuperan información de la base de datos. */
    protected function consultaRead($id='') {
        try {
            $this->abrirConexion(); # Conecta
            $pdo = $this->conexion -> prepare($this->sentenciaSQL); # Crea PDOStatement
            
            if($id !== '') { # Si se determinaron condiciones para la lectura
                $pdo -> bindParam(1, $id);
            }
    
            $pdo -> execute(); # Ejecuta
            $this->registros = $pdo -> fetchAll(PDO::FETCH_ASSOC); # Recupera datos
    
            return $this->registros;

        } catch(PDOException $e) {
            return 'Error: ' . $e->getMessage(); # Si hubo un error lo Retorna
        } finally {
            $pdo = null; # Limpia
            $this->cerrarConexion(); # Cierra
        }
    }
}
?>