<?php
require_once 'mdlConexion.php';

class ModeloContactos extends ModeloConexion {
        # Método constructor
    public function __construct() {
        $this->db_usuario = "root";
        $this->db_password = "";
    }

    public function mdlRegistrar($listaDatos) {
        $this->registros = $listaDatos;
        $this->sentenciaSQL = 'INSERT INTO contactos VALUES (?,?,?,?,?,?,?)';
        return $this->consultasCUD();
    }

    /** Método que retorna todos los registros o, si se indica un id, retorna la coincidencia.
     * En caso de ser exitoso devuelve un array con el resultado.
     * En caso de fallar, devuelve una cadena con el error.
     */
    public function mdlLeer($id='') {
        $this->sentenciaSQL = ($id === '')
            ? 'SELECT contactos.contacto_id, contactos.nombre, contactos.apellido_paterno, contactos.apellido_materno,
                contactos.email, contactos.notas, contactos.tipo_contacto AS tipo_id,
                tipos_contacto.tipo_contacto
                FROM contactos
                INNER JOIN tipos_contacto ON contactos.tipo_contacto = tipos_contacto.tipo_id'
            : 'SELECT contactos.contacto_id, contactos.nombre, contactos.apellido_paterno, contactos.apellido_materno,
                contactos.email, contactos.notas, contactos.tipo_contacto AS tipo_id,
                tipos_contacto.tipo_contacto
                FROM contactos
                INNER JOIN tipos_contacto ON contactos.tipo_contacto = tipos_contacto.tipo_id
                WHERE contactos.contacto_id = ? LIMIT 1';
        return $this->consultaRead($id);
    }

    public function mdlExiste($id) {
        $this->sentenciaSQL = 'SELECT COUNT(*) FROM contactos WHERE contacto_id = ?';
        return $this->consultaRead($id);
    }

    /** Método que retorna todos los registros de una categoría indicada.
     * En caso de ser exitoso devuelve un array con el resultado.
     * En caso de fallar, devuelve una cadena con el error.
     */
    public function mdlLeerPorCategoria($categoria) {
        $this->sentenciaSQL = 'SELECT contactos.contacto_id, contactos.nombre, contactos.apellido_paterno, contactos.apellido_materno,
            contactos.email, contactos.notas, contactos.tipo_contacto AS tipo_id,
            tipos_contacto.tipo_contacto
            FROM contactos
            INNER JOIN tipos_contacto ON contactos.tipo_contacto = tipos_contacto.tipo_id
            WHERE contacto.tipo_contacto = ?';
        return $this->consultaRead($categoria);
    }

    public function mdlBuscarEnFullText($palabra_clave) {
        $this->sentenciaSQL = 'SELECT contactos.contacto_id, contactos.nombre, contactos.apellido_paterno, contactos.apellido_materno,
            contactos.email, contactos.notas, contactos.tipo_contacto AS tipo_id,
            tipos_contacto.tipo_contacto
            FROM contactos
            INNER JOIN tipos_contacto ON contactos.tipo_contacto = tipos_contacto.tipo_id
            WHERE MATCH(contactos.contacto_id, contactos.nombre, contactos.apellido_paterno, contactos.apellido_materno)
            AGAINST (?)';
        return $this->consultaRead($palabra_clave);
    }

    /** Método que envía una lista de datos para actualizar un registro de la BD.
     * En caso se ser exitoso devuelve TRUE, de lo contrario devuelve una cadena con el error.
     * Los datos en la cadena deben tener el orden: contacto_id(NUEVO), nombre, apellido_paterno, apellido_materno,
     * email, notas, tipo_contacto, contacto_id(ORIGINAL).
     */
    public function mdlEditar($listaDatos) {
        $this->registros = $listaDatos;
        $this->sentenciaSQL = 'UPDATE contactos SET contacto_id = ?, nombre = ?, apellido_paterno = ?, apellido_materno = ?,
            email = ?, notas = ?, tipo_contacto = ?
            WHERE contacto_id = ? LIMIT 1';
        return $this->consultasCUD();
    }

    /** Método que borra de la tabla un contacto con el id indicado */
    public function mdlEliminar($id) {
        array_push($this->registros, $id);
        $this->sentenciaSQL = 'DELETE FROM contactos WHERE contacto_id = ? LIMIT 1';
        return $this->consultasCUD();
    }
}