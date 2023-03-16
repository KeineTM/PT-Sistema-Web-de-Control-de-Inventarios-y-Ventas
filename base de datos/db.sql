-- Script de creación de BD, sus tablas y sus relaciones:
CREATE DATABASE `tienda`
    DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--------------------------------------------------------------------------------------------
-- Tabla de tipos de usuario
CREATE TABLE `tipos_usuario` (
    tipo_id SMALLINT(2) PRIMARY KEY AUTO_INCREMENT NOT NULL,
    tipo_usuario VARCHAR(50) NOT NULL
);

-- Tabla de usuarios
CREATE TABLE `usuarios` (
    usuario_id VARCHAR(6) PRIMARY KEY NOT NULL,
    nombre VARCHAR(80) NOT NULL,
    apellido_paterno VARCHAR(80) NOT NULL,
    apellido_materno VARCHAR(80) NOT NULL,
    telefono VARCHAR(10) NOT NULL,
    rfc VARCHAR(13) NOT NULL UNIQUE,
    email VARCHAR(150) NOT NULL,
    password VARCHAR(200) NOT NULL,
    notas VARCHAR(250),
    estado BOOLEAN NOT NULL,
    tipo_usuario SMALLINT(2) NOT NULL,
    FOREIGN KEY (tipo_usuario) REFERENCES tipos_usuario(tipo_id)
);

INSERT INTO usuarios VALUES (
    'ABCDXX', 'Jessica', 'Trejo', 'Méndez', '9876543210', 'ABCDXXXXXXXXX', 
    'jessica.trejome@globokids.com', '$2y$05$Gg1zv/EBXLsPAo63u8J/3ewycyBL8MhyneJHcs1GmixBXMqiM0mMS', 'Usuario de prueba con privilegios de empleado.',
    1, 2);
    
SELECT usuarios.usuario_id, usuarios.password, tipos_usuario.tipo_usuario, 
CONCAT(usuarios.nombre,' ',usuarios.apellido_paterno,' ',usuarios.apellido_materno) AS nombre_completo 
FROM usuarios 
INNER JOIN tipos_usuario ON usuarios.tipo_usuario = tipos_usuario.tipo_id
WHERE usuarios.usuario_id = 'ABCD00' LIMIT 1;

--------------------------------------------------------------------------------------------
-- Tabla de categorías del inventario
CREATE TABLE `categorias` (
    categoria_id SMALLINT(3) PRIMARY KEY AUTO_INCREMENT NOT NULL,
    categoria VARCHAR(100) NOT NULL
);

-- Tabla de inventario de productos
CREATE TABLE `inventario` (
    producto_id VARCHAR(20) NOT NULL,
    nombre VARCHAR(80) NOT NULL,
    categoria_id SMALLINT(3) NOT NULL,
    descripcion TEXT(500),
    unidades INT NOT NULL,
    minimo INT,
    precio_compra DECIMAL(8,2),
    precio_venta DECIMAL(8,2) NOT NULL,
    precio_mayoreo DECIMAL(8,2),
    estado BOOLEAN NOT NULL,
    FOREIGN KEY (categoria_id) REFERENCES categorias(categoria_id) 
);

-- Tabla auxiliar para caducidades de productos que lo necesitan
CREATE TABLE `productos_perecederos` (
    producto_id VARCHAR(20) PRIMARY KEY NOT NULL,
    caducidad DATE NOT NULL
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

