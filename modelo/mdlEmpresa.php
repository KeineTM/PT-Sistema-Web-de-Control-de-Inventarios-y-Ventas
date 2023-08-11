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
        $this->sentenciaSQL = 'INSERT INTO redes_sociales(nombre_red, url) VALUES (?,?)';
        return $this -> consultasCUD();
    }

    public function mdlLeerRedSocial($id = '') {
        $this->sentenciaSQL = ($id === '') 
            ? 'SELECT * FROM redes_sociales'
            : 'SELECT * FROM redes_sociales WHERE red_id = ?';
        return $this -> consultaRead($id);
    }

    public function mdlEditarRedSocial($listaDatos) {
        $this->registros = $listaDatos;
        $this->sentenciaSQL = 'UPDATE redes_sociales SET nombre_red = ?, url = ? WHERE red_id = ?';
        return $this -> consultasCUD();
    }

    public function mdlBorrarRedSocial($listaDatos) {
        $this->registros = $listaDatos;
        if(count($listaDatos) > 0) {
            $this->sentenciaSQL = 'DELETE FROM redes_sociales WHERE red_id = ?';
        } else {
            return 'Servidor: No hay datos suficientes para realizar esta operación.';
        }
        return $this -> consultasCUD($listaDatos);
    }
}