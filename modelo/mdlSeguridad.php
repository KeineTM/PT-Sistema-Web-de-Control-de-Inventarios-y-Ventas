<?php
    # Esta clase contiene diferentes métodos relacionados con la seguridad del sitio web
    # Como son los encriptados y desencriptados de las contraseñas del sistema
    class mdlSeguridad {

        # Método para encriptación de contraseñas utilizando el método por defecto más seguro de PHP
        static public function mdlEncriptarPassword($password) {
            // Tipo de encriptación:
            define("HASH", "PASSWORD_DEFAULT");
            // Veces que se usa el HASH de encriptación (costo):
            define("COST", 5);
            // Generación del HASH (encriptación)
            return password_hash($password, HASH, ['cost' => COST]);
        }

        # Método que usa password_verify() para comparar y determinar si la contraseña y el hash ingresados corresponden
        static public function mdlDesencriptarPassword($password, $hash) {
            return password_verify($password, $hash);
        }
    }

?>