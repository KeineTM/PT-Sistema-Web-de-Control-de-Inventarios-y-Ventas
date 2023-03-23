<?php
require_once "mdlConexion.php";

class ModeloProductos {
    /**
     * Método que registra una nueva categoría en la tabla
     */
    static public function mdlRegistrarCategoria($categoria) {
        if(strlen($categoria) > 0) {
            $consulta = Conexion::conectar() -> prepare('INSERT INTO categorias_inventario("categoria") VALUES (?)');
            $consulta -> bindValue(1, $categoria, PDO::PARAM_STR);
            $consulta -> execute();
                
                
            $consulta -> close();

        } else
            echo 'Debe llenar todos los campos';
    }
        
    /**
     * Método que retorna todos los registros de la tabla de categorías
     */
    static public function mdlListarCategorias() {
        $consulta = Conexion::conectar() -> prepare('SELECT * FROM categorias_inventario WHERE estado=1');
        $consulta -> execute();

        //if($consulta->rowCount() > 0)
        return $consulta -> fetchAll(PDO::FETCH_ASSOC);

        $consulta -> close();
        $consulta = null;            
    }

    static public function mdlValidarFormulario() {

    }

    static public function mdlRegistrarProducto() {

    }

    static public function mdlConsultarProducto() {

    }

    static public function mdlEditarProducto($producto_id) {

    }
}