<?php
# Recuperamos la clase de conexión de esta misma carpeta
require_once "mdlConexion.php";

/** Clase que hereda del mdlConexion y define las consultas a la BD relacionadas con los usuarios del sistema */
class ModeloUsuarios extends ModeloConexion {
    # Método constructor
    public function __construct() {
        $this->db_nombre = "tienda";
    }

    # Implementación de los métodos abstractos
    public function create() {

    }

    public function read($usuario_id='') {
        $this->consulta = ($usuario_id == '')  # Asigna el valor de consulta
            ? "SELECT * FROM usuarios"
            : "SELECT usuarios.usuario_id, usuarios.nombre, usuarios.apellido_paterno, usuarios.apellido_materno, usuarios.telefono,
                usuarios.rfc, usuarios.email, usuarios.password, usuarios.notas, usuarios.estado, tipos_usuario.tipo_usuario 
                FROM usuarios 
                INNER JOIN tipos_usuario ON usuarios.tipo_usuario = tipos_usuario.tipo_id
                WHERE usuarios.usuario_id = ? LIMIT 1";
        
        return $this->consultaRead($usuario_id); # Establece conexión > ejecuta la consulta > asigna valor de $registros
    }

    public function readLogin($usuario_id) {
        $this->consulta = "SELECT usuarios.usuario_id, usuarios.password, tipos_usuario.tipo_usuario, 
                CONCAT(usuarios.nombre,' ',usuarios.apellido_paterno,' ',usuarios.apellido_materno) AS nombre_completo 
                FROM usuarios 
                INNER JOIN tipos_usuario ON usuarios.tipo_usuario = tipos_usuario.tipo_id
                WHERE usuarios.usuario_id = ? AND usuarios.estado = 1 LIMIT 1"; # Determina que sólo podrán iniciar sesión si tienen un estado activo (1)
        
        return $this->consultaRead($usuario_id); # Establece conexión > ejecuta la consulta > asigna valor de $registros
    }

    public function update() {

    }

    public function delete() {

    }

}
?>