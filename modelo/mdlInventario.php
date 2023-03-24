<?php
require_once "mdlConexion.php";

class ModeloProductos extends ModeloConexion{
    # Método constructor
    public function __construct() {
        $this->db_usuario = "root";
        $this->db_password = "";
        $this->db_nombre = "tienda";
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

    public function createCategoria() {

    }

    public function readCategorias($categoria='') {
        $this->sentenciaSQL = ($categoria === '')
            ? "SELECT * FROM categorias_inventario"
            : "SELECT * FROM categorias_inventario WHERE categoria_id = ?";
        
        return $this->consultaRead($categoria);
    }

    public function readCategoriasActivas() {
        $this->sentenciaSQL = "SELECT * FROM categorias_inventario WHERE estado = 1"; 
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