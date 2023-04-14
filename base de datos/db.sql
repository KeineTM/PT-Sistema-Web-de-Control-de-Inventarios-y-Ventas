-- Script de creación de BD, sus tablas y sus relaciones:
CREATE DATABASE `tienda`
    DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--------------------------------------------------------------------------------------------
-- Tabla de tipos de usuario
CREATE TABLE `tipos_usuarios` (
    tipo_id INT PRIMARY KEY AUTO_INCREMENT,
    tipo_usuario VARCHAR(50) NOT NULL
);

INSERT INTO tipos_usuario(tipo_usuario) VALUES
("Administrador"),
("Empleado");

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
    caducidad BOOLEAN DEFAULT 0,
    tipo_usuario INT NOT NULL,
    --Un campo FULLTEXT permite incluir las columnas donde se realizarán ciertas consultas
    FULLTEXT KEY busqueda(usuario_id, nombre, apellido_paterno, apellido_materno),
    FOREIGN KEY (tipo_usuario) REFERENCES tipos_usuario(tipo_id)
        --Evita eliminar registros ligados a otra tabla cuando se intenta eliminar un registro de esta otra
        --Mientras que actualiza en cascada los cambios de la tabla catágolo a la primera
        ON DELETE RESTRICT ON UPDATE CASCADE
);

INSERT INTO usuarios (usuario_id, nombre, apellido_paterno, apellido_materno, telefono, rfc, email, password, notas, tipo_usuario) VALUES 
    ('ABCD00', 'Jessica', 'Trejo', 'Méndex', '9876543210', 'ABCD00XXXXXXX', 'jessica.trejome@globokids.com', 
    '$2y$05$aXRy7SlJIV/ywlWTO5NVk.LsQVv6CCJuPXpM6AGxasy7x5QG7SGRS', 'Usuario de prueba con privilegios de administrador.', 1),
    ('ABCDXX', 'Jessica', 'T', 'M', '9876543210', 'ABCDXXXXXXXXX', 'jessica.tm@globokids.com', 
    '$2y$05$X5UeRCw5IY5gb.fCZySW4OCp/1x0L1e0q45bU3IV8ygEybuBkdA16', 'Usuario de prueba con privilegios de empleado.', 2);
    
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
    categoria_id INT PRIMARY KEY AUTO_INCREMENT,
    categoria VARCHAR(100) UNIQUE NOT NULL,
    estado BOOLEAN DEFAULT 1
);

-- Tabla de inventario de productos
CREATE TABLE `inventario` (
    producto_id VARCHAR(20) PRIMARY KEY,
    nombre VARCHAR(80) NOT NULL,
    categoria_id INT NOT NULL,
    descripcion TEXT(500),
    unidades INT NOT NULL,
    unidades_minimas INT,
    precio_compra DECIMAL(8,2),
    precio_venta DECIMAL(8,2) NOT NULL,
    precio_mayoreo DECIMAL(8,2),
    foto_url VARCHAR(250) DEFAULT "no-foto.jpg",
    caducidad DATE,
    estado BOOLEAN DEFAULT 1,
    FULLTEXT KEY busqueda(producto_id, nombre),
    FOREIGN KEY (categoria_id) REFERENCES categorias_inventario(categoria_id)
        ON DELETE RESTRICT ON UPDATE CASCADE
);

SELECT inventario.producto_id, inventario.nombre, categorias_inventario.categoria, inventario.descripcion,
inventario.unidades, inventario.unidades_minimas, inventario.precio_compra, inventario.precio_venta, inventario.precio_mayoreo,
inventario.foto_url, inventario.caducidad, inventario.estado
FROM inventario
INNER JOIN categorias_inventario ON inventario.categoria_id = categorias_inventario.categoria_id;

--------------------------------------------------------------------------------------------
-- Tabla de tipos de contacto
CREATE TABLE `tipos_contacto` (
    tipo_id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    tipo_contacto VARCHAR(50) NOT NULL
);

INSERT INTO tipos_contacto(tipo_contacto) VALUES
("Proveedor"),
("Cliente"),
("Servicios");

-- Tabla de contactos
CREATE TABLE `contactos` (
    contacto_id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nombre VARCHAR(80) NOT NULL,
    apellido_paterno VARCHAR(80) NOT NULL,
    apellido_materno VARCHAR(80),
    telefono VARCHAR(10) UNIQUE NOT NULL,
    email VARCHAR(150) UNIQUE,
    notas VARCHAR(250),
    tipo_contacto INT NOT NULL,
    FULLTEXT KEY busqueda(nombre, apellido_paterno, apellido_materno),
    FOREIGN KEY (tipo_contacto) REFERENCES tipos_contacto(tipo_id)
        ON DELETE RESTRICT ON UPDATE CASCADE
);

--------------------------------------------------------------------------------------------
-- Tabla catálogo de tipos de transaccion (venta, apartado, devolución)
CREATE TABLE `tipos_operacion` (
    tipo_id CHAR(2) PRIMARY KEY NOT NULL,
    operacion VARCHAR(50) NOT NULL
);

INSERT INTO tipos_operacion VALUES 
("VE", "Venta"),
("AP", "Apartado"),
("DE", "Devolución");

-- Tabla de operaciones
CREATE TABLE `operaciones` (
    operacion_id VARCHAR(10) PRIMARY KEY NOT NULL,
    descuento DECIMAL(8,2) DEFAULT 0,
    total DECIMAL(8,2) NOT NULL,
    notas VARCHAR(250),
    tipo_operacion CHAR(2) NOT NULL,
    estado BOOLEAN DEFAULT 1,
    cliente_id INT,
    FOREIGN KEY (tipo_operacion) REFERENCES tipos_operacion(tipo_id),
    FOREIGN KEY (cliente_id) REFERENCES contactos(contacto_id)
        ON DELETE RESTRICT ON UPDATE CASCADE
);

-- Tabla pivote de productos incluídos en las operaciones
CREATE TABLE `productos_incluidos` (
    operacion_id VARCHAR(10)  NOT NULL,
    producto_id VARCHAR(20) NOT NULL,
    unidades INT NOT NULL,
    -- Llave primaria compuesta
    PRIMARY KEY (operacion_id, producto_id),
    FOREIGN KEY (operacion_id) REFERENCES operaciones(operacion_id),
    FOREIGN KEY (producto_id) REFERENCES inventario(producto_id)
        ON DELETE RESTRICT ON UPDATE CASCADE
);

-- Tabla catálogo de métodos de pago
CREATE TABLE `metodos_pago` (
    metodo_id SMALLINT(2) PRIMARY KEY AUTO_INCREMENT NOT NULL,
    metodo VARCHAR(50) NOT NULL
);

INSERT INTO metodos_pago(metodo) VALUES 
("Efectivo"),
("Transferencia"),
("Tarjeta de débito"),
("Tarjeta de crédito");

-- Tabla pivote de abonos a las operaciones realizados por los empleados
CREATE TABLE `abonos` (
    operacion_id VARCHAR(10) NOT NULL,
    empleado_id VARCHAR(6) NOT NULL,
    fecha TIMESTAMP NOT NULL,
    abono DECIMAL(8,2) NOT NULL,
    metodo_pago SMALLINT(2) DEFAULT 1,
    PRIMARY KEY (operacion_id, empleado_id),
    FOREIGN KEY (operacion_id) REFERENCES operaciones(operacion_id),
    FOREIGN KEY (empleado_id) REFERENCES usuarios(usuario_id),
    FOREIGN KEY (metodo_pago) REFERENCES metodos_pago(metodo_id)
        ON DELETE RESTRICT ON UPDATE CASCADE
);

--------------------------------------------------------------------------------------------

-- Tabla catálogo para las direcciones con los estados de la república
CREATE TABLE `estados` (
    estado_id CHAR(2) PRIMARY KEY NOT NULL,
    estado VARCHAR(150) NOT NULL
);

INSERT INTO estados VALUES
("21", "Puebla");

-- Tabla catálogo para las direcciones con los códigos postales que referencian estados y ciudades
CREATE TABLE `codigos_postales` (
    codigo_postal CHAR(5) PRIMARY KEY NOT NULL,
    estado CHAR(2) NOT NULL,
    ciudad VARCHAR(150) NOT NULL,
    FOREIGN KEY (estado) REFERENCES estados(estado_id)
        ON DELETE RESTRICT ON UPDATE CASCADE
);

INSERT INTO codigos_postales VALUES 
("73680", "21", "Zacapoaxtla"),
("73703", "21", "Zaragoza");

-- Tabla de datos del negocio
CREATE TABLE `negocio` (
    rfc VARCHAR(13) PRIMARY KEY NOT NULL,
    razon_social VARCHAR(200) NOT NULL,
    nombre_tienda VARCHAR(200) NOT NULL,
    descripcion TEXT NOT NULL,
    calle VARCHAR(150) NOT NULL,
    numero VARCHAR(6) NOT NULL,
    codigo_postal CHAR(5) NOT NULL,
    telefono VARCHAR(10) NOT NULL,
    email VARCHAR(200) NOT NULL,
    logo VARCHAR(250) NOT NUll,
    FOREIGN KEY (codigo_postal) REFERENCES codigos_postales(codigo_postal)
        ON DELETE RESTRICT ON UPDATE CASCADE
);

-- Tabla catálogo de redes sociales
CREATE TABLE `redes_sociales` (
    red_id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nombre VARCHAR(150) NOT NULL
);

INSERT INTO redes_sociales(nombre) VALUES
("Web");

-- Tabla pivote con las url de las redes con las que cuenta el negocio
CREATE TABLE `redes_negocio` (
    rfc VARCHAR(13) NOT NULL,
    red_id INT AUTO_INCREMENT NOT NULL,
    url VARCHAR(200) NOT NULL,
    PRIMARY KEY (rfc, red_id),
    FOREIGN KEY (rfc) REFERENCES negocio(rfc),
    FOREIGN KEY (red_id) REFERENCES redes_sociales(red_id)
        ON DELETE RESTRICT ON UPDATE CASCADE
);

-------------------------------------------------------------------------
-- Creación de usuarios y privilegios con DCL
-- Usuario para el login
CREATE USER 'lecturaUsuarios'@'localhost' IDENTIFIED BY 'PassWord_1';
GRANT SELECT ON tienda.usuarios TO 'lecturaUsuarios'@'localhost';
GRANT SELECT ON tienda.tipos_usuario TO 'lecturaUsuarios'@'localhost';
-- Usuario Administrador
CREATE USER 'administradorGloboKids'@'localhost' IDENTIFIED BY "Administrador_PassWord_1";
GRANT SELECT, INSERT, UPDATE ON *.* TO 'administradorGloboKids'@'localhost';
-- Usuario Empleado
CREATE USER 'empleadoGloboKids'@'localhost' IDENTIFIED BY "Empleado_PassWord_1";
GRANT SELECT ON *.* TO 'empleadoGloboKids'@'localhost';
GRANT INSERT, UPDATE ON tienda.categorias_inventario TO 'empleadoGloboKids'@'localhost';
GRANT INSERT, UPDATE ON tienda.inventario TO 'empleadoGloboKids'@'localhost';
GRANT INSERT, UPDATE ON tienda.perecederos_inventario TO 'empleadoGloboKids'@'localhost';
GRANT INSERT, UPDATE ON tienda.contactos TO 'empleadoGloboKids'@'localhost';
GRANT INSERT, UPDATE ON tienda.operaciones TO 'empleadoGloboKids'@'localhost';
GRANT INSERT, UPDATE ON tienda.productos_incluidos TO 'empleadoGloboKids'@'localhost';
GRANT INSERT, UPDATE ON tienda.abonos TO 'empleadoGloboKids'@'localhost';

FLUSH PRIVILEGES;