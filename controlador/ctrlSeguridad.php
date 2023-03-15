<?php
    # Esta clase contiene diferentes métodos relacionados con la seguridad del sitio web
    # Como son los encriptados y desencriptados de las contraseñas del sistema
    class ctrlSeguridad {

        # Método para encriptación de contraseñas utilizando el método por defecto más seguro de PHP
        static public function ctrlEncriptarPassword($password) {
            return mdlSeguridad::mdlEncriptarPassword($password);
        }

        # Método para comparar y determinar si la contraseña y el hash ingresados corresponden
        static public function ctrlDesencriptarPassword($password, $hash) {
            return mdlSeguridad::mdlDesencriptarPassword($password, $hash);
        }
    }

?>