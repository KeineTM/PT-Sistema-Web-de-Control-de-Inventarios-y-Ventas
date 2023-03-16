<?php
    # Recuperamos la clase de conexión de esta misma carpeta
    require_once "conexion.php";

    /**
     * En esta clase se definen las consultas a la BD relacionadas con los usuarios del sistema
     */
    class ModeloUsuarios {

        /** 
         * Método que inicia la conexión y ejecuta la consulta del usuario ingresado.
         * Recibe como parámetro el id del usuario a validar.
         * La información recuperada es el usuario_id, password, tipo_usuario (referenciado de la tabla tipos_usuario) y la concatenación del nombre
         */
        static public function mdlConsultarUsuarios($usuarioID) {
            $consulta = Conexion::conectar() -> prepare("SELECT usuarios.usuario_id, usuarios.password, tipos_usuario.tipo_usuario, 
                CONCAT(usuarios.nombre,' ',usuarios.apellido_paterno,' ',usuarios.apellido_materno) AS nombre_completo 
                FROM usuarios 
                INNER JOIN tipos_usuario ON usuarios.tipo_usuario = tipos_usuario.tipo_id
                WHERE usuarios.usuario_id = ? LIMIT 1");
            
            // Vinculación de variable en la consulta para evitar la inyección SQL
            // En este caso se liga el signo '?' a la variable $usuarioID
            $consulta -> bindValue(1, $usuarioID, PDO::PARAM_STR);
            $consulta -> execute();
            
            // FETCH_ASSOC permite asignar al puntero el nombre que tiene el campo en la tabla
            // En este caso 'usuario_id', 'password', 'tipo_usuario', 'nombre_completo'
            return $consulta -> fetch(PDO::FETCH_ASSOC);

            // Cierre de la conexión
            $consulta -> close();
            $consulta = null;
        }

    }
?>