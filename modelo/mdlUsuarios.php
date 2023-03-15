<?php
    # Recuperamos la clase de conexión de esta misma carpeta
    require_once "conexion.php";

    # En esta clase se definen las consultas a la BD relacionadas con los usuarios del sistema
    class mdlUsuarios {

        # Método que inicia la conexión y ejecuta la consulta del usuario ingresado
        static public function mdlValidarUsuario($usuarioID) {
            $consulta = Conexion::conectar() -> prepare("SELECT usuario_id, password, tipo_usuario, 
                CONCAT(nombre, apellido_paterno, apellido_materno) AS nombre_completo 
                FROM usuarios WHERE usuario_id = ? LIMIT 1");
            
            // Vinculación de variable en la consulta:
            // En este caso se valida que el dato $usuarioID sea una cadena de máximo 6 caracteres
            $consulta -> bindParam(1, $usuarioID, PDO::PARAM_STR, 6);
            $consulta -> execute();

            #return $consulta -> fetchAll();
            $resultado = $consulta->get_result();
            $fila = $resultado->fetch_assoc();
            return $fila['password'];

            // Cierre de la conexión
            $consulta -> close();
            $consulta = null;
        }

    }
?>