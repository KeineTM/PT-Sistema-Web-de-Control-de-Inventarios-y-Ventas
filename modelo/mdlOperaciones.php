<?php
require_once "mdlConexion.php";

class ModeloOperaciones extends ModeloConexion {
    # Método constructor
    public function __construct() {
        $this->db_usuario = "root";
        $this->db_password = "";
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
                (operaciones.total, operaciones.descuento, operaciones.subtotal, operaciones.notas, operaciones.tipo_operacion, operaciones.estado, operaciones.contacto_id) 
                VALUES (?,?,?,?,?,?,?)';
            $consulta = $this->conexion -> prepare($this->sentenciaSQL); # PDOStatement

            for($i = 0; $i < sizeof($datos_operacion); $i++) { # Liga parámetros
                $consulta -> bindParam($i+1, $datos_operacion[$i]);
            }

            $consulta -> execute(); # Ejecuta

            $operacion_id = $this->conexion->lastInsertId(); # Recupera el ID


            # ------------------ # 2 Registro de los productos incluídos ---------------------
            $sentenciaSQLProductosIncluidos = 'INSERT INTO productos_incluidos VALUES (?,?,?,?)';
            $sentenciaSQLUnidadesInventario = ($datos_operacion[4] === 'DE') # Evalúa si se trata de una devolución
                ? 'UPDATE inventario SET unidades = unidades + ? WHERE producto_id = ?' # suma existencias
                : 'UPDATE inventario SET unidades = unidades - ? WHERE producto_id = ?'; # resta existencias
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

    /** Método que devuelve un array con los registros del conjunto de tablas que componen una operación
     * Si se ingresa un ID (INTERGER), devolverá sólo el registro 
     */
    public function mdlLeer($id = '') {
        $this->sentenciaSQL = ($id === '')
            ? 'SELECT operaciones.operacion_id,
                productos_incluidos.producto_id, productos_incluidos.unidades, 
                inventario.nombre, inventario.precio_venta,
                productos_incluidos.total_acumulado,
                operaciones.subtotal, operaciones.descuento, operaciones.total, operaciones.notas, operaciones.tipo_operacion, operaciones.estado,
                metodos_pago.metodo,
                abonos.fecha, abonos.empleado_id, abonos.abono,
                CONCAT(usuarios.nombre," ", usuarios.apellido_paterno," ", usuarios.apellido_materno) AS nombre_completo
                FROM operaciones
                INNER JOIN productos_incluidos ON operaciones.operacion_id = productos_incluidos.operacion_id
                INNER JOIN inventario ON productos_incluidos.producto_id = inventario.producto_id
                INNER JOIN abonos ON operaciones.operacion_id = abonos.operacion_id
                INNER JOIN metodos_pago ON abonos.metodo_pago = metodos_pago.metodo_id
                INNER JOIN usuarios ON usuarios.usuario_id = abonos.empleado_id
                GROUP BY productos_incluidos.producto_id'
            : 'SELECT operaciones.operacion_id,
                productos_incluidos.producto_id, productos_incluidos.unidades, 
                inventario.nombre, inventario.precio_venta,
                productos_incluidos.total_acumulado,
                operaciones.subtotal, operaciones.descuento, operaciones.total, operaciones.notas, operaciones.tipo_operacion, operaciones.estado,
                metodos_pago.metodo,
                abonos.fecha, abonos.empleado_id, abonos.abono,
                CONCAT(usuarios.nombre," ", usuarios.apellido_paterno," ", usuarios.apellido_materno) AS nombre_completo
                FROM operaciones
                INNER JOIN productos_incluidos ON operaciones.operacion_id = productos_incluidos.operacion_id
                INNER JOIN inventario ON productos_incluidos.producto_id = inventario.producto_id
                INNER JOIN abonos ON operaciones.operacion_id = abonos.operacion_id
                INNER JOIN metodos_pago ON abonos.metodo_pago = metodos_pago.metodo_id
                INNER JOIN usuarios ON usuarios.usuario_id = abonos.empleado_id
                WHERE operaciones.operacion_id = ?
                GROUP BY productos_incluidos.producto_id';
        
        return $this->consultaRead($id);
    }

    public function mdlLeerAbonos($id='') {
        $this->sentenciaSQL = ($id === '')
            ? 'SELECT abonos.empleado_id, abonos.fecha, abonos.abono, abonos.metodo_pago,
                metodos_pago.metodo, contactos.contacto_id,
                CONCAT(usuarios.nombre," ", usuarios.apellido_paterno," ", usuarios.apellido_materno) AS nombre_completo, 
                CONCAT(contactos.nombre," ", contactos.apellido_paterno," ", contactos.apellido_materno) AS nombre_completo_cliente 
                FROM abonos
                INNER JOIN metodos_pago ON abonos.metodo_pago = metodos_pago.metodo_id
                INNER JOIN usuarios ON usuarios.usuario_id = abonos.empleado_id
                INNER JOIN operaciones ON operaciones.operacion_id = abonos.operacion_id
                INNER JOIN contactos ON contactos.contacto_id = operaciones.contacto_id'
            : 'SELECT abonos.empleado_id, abonos.fecha, abonos.abono, abonos.metodo_pago,
                metodos_pago.metodo, contactos.contacto_id,
                CONCAT(usuarios.nombre," ", usuarios.apellido_paterno," ", usuarios.apellido_materno) AS nombre_completo, 
                CONCAT(contactos.nombre," ", contactos.apellido_paterno," ", contactos.apellido_materno) AS nombre_completo_cliente 
                FROM abonos
                INNER JOIN metodos_pago ON abonos.metodo_pago = metodos_pago.metodo_id
                INNER JOIN usuarios ON usuarios.usuario_id = abonos.empleado_id
                INNER JOIN operaciones ON operaciones.operacion_id = abonos.operacion_id
                INNER JOIN contactos ON contactos.contacto_id = operaciones.contacto_id
                WHERE abonos.operacion_id = ?';
        return $this -> consultaRead($id); 
    }

    /** Método para el registro de abonos en un apartado */
    public function mdlRegistrarAbono($listaDatos) {
        $this->registros = $listaDatos;
        $this->sentenciaSQL = 'INSERT INTO abonos VALUES (?,?,?,?,?)';
        return $this->consultasCUD($listaDatos);
    }

    /** Método que edita el estado de una operación de apartado para completarla */
    public function mdlCompletarAbono($listaDatos) {
        $this->registros = $listaDatos;
        $this->sentenciaSQL = 
            'UPDATE operaciones
            SET estado = ?;
            LIMIT 1';
    
        return $this->consultasCUD();
    }

    /** Método que devuelve los registros de operaciones tipo venta dentro de un rango de tiempo */
    public function mdlLeerOperacionesPorRangoDeFecha($fecha_inicio, $fecha_fin, $tipo_operacion_id) {
        $this->sentenciaSQL = ($tipo_operacion_id !== 'AP')
            ? 'SELECT operaciones.operacion_id,
            productos_incluidos.producto_id, productos_incluidos.unidades, 
            inventario.nombre, inventario.precio_venta,
            productos_incluidos.total_acumulado,
            operaciones.subtotal, operaciones.descuento, operaciones.total, operaciones.notas, operaciones.tipo_operacion, operaciones.estado,
            metodos_pago.metodo,
            abonos.fecha, abonos.empleado_id, abonos.abono,
            CONCAT(usuarios.nombre," ", usuarios.apellido_paterno," ", usuarios.apellido_materno) AS nombre_completo
            FROM operaciones
            INNER JOIN productos_incluidos ON operaciones.operacion_id = productos_incluidos.operacion_id
            INNER JOIN inventario ON productos_incluidos.producto_id = inventario.producto_id
            INNER JOIN abonos ON operaciones.operacion_id = abonos.operacion_id
            INNER JOIN metodos_pago ON abonos.metodo_pago = metodos_pago.metodo_id
            INNER JOIN usuarios ON usuarios.usuario_id = abonos.empleado_id
            WHERE operaciones.tipo_operacion = ? AND abonos.fecha >= ? AND abonos.fecha < ?
            GROUP BY operaciones.operacion_id'
            : 'SELECT operaciones.operacion_id,
            productos_incluidos.producto_id, productos_incluidos.unidades, 
            inventario.nombre, inventario.precio_venta,
            productos_incluidos.total_acumulado,
            operaciones.subtotal, operaciones.descuento, operaciones.total, operaciones.notas, operaciones.tipo_operacion, operaciones.estado,
            metodos_pago.metodo,
            abonos.fecha, abonos.empleado_id, abonos.abono,
            CONCAT(contactos.nombre," ", contactos.apellido_paterno) AS nombre_cliente,
            CONCAT(usuarios.nombre," ", usuarios.apellido_paterno," ", usuarios.apellido_materno) AS nombre_completo
            FROM operaciones
            INNER JOIN productos_incluidos ON operaciones.operacion_id = productos_incluidos.operacion_id
            INNER JOIN inventario ON productos_incluidos.producto_id = inventario.producto_id
            INNER JOIN abonos ON operaciones.operacion_id = abonos.operacion_id
            INNER JOIN metodos_pago ON abonos.metodo_pago = metodos_pago.metodo_id
            INNER JOIN usuarios ON usuarios.usuario_id = abonos.empleado_id
            INNER JOIN contactos ON operaciones.contacto_id = contactos.contacto_id
            WHERE operaciones.tipo_operacion = ? AND abonos.fecha >= ? AND abonos.fecha < ?
            GROUP BY operaciones.operacion_id';
        
        try {
            $this->abrirConexion(); # Conecta
            $pdo = $this->conexion -> prepare($this->sentenciaSQL); # Crea PDOStatement
            
            $pdo -> bindParam(1, $tipo_operacion_id);
            $pdo -> bindParam(2, $fecha_inicio);
            $pdo -> bindParam(3, $fecha_fin);
    
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

    /** Método que reintegra el número de productos indicados a la tabla de inventario */
    public function mdlRestaurarProductoAlInventario($listaDatos) {
        $this->sentenciaSQL = 'UPDATE inventario SET unidades = unidades + ? WHERE producto_id = ?';
        return $this->consultasCUD($listaDatos);
    }

    /** Método que recibe una lista con el id de la operación.
     * Si se incluye el parámetro $todos = true, eliminará todos los abonos de una misma operación.
     * Si se omite deberá incluirse en la lista el id del empleado también.
    */
    public function mdlEliminarAbono($listaIDs, $todos = false) {
        $this->registros = $listaIDs;
        $this->sentenciaSQL = ($todos === false)
            ? 'DELETE FROM abonos WHERE operacion_id = ? AND empleado_id = ?' # Borra un sólo abono de la operación
            : 'DELETE FROM abonos WHERE operacion_id = ?'; # Borra todos los abonos de la operación
        return $this->consultasCUD();
    }

    /** Método que elimina todos los registros relacionados a un ID de operación en las tablas
     * Operaciones, Abonos, Productos Incluídos.
     * Y ajusta la cantidad de unidades disponibles en el inventario
     */
    public function mdlEliminarOperacionCompleta($operacion_id, $devolucion = false) {
        try {
            $this->abrirConexion();
            # ------------------ # Consulta de los productos de la operación ---------------------
            $this->sentenciaSQL = 'SELECT * FROM productos_incluidos WHERE operacion_id = ?';
            $consulta = $this->conexion -> prepare($this->sentenciaSQL); # PDOStatement
            $consulta -> bindParam(1, $operacion_id);
            $consulta -> execute();
            $productos_incluidos = $consulta -> fetchAll(PDO::FETCH_ASSOC);
                        
            # ------------------ # 1 Eliminación de todos los productos de la operación ---------------------
            $this->conexion -> beginTransaction();
            
            $this->sentenciaSQL = 'DELETE FROM productos_incluidos WHERE operacion_id = ?';
            $sentenciaEliminarProductos = $this->conexion -> prepare($this->sentenciaSQL);
            $sentenciaEliminarProductos -> bindParam(1, $operacion_id);
            $sentenciaEliminarProductos -> execute();

            # ------------------ # 2 Ajuste de unidades de los productos en inventario ---------------------
            $this->sentenciaSQL = ($devolucion === false) 
                ?'UPDATE inventario SET unidades = unidades + ? WHERE producto_id = ?' # Si se elimina una venta o apartado reintegra unidades
                :'UPDATE inventario SET unidades = unidades - ? WHERE producto_id = ?'; # Si se elimina una devolución resta unidades
            $sentenciaRestaurarProductos = $this->conexion -> prepare($this->sentenciaSQL);
            foreach($productos_incluidos as $producto) {
                $sentenciaRestaurarProductos -> bindParam(1, $producto['unidades']);
                $sentenciaRestaurarProductos -> bindParam(2, $producto['producto_id']);
                $sentenciaRestaurarProductos -> execute();
            }

            # ------------------ # 3 Eliminación de todos los abonos ---------------------
            $this->sentenciaSQL = 'DELETE FROM abonos WHERE operacion_id = ?';
            $sentenciaEliminarAbonos = $this->conexion -> prepare($this->sentenciaSQL);
            $sentenciaEliminarAbonos -> bindParam(1, $operacion_id);
            $sentenciaEliminarAbonos -> execute();

            # ------------------ # 4 Eliminación de la operación ---------------------
            $this->sentenciaSQL = 'DELETE FROM operaciones WHERE operacion_id = ?';
            $sentenciaEliminarOperacion = $this->conexion -> prepare($this->sentenciaSQL);
            $sentenciaEliminarOperacion -> bindParam(1, $operacion_id);
            $sentenciaEliminarOperacion -> execute();

            return true;
        } catch(PDOException $e) {
            return 'Error: ' .$e->getMessage();
        } finally {
            $this->conexion -> commit();
            $sentenciaEliminarProductos = null;
            $sentenciaRestaurarProductos = null;
            $sentenciaEliminarAbonos = null;
            $sentenciaEliminarOperacion = null;
            $this->cerrarConexion();
        }
    }
}