<?php
class ControladorOperaciones
{
    private $descuento;
    private $subtotal;
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

    private const DESCUENTO_MAX = 0.5;

    public function __construct(
        $descuento,
        $subtotal,
        $total,
        $notas,
        $tipo_operacion,
        $estado,
        $cliente_id,
        $productos_incuidos,
        $empleado_id,
        $abono,
        $metodo_pago
    ) {
        $this->descuento = $descuento;
        $this->subtotal = $subtotal;
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
    static public function calcularSubtotal() {
        $total = 0;
        foreach ($_SESSION['carrito'] as $producto) {
            $total += $producto->total;
        }
        return $total;
    }

    /** Método que recibe un código o id de producto para agregarlo al carrito con el uso de variables de sesión */
    static public function agregarAlCarrito() {
        if (!isset($_POST['idProducto-txt'])) return;

        $producto_id = $_POST['idProducto-txt'];
        $busqueda = ControladorProductos::ctrlBuscarUno($producto_id);
        $productoPorAgregar = new ControladorProductos(
            $busqueda[0]['producto_id'],
            $busqueda[0]['nombre'],
            $busqueda[0]['categoria'],
            $busqueda[0]['descripcion'],
            $busqueda[0]['unidades'],
            $busqueda[0]['unidades_minimas'],
            $busqueda[0]['precio_compra'],
            $busqueda[0]['precio_venta'],
            $busqueda[0]['precio_mayoreo'],
            $busqueda[0]['estado'],
            $busqueda[0]['foto_url'],
            $busqueda[0]['caducidad']
        );

        # Evalúa si el resultado de la búsqueda devolvió un producto con atributos:
        if (!$productoPorAgregar->producto_id) {
            echo # ID de producto inválido o inexistente
            '<script type="text/javascript">
                window.location.href = "index.php?pagina=ventas&opciones=alta&estado=no-existe";
                </script>';
            die();
        }

        # Evalúa unidades existentes
        if ($productoPorAgregar->unidades === 0) { # Se acabó el producto
            echo '<script type="text/javascript">
                    window.location.href = "index.php?pagina=ventas&opciones=alta&estado=agotado";
                    </script>';
            exit;
        }

        # Evalúa si el producto ya está en el carrito
        $productoEnCarrito = false;
        foreach ($_SESSION['carrito'] as $indice => $productoExistente) {
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
            array_push($_SESSION['carrito'], $productoPorAgregar);
        }
        # Si hay coincidencias aumenta la cantidad en carrito más 1 y actualiza el total acumulado por producto
        else { # siempre y cuando no sobrepase las unidades existentes
            if($_SESSION['carrito'][$productoEnCarrito]->cantidad < $_SESSION['carrito'][$productoEnCarrito]->unidades) {
                $_SESSION['carrito'][$productoEnCarrito]->cantidad++;
                $_SESSION['carrito'][$productoEnCarrito]->total = ($_SESSION['carrito'][$productoEnCarrito]->cantidad) * ($_SESSION['carrito'][$productoEnCarrito]->precioVenta);
            } else {
                echo 
                    '<script type="text/javascript">
                    window.location.href = "index.php?pagina=ventas&opciones=alta&estado=maximo";
                    </script>';
                die();
            }
        }
        # Regresa
        echo 
            '<script type="text/javascript">
            window.location.href = "index.php?pagina=ventas&opciones=alta";
            </script>';
        die();
    }

    /** Método que aumenta en 1 la cantidad de un producto en el carrito, mientras este exista. Y no se pueden agregar más de los que se tienen en existencia */
    static public function sumarDelCarrito() {
        if (!isset($_GET['sumar'])) return;

        $indice = $_GET['sumar'];

        # Si existe el producto en carrito y no se ha alcanzado el máximo de unidades disponibles
        if ($_SESSION['carrito'][$indice]->producto_id && $_SESSION['carrito'][$indice]->cantidad < $_SESSION['carrito'][$indice]->unidades) {
            # Suma
            $_SESSION['carrito'][$indice]->cantidad++;
            $_SESSION['carrito'][$indice]->total = ($_SESSION['carrito'][$indice]->cantidad) * ($_SESSION['carrito'][$indice]->precioVenta);
        
        } else if(!$_SESSION['carrito'][$indice]->cantidad < $_SESSION['carrito'][$indice]->unidades) {
            echo # Indica que en el carrito ya se tiene el máximo de unidades disponibles
                '<script type="text/javascript">
                window.location.href = "index.php?pagina=ventas&opciones=alta&estado=maximo";
                </script>';
            die();
        }

        echo '<script type="text/javascript">
            window.location.href = "index.php?pagina=ventas&opciones=alta";
            </script>';
        die();
    }

    /** Método que resta en 1 la cantidad de un producto en el carrito, mientras este exista. Si llega a 0, retira el producto */
    static public function restarDelCarrito() {
        if (!isset($_GET['restar'])) return;

        $indice = $_GET['restar'];

        if ($_SESSION['carrito'][$indice]->producto_id) { # Si existe el producto
            $_SESSION['carrito'][$indice]->cantidad--;
            $_SESSION['carrito'][$indice]->total = ($_SESSION['carrito'][$indice]->cantidad) * ($_SESSION['carrito'][$indice]->precioVenta);

            if ($_SESSION['carrito'][$indice]->cantidad < 1) { # Si la cantidad de producto llega a 0
                unset($_SESSION['carrito'][$indice]); # lo retira
                echo # Indica que se ha retirado un producto
                    '<script type="text/javascript">
                    window.location.href = "index.php?pagina=ventas&opciones=alta&estado=eliminado";
                    </script>';
                    die();
            }
        }

        echo
            '<script type="text/javascript">
            window.location.href = "index.php?pagina=ventas&opciones=alta";
            </script>';
        exit;
    }

    /** Método qur recibe un índice para quitar el carrito con el uso de variables de sesión */
    static public function quitarDelCarrito() {
        if (!isset($_GET['quitar'])) return;

        $indice = $_GET['quitar'];

        #array_splice($_SESSION['carrito'], $indice, 1);
        unset($_SESSION['carrito'][$indice]);

        echo # Indica que el producto fue removido
            '<script type="text/javascript">
            window.location.href = "index.php?pagina=ventas&opciones=alta&estado=eliminado";
            </script>';
        exit;
    }

    //---------------------------------------------------------------------------------------
    //------------------------ Métodos de la operación-------------------------------------

    /** Método para validar un el descuento
     * Retorna false si el dato no coincide con el formato esperado (números y letras)
     * Retorna false si el descuento es mayor al 50% del total
     * Retorna true si no hay dato de descuento o es válido
     */
    static public function validarDescuento($descuento) {
        require_once 'ctrlSeguridad.php';
        $subtotal = 0;
        $resultado = null;
        $regex = '/^[0-9]+(\\.[0-9]{1,2})?$/';
        $referencia = 'numeros con hasta 2 decimales';
        if (strlen($descuento) !== 0 && $descuento !== null) { # Evalúa si hay un dato para validar el formato
            $resultado = ControladorSeguridad::validarFormato('Descuento', $descuento, $regex, $referencia);

            if ($resultado === null) # Formato valido: valida el rango
                $resultado = ControladorSeguridad::validarRangoNumerico('Descuento', $descuento, 0, 9999);

            if ($resultado === null) { # Pasó las 2 validaciones
                $subtotal = self::calcularSubtotal();
                if ($descuento > ($subtotal / 2)) # Evalúa que el descuento con el subtotal
                    return $resultado = false; # El descuento es igual o supera el subtotal
                else
                    return $resultado = true; # El descuento es válido
            }

            return $resultado = false; # No pasó las validaciones  
        } else
            return $resultado = true; # El descuento no tiene valor


    }

    /** Método que registra la operación en BD recuperando el ID de la operación
     * En caso de error retorna false
     */
    public function ctrlRegistrarOperacion() {
        $regex = '/^([0-9])*$/'; # números
        $listaDatos = [
            $this->total,
            $this->descuento,
            $this->subtotal,
            $this->notas,
            $this->tipo_operacion,
            $this->estado,
            $this->cliente_id
        ];
        $modelo_registro = new ModeloOperaciones();
        $resultado = $modelo_registro->mdlRegistrarOperacion($listaDatos);

        # El resultado es una cadena que puede ser el ID o un error
        # Se evalúa que la cadena se componga únicamente por números, pues es el formato del ID
        return (preg_match($regex, $resultado))
                ? $resultado # devuelve el ID
                : false;
    }

    /** Método que registra los productos incluídos en una operación previamente registrada en BD y
     * resta la cantidad de productos respectiva en su tabla. 
     * Devuelve false si ocurrió un error. Y true si todo salió correcto.
     */
    public function ctrlRegistrarProductosIncuidos($operacion_id) {
        $resultado = false;
        $registro_operacion = new ModeloOperaciones();
        $edicion_inventario = new ModeloProductos();

        foreach($this->productos_incuidos as $producto) {
            $datos_por_producto = [$operacion_id];
            $datos_edicion_unidades = [];

            array_push($datos_por_producto, $producto->producto_id);
            array_push($datos_por_producto, $producto->cantidad);
            array_push($datos_por_producto, $producto->total);
            # Registra los productos del carrito
            $resultado = $registro_operacion -> mdlRegistrarProductosIncluidos($datos_por_producto);
            
            if($resultado === true) { # Si el resultado es estrictamente igual a true significa que todo salió correcto
                array_push($datos_edicion_unidades, $producto->cantidad);
                array_push($datos_edicion_unidades, $producto->producto_id);
                # Los resta del inventario
                $resultado = $edicion_inventario -> editarUnidades($datos_edicion_unidades);
            }
        }

        return $resultado;
    }

    /** Método que registra los datos del pago o abono y el usuario que la realizó en una operación previamente registrada */
    public function ctrlRegistrarAbono($operacion_id) {
        $resultado = null;
        $listaDatos = [$operacion_id, $this->empleado_id, $this->fecha, $this->abono, $this->metodo_pago];
        $modelo_registro = new ModeloOperaciones();
        $resultado = $modelo_registro -> mdlRegistrarAbono($listaDatos);
        return $resultado;
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

    static public function ctrlCrearVenta() {
        if (!isset($_POST['total-txt'])) return;
        if (($_POST['total-txt']) < 0) {
            echo # Indica que el total no puede ser 0 o menor
            '<script type="text/javascript">
                    window.location.href = "index.php?pagina=ventas&opciones=alta&estado=error-total";
                </script>';
            exit;
        }
        if (count($_SESSION['carrito']) < 1) {
            echo # Indica que el carrito está vacío
            '<script type="text/javascript">
                    window.location.href = "index.php?pagina=ventas&opciones=alta&estado=error-carrito";
                </script>';
            exit;
        }

        $descuento = (strlen($_POST['descuento-txt']) > 0)
            ? $_POST['descuento-txt']
            : null;
        $subtotal = $_POST['total-txt']; # Requerido
        $notas = (strlen($_POST['notas-txt']) > 0)
            ? $_POST['notas-txt']
            : null;
        $tipo_operacion = 'VE'; # Venta, Requerido
        $estado = 1; # 1 = Completada, Sólo se usa 0 para Apartados
        $cliente_id = null; # No se requiere cliente para la venta
        $productos_incuidos = new ArrayObject($_SESSION['carrito']); # Productos incluídos en el carrito al momento de procesar la venta
        $empleado_id = $_SESSION['idUsuarioSesion']; # Usuario con la sesión activa
        $monto_abonado = null; # En una venta es igual al total pagado
        $metodo_pago = (strlen($_POST['metodo-pago-txt']) === 0)
            ? $_POST['metodo-pago-txt']
            : 1; # Por defecto asigna el pago en efectivo

        # Valida descuento
        if (!self::validarDescuento($descuento)) {
            echo # Indica que el descuento no es válido
            '<script type="text/javascript">
                    window.location.href = "index.php?pagina=ventas&opciones=alta&estado=error-descuento";
                </script>';
            exit;
        }
        $total = $subtotal - $descuento;
        $monto_abonado = $total; # En una venta es igual al total pagado

        $venta_nueva = new ControladorOperaciones($descuento, $subtotal, $total, $notas, $tipo_operacion, $estado, $cliente_id, $productos_incuidos, $empleado_id, $monto_abonado, $metodo_pago);
        
        $venta_nueva -> ctrlRegistrarOperacionCompleta();

        /*#1 Registra la operación y recupera su ID
        $resultado_operacion = $venta_nueva -> ctrlRegistrarOperacion();

        # Evalúa si el resultado es diferente de false, porque en caso de éxito retorna un número de ID
        if($resultado_operacion !== false) {
            #2 Registra los productos incluídos (y resta unidades del inventario)
            $resultado_productos = $venta_nueva -> ctrlRegistrarProductosIncuidos($resultado_operacion);

            if($resultado_productos === true) {
                # 3 Registra el abono
                $resultado_abono = $venta_nueva -> ctrlRegistrarAbono($resultado_operacion);

                if($resultado_abono === true) {
                    echo # Indica que el la venta fue exitosa
                        '<script type="text/javascript">
                            window.location.href = "index.php?pagina=ventas&opciones=alta&estado=creada";
                        </script>';
                    exit;
                }
            }
        }*/

        # Si llegó a este código significa que hubo un error
        echo '<div id="alerta-formulario" class="alerta-roja">Ocurrió un error</div>';
        exit;
    }
}
