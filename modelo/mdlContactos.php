<?php
require_once 'mdlConexion.php';

class ModeloContactos extends ModeloConexion {
    # Método constructor
    public function __construct() {
        $this->db_usuario = "root";
        $this->db_password = "";
    }

    public function mdlRegistrar($listaDatos) {
        $this->registros = $listaDatos;
        $this->sentenciaSQL = 'INSERT INTO contactos VALUES (?,?,?,?,?,?,?)';
        return $this->consultasCUD();
    }

    /** Método que retorna todos los registros o, si se indica un id, retorna la coincidencia.
     * En caso de ser exitoso devuelve un array con el resultado.
     * En caso de fallar, devuelve una cadena con el error.
     */
    public function mdlLeer($id='') {
        $this->sentenciaSQL = ($id === '')
            ? 'SELECT contactos.contacto_id, contactos.nombre, contactos.apellido_paterno, contactos.apellido_materno,
                contactos.email, contactos.notas, contactos.tipo_contacto AS tipo_id,
                tipos_contacto.tipo_contacto
                FROM contactos
                INNER JOIN tipos_contacto ON contactos.tipo_contacto = tipos_contacto.tipo_id'
            : 'SELECT contactos.contacto_id, contactos.nombre, contactos.apellido_paterno, contactos.apellido_materno,
                contactos.email, contactos.notas, contactos.tipo_contacto AS tipo_id,
                tipos_contacto.tipo_contacto
                FROM contactos
                INNER JOIN tipos_contacto ON contactos.tipo_contacto = tipos_contacto.tipo_id
                WHERE contactos.contacto_id = ? LIMIT 1';
        return $this->consultaRead($id);
    }

    public function mdlExiste($id) {
        $this->sentenciaSQL = 'SELECT COUNT(*) AS conteo FROM contactos WHERE contacto_id = ?';
        return $this->consultaRead($id);
    }

    /** Método que retorna todos los registros de una categoría indicada.
     * En caso de ser exitoso devuelve un array con el resultado.
     * En caso de fallar, devuelve una cadena con el error.
     */
    public function mdlLeerPorCategoria($categoria) {
        $this->sentenciaSQL = 'SELECT contactos.contacto_id, contactos.nombre, contactos.apellido_paterno, contactos.apellido_materno,
            contactos.email, contactos.notas, contactos.tipo_contacto AS tipo_id,
            tipos_contacto.tipo_contacto
            FROM contactos
            INNER JOIN tipos_contacto ON contactos.tipo_contacto = tipos_contacto.tipo_id
            WHERE contacto.tipo_contacto = ?';
        return $this->consultaRead($categoria);
    }

    /** Método que envía una lista de datos para actualizar un registro de la BD.
     * En caso se ser exitoso devuelve TRUE, de lo contrario devuelve una cadena con el error.
     * Los datos en la cadena deben tener el orden: contacto_id(NUEVO), nombre, apellido_paterno, apellido_materno,
     * email, notas, tipo_contacto, contacto_id(ORIGINAL).
     */
    public function mdlEditar($listaDatos) {
        $this->registros = $listaDatos;
        $this->sentenciaSQL = 'UPDATE contactos SET contacto_id = ?, nombre = ?, apellido_paterno = ?, apellido_materno = ?,
            email = ?, notas = ?, tipo_contacto = ?
            WHERE contacto_id = ? LIMIT 1';
        return $this->consultasCUD();
    }

    /** Método que borra de la tabla un contacto con el id indicado */
    public function mdlEliminar($id) {
        array_push($this->registros, $id);
        $this->sentenciaSQL = 'DELETE FROM contactos WHERE contacto_id = ? LIMIT 1';
        return $this->consultasCUD();
    }

    /** Método que cuenta los teléfonos que coincidan con un id para validar su existencia.*/
    public function mdlContarCoincidencias($id_nuevo, $id_antiguo = '') {
        $this->sentenciaSQL = ($id_antiguo === '')
            ? 'SELECT count(*) AS conteo FROM contactos WHERE contacto_id = ?'
            : 'SELECT count(*) AS conteo FROM contactos WHERE contacto_id = ? AND contacto_id <> ?';
        
        try {
            $this->abrirConexion(); # Conecta
            $pdo = $this->conexion -> prepare($this->sentenciaSQL); # Crea PDOStatement
                
            $pdo -> bindParam(1, $id_nuevo, PDO::PARAM_STR);
            if($id_antiguo !== '')
                $pdo -> bindParam(2, $id_antiguo, PDO::PARAM_STR);
        
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


    /**
     * Método que cuenta los registros en la tabla. 
     */
    public function mdlConteoRegistros($id = '', $tipo = '') {
        switch(true) {
            case($id === '' && $tipo === ''):  $this->sentenciaSQL = 'SELECT count(*) AS conteo FROM contactos'; break;
            case($id === '' && $tipo !== ''):  $this->sentenciaSQL = 'SELECT count(*) AS conteo FROM contactos WHERE tipo_contacto = ?'; break;
            case($id !== '' && $tipo === ''):  $this->sentenciaSQL = 'SELECT count(*) AS conteo FROM contactos WHERE contacto_id = ?'; break;
            case($id !== '' && $tipo !== ''):  $this->sentenciaSQL = 'SELECT count(*) AS conteo FROM contactos WHERE contacto_id = ? AND tipo_contacto = ?'; break;
        }

        try {
            $this->abrirConexion(); # Conecta
            $pdo = $this->conexion -> prepare($this->sentenciaSQL); # Crea PDOStatement
            
            switch(true) {
                case($id === '' && $tipo !== ''):  $pdo -> bindParam(1, $tipo, PDO::PARAM_INT); break;
                case($id !== '' && $tipo === ''):  $pdo -> bindParam(1, $id, PDO::PARAM_INT); break;
                case($id !== '' && $tipo !== ''):  
                    $pdo -> bindParam(1, $id, PDO::PARAM_INT);
                    $pdo -> bindParam(2, $tipo, PDO::PARAM_INT);
                break;
            }
    
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

    /** Método que recupera los registros para la paginación */
    public function mdlLeerParaPaginacion($limit, $offset, $tipo='') {
        $this->sentenciaSQL = ($tipo === '') 
            ? 'SELECT contactos.contacto_id, contactos.nombre, contactos.apellido_paterno, contactos.apellido_materno,
            contactos.email, contactos.notas, contactos.tipo_contacto AS tipo_id,
            tipos_contacto.tipo_contacto
            FROM contactos
            INNER JOIN tipos_contacto ON contactos.tipo_contacto = tipos_contacto.tipo_id
            LIMIT ? OFFSET ?'
            : 'SELECT contactos.contacto_id, contactos.nombre, contactos.apellido_paterno, contactos.apellido_materno,
            contactos.email, contactos.notas, contactos.tipo_contacto AS tipo_id,
            tipos_contacto.tipo_contacto
            FROM contactos
            INNER JOIN tipos_contacto ON contactos.tipo_contacto = tipos_contacto.tipo_id
            WHERE contactos.tipo_contacto= ?
            LIMIT ? OFFSET ?';

        try {
            $this->abrirConexion(); # Conecta
            $pdo = $this->conexion -> prepare($this->sentenciaSQL); # Crea PDOStatement
            
            if($tipo === '') {
                $pdo -> bindParam(1, $limit, PDO::PARAM_INT);
                $pdo -> bindParam(2, $offset, PDO::PARAM_INT);
            } else {
                $pdo -> bindParam(1, $tipo, PDO::PARAM_INT);
                $pdo -> bindParam(2, $limit, PDO::PARAM_INT);
                $pdo -> bindParam(3, $offset, PDO::PARAM_INT);
            }
            

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

    public function mdlBuscarEnFullText($palabra_clave, $limit, $offset) {
        $this->sentenciaSQL = 'SELECT contactos.contacto_id, contactos.nombre, contactos.apellido_paterno, contactos.apellido_materno,
            contactos.email, contactos.notas, contactos.tipo_contacto AS tipo_id,
            tipos_contacto.tipo_contacto
            FROM contactos
            INNER JOIN tipos_contacto ON contactos.tipo_contacto = tipos_contacto.tipo_id
            WHERE MATCH(contactos.contacto_id, contactos.nombre, contactos.apellido_paterno, contactos.apellido_materno)
            AGAINST (?)
            LIMIT ? OFFSET ?';
        
        try {
            $this->abrirConexion(); # Conecta
            $pdo = $this->conexion -> prepare($this->sentenciaSQL); # Crea PDOStatement
            
            $pdo -> bindParam(1, $palabra_clave, PDO::PARAM_STR);
            $pdo -> bindParam(2, $limit, PDO::PARAM_INT);
            $pdo -> bindParam(3, $offset, PDO::PARAM_INT);
    
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