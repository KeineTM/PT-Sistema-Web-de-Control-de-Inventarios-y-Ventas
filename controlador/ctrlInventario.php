<?php
class ControladorProductos {
    private $producto_id;
    private $nombre;
    private $categoria_id;
    private $descripcion;
    private $unidades;
    private $unidadesMinimas;
    private $precioCompra;
    private $precioVenta;
    private $precioMayoreo;
    private $estado;
    private $foto_url;
    private $caducidad;

    ## Métodos Constructores y Destructores
    public function __construct($producto_id, $nombre, $categoria_id, $descripcion, $unidades, $unidadesMinimas,
                                $precioCompra, $precioVenta, $precioMayoreo, $estado, $foto_url, $caducidad) {
        $this->producto_id = $producto_id;
        $this->nombre = $nombre;
        $this->categoria_id = $categoria_id;
        $this->descripcion = $descripcion;
        $this->unidades = $unidades;
        $this->unidadesMinimas = $unidadesMinimas;
        $this->precioCompra = $precioCompra;
        $this->precioVenta = $precioVenta;
        $this->precioMayoreo = $precioMayoreo;
        $this->estado = $estado;
        $this->foto_url = $foto_url;
        $this->caducidad = $caducidad;
    }

    /** Sí es válido retorna la lista de datos a registrar */
    public function validarDato($campo) {
        $mensaje = null;

        switch ($campo) {
            case 'Folio del producto': # Requerido
                $regex = '/^[a-zA-Z0-9]{1,20}$/';
                $referencia = 'numeros y letras';
                $mensaje = ControladorSeguridad::validarLongitudCadena($campo, $this->producto_id, 1, 20);
                if ($mensaje === null)
                    $mensaje = ControladorSeguridad::validarFormato($campo, $this->producto_id, $regex, $referencia);
                return $mensaje;
                break;
            case 'Nombre de producto': # Requerido
                $mensaje = ControladorSeguridad::validarLongitudCadena($campo, $this->nombre, 4, 80);
                return $mensaje;
                break;
            case 'Categoria': # Requerido
                $mensaje = ControladorSeguridad::validarLongitudCadena($campo, $this->categoria_id, 1, 5);
                return $mensaje;
                break;
            case 'Descripcion':
                return $mensaje = (strlen($this->descripcion) !== 0 && $this->descripcion !== null)
                    ? ControladorSeguridad::validarLongitudCadena($campo, $this->descripcion, 0, 400)
                    : null;
                break;
            case 'Unidades': # Requerido
                $regex = '/^([0-9])*$/';
                $referencia = 'numeros enteros';
                $mensaje = ControladorSeguridad::validarLongitudCadena($campo, $this->unidades, 1, 4);
                if ($mensaje === null)
                    $mensaje = ControladorSeguridad::validarFormato($campo, $this->unidades, $regex, $referencia);
                if ($mensaje === null)
                    $mensaje = ControladorSeguridad::validarRangoNumerico($campo, $this->unidades, 1, 9999);
                return $mensaje;
                break;
            case 'Unidades Minimas':
                $regex = '/^([0-9])*$/';
                $referencia = 'numeros enteros';

                if (strlen($this->unidadesMinimas) !== 0 && $this->unidadesMinimas !== null) {
                    $mensaje = ControladorSeguridad::validarFormato($campo, $this->unidadesMinimas, $regex, $referencia);
                    if ($mensaje === null)
                        $mensaje = ControladorSeguridad::validarRangoNumerico($campo, $this->unidadesMinimas, 0, 9999);
                }
                return $mensaje;
                break;
            case 'Precio de Compra':
                $regex = '/^[0-9]+(\\.[0-9]{1,2})?$/';
                $referencia = 'numeros con hasta 2 decimales';
                if (strlen($this->precioCompra) !== 0 && $this->precioCompra !== null) {
                    $mensaje = ControladorSeguridad::validarFormato($campo, $this->precioCompra, $regex, $referencia);
                    if ($mensaje === null)
                        $mensaje = ControladorSeguridad::validarRangoNumerico($campo, $this->precioCompra, 0, 9999);
                }
                return $mensaje;
                break;
            case 'Precio de Venta': # Requerido
                $regex = '/^[0-9]+(\\.[0-9]{1,2})?$/';
                $referencia = 'numeros con hasta 2 decimales';
                $mensaje = ControladorSeguridad::validarLongitudCadena($campo, $this->precioVenta, 1, 7);
                if ($mensaje === null)
                    $mensaje = ControladorSeguridad::validarFormato($campo, $this->precioVenta, $regex, $referencia);
                if ($mensaje === null)
                    $mensaje = ControladorSeguridad::validarRangoNumerico($campo, $this->precioVenta, 1, 9999);
                return $mensaje;
                break;
            case 'Precio de Mayoreo':
                $regex = '/^[0-9]+(\\.[0-9]{1,2})?$/';
                $referencia = 'numeros con hasta 2 decimales';

                if (strlen($this->precioMayoreo) !== 0 && $this->precioMayoreo !== null) {
                    $mensaje = ControladorSeguridad::validarFormato($campo, $this->precioMayoreo, $regex, $referencia);
                    if ($mensaje === null)
                        $mensaje = ControladorSeguridad::validarRangoNumerico($campo, $this->precioMayoreo, 0, 9999);
                }
                return $mensaje;
                break;
            case 'Caducidad':
                if (strlen($this->caducidad) !== 0 && $this->caducidad !== null) {
                    $mensaje = ControladorSeguridad::validarFecha($campo, $this->caducidad);
                }
                return $mensaje;
                break;
            case 'Estado': # Requerido
                $regex = '/^([0-9])*$/';
                $referencia = 'numeros enteros';

                $mensaje = ControladorSeguridad::validarLongitudCadena($campo, $this->estado, 1, 1);
                if ($mensaje === null)
                    $mensaje = ControladorSeguridad::validarFormato($campo, $this->estado, $regex, $referencia);
                if ($mensaje === null)
                    $mensaje = ControladorSeguridad::validarRangoNumerico($campo, $this->estado, 0, 1);
                return $mensaje;
                break;
            case 'Imagen URL':
                $regex = '/\.(jpg|jpeg|png|gif|webp|svg)$/i';
                $referencia = 'extensiones .jpg, .jpeg, .png, .gif, .webp o .svg';

                if (strlen($this->foto_url) !== 0) {
                    $mensaje = ControladorSeguridad::validarFormato($campo, $this->foto_url, $regex, $referencia);
                    if ($mensaje === null)
                        $mensaje = ControladorSeguridad::validarLongitudCadena($campo, $this->foto_url, 0, 250);
                }
                return $mensaje;
                break;
        }
    }

    /** Método que registra un nuevo producto en la base de datos */
    public function ctrlRegistrar() {
        $listaDatos = [$this->producto_id, $this->nombre, $this->categoria_id, $this->descripcion, $this->unidades, $this->unidadesMinimas, 
        $this->precioCompra, $this->precioVenta, $this->precioMayoreo, $this->estado, $this->foto_url, $this->caducidad];
        $productoNuevo = new ModeloProductos;
        return $productoNuevo -> registrar($listaDatos);
    }

    /** Método que actualiza un producto existente en la base de datos */
    public function ctrlEditar() {
        $listaDatos = [$this->producto_id, $this->nombre, $this->categoria_id, $this->descripcion, $this->unidades, $this->unidadesMinimas, 
        $this->precioCompra, $this->precioVenta, $this->precioMayoreo, $this->estado, $this->foto_url, $this->caducidad, $this->producto_id];
        $producto = new ModeloProductos;
        return $producto -> update($listaDatos);
    }

    /** Método que devuelve todos los productos de la tabla inventario */
    static public function ctrlLeerTodos() {
        $listaProductos = new ModeloProductos();
        return $listaProductos -> leer();
    }

    static public function ctrlBuscar($palabraClave) {
        if(strlen($palabraClave) > 0) {
            $listaProductos = new ModeloProductos();
            return $listaProductos -> leer($palabraClave);
        } else
            return "Debe ingresar un dato para buscar";
    }

    ## Otros métodos
    /** Método para registrar una categoría, recibe una cadena con el nombre */
    static public function ctrlRegistrarCategoria($categoria) {
        if(strlen($categoria) > 0) {
            $modelo = new ModeloProductos();
            return $modelo -> createCategoria($categoria);
        } else {
            return "Debe ingresar una categoria";
        }
    }

    /** Este método devuelve un listado de las categorías registradas */
    static public function ctrlCategoriasActivas() {
        $modelo = new ModeloProductos();
        return $modelo -> readCategoriasActivas();
    }

    public function __toString() {
        return 'ID producto: ' . $this->producto_id . ' correspondiente a ' . $this->nombre;
    }
}

# Invocación de métodos para API Fetch de JS:
if(isset($_GET['funcion'])) {
    require '../modelo/mdlInventario.php';

    if($_GET['funcion'] === 'listar-categorias') {
        echo json_encode(ControladorProductos::ctrlCategoriasActivas());
        die();
    } 
    else if($_GET['funcion'] === 'registrar-categoria') {
        $categoria = $_POST['categoria-txt'];
        $resultado = ControladorProductos::ctrlRegistrarCategoria($categoria);
        echo $resultado;
        die();
    } 
    else if($_GET['funcion'] === 'registrar-producto') {
        # Recuperación de valores
        $producto_id = $_POST['idProducto-txt']; # Requerido
        $nombre = $_POST['nombreProducto-txt']; # Requerido
        $categoria_id = ($_POST['categoriaProducto-txt']); # Requerido
        $descripcion = (strlen($_POST['descripcionProducto-txt']))
                        ? $_POST['descripcionProducto-txt']
                        : null;
        $unidades = $_POST['unidadesProducto-txt']; # Requerido
        $unidadesMinimas = (strlen($_POST['unidadesMinimasProducto-txt']))
                            ? $_POST['unidadesMinimasProducto-txt']
                            : null;
        $precioCompra = (strlen($_POST['precioCompraProducto-txt']))
                        ? $_POST['precioCompraProducto-txt']
                        : null;
        $precioVenta = $_POST['precioVentaProducto-txt']; # Requerido
        $precioMayoreo = (strlen($_POST['precioMayoreoProducto-txt']))
                        ? $_POST['precioMayoreoProducto-txt']
                        : null;
        $estado = (isset($_POST['estadoProducto-txt'])) # Requerido
                    ? $_POST['estadoProducto-txt']
                    : 1;
        $foto_url = (strlen($_POST['imagenProducto-txt']) > 0)
                    ? $_POST['imagenProducto-txt']
                    : "vistas/img/image.svg";
        $caducidad = (strlen($_POST['caducidadProducto-txt']) > 0)
                    ? $_POST['caducidadProducto-txt']
                    : null;

        $productoNuevo = new ControladorProductos(
            $producto_id, $nombre, $categoria_id, $descripcion, $unidades, $unidadesMinimas, 
            $precioCompra, $precioVenta, $precioMayoreo, $estado, $foto_url, $caducidad);

        require_once 'ctrlSeguridad.php';

        $listaErrores = [];
        $listaCampos = ['Folio del producto', 'Nombre de producto', 'Categoria', 'Descripcion', 'Unidades', 'Unidades Minimas', 'Precio de Compra', 'Precio de Venta', 'Precio de Mayoreo', 'Caducidad', 'Imagen URL', 'Estado'];
        
        # Validación de los campos
        foreach($listaCampos as $campo) {
            if($productoNuevo->validarDato($campo) !== null) { # Si hay un error
                array_push($listaErrores, $productoNuevo->validarDato($campo)); # Se agrega a la lista
            }
        }

        if(count($listaErrores) > 0) { // Si existen errores devuelve la lista con ellos
            echo json_encode($listaErrores);
            die();
        } else { # Sino, ejecuta el registro
            echo $productoNuevo->ctrlRegistrar();
            die();
        }
    }
    else if($_GET['funcion'] === 'editar-producto') {
        # Recuperación de valores
        $producto_id = $_POST['idProducto-txt']; # Requerido
        $nombre = $_POST['nombreProducto-txt']; # Requerido
        $categoria_id = ($_POST['categoriaProducto-txt']); # Requerido
        $descripcion = (strlen($_POST['descripcionProducto-txt']))
                        ? $_POST['descripcionProducto-txt']
                        : null;
        $unidades = $_POST['unidadesProducto-txt']; # Requerido
        $unidadesMinimas = (strlen($_POST['unidadesMinimasProducto-txt']))
                            ? $_POST['unidadesMinimasProducto-txt']
                            : null;
        $precioCompra = (strlen($_POST['precioCompraProducto-txt']))
                        ? $_POST['precioCompraProducto-txt']
                        : null;
        $precioVenta = $_POST['precioVentaProducto-txt']; # Requerido
        $precioMayoreo = (strlen($_POST['precioMayoreoProducto-txt']))
                        ? $_POST['precioMayoreoProducto-txt']
                        : null;
        $estado = $_POST['estadoProducto-txt']; # Requerido
        $foto_url = (strlen($_POST['imagenProducto-txt']) > 0)
                    ? $_POST['imagenProducto-txt']
                    : "vistas/img/image.svg";
        $caducidad = (strlen($_POST['caducidadProducto-txt']) > 0)
                    ? $_POST['caducidadProducto-txt']
                    : null;

        $productoNuevo = new ControladorProductos(
            $producto_id, $nombre, $categoria_id, $descripcion, $unidades, $unidadesMinimas, 
            $precioCompra, $precioVenta, $precioMayoreo, $estado, $foto_url, $caducidad);

        require_once 'ctrlSeguridad.php';

        $listaErrores = [];
        $listaCampos = ['Folio del producto', 'Nombre de producto', 'Categoria', 'Descripcion', 'Unidades', 'Unidades Minimas', 'Precio de Compra', 'Precio de Venta', 'Precio de Mayoreo', 'Caducidad', 'Imagen URL', 'Estado'];
        
        # Validación de los campos
        foreach($listaCampos as $campo) {
            if($productoNuevo->validarDato($campo) !== null) { # Si hay un error
                array_push($listaErrores, $productoNuevo->validarDato($campo)); # Se agrega a la lista
            }
        }

        if(count($listaErrores) > 0) { // Si existen errores devuelve la lista con ellos
            echo json_encode($listaErrores);
            die();
        } else { # Sino, ejecuta el registro
            echo $productoNuevo->ctrlEditar();
            die();
        }
    }
    else if($_GET['funcion'] === 'listar-productos') {
        if(isset($_POST['buscarProducto-txt'])) {
            $palabraClave = $_POST['buscarProducto-txt'];
            echo json_encode(ControladorProductos::ctrlBuscar($palabraClave));
            die();
        } else {
            echo json_encode(ControladorProductos::ctrlLeerTodos());
            die();
        }
        
    }
}
