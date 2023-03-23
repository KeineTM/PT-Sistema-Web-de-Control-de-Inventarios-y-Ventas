-- Script de creación de BD, sus tablas y sus relaciones:
CREATE DATABASE `tienda`
    DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--------------------------------------------------------------------------------------------
-- Tabla de tipos de usuario
CREATE TABLE `tipos_usuario` (
    tipo_id SMALLINT(2) PRIMARY KEY AUTO_INCREMENT,
    tipo_usuario VARCHAR(50) NOT NULL
);

-- Tabla de usuarios
CREATE TABLE `usuarios` (
    usuario_id VARCHAR(6) PRIMARY KEY,
    nombre VARCHAR(80) NOT NULL,
    apellido_paterno VARCHAR(80) NOT NULL,
    apellido_materno VARCHAR(80) NOT NULL,
    telefono CHAR(10) NOT NULL,
    rfc CHAR(13) NOT NULL UNIQUE,
    email VARCHAR(150) NOT NULL,
    password VARCHAR(200) NOT NULL,
    notas VARCHAR(250),
    estado BOOLEAN DEFAULT 1,
    tipo_usuario SMALLINT(2) NOT NULL,
    --Un campo FULLTEXT permite incluir las columnas donde se realizarán ciertas consultas
    FULLTEXT KEY busqueda(usuario_id, nombre, apellido_paterno, apellido_materno),
    FOREIGN KEY (tipo_usuario) REFERENCES tipos_usuario(tipo_id)
        --Evita eliminar registros ligados a otra tabla cuando se intenta eliminar un registro de esta otra
        --Mientras que actualiza en cascada los cambios de la tabla catágolo a la primera
        ON DELETE RESTRICT ON UPDATE CASCADE
);

INSERT INTO usuarios (usuario_id, nombre, apellido_paterno, apellido_materno, telefono, rfc, email, password, notas, tipo_usuario) VALUES 
    ('ABCD00', 'Jessica', 'Trejo', 'Méndex', '9876543210', 'ABCD00XXXXXXX', 'jessica.trejome@globokids.com', 
    '$2y$05$Gg1zv/EBXLsPAo63u8J/3ewycyBL8MhyneJHcs1GmixBXMqiM0mMS', 'Usuario de prueba con privilegios de administrador.', 1),
    ('ABCDXX', 'Jessica', 'T', 'M', '9876543210', 'ABCDXXXXXXXXX', 'jessica.trejome@globokids.com', 
    '$2y$05$8qKl/Nz7EUti/Fq.u/TCdOJqM23dLDhrl1xAX9AOmMHQF.hegsQ.C', 'Usuario de prueba con privilegios de empleado.', 2);
    
SELECT usuarios.usuario_id, usuarios.password, tipos_usuario.tipo_usuario, 
    CONCAT(usuarios.nombre,' ',usuarios.apellido_paterno,' ',usuarios.apellido_materno) AS nombre_completo 
    FROM usuarios 
    INNER JOIN tipos_usuario ON usuarios.tipo_usuario = tipos_usuario.tipo_id
    WHERE usuarios.usuario_id = 'ABCD00' LIMIT 1;

SELECT *  FROM usuarios
    WHERE MATCH(usuario_id, nombre, apellido_paterno, apellido_materno)
    AGAINST('busqueda' IN BOOLEAN MODE);

--------------------------------------------------------------------------------------------
-- Tabla de categorías del inventario
CREATE TABLE `categorias_inventario` (
    categoria_id SMALLINT(3) PRIMARY KEY AUTO_INCREMENT,
    categoria VARCHAR(100) UNIQUE NOT NULL,
    estado BOOLEAN DEFAULT 1
);

-- Tabla de inventario de productos
CREATE TABLE `inventario` (
    producto_id VARCHAR(20) PRIMARY KEY,
    nombre VARCHAR(80) NOT NULL,
    categoria_id SMALLINT(3) NOT NULL,
    descripcion TEXT(500),
    unidades INT NOT NULL,
    unidades_minimas INT,
    precio_compra DECIMAL(8,2),
    precio_venta DECIMAL(8,2) NOT NULL,
    precio_mayoreo DECIMAL(8,2),
    estado BOOLEAN DEFAULT 1,
    foto_url VARCHAR(250) DEFAULT "no-foto.jpg",
    FULLTEXT KEY busqueda(producto_id, nombre),
    FOREIGN KEY (categoria_id) REFERENCES categorias_inventario(categoria_id)
        ON DELETE RESTRICT ON UPDATE CASCADE
);

-- Tabla auxiliar para caducidades de productos que lo necesitan
CREATE TABLE `perecederos_inventario` (
    producto_id VARCHAR(20) PRIMARY KEY,
    caducidad DATE NOT NULL,
    FOREIGN KEY (producto_id) REFERENCES inventario(producto_id)
        ON DELETE RESTRICT ON UPDATE CASCADE
);

--------------------------------------------------------------------------------------------
-- Tabla de contactos


--------------------------------------------------------------------------------------------
-- Tabla catálogo de tipos de transaccion (venta, apartado, devolución)

-- Tabla de operaciones

-- Tabla pivote de productos incluídos en las operaciones

-- Tabla catálogo de métodos de pago

-- Tabla pivote de abonos a las operaciones realizados por los empleados


--------------------------------------------------------------------------------------------

-- Tabla catálogo para las direcciones con los estados de la república

-- Tabla catálogo para las direcciones con los códigos postales que referencian estados y ciudades

-- Tabla de datos del negocio

-- Tabla catálogo de redes sociales

-- Tabla pivote con las url de las redes con las que cuenta el negocio

