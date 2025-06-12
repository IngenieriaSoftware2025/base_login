create database proyecto_celulares


CREATE TABLE permisos(
    id_permiso SERIAL PRIMARY KEY,
    nombre_permiso VARCHAR(100) NOT NULL,
    descripcion VARCHAR(250),
    fecha_creacion DATE DEFAULT TODAY,
    situacion SMALLINT DEFAULT 1
);
INSERT INTO permisos (nombre_permiso, descripcion) VALUES 
('Administrar Usuarios', 'Puede crear, editar y eliminar usuarios');
INSERT INTO permisos (nombre_permiso, descripcion) VALUES 
('Ver Inventario', 'Puede consultar el inventario de celulares');
INSERT INTO permisos (nombre_permiso, descripcion) VALUES 
('Realizar Ventas', 'Puede hacer ventas de celulares');
INSERT INTO permisos (nombre_permiso, descripcion) VALUES 
('Hacer Reparaciones', 'Puede recibir y gestionar reparaciones');
INSERT INTO permisos (nombre_permiso, descripcion) VALUES 
('Ver Reportes', 'Puede ver estadísticas y reportes del negocio');




CREATE TABLE roles(
    id_rol SERIAL PRIMARY KEY,
    nombre_rol VARCHAR(100) NOT NULL,
    nombre_corto VARCHAR(25) NOT NULL,
    descripcion VARCHAR(250),
    fecha_creacion DATE DEFAULT TODAY,
    situacion SMALLINT DEFAULT 1
);
INSERT INTO roles (nombre_rol, nombre_corto, descripcion) VALUES 
('Administrador', 'ADMIN', 'Acceso completo al sistema');
INSERT INTO roles (nombre_rol, nombre_corto, descripcion) VALUES 
('Técnico', 'TECNICO', 'Puede manejar reparaciones e inventario');
INSERT INTO roles (nombre_rol, nombre_corto, descripcion) VALUES 
('Vendedor', 'VENDEDOR', 'Puede realizar ventas');
INSERT INTO roles (nombre_rol, nombre_corto, descripcion) VALUES 
('Usuario', 'USER', 'Acceso básico');




CREATE TABLE usuarios(
    id_usuario SERIAL PRIMARY KEY,
    primer_nombre VARCHAR(100) NOT NULL,
    segundo_nombre VARCHAR(100),
    primer_apellido VARCHAR(100) NOT NULL,
    segundo_apellido VARCHAR(100),
    telefono VARCHAR(15),
    direccion VARCHAR(100),
    dpi VARCHAR(100),
    correo VARCHAR(100),
    contrasena LVARCHAR(1056),
    token LVARCHAR(1056),
    fecha_creacion DATE DEFAULT TODAY,
    fecha_contrasena DATE DEFAULT TODAY,
    fotografia LVARCHAR(2056),
    id_rol INTEGER,
    situacion SMALLINT DEFAULT 1,
    FOREIGN KEY (id_rol) REFERENCES roles(id_rol)
);



CREATE TABLE marcas(
    id_marca SERIAL PRIMARY KEY,
    nombre_marca VARCHAR(100) NOT NULL UNIQUE,
    descripcion VARCHAR(250),
    fecha_creacion DATE DEFAULT TODAY,
    situacion SMALLINT DEFAULT 1
);


CREATE TABLE modelos(
    id_modelo SERIAL PRIMARY KEY,
    id_marca INTEGER NOT NULL,
    nombre_modelo VARCHAR(100) NOT NULL,
    color VARCHAR(50),
    fecha_creacion DATE DEFAULT TODAY,
    situacion SMALLINT DEFAULT 1,
    FOREIGN KEY (id_marca) REFERENCES marcas(id_marca)
);

CREATE TABLE clientes(
    id_cliente SERIAL PRIMARY KEY,
    primer_nombre VARCHAR(100) NOT NULL,
    segundo_nombre VARCHAR(100),
    primer_apellido VARCHAR(100) NOT NULL,
    segundo_apellido VARCHAR(100),
    telefono VARCHAR(15),
    dpi VARCHAR(20),
    correo VARCHAR(100),
    direccion VARCHAR(200),
    fecha_registro DATE DEFAULT TODAY,
    situacion SMALLINT DEFAULT 1
);

CREATE TABLE inventario(
    id_inventario SERIAL PRIMARY KEY,
    id_modelo INTEGER NOT NULL,
    imei VARCHAR(20) UNIQUE,
    estado_celular VARCHAR(20) DEFAULT 'nuevo' CHECK (estado_celular IN ('nuevo', 'usado', 'dañado')),
    precio_compra DECIMAL(10,2),
    precio_venta DECIMAL(10,2),
    fecha_ingreso DATE DEFAULT TODAY,
    estado_inventario VARCHAR(20) DEFAULT 'disponible' CHECK (estado_inventario IN ('disponible', 'vendido', 'en_reparacion')),
    situacion SMALLINT DEFAULT 1,
    FOREIGN KEY (id_modelo) REFERENCES modelos(id_modelo)
);



CREATE TABLE reparaciones(
    id_reparacion SERIAL PRIMARY KEY,
    id_cliente INTEGER NOT NULL,
    id_usuario_recibe INTEGER NOT NULL,
    id_usuario_asignado INTEGER,
    numero_orden VARCHAR(50) UNIQUE,
    tipo_celular VARCHAR(100),
    marca_celular VARCHAR(100),
    imei VARCHAR(20),
    motivo_ingreso LVARCHAR(1000),
    diagnostico LVARCHAR(1000),
    fecha_ingreso DATE DEFAULT TODAY,
    fecha_asignacion DATE,
    fecha_entrega_real DATE,
    tipo_servicio VARCHAR(50),
    estado_reparacion VARCHAR(20) DEFAULT 'recibido' CHECK (estado_reparacion IN ('recibido', 'en_proceso', 'terminado', 'entregado', 'cancelado')),
    costo_total DECIMAL(10,2) DEFAULT 0,
    situacion SMALLINT DEFAULT 1,
    FOREIGN KEY (id_cliente) REFERENCES clientes(id_cliente),
    FOREIGN KEY (id_usuario_recibe) REFERENCES usuarios(id_usuario),
    FOREIGN KEY (id_usuario_asignado) REFERENCES usuarios(id_usuario)
);












