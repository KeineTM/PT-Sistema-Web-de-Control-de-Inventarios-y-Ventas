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
    estado BOOLEAN DEFAULT 1,
    caducidad BOOLEAN DEFAULT 0,
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
-- Tabla de tipos de contacto
CREATE TABLE `tipos_contacto` (
    tipo_id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    tipo_contacto VARCHAR(50) NOT NULL
)

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
    tipo_contacto int NOT NULL,
    FULLTEXT KEY busqueda(nombre, apellido_paterno, apellido_materno, tipo_contacto),
    FOREIGN KEY (tipo_contacto) REFERENCES tipos_contacto(tipo_id)
        ON DELETE RESTRICT ON UPDATE CASCADE
);

--------------------------------------------------------------------------------------------
-- Tabla catálogo de tipos de transaccion (venta, apartado, devolución)
CREATE TABLE `tipos_operacion` (
    tipo_id CHAR(2) PRIMARY KEY NOT NULL,
    operacion VARCHAR(50) NOT NULL,
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
    tipos_operacion CHAR(2) NOT NULL,
    estado BOOLEAN DEFAULT 1,
    cliente_id VARCHAR(6)
    FOREIGN KEY (tipos_operacion) REFERENCES tipos_operacion(tipo_id),
    FOREIGN KEY (cliente_id) REFERENCES contactos(contacto_id)
        ON DELETE RESTRICT ON UPDATE CASCADE
);

-- Tabla pivote de productos incluídos en las operaciones
CREATE TABLE `productos_incluidos` (
    operacion_id VARCHAR(10) PRIMARY KEY NOT NULL,
    producto_id VARCHAR(20) PRIMARY KEY NOT NULL,
    unidades INT NOT NULL,
    FOREIGN KEY (operacion_id) REFERENCES operaciones(operacion_id),
    FOREIGN KEY (producto_id) REFERENCES inventario(producto_id)
        ON DELETE RESTRICT ON UPDATE CASCADE
);

-- Tabla catálogo de métodos de pago
CREATE TABLE `metodos_pago` (
    metodo_id SMALLINT(2) PRIMARY KEY AUTO_INCREMENT NOT NULL,
    metodo VARCHAR(50) NOT NULL
)

INSERT INTO metodos_pago(metodo) VALUES 
("Efectivo"),
("Transferencia"),
("Tarjeta de débito"),
("Tarjeta de crédito");

-- Tabla pivote de abonos a las operaciones realizados por los empleados
CREATE TABLE `abonos` (
    operacion_id VARCHAR(10) PRIMARY KEY NOT NULL,
    empleado_id VARCHAR(6) PRIMARY KEY NOT NULL,
    fecha TIMESTAMP NOT NULL,
    abono DECIMAL(8,2) NOT NULL,
    metodo_pago SMALLINT(2) DEFAULT 1
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
    rfc VARCHAR(13) PRIMARY KEY NOT NULL,
    red_id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    url VARCHAR(200) NOT NULL,
    FOREIGN KEY (rfc) REFERENCES negocio(rfc),
    FOREIGN KEY (red_id) REFERENCES redes_sociales(red_id)
        ON DELETE RESTRICT ON UPDATE CASCADE
);
