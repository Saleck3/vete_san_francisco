CREATE
DATABASE `vete` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

CREATE TABLE vete.roles (
	id int auto_increment NOT NULL,
	nombre varchar(50) NOT NULL,
	CONSTRAINT roles_PK PRIMARY KEY (id)
)
INSERT INTO vete.roles (id, nombre) VALUES(1, 'admin'),(2, 'usuario');


CREATE TABLE vete.usuarios (
	id int auto_increment NOT NULL,
	nombre varchar(50) NOT NULL,
	mail varchar(50) NOT NULL,
	password varchar(255) NOT NULL,
	matricula varchar(50) NULL,
	rol_id INT NOT NULL,
	CONSTRAINT usuarios_PK PRIMARY KEY (id),
	CONSTRAINT usuarios_FK FOREIGN KEY (rol_id) REFERENCES vete.roles(id)
)


CREATE TABLE vete.medicamentos (
	id INT UNSIGNED auto_increment NOT NULL,
	nombre varchar(50) NULL,
	presentacion varchar(50) NULL,
	CONSTRAINT medicamentos_PK PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

ALTER TABLE vete.usuarios ADD imagen_perfil INT UNSIGNED NULL;
ALTER TABLE vete.usuarios ADD CONSTRAINT usuarios_FK_1 FOREIGN KEY (imagen_perfil) REFERENCES vete.archivos(id);

CREATE TABLE vete.archivos (
	id INT UNSIGNED auto_increment NOT NULL,
	nombre varchar(250) NULL,
	path_absoluto varchar(250) NULL,
	path_relativo varchar(250) NULL,
	extension varchar(10) NULL,
	CONSTRAINT archivos_PK PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;