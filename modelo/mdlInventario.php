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
        $this->sentenciaSQL = "INSERT INTO inventario VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";
        return $this->consultasCUD();
    }

    /** Metodo que devuelve todo el listado de productos en la tabla inventario */
    public function leer($palabraClave='') {
        if ($palabraClave === '') {
            $this->sentenciaSQL =
            'SELECT inventario.producto_id, inventario.nombre, inventario.categoria_id, categorias_inventario.categoria, inventario.descripcion,
                inventario.unidades, inventario.unidades_minimas, inventario.precio_compra, inventario.precio_venta, inventario.precio_mayoreo,
                inventario.foto_url, inventario.caducidad, inventario.estado
                FROM inventario
                INNER JOIN categorias_inventario ON inventario.categoria_id = categorias_inventario.categoria_id';
        
            return  $this->consultaRead();

        } else {
            $this->sentenciaSQL =
            'SELECT inventario.producto_id, inventario.nombre, inventario.categoria_id, categorias_inventario.categoria, inventario.descripcion,
                inventario.unidades, inventario.unidades_minimas, inventario.precio_compra, inventario.precio_venta, inventario.precio_mayoreo,
                inventario.foto_url, inventario.caducidad, inventario.estado
                FROM inventario
                INNER JOIN categorias_inventario ON inventario.categoria_id = categorias_inventario.categoria_id 
                WHERE inventario.nombre LIKE ?';

            return  $this->consultaRead("%" . $palabraClave . "%");
        }
    }

    public function update($listaDatos) {
        $this->registros = $listaDatos; # Cuidar que a lista de datos tenga el orden de la consulta, incluyendo la repetición del ID
        $this->sentenciaSQL = "UPDATE inventario SET producto_id = ?, nombre = ?, categoria_id = ?, descripcion = ?, unidades = ?, 
        unidades_minimas = ?, precio_compra = ?, precio_venta = ?, precio_mayoreo = ?, estado = ?, foto_url = ?, caducidad = ?
        WHERE producto_id = ?";
        return $this->consultasCUD();
    }

    public function delete() {
        
    }

    /** Método que registra una categoría nueva */
    public function createCategoria($categoria) {
        $this->sentenciaSQL = "INSERT INTO categorias_inventario (categoria) VALUES (?)";
        $this -> abrirConexion();

        try {
            $registro = $this->conexion -> prepare($this->sentenciaSQL);
            $registro -> bindValue(1, $categoria, PDO::PARAM_STR);
            $registro -> execute();

            return $categoria . ' registrado correctamente';

        } catch(PDOException $e) {
            return 'Error: ' .$e->getMessage();
        } finally {
            $registro = null;
            $this->cerrarConexion();
        }
    }

    /** Este método devuelve todas las categorias en la tabla. Si se le indica un id, sólo devuelve el registro correspondiente */
    public function readCategorias($categoriaID='') {
        $this->sentenciaSQL = ($categoriaID === '')
            ? "SELECT * FROM categorias_inventario"
            : "SELECT * FROM categorias_inventario WHERE categoria_id = ?";
        
        return $this-> consultaRead($categoriaID);
    }

    /** Este método devuelve la lista de categorias activas ordenadas alfabéticamente */
    public function readCategoriasActivas() {
        $this->sentenciaSQL = "SELECT * FROM categorias_inventario WHERE estado = 1 ORDER BY categoria"; 
        return $this-> consultaRead();
    }

    public function updateCategoria() {
        
    }

}