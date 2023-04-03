<?php
require_once "mdlConexion.php";

class ModeloProductos extends ModeloConexion{
    # Método constructor
    public function __construct() {
        $this->db_usuario = "root";
        $this->db_password = "";
    }

    /** Método que registra una nueva categoría en la tabla */
    public function create() {
        
    }

    public function read($id='') {
        $this->sentenciaSQL = ($id === '')
            ? "SELECT * FROM productos"
            : "";
    }

    public function update() {
        
    }

    public function delete() {
        
    }

    /** Método que registra una categoría nueva */
    public function createCategoria($categoria) {
        $this->sentenciaSQL = "INSERT INTO categorias_inventario (categoria) VALUES (?)";
        $this -> abrirConexion(); # Conecta

        try {
            $registro = $this->conexion -> prepare($this->sentenciaSQL); #
            $registro -> bindValue(1, $categoria, PDO::PARAM_STR);
            $registro -> execute(); # Ejecuta

            return $categoria . ' registrado correctamente';

        } catch(PDOException $e) {
            return 'Error: ' .$e->getMessage();
        } finally {
            $registro = null; # Limpia
            $this->cerrarConexion(); # Cierra
        }
    }

    /** Este método devuelve todas las categorias en la tabla. Si se le indica un id, sólo devuelve el registro correspondiente */
    public function readCategorias($categoria='') {
        $this->sentenciaSQL = ($categoria === '')
            ? "SELECT * FROM categorias_inventario"
            : "SELECT * FROM categorias_inventario WHERE categoria_id = ?";
        
        return $this->consultaRead($categoria);
    }

    /** Este método devuelve la lista de categorias activas ordenadas alfabéticamente */
    public function readCategoriasActivas() {
        $this->sentenciaSQL = "SELECT * FROM categorias_inventario WHERE estado = 1 ORDER BY categoria"; 
        return $this->consultaRead();
    }

    public function updateCategoria() {
        
    }

    public function createCaducidad() {

    }

    public function readCaducidad() {

    }

    public function updateCaducidad() {
        
    }
}