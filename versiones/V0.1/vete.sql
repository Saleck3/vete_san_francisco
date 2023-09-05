CREATE
DATABASE `vete` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

CREATE TABLE vete.roles (
	id INT UNSIGNED auto_increment NOT NULL,
	nombre varchar(50) NOT NULL,
	CONSTRAINT roles_PK PRIMARY KEY (id)
)
INSERT INTO vete.roles (id, nombre) VALUES(1, 'admin'),(2, 'usuario');


CREATE TABLE vete.usuarios (
	id INT UNSIGNED auto_increment NOT NULL,
	nombre varchar(50) NOT NULL,
	mail varchar(50) NOT NULL,
	password varchar(255) NOT NULL,
	matricula varchar(50) NULL,
	rol_id INT NOT NULL,
	CONSTRAINT usuarios_PK PRIMARY KEY (id),
	CONSTRAINT usuarios_FK FOREIGN KEY (rol_id) REFERENCES vete.roles(id)
)

CREATE TABLE vete.duegnos (
	id INT UNSIGNED auto_increment NOT NULL,
	nombre varchar(50) NOT NULL,
	numero_tel varchar(20) NULL,
	mail varchar(50) NULL,
	direccion varchar(100) NULL,
	CONSTRAINT duegnos_PK PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

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

CREATE TABLE `especies` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) COLLATE utf8mb4_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

CREATE TABLE `razas` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) COLLATE utf8mb4_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;


-- vete.mascotas definition

CREATE TABLE `mascotas` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) COLLATE utf8mb4_spanish_ci NOT NULL,
  `raza_id` int unsigned NOT NULL DEFAULT '1',
  `especie_id` int unsigned NOT NULL DEFAULT '1',
  `color` varchar(20) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `peso` smallint unsigned DEFAULT NULL,
  `fnac` date DEFAULT NULL,
  `muerto` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mascotas_FK` (`raza_id`),
  KEY `mascotas_FK_1` (`especie_id`),
  CONSTRAINT `mascotas_FK` FOREIGN KEY (`raza_id`) REFERENCES `razas` (`id`),
  CONSTRAINT `mascotas_FK_1` FOREIGN KEY (`especie_id`) REFERENCES `especies` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;
