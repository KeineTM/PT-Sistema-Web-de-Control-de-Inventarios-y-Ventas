<?php
    /** 
     * Esta clase contiene diferentes métodos relacionados con la seguridad del sitio web, 
     * como son los encriptados y desencriptados de las contraseñas del sistema
     */
    class ControladorSeguridad {
        // Tipo de encriptación:
        private const HASH = "PASSWORD_DEFAULT";
        // Veces que se usa el HASH de encriptación (costo):
        private const COST = 10;
        // Clave secreta reCAPTCHA
        private static $claves = [
            'publica' => '6LfZkFIlAAAAAAgFDKJkqcOVUlJcAVuM1mEvO9CI',
            'privada' => '6LfZkFIlAAAAAHPGswFkjoWcs8KUHBKFbYF-Cv-c'
        ];

        /** Método para encriptación de contraseñas utilizando el método por defecto más seguro de PHP */
        static public function ctrlEncriptarPassword($password) {
            // Generación del HASH (encriptación)
            return password_hash($password, self::HASH, ['cost' => self::COST]);
        }

        /** Método para comparar y verificar si la contraseña y el hash ingresados corresponden, además de actualizar el hash */
        static public function ctrlValidarPassword($password, $hash) {
            return password_verify($password, $hash);
                /*if(password_needs_rehash($hash, self::HASH, ['cost' => self::COST]))
                    $hashNuevo = self::ctrlEncriptarPassword($password);*/
        }

        static public function getClavesReCAPTCHA() {
            return self::$claves;
        }

        /** Método evaluación de reCAPTCHA, manda y recupera la información del API de Google para retornar:
         * true = si el usuario se evalúa humano,
         * false = si el usuario se evalúa bot;
         */
        static public function ctrlEvaluarReCAPTCHA($token) {
            $url = 'https://www.google.com/recaptcha/api/siteverify';
            $respuesta = file_get_contents($url . '?secret=' . self::$claves['privada'] . '&response=' . $token); // Almacena el resultado de la evaluación del token
            $json = json_decode($respuesta, true); // Interpreta la respuesta

            // Evalúa que el token sea válido y que el score (provisto por Google) sea mayor que 0.7 para tomarlo como válido
            if($json['success'] === false && $json['score'] < 0.7)
                return false;
            else
                return true;
        }

        ## Validaciones
        /** Recibe una lista de datos. Retorna true si todos los datos no están vacíos y false si al menos uno está vacío */
        static public function validarVacio($listaDatos) {
            $contador = 0;
            foreach($listaDatos as $dato) {
                if(strlen($dato) > 0) $contador++;
            }

            if($contador === sizeof($listaDatos))
                return true;
            else
                return false;
        }

        /** Recibe una lista de datos. Retorna true si todos los datos son tipo INT, de lo contrario devuelve false */
        static public function validarEnterno($listaDatos) {
            $contador = 0;
            $regex = '/^([0-9])*$/'; # Números enteros de máximo 4 caracteres.

            foreach($listaDatos as $dato) {
                if(preg_match($regex, $dato)) $contador++;
            }

            if($contador === sizeof($listaDatos))
                return true;
            else
                return false;
        }

        /** Recibe una lista de datos. Retorna true si se trata de un número con un máximo de 2 decimales, de lo contrario devuelve false */
        static public function validarDecimal($listaDatos) {
            $contador = 0;
            $regex = '/^[0-9]{1,10}(\.[0-9]{1,2})?$/'; # Números decimales de máximo 10 digitos y de 0 a 2 decimales

            foreach($listaDatos as $dato) {
                if(preg_match($regex, $dato)) $contador++;
            }

            if($contador === sizeof($listaDatos))
                return true;
            else
                return false;
        }

        /** Compara una fecha de caducidad con la fecha actual, retorna true si la fecha ingresada es mayor que la actual, de lo contrario retorna false */
        static public function validarCaducidad($fecha) {
            $fecha_actual = date("Y-m-d");
            if($fecha_actual > $fecha)
                return false;
            else
                return true;
        }
    }

?>