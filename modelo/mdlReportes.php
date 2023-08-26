<?php
require_once 'mdlConexion.php';

class ModeloReportes extends ModeloConexion {
    # Método constructor
    public function __construct() {
        $this->db_usuario = "root";
        $this->db_password = "";
    }

    
}