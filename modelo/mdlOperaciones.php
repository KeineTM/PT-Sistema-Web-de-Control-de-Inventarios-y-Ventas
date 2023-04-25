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

    /** Método que ejecuta la serie de consulas necesarioas para dar de alta una operacion
     * Retorna true si todo salió correctamente
     * De lo contrario retorna el error
     */
    public function mdlRegistrarOperacionCompleta($datos_operacion, $lista_productos_incluidos, $datos_abono) {
        try {
            $this->abrirConexion();

            # ------------------ # 1 Registro de la operación ---------------------
            $this->sentenciaSQL =
                'INSERT INTO operaciones 
                (operaciones.total, operaciones.descuento, operaciones.subtotal, operaciones.notas, operaciones.tipo_operacion, operaciones.estado, operaciones.cliente_id) 
                VALUES (?,?,?,?,?,?,?)';
            $consulta = $this->conexion -> prepare($this->sentenciaSQL); # PDOStatement

            for($i = 0; $i < sizeof($datos_operacion); $i++) { # Liga parámetros
                $consulta -> bindParam($i+1, $datos_operacion[$i]);
            }

            $consulta -> execute(); # Ejecuta

            $operacion_id = $this->conexion->lastInsertId(); # Recupera el ID


            # ------------------ # 2 Registro de los productos incluídos ---------------------
            $sentenciaSQLProductosIncluidos = 'INSERT INTO productos_incluidos VALUES (?,?,?,?)';
            $sentenciaSQLUnidadesInventario = 'UPDATE inventario SET unidades = unidades - ? WHERE producto_id = ?';
            $consulta = $this->conexion -> prepare($sentenciaSQLProductosIncluidos); # PDOStatement
            $registro_unidades_inventario = $this->conexion -> prepare($sentenciaSQLUnidadesInventario); # PDOStatement

            foreach($lista_productos_incluidos as $producto) {
                $consulta -> bindParam(1, $operacion_id);
                $consulta -> bindParam(2, $producto->producto_id);
                $consulta -> bindParam(3, $producto->cantidad);
                $consulta -> bindParam(4, $producto->total);

                $consulta -> execute();

                $registro_unidades_inventario -> bindParam(1, $producto->cantidad);
                $registro_unidades_inventario -> bindParam(2, $producto->producto_id);
                
                $registro_unidades_inventario -> execute();
            }
            $registro_unidades_inventario = null;
            
            # ------------------ # 3 Registro del abono correspondiente ---------------------
            $sentenciaSQLAbono = 'INSERT INTO abonos VALUES (?,?,?,?,?)';
            $consulta = $this->conexion -> prepare($sentenciaSQLAbono); # PDOStatement
            array_unshift($datos_abono, $operacion_id);
            
            for($i = 0; $i < sizeof($datos_abono); $i++) { # Liga parámetros
                $consulta -> bindParam($i+1, $datos_abono[$i]);
            }

            $consulta -> execute();

            return true;

        } catch(PDOException $e) {
            return 'Error: ' .$e->getMessage();
        } finally {
            $consulta = null;
            $registro_unidades_inventario = null;
            $this->cerrarConexion();
        }
    }

    /** Método que devuelve los registros de operaciones tipo venta dentro de un rango de tiempo */
    public function mdlLeerVentasPorRangoDeFecha($fecha_inicio, $fecha_fin) {
        try {
            // Retorna 13 datos en total 
            // En una venta 8 registros son únicos y pueden repetirse por el número de productos incluidos
            $this->sentenciaSQL = 
                'SELECT operaciones.operacion_id,
                productos_incluidos.producto_id, productos_incluidos.unidades, 
                inventario.nombre, inventario.precio_venta,
                productos_incluidos.total_acumulado,
                operaciones.subtotal, operaciones.descuento, operaciones.total, operaciones.notas,
                metodos_pago.metodo,
                abonos.fecha, abonos.empleado_id
                FROM operaciones
                INNER JOIN productos_incluidos ON operaciones.operacion_id = productos_incluidos.operacion_id
                INNER JOIN inventario ON productos_incluidos.producto_id = inventario.producto_id
                INNER JOIN abonos ON operaciones.operacion_id = abonos.operacion_id
                INNER JOIN metodos_pago ON abonos.metodo_pago = metodos_pago.metodo_id
                WHERE abonos.fecha >= ? AND abonos.fecha < ?';
        
            $this->abrirConexion(); # Conecta
            $pdo = $this->conexion -> prepare($this->sentenciaSQL); # Crea PDOStatement
            
            $pdo -> bindParam(1, $fecha_inicio, PDO::PARAM_STR, 19);
            $pdo -> bindParam(2, $fecha_fin, PDO::PARAM_STR, 19);

            $pdo -> execute(); # Ejecuta

            $this->registros = $pdo -> fetchAll(PDO::FETCH_ASSOC); # Recupera datos

            return $this->registros;
        } catch(PDOException $e) {
            return 'Error: ' .$e->getMessage(); # Si hubo un error lo Retorna
        } finally {
            $pdo = null; # Limpia
            $this->cerrarConexion(); # Cierra
        }
        
    }

    /** Método que devuelve un array con los registros del conjunto de tablas que componen una operación
     * Si se ingresa un ID (INTERGER), devolverá sólo el registro 
     */
    public function mdlLeer($id = '') {
        $this->sentenciaSQL = ($id !== '')
            ? 'SELECT operaciones.operacion_id,
                productos_incluidos.producto_id, productos_incluidos.unidades, 
                inventario.nombre, inventario.precio_venta,
                productos_incluidos.total_acumulado,
                operaciones.subtotal, operaciones.descuento, operaciones.total, operaciones.notas,
                metodos_pago.metodo,
                abonos.fecha, abonos.empleado_id
                FROM operaciones
                INNER JOIN productos_incluidos ON operaciones.operacion_id = productos_incluidos.operacion_id
                INNER JOIN inventario ON productos_incluidos.producto_id = inventario.producto_id
                INNER JOIN abonos ON operaciones.operacion_id = abonos.operacion_id
                INNER JOIN metodos_pago ON abonos.metodo_pago = metodos_pago.metodo_id'
            : 'SELECT operaciones.operacion_id,
                productos_incluidos.producto_id, productos_incluidos.unidades, 
                inventario.nombre, inventario.precio_venta,
                productos_incluidos.total_acumulado,
                operaciones.subtotal, operaciones.descuento, operaciones.total, operaciones.notas,
                metodos_pago.metodo,
                abonos.fecha, abonos.empleado_id
                FROM operaciones
                INNER JOIN productos_incluidos ON operaciones.operacion_id = productos_incluidos.operacion_id
                INNER JOIN inventario ON productos_incluidos.producto_id = inventario.producto_id
                INNER JOIN abonos ON operaciones.operacion_id = abonos.operacion_id
                INNER JOIN metodos_pago ON abonos.metodo_pago = metodos_pago.metodo_id
                WHERE operaciones.operacion_id = ? LIMIT 1';
        
        return $this->consultaRead($id);
    }
}