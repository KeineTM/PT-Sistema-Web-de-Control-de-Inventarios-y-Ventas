<?php
require_once "mdlConexion.php";

class ModeloOperaciones extends ModeloConexion {
    # Método constructor
    public function __construct() {
        $this->db_usuario = "root";
        $this->db_password = "";
    }

    /** Método que registra la operación en BD recuperando el ID del registro */
    public function mdlRegistrarOperacion($listaDatos) {
        $this->registros = $listaDatos;
        $this->sentenciaSQL = 
            'INSERT INTO operaciones 
            (operaciones.total, operaciones.descuento, operaciones.subtotal, operaciones.notas, operaciones.tipo_operacion, operaciones.estado, operaciones.cliente_id) 
            VALUES (?,?,?,?,?,?,?)';
        return $this->consultasCUD(true);
    }

    public function mdlRegistrarProductosIncluidos($listaDatos) {
        $this->registros = $listaDatos;
        $this->sentenciaSQL = 
            'INSERT INTO productos_incluidos VALUES (?,?,?,?)';
        return $this->consultasCUD();
    }
    
    public function mdlRegistrarAbono($listaDatos) {
        $this->registros = $listaDatos;
        $this->sentenciaSQL = 
            'INSERT INTO abonos VALUES (?,?,?,?,?)';
        return $this->consultasCUD();
    }

    public function mdlRegistrarOperacionCompleta($datos_operacion, $lista_productos_incluidos, $datos_abono) {
        try {
            $this->abrirConexion();

            # ------------------ # 1 Registro de la operación ---------------------
            $sentenciaSQLOperacion =
                'INSERT INTO operaciones 
                (operaciones.total, operaciones.descuento, operaciones.subtotal, operaciones.notas, operaciones.tipo_operacion, operaciones.estado, operaciones.cliente_id) 
                VALUES (?,?,?,?,?,?,?)';
            $registro_operacion = $this->conexion -> prepare($sentenciaSQLOperacion); # PDOStatement

            for($i = 0; $i < sizeof($datos_operacion); $i++) { # Liga parámetros
                $registro_operacion -> bindParam($i+1, $datos_operacion[$i]);
            }

            $registro_operacion -> execute(); # Ejecuta

            $operacion_id = $this->conexion->lastInsertId(); # Recupera el ID


            # ------------------ # 2 Registro de los productos incluídos ---------------------
            $sentenciaSQLProductosIncluidos = 'INSERT INTO productos_incluidos VALUES (?,?,?,?)';
            $sentenciaSQLUnidadesInventario = 'UPDATE inventario SET unidades = unidades - ? WHERE producto_id = ?';
            $registro_productos = $this->conexion -> prepare($sentenciaSQLProductosIncluidos); # PDOStatement
            $registro_unidades_inventario = $this->conexion -> prepare($sentenciaSQLUnidadesInventario); # PDOStatement

            foreach($lista_productos_incluidos as $producto) {
                $registro_productos -> bindParam(1, $operacion_id);
                $registro_productos -> bindParam(2, $producto->producto_id);
                $registro_productos -> bindParam(3, $producto->cantidad);
                $registro_productos -> bindParam(4, $producto->total);

                $registro_productos -> execute();

                $registro_unidades_inventario -> bindParam(1, $producto->cantidad);
                $registro_unidades_inventario -> bindParam(2, $producto->producto_id);
                
                $registro_unidades_inventario -> execute();
            }
            
            # ------------------ # 3 Registro del abono correspondiente ---------------------
            $sentenciaSQLAbono = 'INSERT INTO abonos VALUES (?,?,?,?,?)';
            $registro_abono = $this->conexion -> prepare($sentenciaSQLAbono); # PDOStatement
            array_unshift($datos_abono, $operacion_id);
            
            for($i = 0; $i < sizeof($datos_abono); $i++) { # Liga parámetros
                $registro_abono -> bindParam($i+1, $datos_abono[$i]);
            }

            $registro_abono -> execute();

            # Mantiene todos los cambios siempre que cada execute sea exitoso
            # De saltar un error, revierte estos cambios
            $this->conexion -> commit();

            return true;

        } catch(PDOException $e) {
            return 'Error: ' .$e->getMessage();
        } finally {
            $registro_operacion = null;
            $registro_productos = null;
            $registro_operacion = null;
            $registro_abono = null;
            $this->cerrarConexion();
        }
    }
}