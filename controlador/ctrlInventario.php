<?php
require 'ctrlSeguridad.php';

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
    /*public function __construct($producto_id, $nombre, $categoria_id, $descripcion, $unidades, $unidadesMinimas,
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
    }*/

    /** Método que registra un nuevo producto en la base de datos */
    public function ctrlRegistrarProducto() {
        $this->producto_id = $_POST['idProducto-txt'];
        $this->nombre = $_POST['nombreProducto-txt'];
        $this->categoria_id = ($_POST['categoriaProducto-txt']);
        $this->descripcion = $_POST['descripcionProducto-txt'];
        $this->unidades = $_POST['unidadesProducto-txt'];
        $this->unidadesMinimas = $_POST['unidadesMinimasProducto-txt'];
        $this->precioCompra = (strlen($_POST['precioCompraProducto-txt']) > 0)
                                ? $_POST['precioCompraProducto-txt']
                                : null;
        $this->precioVenta = $_POST['precioVentaProducto-txt'];
        $this->precioMayoreo = (strlen($_POST['precioMayoreoProducto-txt']) > 0)
                                ? $_POST['precioMayoreoProducto-txt']
                                : null;
        $this->estado = 1; // Todos los productos registrados se asignan con un estado '1' correspondiente a activos
        $this->foto_url = (strlen($_POST['imagenProducto-txt']) > 0)
                            ? $_POST['imagenProducto-txt']
                            : "vistas/img/image.svg";
        $this->caducidad = (strlen($_POST['caducidadProducto-txt']) > 0)
                            ? $_POST['caducidadProducto-txt']
                            : null;
        
        # Validaciones
        $listaCamposObligatorios = [$this->producto_id, $this->nombre, $this->categoria_id, $this->unidades, $this->precioVenta];
        $listaCamposInt = [$this->categoria_id, $this->unidades, $this->unidadesMinimas];
        $listaCamposDecimal = [$this->precioVenta];
        if($this->precioCompra) array_push($listaCamposDecimal, $this->precioCompra);
        if($this->precioMayoreo) array_push($listaCamposDecimal, $this->precioMayoreo);

        if(ControladorSeguridad::validarVacio($listaCamposObligatorios) &&
            ControladorSeguridad::validarEnterno($listaCamposInt) &&
            ControladorSeguridad::validarDecimal($listaCamposDecimal) &&
            ControladorSeguridad::validarCaducidad($this->caducidad)) {

            $listaDatos = [$this->producto_id, $this->nombre, $this->categoria_id, $this->descripcion, $this->unidades, $this->unidadesMinimas, 
                            $this->precioCompra, $this->precioVenta, $this->precioMayoreo, $this->estado, $this->foto_url, $this->caducidad];
            $modelo = new ModeloProductos;
            return $modelo -> registrarNuevoProducto($listaDatos);
        } # Control de las validaciones
        elseif (!ControladorSeguridad::validarVacio($listaCamposObligatorios))
            return "No se han completado los campos obligatorios.";
        elseif (!ControladorSeguridad::validarEnterno($listaCamposInt))
            return "Los campos Categoría, Unidades y Unidades Mínimas sólo aceptan números enteros menores a 9999";
        elseif (!ControladorSeguridad::validarDecimal($listaCamposDecimal))
            return "Los campos Precio de Venta, Precio de Compra y Precio Mayoreo sólo aceptan números con un máximo de 2 decimales";
        elseif (!ControladorSeguridad::validarCaducidad($this->caducidad))
            return "La fecha de caducidad no puede ser anterior al día de hoy.";
    }

    ## Otros métodos
    static public function ctrlRegistrarCategoria($categoria) {
        if(strlen($categoria) > 0) {
            $modelo = new ModeloProductos();
            return $modelo -> createCategoria($categoria);
        } else {
            return "No se insertaron los datos solicitados";
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
        $producto = new ControladorProductos();
        echo $producto -> ctrlRegistrarProducto();
        die();
    }
}