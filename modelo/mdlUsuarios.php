<?php
# Recuperamos la clase de conexión de esta misma carpeta
require_once "mdlConexion.php";

/** Clase que hereda del mdlConexion y define las consultas a la BD relacionadas con los usuarios del sistema */
class ModeloUsuarios extends ModeloConexion {
    # Método constructor
    public function __construct() {
        $this->db_usuario = "root";
        $this->db_password = "";
    }

    # Implementación de los métodos abstractos
    public function mdlRegistrar($listaDatos) {
        $this->registros = $listaDatos;
        $this->sentenciaSQL = 'INSERT INTO usuarios VALUES (?,?,?,?,?,?,?,?,?,?,?)';
        return $this->consultasCUD();
    }

    /** Método que recupera toda la información de la tabla de usuarios. 
     * Si se especifica un ID, entonces extraerá sólo la información correspondiente a este, de no encontrarlo retorna null.
     */
    public function read($usuario_id='') {
        $this->sentenciaSQL = ($usuario_id === '')  # Asigna el valor de consulta
            ? "SELECT * FROM usuarios ORDER BY estado DESC"
            : "SELECT usuarios.usuario_id, usuarios.nombre, usuarios.apellido_paterno, usuarios.apellido_materno, usuarios.telefono,
                usuarios.rfc, usuarios.email, usuarios.password, usuarios.notas, usuarios.estado, tipos_usuario.tipo_usuario 
                FROM usuarios 
                INNER JOIN tipos_usuario ON usuarios.tipo_usuario = tipos_usuario.tipo_id
                WHERE usuarios.usuario_id = ? LIMIT 1";
        
        return $this->consultaRead($usuario_id);
    }

    /** Método que busca un usuario por su ID en la tabla para recuperar la información necesaria para el inicio de sesión, de no encontrarlo retorna null */
    public function readLogin($usuario_id) {
        $this->sentenciaSQL = "SELECT usuarios.usuario_id, usuarios.password, tipos_usuario.tipo_usuario, 
                CONCAT(usuarios.nombre,' ',usuarios.apellido_paterno,' ',usuarios.apellido_materno) AS nombre_completo 
                FROM usuarios 
                INNER JOIN tipos_usuario ON usuarios.tipo_usuario = tipos_usuario.tipo_id
                WHERE usuarios.usuario_id = ? AND usuarios.estado = 1 LIMIT 1"; # Determina que sólo podrán iniciar sesión si tienen un estado activo (1)
        
        return $this->consultaRead($usuario_id); # Retorna el resultado de una consulta de lectura
        
    }

    public function update($listaDatos) {
        $this->registros = $listaDatos;
        $this->sentenciaSQL = 
            'UPDATE usuarios SET usuario_id = ?, nombre = ?, apellido_paterno = ?, apellido_materno = ?,
            telefono = ?, rfc = ?, email = ?, password = ?, notas = ?, estado = ?, tipo_usuario = ?
            WHERE usuario_id = ? LIMIT 1';
    
        return $this->consultasCUD();
    }

    public function delete() {

    }

}
?>