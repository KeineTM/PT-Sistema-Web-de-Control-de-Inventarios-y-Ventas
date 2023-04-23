<?php
require_once "mdlConexion.php";

class ModeloOperaciones extends ModeloConexion {
    # Método constructor
    public function __construct() {
        $this->db_usuario = "root";
        $this->db_password = "";
    }

    /** Método que registra la operación en BD recuperando el ID del registro */
    public function mdlRegistrarOperacion($listaDatos) {
        $this->registros = $listaDatos;
        $this->sentenciaSQL = 
            'INSERT INTO operaciones 
            (operaciones.total, operaciones.descuento, operaciones.subtotal, operaciones.notas, operaciones.tipo_operacion, operaciones.estado, operaciones.cliente_id) 
            VALUES (?,?,?,?,?,?,?)';
        return $this->consultasCUD(true);
    }

    public function mdlRegistrarProductosIncluidos($listaDatos) {
        $this->registros = $listaDatos;
        $this->sentenciaSQL = 
            'INSERT INTO productos_incluidos VALUES (?,?,?,?)';
        return $this->consultasCUD();
    }
    
    public function mdlRegistrarAbono($listaDatos) {
        $this->registros = $listaDatos;
        $this->sentenciaSQL = 
            'INSERT INTO abonos VALUES (?,?,?,?,?)';
        return $this->consultasCUD();
    }
}