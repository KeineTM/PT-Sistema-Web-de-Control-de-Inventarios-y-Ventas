<?php 
    # En esta clase se define la conexión a la base de datos

    class Conexion {
        # Método que inicia conexión con la base de datos
        static public function conectar() {
            $host = "localhost";
            $usuario = "root"; // HAY QUE CAMBIARLO PARA EL ADMINISTRADOR Y EL EMPLEADO
            $password = "";
            $nombreBD = "tienda";

            # Empleo de Sentencias Preparadas u Objetos de Datos PHP PDO
            $link = new PDO("mysql:host=".$host.";dbname=".$nombreBD,$usuario,$password);

            # Inclusión de caracteres especiales como emojis:
            $link->exec("set name utf8mb4");
        }
    }
?>