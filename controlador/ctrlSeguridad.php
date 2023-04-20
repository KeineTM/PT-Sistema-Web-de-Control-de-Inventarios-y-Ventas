<?php
/** 
 * Esta clase contiene diferentes métodos relacionados con la seguridad del sitio web, 
 * como son los encriptados y desencriptados de las contraseñas del sistema
 */
class ControladorSeguridad
{
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

    static public function getClavesReCAPTCHA() { return self::$claves; }

    /** Método evaluación de reCAPTCHA, manda y recupera la información del API de Google para retornar:
     * true = si el usuario se evalúa humano,
     * false = si el usuario se evalúa bot;
     */
    static public function ctrlEvaluarReCAPTCHA($token) {
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $respuesta = file_get_contents($url . '?secret=' . self::$claves['privada'] . '&response=' . $token); // Almacena el resultado de la evaluación del token
        $json = json_decode($respuesta, true); // Interpreta la respuesta

        // Evalúa que el token sea válido y que el score (provisto por Google) sea mayor que 0.7 para tomarlo como válido
        if ($json['success'] === false && $json['score'] < 0.7)
            return false;
        else
            return true;
    }

    ## Validaciones
    /** Función que define y devuelve una lista de mensajes de error */
    static private function listarMensajesErrorProducto() {
        return [
            'productoID' => [
                'vacio' => 'El código del producto no puede quedar vacío',
                'formato' => 'El código del producto sólo admite números y letras',
                'muyLargo' => 'El máximo de letras es 20'
            ],
            'nombreProducto' => [
                'vacio' => 'El nombre del producto no puede quedar vacío',
                'muyCorto' => 'El nombre del producto debe ser mayor de 4 letras',
                'muyLargo' => 'El nombre del producto no puede tener más de 80 letras'
            ],
            'categoriaID' => [
                'vacio' => 'Debe seleccionar una categoria',
                'muyLargo' => 'Seleccione una categoria válida'
            ],
            'descripcion' => ['muyLargo' => 'Seleccione una categoría de la lista'],
            'unidades' => [
                'vacio' => 'Las unidades no pueden quedar vacías',
                'limiteMin' => 'Las unidades deben ser mayores de 0',
                'limiteMax' => 'Las unidades deben ser menores de 9999',
                'formato' => 'Las unidades sólo pueden ser números'
            ],
            'unidadesMinimas' => [
                'limiteMin' => 'Las unidades mínimas deben ser mayores de 0',
                'limiteMax' => 'Las unidades mínimas deben ser menores de 9999',
                'formato' => 'Las unidades mínimas sólo pueden ser números'
            ],
            'precioCompra' => [
                'limiteMin' => 'El precio de compra debe ser mayor de 0',
                'limiteMax' => 'El precio de compra debe ser menores de 9999',
                'formato' => 'El precio de compra sólo admite números con hasta 2 decimales. Ej: 0.50'
            ],
            'precioVenta' => [
                'vacio' => 'Debe ingresar un precio de venta',
                'limiteMin' => 'El precio de venta debe ser mayor de 0',
                'limiteMax' => 'El precio de venta debe ser menores de 9999',
                'formato' => 'El precio de venta sólo admite números con hasta 2 decimales. Ej: 0.50',
            ],
            'precioMayoreo' => [
                'limiteMin' => 'El precio de mayoreo debe ser mayor de 0',
                'limiteMax' => 'El precio de mayoreo debe ser menores de 9999',
                'formato' => 'El precio de mayoreo sólo admite números con hasta 2 decimales. Ej: 0.50'
            ],
            'caducidad' => [
                'formato' => 'El formato de la fecha debe ser aaaa-mm-dd',
                'limiteMin' => 'La fecha no puede ser anterior al día de hoy',
                'limiteMax' => 'La fecha no puede ir más allá de 5 años'
            ],
            'imagenURL' => [
                'formato' => 'La dirección debe terminar con extensión .jpg, .png o .webp',
                'muyLargo' => 'La dirección no debe sobrepasar las 250 letras'
            ]
        ];
    }

    /** Método que recibe dos strings, una con el nombre del campo y otro con el tipo de error detectado para devolver el mensaje correspondiente */
    static private function mostrarMensajeDeError($campo, $errorEncontrado) {
        $listaMensajes = self::listarMensajesErrorProducto();
        $tipoError = ['vacio', 'formato', 'muyCorto', 'muyLargo', 'limiteMin', 'limiteMax'];

        $mensaje = null;

        foreach ($tipoError as $error) {
            if ($errorEncontrado === $error) $mensaje = $listaMensajes[$campo][$error];
        }
        return $mensaje;
    }

    ## Validaciones
    /** Método que compara el valor del campo con una expresión regular.
     * Recibe el nombre del campo, el valor a comprar, la expresión regular y una referencia a esta.
     * Ej: 'números y letras', 'fechas con formato: aaaa-mm-dd'.
     */
    static public function validarFormato($campo, $campo_valor, $regex, $referencia) {
        return (!preg_match($regex, $campo_valor))
                ? 'El campo ' . $campo . ' solo admite ' . $referencia
                : null;
    }

    /** Método que evalúa que la longitud de caracteres de un dato corresponda con los límites ingresados */
    static public function validarLongitudCadena($campo, $campo_valor, $longitud_minima, $longitud_maxima) {
        // Se quita la posibilidad de recibir números negativos
        $limiteMin = abs($longitud_minima);
        $limiteMax = abs($longitud_maxima);
        $mensaje = null;

        // Si el campo NO debe quedar vacío
        if ($limiteMin > 0 && strlen($campo_valor) === 0) 
            return $mensaje = 'El campo ' . $campo . ' no puede quedar vacio';
        if(strlen($campo_valor) < $limiteMin) 
            $mensaje = 'El campo ' . $campo . ' debe tener mas de ' . $limiteMin . ' letras';
        if(strlen($campo_valor) > $limiteMax) 
            $mensaje = 'El campo ' . $campo . ' debe tener menos de ' . $limiteMax . ' letras';

        return $mensaje;
    }

    /** Método que evalúa que el valor de un número se encuentre entre los límites ingresados */
    static public function validarRangoNumerico($campo, $campo_valor, $limite_minimo, $limite_maximo) {
        // Se quita la posibilidad de recibir números negativos
        $limiteMin = abs($limite_minimo);
        $limiteMax = abs($limite_maximo);
        $mensaje = null;

        if($campo_valor < $limiteMin) 
            $mensaje = 'El campo ' . $campo . ' debe ser mayor o igual a ' . $limiteMin;
        if(strlen($campo_valor) > $limiteMax) 
            $mensaje = 'El campo ' . $campo . ' debe ser menor a ' . $limiteMax;

        return $mensaje;
    }

    /** Método que recibe una fecha para comparar si se encuentra entre el límite del día de hoy y 5 años después */
    static public function validarFecha($campo, $fecha) {
        // Formato yyyy-mm-dd, además los meses no pueden superar 12 y los días 31
        $regex = '/^\d{4}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])$/';
        $fecha_min = date("Y-m-d");
        $fecha_max = date("Y-m-d", strtotime("+5 year", strtotime($fecha_min)));
        $mensaje = null;

        if (preg_match($regex, $fecha)) {
            if ($fecha < $fecha_min) $mensaje = 'La fecha de ' . $campo . ' no puede ser anterior a ' . $fecha_min;
            if ($fecha > $fecha_max) $mensaje = 'La fecha de ' . $campo . ' no puede ir mas alla de ' . $fecha_max;
        } else
            $mensaje = 'La fecha de ' . $campo . ' debe tener un formato aaaa-mm-dd. Ej: 2025-02-27';

        return $mensaje;
    }
}