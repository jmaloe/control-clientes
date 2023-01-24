/*Autor: Jesus Malo Escobar
 email: dic.malo@gmail.com
 date: 01-10-2020
 Cel. 962 212 4109
*/

CREATE DATABASE dbcontrolclientes CHARACTER SET utf8 COLLATE utf8_general_ci;
USE dbcontrolclientes;

/*CREACION DE TABLAS PARA EL ACCESO A USUARIOS*/
CREATE TABLE usuarios(
	idUser SMALLINT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	user varchar(25) NOT NULL,
	password TINYTEXT NOT NULL,
	email TINYTEXT NOT NULL,
	nombre TINYTEXT NOT NULL,	
	vigente TINYINT(1) DEFAULT 1,
	creado_por SMALLINT UNSIGNED,
	fechaCaptura TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)ENGINE=INNODB;

insert into usuarios(user,password,email,nombre,vigente) values('jesus.malo','c9acd907f21fa81033a64809fd73e991','dic.malo@gmail.com','Jesús Malo Escobar',1);

CREATE TABLE roles(
	idRol SMALLINT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	nombreRol VARCHAR(25) NOT NULL
)ENGINE=INNODB;

insert into roles(nombreRol) values('Superusuario'),('Administrador'),('Gestor');

CREATE TABLE recursos(
	idRecurso SMALLINT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	nombreRecurso TINYTEXT NOT NULL	
)ENGINE=INNODB;

insert into recursos(nombreRecurso) values('FRegistro'),('FAdministracion'),('FAdminPermisos'),('FAdminRoles'),('FAdminUsuarios'),('FAdminRecursos'),('FReportes');

CREATE TABLE permisos_rol(
	idPermiso SMALLINT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	idRol SMALLINT UNSIGNED NOT NULL,
	idRecurso SMALLINT UNSIGNED NOT NULL,
	lectura TINYINT(1) NOT NULL,
	escritura TINYINT(1) NOT NULL,
	actualizacion TINYINT(1) NOT NULL,
	eliminacion TINYINT(1) NOT NULL,
	FOREIGN KEY (idRol) REFERENCES roles(idRol) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY (idRecurso) REFERENCES recursos(idRecurso) ON UPDATE CASCADE ON DELETE CASCADE
)ENGINE=INNODB;

insert into permisos_rol(idRol,idRecurso,lectura,escritura,actualizacion,eliminacion) values(1,1,1,1,1,1),(1,2,1,1,1,1),(1,3,1,1,1,1),(1,4,1,1,1,1),(1,5,1,1,1,1),(1,6,1,1,1,1),(1,7,1,1,1,1);

CREATE TABLE rol_del_usuario(
	cns_rol_usuario SMALLINT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	idRol SMALLINT UNSIGNED,
	idUser SMALLINT UNSIGNED,
	FOREIGN KEY (idRol) REFERENCES roles(idRol) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY (idUser) REFERENCES usuarios(idUser) ON UPDATE CASCADE ON DELETE CASCADE
)ENGINE=INNODB;

insert into rol_del_usuario(idRol,idUser) values(1,1);

CREATE TABLE Clientes(
	id_cliente SMALLINT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	nombre varchar(25) NOT NULL,
	apellido_paterno varchar(25) NOT NULL,
	apellido_materno varchar(25) NOT NULL,
	direccion TINYTEXT,
	telefono char(13),
	fecha_alta TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	fecha_baja TIMESTAMP DEFAULT NULL,
	dia_de_pago TINYINT,
	saldo DECIMAL(6,2),
	activo BIT(1) default 1
);

CREATE TABLE Productos(
	id_producto SMALLINT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	nombre TINYTEXT,
	descripcion TINYTEXT,
	precio_compra DECIMAL(6,2),
	precio_venta DECIMAL(6,2)
)ENGINE=INNODB;

CREATE TABLE Contratos(
	num_contrato INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	id_cliente SMALLINT UNSIGNED,
	fecha_inicio date NOT NULL,
	fecha_termino TIMESTAMP DEFAULT NULL,
	descripcion TINYTEXT,
	total DECIMAL(6,2),
	vigente TINYINT DEFAULT 1,
	FOREIGN KEY(id_cliente) REFERENCES Clientes(id_cliente) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE DetalleContrato(
	cnsdetcon INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	num_contrato INTEGER UNSIGNED,
	id_producto SMALLINT UNSIGNED,
	cantidad DECIMAL(6,2),
	precio DECIMAL(6,2),
	total DECIMAL(6,2),
	FOREIGN KEY(num_contrato) REFERENCES Contratos(num_contrato) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY(id_producto) REFERENCES Productos(id_producto) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE Pagos(
	no_pago INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	num_contrato INTEGER UNSIGNED,
	fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	monto DECIMAL(6,2),
	idUser SMALLINT UNSIGNED,	
	FOREIGN KEY(idUser) REFERENCES usuarios(idUser) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY(num_contrato) REFERENCES Contratos(num_contrato) ON UPDATE CASCADE ON DELETE CASCADE
)ENGINE=INNODB;

CREATE TABLE DetalleDePago(
	cnsdetpago INTEGER UNSIGNED PRIMARY KEY AUTO_INCREMENT,
	no_pago INTEGER UNSIGNED,
	mes_liq NUMERIC(2) UNSIGNED,
	anio_liq NUMERIC(4) UNSIGNED,	
	FOREIGN KEY(no_pago) REFERENCES Pagos(no_pago) ON UPDATE CASCADE ON DELETE CASCADE	
)ENGINE=INNODB;