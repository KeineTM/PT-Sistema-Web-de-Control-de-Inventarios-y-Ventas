<?php
require_once "mdlConexion.php";

class ModeloProductos extends ModeloConexion{
    # Método constructor
    public function __construct() {
        $this->db_usuario = "root";
        $this->db_password = "";
    }

    /** Método que registra un nuevo producto en la tabla. Recibe una lista con los tados a registrar. */
    public function registrar($listaDatos) {
        $this->registros = $listaDatos;
        $this->sentenciaSQL = 'INSERT INTO inventario VALUES (?,?,?,?,?,?,?,?,?,?,?)';
        return $this->consultasCUD();
    }

    /** Metodo que devuelve todo el listado de productos en la tabla inventario */
    public function mdlLeer($id='') {
        $this->sentenciaSQL = ($id === '')
        ? 'SELECT inventario.producto_id, inventario.nombre, inventario.categoria_id, categorias_inventario.categoria, inventario.descripcion,
            inventario.unidades, inventario.unidades_minimas, inventario.precio_compra, inventario.precio_venta,
            inventario.estado, inventario.foto_url, inventario.caducidad
            FROM inventario
            INNER JOIN categorias_inventario ON inventario.categoria_id = categorias_inventario.categoria_id
            ORDER BY CAST(inventario.producto_id AS UNSIGNED)'
        : 'SELECT inventario.producto_id, inventario.nombre, inventario.categoria_id, categorias_inventario.categoria, inventario.descripcion,
            inventario.unidades, inventario.unidades_minimas, inventario.precio_compra, inventario.precio_venta,
            inventario.estado, inventario.foto_url, inventario.caducidad
            FROM inventario
            INNER JOIN categorias_inventario ON inventario.categoria_id = categorias_inventario.categoria_id 
            WHERE inventario.producto_id = ?
            LIMIT 1';

        return  $this->consultaRead($id);
    }

    public function mdlBuscarPorPalabraClave($palabraClave) {
        $this->sentenciaSQL =
            'SELECT inventario.producto_id, inventario.nombre, inventario.categoria_id, categorias_inventario.categoria, inventario.descripcion,
            inventario.unidades, inventario.unidades_minimas, inventario.precio_compra, inventario.precio_venta,
            inventario.estado, inventario.foto_url, inventario.caducidad
            FROM inventario
            INNER JOIN categorias_inventario ON inventario.categoria_id = categorias_inventario.categoria_id 
            WHERE inventario.nombre LIKE ?
            ORDER BY CAST(inventario.producto_id AS UNSIGNED)';

        return  $this->consultaRead("%" . $palabraClave . "%");
    }

    public function editar($listaDatos) {
        $this->registros = $listaDatos; # Cuidar que a lista de datos tenga el orden de la consulta, incluyendo la repetición del ID
        $this->sentenciaSQL = 
            'UPDATE inventario SET producto_id = ?, nombre = ?, categoria_id = ?, descripcion = ?, unidades = ?, 
            unidades_minimas = ?, precio_compra = ?, precio_venta = ?, estado = ?, foto_url = ?, caducidad = ? 
            WHERE producto_id = ? LIMIT 1';
        return $this->consultasCUD();
    }

    /** Método usado por la clase ctrlOperaciones para cambiar el número de unidades de un producto */
    public function editarUnidades($listaDatos) {
        $this->registros = $listaDatos;
        $this->sentenciaSQL = 
            'UPDATE inventario SET unidades = unidades - ? WHERE producto_id = ? LIMIT 1';
        return $this->consultasCUD();
    }

    public function delete() {
        
    }

    /** Método que registra una categoría nueva */
    public function mdlRegistrarCategoria($listaDatos) {
        $this->registros = $listaDatos;
        $this->sentenciaSQL = "INSERT INTO categorias_inventario (categoria) VALUES (?)";
        return $this->consultasCUD();
    }

    /** Este método devuelve todas las categorias en la tabla. Si se le indica un id, sólo devuelve el registro correspondiente */
    public function mdlLeerCategorias($categoria_id='') { 
        $this->sentenciaSQL = ($categoria_id === '')
            ? "SELECT * FROM categorias_inventario  ORDER BY categoria"
            : "SELECT * FROM categorias_inventario WHERE categoria_id = ?  ORDER BY categoria";
        
        return $this-> consultaRead($categoria_id);
    }

    /** Este método devuelve la lista de categorias activas ordenadas alfabéticamente */
    public function mdlLeerCategoriasActivas() {
        $this->sentenciaSQL = "SELECT * FROM categorias_inventario WHERE estado = 1 ORDER BY categoria"; 
        return $this-> consultaRead();
    }

    public function mdlEditarCategoria($listaDatos) {
        $this->registros = $listaDatos;
        $this->sentenciaSQL = 'UPDATE categorias_inventario SET categoria = ?, estado = ? WHERE categoria_id = ?';
        return $this-> consultasCUD();
    }

    //--------------------------------------------------------------------------------------------------
    // Métodos de paginación
    //--------------------------------------------------------------------------------------------------

    /**
     * Método que cuenta los productos en la tabla. 
     * $estado = true = activos / false = todos.
     */
    public function mdlConteoProductos($estado=false) {
        $this->sentenciaSQL = ($estado) 
            ? 'SELECT count(*) AS conteo FROM inventario WHERE estado = true'
            : 'SELECT count(*) AS conteo FROM inventario';
        return $this->consultaRead();
    }

    /** Método que recupera sólo los productos activos para una vista de catálogo */
    public function mdlLeerParaPaginacion($limit, $offset, $estado=false) {
        $this->sentenciaSQL = ($estado)
        ? 'SELECT inventario.producto_id, inventario.nombre, inventario.categoria_id, categorias_inventario.categoria, inventario.descripcion,
            inventario.unidades, inventario.precio_venta,
            inventario.estado, inventario.foto_url, inventario.caducidad
            FROM inventario
            INNER JOIN categorias_inventario ON inventario.categoria_id = categorias_inventario.categoria_id
            WHERE inventario.estado = true
            ORDER BY CAST(inventario.producto_id AS UNSIGNED)
            LIMIT ? OFFSET ?'
        : 'SELECT inventario.producto_id, inventario.nombre, inventario.categoria_id, categorias_inventario.categoria, inventario.descripcion,
            inventario.unidades, inventario.precio_venta,
            inventario.estado, inventario.foto_url, inventario.caducidad
            FROM inventario
            INNER JOIN categorias_inventario ON inventario.categoria_id = categorias_inventario.categoria_id
            ORDER BY CAST(inventario.producto_id AS UNSIGNED)
            LIMIT ? OFFSET ?';

        try {
            $this->abrirConexion(); # Conecta
            $pdo = $this->conexion -> prepare($this->sentenciaSQL); # Crea PDOStatement
            
            $pdo -> bindParam(1, $limit, PDO::PARAM_INT);
            $pdo -> bindParam(2, $offset, PDO::PARAM_INT);
    
            $pdo -> execute(); # Ejecuta
            $this->registros = $pdo -> fetchAll(PDO::FETCH_ASSOC); # Recupera datos
    
            return $this->registros;

        } catch(PDOException $e) {
            return 'Error: ' . $e->getMessage(); # Si hubo un error lo Retorna
        } finally {
            $pdo = null; # Limpia
            $this->cerrarConexion(); # Cierra
        }
    }
}