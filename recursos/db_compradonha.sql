Drop database if exists  db_compradonha;

Create database db_compradonha;

Use db_compradonha;

CREATE TABLE Comunidades(
codComunidad int NOT NULL,
nombre varchar(100) NOT NULL,
PRIMARY KEY (codComunidad)
) ENGINE=InnoDB;

CREATE TABLE Provincia(
codProvincia int NOT NULL,
codComunidad int NOT NULL,
nombre varchar(90) NOT NULL,
PRIMARY KEY (codProvincia),
foreign key (codComunidad) references Comunidades(codComunidad) on delete restrict
) engine = InnoDB;

CREATE TABLE Roles(  
id int NOT NULL,     
tipo varchar(9) NOT NULL,     
primary key Roles(id) 
) engine = InnoDB;

CREATE table Usuarios(  
id int auto_increment NOT NULL,     
nombre varchar(20) NOT NULL,     
apellidos varchar(30) NOT NUll,    
DNI char(9) NOT NULL,  
telefono int(9) NOT NULL,
calle varchar(50) NOT NULL,
numero varchar(5) NOT NULL,     
piso char(2),     
puerta char(2), 
cp int(5) NOT NULL,
poblacion varchar(44) NOT NULL,
codProvincia int NOT NULL,
codComunidad int NOT NULL,
mail varchar(40) NOT NULL,     
id_rol int NOT NULL,     
primary key Usuarios(id),     
foreign key (id_rol) references Roles(id) on delete restrict,
foreign key (codProvincia) references Provincia(codProvincia) on delete restrict,
foreign key (codComunidad) references Comunidades(codComunidad) on delete restrict
) engine = InnoDB;

CREATE TABLE Sesiones(  
id_usuario int,     
username varchar(20) NOT NULL,     
passwd varchar(64) NOT NULL,     
fecha datetime NOT NULL,
verificado bool NOT NULL,
codVerifica varchar(64),
foreign key (id_usuario) references Usuarios(id) on delete cascade 
) engine = InnoDB;

CREATE TABLE Tipos_Producto(  
id int NOT NULL,    
tipo varchar(30) NOT NULL,     
primary key Tipos_Producto(id) 
) engine = InnoDB;

CREATE TABLE Productos(
id int auto_increment NOT NULL,     
nombre varchar(20) NOT NULL,     
descripcion varchar(500),
fecha datetime NOT NULL,     
precio double NOT NULL,  
inventario int NOT NULL,  
imagen LONGBLOB NOT NULL,
id_tipo int NOT NULL,     
primary key Productos(id),     
foreign key (id_tipo) references Tipos_Producto(id) on delete restrict,
constraint CK_Inventario_Positivos CHECK (inventario >= 0)
) engine = InnoDB;

CREATE TABLE Compras(  
id int NOT NULL,
id_usuario int NOT NULL,     
id_producto int NOT NULL,      
fecha datetime NOT NULL,
cantidad int NOT NULL,     
foreign key (id_usuario) references Usuarios(id) on delete cascade,     
foreign key (id_producto) references Productos(id) on delete cascade 
) engine = InnoDB;

/*CREATE TABLE Cupones(  
id int NOT NULL,     
nombre varchar(30) NOT NULL,     
 El descuento es un porcentaje   
descuento double NOT NULL,     
tipo_producto int NOT NULL,     
primary key Cupones(id) 
) engine = InnoDB;*/

/*CREATE TABLE Usuario_Cupon(  
id_usuario int NOT NULL,     
id_cupon int NOT NULL,     
cantidad int NOT NULL,  
foreign key (id_usuario) references Usuarios(id) on delete cascade,     
foreign key (id_cupon) references Cupones(id) on delete cascade 
) engine = InnoDB;*/

CREATE TABLE Carro(
id_usuario int NOT NULL,
id_producto int NOT NULL,
cantidad int NOT NULL,
foreign key (id_usuario) references Usuarios(id) on delete cascade,
foreign key (id_producto) references Productos(id) on delete cascade
) engine = InnoDB;

/* Inserción de valores */
INSERT INTO Roles values
(1, "Cliente"),
(2, "Empleado");

INSERT INTO Tipos_Producto values
(1, "Alcohol"),
(2, "Carne"),
(3, "Fruta"),
(4, "Kebab"),
(5, "Leche"),
(6, "Monster"),
(7, "Móviles"),
(8, "Papel higiénico"),
(9, "Pescado"),
(10, "Verduras"),
(11, "Panadería"),
(12, "Yogures"),
(13, "Salsas");

/*INSERT INTO Cupones values
(1, "Descuento en Alcohol", 20, 1),
(2, "Descuento en Carne", 10, 2),
(3, "Descuento en Fruta", 15, 3),
(4, "Descuento en Kebab", 50, 4),
(5, "Descuento en Leche", 10, 5),
(6, "Descuento en Monster", 2, 6),
(7, "Descuento en Móviles", 30, 7),
(8, "Descuento en Papel higiénico", 90, 8),
(9, "Descuento en Pescado", 20, 9),
(10, "Descuento en Verduras", 13, 10);*/

INSERT INTO Comunidades(codComunidad, nombre)
VALUES
	(1,'Andalucía'),
	(2,'Aragón'),
	(3,'Asturias, Principado de'),
	(4,'Balears, Illes'),
	(5,'Canarias'),
	(6,'Cantabria'),
	(7,'Castilla y León'),
	(8,'Castilla - La Mancha'),
	(9,'Catalunya'),
	(10,'Comunitat Valenciana'),
	(11,'Extremadura'),
	(12,'Galicia'),
	(13,'Madrid, Comunidad de'),
	(14,'Murcia, Región de'),
	(15,'Navarra, Comunidad Foral de'),
	(16,'País Vasco'),
	(17,'Rioja, La'),
	(18,'Ceuta'),
	(19,'Melilla')
;

INSERT INTO Provincia(codProvincia, codComunidad, nombre)
VALUES
	(2, 8, 'Albacete'),
	(3, 10, 'Alicante/Alacant'),
	(4, 1, 'Almería'),
	(1, 16, 'Araba/Álava'),
	(33, 3, 'Asturias'),
	(5, 7, 'Ávila'),
	(6, 11, 'Badajoz'),
	(7, 4, 'Balears, Illes'),
	(8, 9, 'Barcelona'),
	(48, 16, 'Bizkaia'),
	(9, 7, 'Burgos'),
	(10, 11, 'Cáceres'),
	(11, 1, 'Cádiz'),
	(39, 6, 'Cantabria'),
	(12, 10, 'Castellón/Castelló'),
	(51, 18, 'Ceuta'),
	(13, 8, 'Ciudad Real'),
	(14, 1, 'Córdoba'),
	(15, 12, 'Coruña, A'),
	(16, 8, 'Cuenca'),
	(20, 16, 'Gipuzkoa'),
	(17, 9, 'Girona'),
	(18, 1, 'Granada'),
	(19, 8, 'Guadalajara'),
	(21, 1, 'Huelva'),
	(22, 2, 'Huesca'),
	(23, 1, 'Jaén'),
	(24, 7, 'León'),
	(27, 12, 'Lugo'),
	(25, 9, 'Lleida'),
	(28, 13, 'Madrid'),
	(29, 1, 'Málaga'),
	(52, 19, 'Melilla'),
	(30, 14, 'Murcia'),
	(31, 15, 'Navarra'),
	(32, 12, 'Ourense'),
	(34, 7, 'Palencia'),
	(35, 5, 'Palmas, Las'),
	(36, 12, 'Pontevedra'),
	(26, 17, 'Rioja, La'),
	(37, 7,  'Salamanca'),
	(38, 5, 'Santa Cruz de Tenerife'),
	(40, 7, 'Segovia'),
	(41, 1, 'Sevilla'),
	(42, 7, 'Soria'),
	(43, 9, 'Tarragona'),
	(44, 2, 'Teruel'),
	(45, 8, 'Toledo'),
	(46, 10, 'Valencia/València'),
	(47, 7, 'Valladolid'),
	(49, 7, 'Zamora'),
	(50, 2, 'Zaragoza')
;


/*INSERT INTO Productos values
(1, "Pataca", time(now()), 0.9, 10);*/

/* Inserts de prueba */
/*INSERT INTO usuarios values (0,"Pablo", "Pérez Pereira", "34288351C", "Rúa Illas Cíes", "46", "2", "E", "perperpab@outlook.com", "2");*/
/*INSERT INTO sesiones values ((SELECT max(id) FROM usuarios), "Rusito", "abc123.", time(now()));*/
/*INSERT INTO usuario_cupon values ((SELECT id_usuario FROM sesiones WHERE username = "Rusito"), 3, 5);*/
/*INSERT INTO usuario_producto values ((SELECT id_usuario FROM sesiones WHERE username = "Rusito"), 1, 5, time(now()));*/

