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
    protected $consulta = array();

    ## Métodos Constructores y Destructores
    public function __construct($producto_id, $nombre, $categoria_id, $descripcion, $unidades, $unidadesMinimas,
    $precioCompra, $precioVenta, $precioMayoreo, $foto_url) {
        $this->producto_id = $producto_id;
        $this->nombre = $nombre;
        $this->categoria_id = $categoria_id;
        $this->descripcion = $descripcion;
        $this->unidades = $unidades;
        $this->unidadesMinimas = $unidadesMinimas;
        $this->precioCompra = $precioCompra;
        $this->precioVenta = $precioVenta;
        $this->precioMayoreo = $precioMayoreo;
        $this->estado = 1; // Todos los productos registrados se asignan con un estado '1' correspondiente a activos
        $this->foto_url = $foto_url;
    }

    public function __destruct() {
            
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

    /**
     * Este método devuelve un listado de las categorías registradas
     */
    static public function ctrlCategoriasActivas() {
        $modelo = new ModeloProductos();
        return $modelo -> readCategoriasActivas();
    }

    public function __toString() {
        return 'ID producto: ' . $this->producto_id . ' correspondiente a ' . $this->nombre;
    }
}