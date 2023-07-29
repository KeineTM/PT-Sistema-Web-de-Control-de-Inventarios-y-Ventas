<?php
# Recuperamos la clase de conexión de esta misma carpeta
require_once "mdlConexion.php";

class ModeloEmpresa extends ModeloConexion {
    public function __construct()
    {
        $this->db_usuario = "root";
        $this->db_password = "";
    }

    // CRUD para la tabla NEGOCIO
    public function mdlRegistrar($listaDatos) {
        $this->registros = $listaDatos;
        $this->sentenciaSQL = 'INSERT INTO negocio VALUES (?,?,?,?,?,?,?,?,?,?)';
        return $this -> consultasCUD();
    }

    public function mdlLeer($id='') {
        $this->sentenciaSQL = 
            'SELECT * FROM negocio
            INNER JOIN codigos_postales ON negocio.codigo_postal = codigos_postales.codigo_postal
            INNER JOIN estados ON codigos_postales.estado_id = estados.estado_id
            LIMIT 1';
        return $this -> consultaRead($id);
    }

    public function mdlEditar($listaDatos) {
        $this->registros = $listaDatos;
        $this->sentenciaSQL = 
            'UPDATE negocio
            SET rfc = ?,
            razon_social = ?,
            nombre_tienda = ?,
            descripcion = ?,
            calle = ?,
            numero = ?,
            codigo_postal = ?,
            telefono = ?,
            email = ?,
            logo = ?
            WHERE rfc = ?
            LIMIT 1';
        return $this -> consultasCUD();
    }

    // CRUD para la tabla REDES_SOCIALES:
    public function mdlRegistrarRedSocial($listaDatos) {
        $this->registros = $listaDatos;
        $this->sentenciaSQL = 'INSERT INTO redes_sociales VALUES (?,?)';
        return $this -> consultasCUD();
    }

    public function mdlLeerRedSocial() {

    }

    public function mdlEditarRedSocial() {

    }

    public function mdlBorrarRedSocial() {

    }
}