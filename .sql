create database proyecto_celulares


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
