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
    }

?>