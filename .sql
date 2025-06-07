create database login;

create table usuarios(
    id_usuario serial primary key,
    primer_nombre varchar(100) not null,
    segundo_nombre varchar(100),
    primer_apellido varchar(100) not null,
    segundo_apellido varchar(100),
    telefono varchar(15),
    direccion varchar(100),
    dpi varchar(100),
    correo varchar(100),
    contrasena lvarchar(1056),
    token lvarchar(1056),
    fecha_creacion date default today,
    fecha_contrasena date default today,
    fotografia lvarchar(2056),
    situacion smallint default 1
);



create table aplicaciones(
    id_aplicacion serial primary key,
    nombre_app_lg varchar(100),
    nombre_app_md varchar(100),
    nombre_app_ct varchar(100),
    fecha_creacion date default today,
    situacion smallint default 1
);

create table permisos(
    id_permiso serial primary key,
    id_aplicacion integer not null,
    nombre_permiso varchar(100),
    clave_permiso varchar(100),
    descripcion varchar(250),
    fecha date default today,
    situacion smallint default 1,
    foreign key (id_aplicacion) references aplicaciones(id_aplicacion)
);

create table asig_permisos(
    id_asig_permisos serial primary key,
    id_usuario integer not null,
    id_aplicacion integer not null,
    id_permiso integer not null,
    fecha date default today,
    usuario_asigno varchar(100),
    motivo varchar(200),
    situacion smallint default 1,
    foreign key (id_usuario) references usuarios(id_usuario),
    foreign key (id_aplicacion) references aplicaciones(id_aplicacion),
    foreign key (id_permiso) references permisos(id_permiso)
);

create table rutas(
    id_ruta serial primary key,
    id_aplicacion integer not null,
    ruta lvarchar(1050),
    descripcion varchar(250),
    situacion smallint default 1,
    foreign key (id_aplicacion) references aplicaciones(id_aplicacion)
);

create table historial_actividades(
    id_hist_actividad serial primary key,
    id_usuario integer not null,
    fecha datetime year to minute,
    id_ruta integer not null,
    ejecucion varchar(250),
    situacion smallint default 1,
    foreign key (id_usuario) references usuarios(id_usuario),
    foreign key (id_ruta) references rutas(id_ruta)
);