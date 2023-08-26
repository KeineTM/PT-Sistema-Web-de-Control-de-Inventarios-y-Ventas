<?php
class ControladorOperaciones
{
    private $subtotal;
    private $descuento;
    private $total;
    private $notas;
    private $tipo_operacion;
    private $estado;
    private $cliente_id;
    private $productos_incuidos = [];
    private $empleado_id;
    private $fecha;
    private $abono;
    private $metodo_pago;
    private static $descuento_max = 0.5; # % descuento máximo

    public function __construct(
        $subtotal,
        $descuento,
        $total,
        $notas,
        $tipo_operacion,
        $estado,
        $cliente_id,
        $productos_incuidos,
        $empleado_id,
        $abono,
        $metodo_pago ) {
        date_default_timezone_set('America/Mazatlan');
        $this->subtotal = $subtotal;
        $this->descuento = $descuento;
        $this->total = $total;
        $this->notas = $notas;
        $this->tipo_operacion = $tipo_operacion;
        $this->estado = $estado;
        $this->cliente_id = $cliente_id;
        $this->productos_incuidos = $productos_incuidos;
        $this->empleado_id = $empleado_id;
        $this->fecha = date('Y-m-d H:i:s'); # Fecha actual
        $this->abono = $abono;
        $this->metodo_pago = $metodo_pago;
    }

    //------------------------ Métodos del carrito-------------------------------------
    /** Método que calcula el total de la suma de productos (sin descuentos) */
    static public function calcularSubtotal($nombre_carrito) {
        $total = 0;
        foreach ($_SESSION[$nombre_carrito] as $producto) {
            $total += $producto->total;
        }
        return $total;
    }

    /** Método que recibe un código o id de producto para agregarlo al carrito con el uso de variables de sesión */
    static public function agregarAlCarrito($nombre_carrito, $tipo_operacion_url) {
        if (isset($_POST['idProducto-txt']) || isset($_GET['idProducto-txt'])) {

            if (isset($_POST['idProducto-txt'])) $producto_id = $_POST['idProducto-txt'];
            else if (isset($_GET['idProducto-txt'])) $producto_id = $_GET['idProducto-txt'];
            
            $busqueda = ControladorProductos::ctrlLeerUno($producto_id);

            # Evalúa si el resultado de la búsqueda devolvió un producto con atributos:
            if ($busqueda == null) {
                echo '<script type="text/javascript">
                        window.location.href = "index.php?pagina=' . $tipo_operacion_url .'&opciones=alta&estado=no-existe";
                        </script>';
                exit;
            }

            # Instancia un objeto de la clase
            $productoPorAgregar = new ControladorProductos(
                $busqueda[0]['producto_id'],
                $busqueda[0]['nombre'],
                $busqueda[0]['categoria'],
                $busqueda[0]['descripcion'],
                $busqueda[0]['unidades'],
                $busqueda[0]['unidades_minimas'],
                $busqueda[0]['precio_compra'],
                $busqueda[0]['precio_venta'],
                $busqueda[0]['estado'],
                $busqueda[0]['foto_url'],
                $busqueda[0]['caducidad']
            );

            # Evalúa unidades existentes
            # Si la operación es una devolución, no se evalúa
            if ($tipo_operacion_url !== 'devoluciones' && $productoPorAgregar->unidades === 0) { # Se acabó el producto
                echo '<script type="text/javascript">
                        window.location.href = "index.php?pagina=' . $tipo_operacion_url . '&opciones=alta&estado=agotado";
                        </script>';
                exit;
            }

            # Evalúa si el producto ya está en el carrito
            $productoEnCarrito = false;
            foreach ($_SESSION[$nombre_carrito] as $indice => $productoExistente) {
                if ($productoExistente->producto_id === $productoPorAgregar->producto_id) {
                    # Si lo está, recupera el valor del índice
                    $productoEnCarrito = $indice;
                    break;
                }
            }

            # Si no hay coincidencias en el carrito se agregan los atributos: cantidad (1) y el total(acumulado por producto)
            if ($productoEnCarrito === false) {
                $productoPorAgregar->cantidad = 1;
                $productoPorAgregar->total = $productoPorAgregar->precioVenta;
                array_push($_SESSION[$nombre_carrito], $productoPorAgregar);
            }
            # Si hay coincidencias aumenta la cantidad en carrito más 1 y actualiza el total acumulado por producto
            else { # siempre y cuando no sobrepase las unidades existentes
                if($tipo_operacion_url !== 'devoluciones' &&
                    $_SESSION[$nombre_carrito][$productoEnCarrito]->cantidad < $_SESSION[$nombre_carrito][$productoEnCarrito]->unidades) {
                    $_SESSION[$nombre_carrito][$productoEnCarrito]->cantidad++;
                    $_SESSION[$nombre_carrito][$productoEnCarrito]->total = ($_SESSION[$nombre_carrito][$productoEnCarrito]->cantidad) * ($_SESSION[$nombre_carrito][$productoEnCarrito]->precioVenta);
                } else if($tipo_operacion_url === 'devoluciones') {
                    $_SESSION[$nombre_carrito][$productoEnCarrito]->cantidad++;
                    $_SESSION[$nombre_carrito][$productoEnCarrito]->total = ($_SESSION[$nombre_carrito][$productoEnCarrito]->cantidad) * ($_SESSION[$nombre_carrito][$productoEnCarrito]->precioVenta);
                } else {
                    echo 
                        '<script type="text/javascript">
                        window.location.href = "index.php?pagina=' . $tipo_operacion_url .'&opciones=alta&estado=maximo";
                        </script>';
                    die();
                }
            }
            # Regresa
            echo 
                '<script type="text/javascript">
                window.location.href = "index.php?pagina=' . $tipo_operacion_url . '&opciones=alta";
                </script>';
            die();

        } else return;
    }

    /** Método que aumenta en 1 la cantidad de un producto en el carrito, mientras este exista. Y no se pueden agregar más de los que se tienen en existencia */
    static public function sumarDelCarrito($nombre_carrito, $tipo_operacion_url) {
        if (!isset($_GET['sumar'])) return;

        $indice = $_GET['sumar'];

        # Si existe el producto en carrito y no se ha alcanzado el máximo de unidades disponibles
        if ($tipo_operacion_url === 'devoluciones' ||
            $_SESSION[$nombre_carrito][$indice]->producto_id && $_SESSION[$nombre_carrito][$indice]->cantidad < $_SESSION[$nombre_carrito][$indice]->unidades) {
            # Suma
            $_SESSION[$nombre_carrito][$indice]->cantidad++;
            $_SESSION[$nombre_carrito][$indice]->total = ($_SESSION[$nombre_carrito][$indice]->cantidad) * ($_SESSION[$nombre_carrito][$indice]->precioVenta);
        
        } else if($tipo_operacion_url !== 'devoluciones' &&
            !$_SESSION[$nombre_carrito][$indice]->cantidad < $_SESSION[$nombre_carrito][$indice]->unidades) {
            echo # Indica que en el carrito ya se tiene el máximo de unidades disponibles
                '<script type="text/javascript">
                window.location.href = "index.php?pagina='  . $tipo_operacion_url . '&opciones=alta&estado=maximo";
                </script>';
            die();
        }

        echo '<script type="text/javascript">
            window.location.href = "index.php?pagina='  . $tipo_operacion_url . '&opciones=alta";
            </script>';
        die();
    }

    /** Método que resta en 1 la cantidad de un producto en el carrito, mientras este exista. Si llega a 0, retira el producto */
    static public function restarDelCarrito($nombre_carrito, $tipo_operacion_url) {
        if (!isset($_GET['restar'])) return;

        $indice = $_GET['restar'];

        if ($_SESSION[$nombre_carrito][$indice]->producto_id) { # Si existe el producto
            $_SESSION[$nombre_carrito][$indice]->cantidad--;
            $_SESSION[$nombre_carrito][$indice]->total = ($_SESSION[$nombre_carrito][$indice]->cantidad) * ($_SESSION[$nombre_carrito][$indice]->precioVenta);

            if ($_SESSION[$nombre_carrito][$indice]->cantidad < 1) { # Si la cantidad de producto llega a 0
                unset($_SESSION[$nombre_carrito][$indice]); # lo retira
                echo # Indica que se ha retirado un producto
                    '<script type="text/javascript">
                    window.location.href = "index.php?pagina='  . $tipo_operacion_url . '&opciones=alta&estado=eliminado";
                    </script>';
                    die();
            }
        }

        echo
            '<script type="text/javascript">
            window.location.href = "index.php?pagina='  . $tipo_operacion_url . '&opciones=alta";
            </script>';
        exit;
    }

    /** Método que recibe un índice para quitar el carrito con el uso de variables de sesión */
    static public function quitarDelCarrito($nombre_carrito, $tipo_operacion_url) {
        if (!isset($_GET['quitar'])) return;

        $indice = $_GET['quitar'];

        #array_splice($_SESSION[$nombre_carrito], $indice, 1);
        unset($_SESSION[$nombre_carrito][$indice]);

        echo # Indica que el producto fue removido
            '<script type="text/javascript">
            window.location.href = "index.php?pagina='  . $tipo_operacion_url . '&opciones=alta&estado=eliminado";
            </script>';
        exit;
    }

    /** Método que vacía la variable de sesión del carrito */
    public function vaciarCarrito($nombre_carrito) {
        unset($_SESSION[$nombre_carrito]);
        $_SESSION[$nombre_carrito] = [];
    }

    static function btnVaciarCarrito($nombre_carrito) {
        if(!isset($_GET['vaciar'])) return;

        unset($_SESSION[$nombre_carrito]);
        $_SESSION[$nombre_carrito] = [];

        return;
    }

    //---------------------------------------------------------------------------------------
    //------------------------ Métodos de las operaciones -------------------------------------

    /** Método para validar un el descuento
     * Retorna false si el dato no coincide con el formato esperado (números y letras)
     * Retorna false si el descuento es mayor al 50% del total
     * Retorna true si no hay dato de descuento o es válido
     */
    static public function validarDescuento($descuento, $nombre_carrito) {
        require_once 'ctrlSeguridad.php';
        $subtotal = 0;
        $resultado = null;
        $regex = '/^[0-9]+(\\.[0-9]{1,2})?$/';
        $referencia = 'números con hasta 2 decimales';
        if (strlen($descuento) !== 0 && $descuento !== null) { # Evalúa si hay un dato para validar el formato
            $resultado = ControladorSeguridad::validarFormato('Descuento', $descuento, $regex, $referencia);

            if ($resultado === null) # Formato valido: valida el rango
                $resultado = ControladorSeguridad::validarRangoNumerico('Descuento', $descuento, 0, 9999);

            if ($resultado === null) { # Pasó las 2 validaciones
                $subtotal = self::calcularSubtotal($nombre_carrito);
                if ($descuento > ($subtotal * self::$descuento_max)) # Evalúa que el descuento con el subtotal
                    return $resultado = false; # El descuento es igual o supera el subtotal
                else
                    return $resultado = true; # El descuento es válido
            }

            return $resultado = false; # No pasó las validaciones  
        } else
            return $resultado = true; # El descuento no tiene valor
    }

    /** Redirección al mensaje de error correspondiente a cada tipo de operación */
    private function mensajeErrorDelServidor($tipo_operacion_url, $nombre_error) {
        return '<script type="text/javascript">
                    window.location.href = "index.php?pagina='  . $tipo_operacion_url . '&opciones=alta&estado=' . $nombre_error . '";
                </script>';
    }

    /** Método de validación de datos para la operación, en caso de encontrar un error lo retorna interrumpiendo el proceso en curso */
    public function validarDatos($nombre_carrito, $tipo_operacion_url) {
        if (!self::validarDescuento($this->descuento, $nombre_carrito)) {
            echo $this->mensajeErrorDelServidor($tipo_operacion_url, 'error-descuento');
            exit;
        }
        if(!is_numeric($this->abono)) {
            echo $this->mensajeErrorDelServidor($tipo_operacion_url, 'error-abono');
            exit;
        }
        if($this->subtotal != self::calcularSubtotal($nombre_carrito) ||
        $this->subtotal <= 0 ||
        $this->total <= 0 ||
        $this->abono < 0 ||
        $this->abono > $this->total) {
            echo $this->mensajeErrorDelServidor($tipo_operacion_url, 'error');
            exit;
        }
        if(strlen($this->tipo_operacion) <= 0 ||
        strlen($this->notas) > 250) {
            echo $this->mensajeErrorDelServidor($tipo_operacion_url, 'incompleto');
            exit;
        }
        if(count($this->productos_incuidos) < 1) {
            echo $this->mensajeErrorDelServidor($tipo_operacion_url, 'error-carrito');
            exit;
        }
    }

    /** Método que ejecuta la serie de consultas que componen una operación completa:
     * Registro en tabla operaciones.
     * Registro en tabla productos incluidos.
     * Actualización de unidades en tabla de productos.
     * Registro en tabla de abonos.
     * Retorna true si todo salió correctamente.
     * De haber un error retorna dicho error.
     */
    public function ctrlRegistrarOperacionCompleta() {
        $datos_operacion = [
            $this->total,
            $this->descuento,
            $this->subtotal,
            $this->notas,
            $this->tipo_operacion,
            $this->estado,
            $this->cliente_id
        ];
        $datos_abono = [
            $this->empleado_id, 
            $this->fecha, 
            $this->abono, 
            $this->metodo_pago
        ];
        $registro = new ModeloOperaciones();
        return $registro -> mdlRegistrarOperacionCompleta($datos_operacion, $this->productos_incuidos, $datos_abono);
    }

    /** Método que recibe los datos del formulario para procesar una venta */
    static public function ctrlCrearVenta($nombre_carrito, $tipo_operacion_url) {
        if (!isset($_POST['total-txt'])) return;
        if (count($_SESSION[$nombre_carrito]) < 1) {
            echo # Indica que el carrito está vacío
            '<script type="text/javascript">
                    window.location.href = "index.php?pagina='  . $tipo_operacion_url . '&opciones=alta&estado=error-carrito";
                </script>';
            exit;
        }
        if (($_POST['total-txt']) <= 0) {
            echo # Indica que el total no puede ser 0 o menor
            '<script type="text/javascript">
                    window.location.href = "index.php?pagina='  . $tipo_operacion_url . '&opciones=alta&estado=error-total";
                </script>';
            exit;
        }

        $descuento = (strlen($_POST['descuento-txt']) > 0)
            ? $_POST['descuento-txt']
            : 0;
        $subtotal = abs($_POST['total-txt']); # Requerido
        $notas = (strlen($_POST['notas-txt']) > 0)
            ? $_POST['notas-txt']
            : null;
        $tipo_operacion = 'VE'; # Venta, Requerido
        $estado = 1; # 1 = Completada, Sólo se usa 0 para Apartados
        $cliente_id = null; # No se requiere cliente para la venta
        $productos_incuidos = new ArrayObject($_SESSION[$nombre_carrito]); # Productos incluídos en el carrito al momento de procesar la venta
        $empleado_id = $_SESSION['idUsuarioSesion']; # Usuario con la sesión activa
        $monto_abonado = null; # En una venta es igual al total pagado
        $metodo_pago = (strlen($_POST['metodo-pago-txt']) !== 0)
            ? abs($_POST['metodo-pago-txt'])
            : 1; # Por defecto asigna el pago en efectivo

        $total = $subtotal - $descuento;
        $monto_abonado = $total; # En una venta es igual al total pagado

        $venta_nueva = new ControladorOperaciones($subtotal, $descuento, $total, $notas, $tipo_operacion, $estado, $cliente_id, $productos_incuidos, $empleado_id, $monto_abonado, $metodo_pago);
 
        $venta_nueva -> validarDatos($nombre_carrito, $tipo_operacion_url);

        $resultado = $venta_nueva -> ctrlRegistrarOperacionCompleta();

        if($resultado === true) {
            $venta_nueva -> vaciarCarrito($nombre_carrito);
            echo # Indica que el la venta fue exitosa
            '<script type="text/javascript">
                window.location.href = "index.php?pagina='  . $tipo_operacion_url . '&opciones=alta&estado=creada";
            </script>';
            exit;
        } else { # Indica que hubo un error
            echo '<div id="alerta-formulario" class="alerta-roja">
                Ocurrió un error: Vuelva a intentar la venta.<br>Detalles: ' . $resultado . '
            </div>';
            exit;
        }    
    }

    /** Método que recibe los datos del formulario para procesar un apartado */
    static public function ctrlCrearApartado($nombre_carrito, $tipo_operacion_url) {
        if (!isset($_POST['total-txt'])) return;
        if (count($_SESSION[$nombre_carrito]) < 1) {
            echo # Indica que el carrito está vacío
            '<script type="text/javascript">
                    window.location.href = "index.php?pagina='  . $tipo_operacion_url . '&opciones=alta&estado=error-carrito";
                </script>';
            exit;
        }
        if (($_POST['total-txt']) <= 0) {
            echo # Indica que el total no puede ser 0 o menor
            '<script type="text/javascript">
                    window.location.href = "index.php?pagina='  . $tipo_operacion_url . '&opciones=alta&estado=error-total";
                </script>';
            exit;
        }
        if(strlen($_POST['cliente_id-txt']) !== 10) {
            echo # Indica que el número de teléfono del cliente incompleto
            '<script type="text/javascript">
                    window.location.href = "index.php?pagina='  . $tipo_operacion_url . '&opciones=alta&estado=incompleto";
                </script>';
            exit;
        }
        # Valida que el cliente está registrado:
        $resultadoCliente = ControladorContactos::ctrlExiste($_POST['cliente_id-txt']);
        if(!$resultadoCliente[0]['COUNT(*)']) {
            echo # Indica que el número de teléfono del cliente no encontrado
            '<script type="text/javascript">
                    window.location.href = "index.php?pagina='  . $tipo_operacion_url . '&opciones=alta&estado=error-telefono";
                </script>';
            exit;
        }

        $descuento = 0;
        $subtotal = abs($_POST['total-txt']); # TOTAL DE LA OPERACIÓN
        $total = $subtotal; # BAJO LA REGLA DE QUE EN APARTADOS NO HAY DESCUENTOS
        $monto_abonado = abs($_POST['abono-txt']); # TOTAL APAGO O ABONADO
        $notas = (strlen($_POST['notas-txt']) > 0)
            ? $_POST['notas-txt']
            : null;
        $tipo_operacion = 'AP'; # Apartado, Requerido
        $estado = ($total === $monto_abonado)
            ? 1 # Finalizada
            : 0; # Abierta
        $cliente_id = $_POST['cliente_id-txt']; # Requerido para un apartado
        $productos_incuidos = new ArrayObject($_SESSION[$nombre_carrito]); # Productos incluídos en el carrito al momento de procesar la venta
        $empleado_id = $_SESSION['idUsuarioSesion']; # Usuario con la sesión activa
        $metodo_pago = (strlen($_POST['metodo-pago-txt']) !== 0)
            ? $_POST['metodo-pago-txt']
            : 1; # Por defecto asigna el pago en efectivo

        $apartado_nuevo = new ControladorOperaciones($subtotal, $descuento, $total, $notas, $tipo_operacion, $estado, $cliente_id, $productos_incuidos, $empleado_id, $monto_abonado, $metodo_pago);
 
        $apartado_nuevo -> validarDatos($nombre_carrito, $tipo_operacion_url);

        $resultado = $apartado_nuevo -> ctrlRegistrarOperacionCompleta();

        if($resultado === true) {
            $apartado_nuevo -> vaciarCarrito($nombre_carrito);
            echo # Indica que el la venta fue exitosa
            '<script type="text/javascript">
                window.location.href = "index.php?pagina='  . $tipo_operacion_url . '&opciones=alta&estado=creada";
            </script>';
            exit;
        } else { # Indica que hubo un error
            echo '<div id="alerta-formulario" class="alerta-roja">
                Ocurrió un error: Vuelva a intentar
            </div>';
            exit;
        }
    }

    /** Método que recupera un apartado abierto (estado = 0) y agrega un abono */
    static public function ctrlAbonarAlApartado() {
        if(!isset($_POST['folio-txt'])) return;
        if(!isset($_POST['abono_nuevo-txt'])) return;

        $operacion_id = $_POST['folio-txt'];
        $empleado_id = $_SESSION['idUsuarioSesion'];
        $fecha_abono = date('Y-m-d H:i:s'); # Fecha actual
        $abono = $_POST['abono_nuevo-txt'];
        $metodo_pago = (isset($_POST['metodo-pago-txt']))
                        ? $_POST['metodo-pago-txt']
                        : 1; # Efectivo

        $regex = '/^[0-9]+(\\.[0-9]{1,2})?$/';
        $total_abonado = 0;
        $total = 0;
        $total_restante = 0;

        if($abono <= 0 ||
            $abono > 9999 ||
            !preg_match($regex, $abono)) {
            $error = 'Servidor: Sólo se aceptan números entre 1 y 9999 con máximo 2 decimales.';
            echo $error;
            exit;
        }

        // Consulta SQL: Obtención de la suma de abonos
        $consulta_abonos = self::ctrlLeerAbonos($operacion_id);

        // Evalúa que el folio exista
        if(count($consulta_abonos) < 1) {
            echo 'No existen datos para este folio.';
            die();
        }

        for($i = 0; $i < count($consulta_abonos); $i++) { 
            $total_abonado += $consulta_abonos[$i]['abono']; #SUMA DE ABONOS
        }

        // Consulta SQL: Obtención del total de la operación
        $consulta_apartado = self::ctrlLeer($operacion_id);
        $total = $consulta_apartado[0]['total'];

        // Calcula el total restante del apartado
        $total_restante = $total - $total_abonado;

        if($total_restante <= 0) {
            echo 'Servidor: Este apartado ya fue pagado.';
            die();
        }

        if($total_restante < $abono) {
            echo 'Servidor: El monto abonado excede a la deuda.';
            die();
        }

        // REGISTRA EL ABONO
        $listaDatos = [
            $operacion_id,
            $empleado_id,
            $fecha_abono,
            $abono,
            $metodo_pago
        ];

        $modelo = new ModeloOperaciones;
        $resultado = $modelo->mdlRegistrarAbono($listaDatos);

        if ($resultado === true) {
            echo # Indica que el registro del abono fue exitoso
                '<div id="alerta-formulario" class="alerta-verde">
                Abono exitoso.
                </div>';

            // EVALÚA SI SE COMPLETÓ EL PAGO PARA REGISTRARLO SI ES ASÍ
            if($abono == $total_restante) {
                $lista = [1];
                $modelo->mdlCompletarAbono($lista);
            }

            die();
        } else { # Indica que hubo un error
            echo '<div id="alerta-formulario" class="alerta-roja">
                Servidor: Ocurrió un error en el registro del abono, inténtelo nuevamente.
                </div>';
            die();;
        }
    }


    static public function ctrlCrearDevolucion($nombre_carrito, $tipo_operacion_url) {
        if(!isset($_POST['total-txt'])) return;
        if(count($_SESSION[$nombre_carrito]) < 1) {
            echo # Indica que el carrito está vacío
            '<script type="text/javascript">
                    window.location.href = "index.php?pagina='  . $tipo_operacion_url . '&opciones=alta&estado=error-carrito";
                </script>';
            exit;
        }
        if(($_POST['total-txt']) <= 0) {
            echo
            '<script type="text/javascript">
                    window.location.href = "index.php?pagina='  . $tipo_operacion_url . '&opciones=alta&estado=error-total";
                </script>';
            exit;
        }

        $descuento = (strlen($_POST['descuento-txt']) > 0)
            ? abs($_POST['descuento-txt'])
            : 0;
        $subtotal = abs($_POST['total-txt']); # Requerido
        $notas = (strlen($_POST['notas-txt']) > 0) # Requerido
            ? $_POST['notas-txt']
            : null;
        $tipo_operacion = 'DE'; # Devolución, Requerido
        $estado = 1;
        $cliente_id = null; # No se requiere cliente para la devolución
        $productos_incuidos = new ArrayObject($_SESSION[$nombre_carrito]);
        $empleado_id = $_SESSION['idUsuarioSesion'];
        $monto_abonado = null; # En una venta es igual al total pagado
        $metodo_pago = (strlen($_POST['metodo-pago-txt']) !== 0)
            ? abs($_POST['metodo-pago-txt'])
            : 1; # Por defecto asigna el pago en efectivo

        $total = $subtotal - $descuento;
        $monto_abonado = $total;

        $devolucion_nueva = new ControladorOperaciones($subtotal, $descuento, $total, $notas, $tipo_operacion, $estado, $cliente_id, $productos_incuidos, $empleado_id, $monto_abonado, $metodo_pago);
 
        $devolucion_nueva -> validarDatos($nombre_carrito, $tipo_operacion_url);

        $resultado = $devolucion_nueva -> ctrlRegistrarOperacionCompleta();

        if($resultado === true) {
            $devolucion_nueva -> vaciarCarrito($nombre_carrito);
            echo # Indica que el la venta fue exitosa
            '<script type="text/javascript">
                window.location.href = "index.php?pagina='  . $tipo_operacion_url . '&opciones=alta&estado=creada";
            </script>';
            exit;
        } else { # Indica que hubo un error
            echo '<div id="alerta-formulario" class="alerta-roja">
                Ocurrió un error: Vuelva a intentar la venta.<br>Detalles: ' . $resultado . '
            </div>';
            exit;
        }
    }

    /** Método que devuelve toda la información de la tabla operaciones y sus tablas pivote */
    static public function ctrlLeer($id='') {
        $modelo_consulta = new ModeloOperaciones();
        return $modelo_consulta->mdlLeer($id);
    }

    static public function ctrlLeerAbonos($id='') {
        $modelo_consulta = new ModeloOperaciones();
        return $modelo_consulta->mdlLeerAbonos($id);
    }

    /** Método que devuelve una lista de ventas dentro de un rango de fecha. */
    static public function ctrlLeerOperacionesPorRangoDeFecha($fecha_inicio='', $fecha_fin='', $tipo_operacion_id, $limit, $offset) {
        $modelo_consulta = new ModeloOperaciones();
        return $modelo_consulta -> mdlLeerOperacionesPorRangoDeFecha($fecha_inicio, $fecha_fin, $tipo_operacion_id,$limit, $offset);
    }

    /** Método que elimina un ID de operación recibido */
    static public function ctrlEliminar($tipo_operacion_url, $devolucion = false) {
        # Validación de variables
        if(!isset($_POST['folio-txt'])) return;

        $operacion_id = $_POST['folio-txt'];
        $modelo_consulta = new ModeloOperaciones();
        $resultado = $modelo_consulta -> mdlEliminarOperacionCompleta($operacion_id, $devolucion);
        
        if($resultado == true) {
            echo # Indica que la elminación fue exitosa
                '<script type="text/javascript">
                window.location.href = "index.php?pagina='  . $tipo_operacion_url . '&opciones=exito";
                </script>';
            exit;
        } else {
            echo # Indica que hubo un error
                '<script type="text/javascript">
                window.location.href = "index.php?pagina='  . $tipo_operacion_url . '&opciones=error";
                </script>';
            exit;
        }
    }

    /** Método que devuelve las coincidencias encontradas en una búsqueda */
    static public function ctrlBuscarTodos($pagina) {
        if(!isset($_POST['buscarOperacion-txt'])) return;

        $palabraClave = $_POST['buscarOperacion-txt'];

        if(strlen($palabraClave) > 0) {
            echo '<script type="text/javascript">
                    window.location.href = "index.php?pagina=' . $pagina . '&opciones=buscar-folio&clave=' . $palabraClave .'";
                    </script>';
        } else
            return "Servidor: Debe ingresar un dato para buscar";
    }

    //--------------------------------------------------------------------------------
    /** Método búsqueda de productos, sólo activos*/
    static public function ctrlBuscarProductos() {
        if(!isset($_POST['buscarProducto-txt'])) return;

        $palabraClave = $_POST['buscarProducto-txt'];

        if(strlen($palabraClave) > 0) {
            echo '<script type="text/javascript">
                    window.location.href = "index.php?pagina=ventas&opciones=buscar-productos&clave=' . $palabraClave .'";
                    </script>';
        } else
            return "Servidor: Debe ingresar un dato para buscar";
    }
}

if(isset($_GET['funcion'])) {
    require_once '../modelo/mdlOperaciones.php'; # Recordar llamar en estos casos al modelo

    if($_GET['funcion'] === 'buscar') {
        if(!isset($_POST['buscarOperacion-txt'])) die();

        $operacion_id = $_POST['buscarOperacion-txt'];
        $regex = '/^([0-9])*$/';
        
        if(strlen($operacion_id) === 0 || strlen($operacion_id) > 18) {
            echo 'Servidor: El folio debe tener de 1 a 18 numeros';
            die();
        }

        if(is_integer($operacion_id)) {
            echo 'Servidor: El folio solo acepta numeros';
            die();
        }

        if($operacion_id < 0) {
            echo 'Servidor: El folio no puede ser menor a 0';
            die();
        }

        $consulta = ControladorOperaciones::ctrlLeer($operacion_id);
        
        if(is_array($consulta))
            echo json_encode($consulta);
        else 
            echo false;
    }
}
