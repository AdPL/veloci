SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `mydb` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `mydb` ;

-- -----------------------------------------------------
-- Table `mydb`.`piloto`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`piloto` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(45) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `email` VARCHAR(45) NOT NULL,
  `escuderia` VARCHAR(45) NOT NULL,
  `nombre_completo` VARCHAR(60) NOT NULL,
  `num_sanciones` INT UNSIGNED NOT NULL,
  `num_victorias` INT UNSIGNED NOT NULL,
  `rol` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `name_UNIQUE` (`nombre` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`categoria`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`categoria` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(45) NOT NULL,
  `imagen` VARCHAR(50) NULL,
  `plazas` INT UNSIGNED NOT NULL,
  `precio_inscripcion` INT UNSIGNED NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `nombre_UNIQUE` (`nombre` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`circuito`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`circuito` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(45) NOT NULL,
  `pais` VARCHAR(45) NOT NULL,
  `distancia` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`calendario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`calendario` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(45) NOT NULL,
  `categoria_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_calendario_categoria1_idx` (`categoria_id` ASC),
  CONSTRAINT `fk_calendario_categoria1`
    FOREIGN KEY (`categoria_id`)
    REFERENCES `mydb`.`categoria` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`carrera`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`carrera` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(45) NOT NULL,
  `neumatico1` ENUM('Super blandos', 'Blandos', 'Medios', 'Duros') NOT NULL,
  `neumatico2` ENUM('Super blandos', 'Blandos', 'Medios', 'Duros') NOT NULL,
  `vueltas` VARCHAR(45) NOT NULL,
  `fecha` DATE NOT NULL,
  `hora` TIME NOT NULL,
  `categoria_id` INT UNSIGNED NOT NULL,
  `circuito_id` INT UNSIGNED NOT NULL,
  `calendario_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_carrera_categoria1_idx` (`categoria_id` ASC),
  INDEX `fk_carrera_circuito1_idx` (`circuito_id` ASC),
  INDEX `fk_carrera_calendario1_idx` (`calendario_id` ASC),
  CONSTRAINT `fk_carrera_categoria1`
    FOREIGN KEY (`categoria_id`)
    REFERENCES `mydb`.`categoria` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_carrera_circuito1`
    FOREIGN KEY (`circuito_id`)
    REFERENCES `mydb`.`circuito` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_carrera_calendario1`
    FOREIGN KEY (`calendario_id`)
    REFERENCES `mydb`.`calendario` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`incidente`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`incidente` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `vuelta` INT NOT NULL,
  `minuto` TIME NOT NULL,
  `piloto_id` INT UNSIGNED NOT NULL,
  `carrera_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_incidente_piloto1_idx` (`piloto_id` ASC),
  INDEX `fk_incidente_carrera1_idx` (`carrera_id` ASC),
  CONSTRAINT `fk_incidente_piloto1`
    FOREIGN KEY (`piloto_id`)
    REFERENCES `mydb`.`piloto` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_incidente_carrera1`
    FOREIGN KEY (`carrera_id`)
    REFERENCES `mydb`.`carrera` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`piloto_categoria`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`piloto_categoria` (
  `categoria_id` INT UNSIGNED NOT NULL,
  `piloto_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`categoria_id`, `piloto_id`),
  INDEX `fk_pilo_cat_categoria1_idx` (`categoria_id` ASC),
  INDEX `fk_pilo_cat_piloto1_idx` (`piloto_id` ASC),
  CONSTRAINT `fk_pilo_cat_categoria1`
    FOREIGN KEY (`categoria_id`)
    REFERENCES `mydb`.`categoria` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_pilo_cat_piloto1`
    FOREIGN KEY (`piloto_id`)
    REFERENCES `mydb`.`piloto` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`reclamacion`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`reclamacion` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `titulo` VARCHAR(45) NULL,
  `comentario` TEXT NOT NULL,
  `incidente_id` INT UNSIGNED NOT NULL,
  `piloto_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_reclamacion_piloto1_idx` (`piloto_id` ASC),
  INDEX `fk_reclamacion_incidente1_idx` (`incidente_id` ASC),
  CONSTRAINT `fk_reclamacion_incidente1`
    FOREIGN KEY (`incidente_id`)
    REFERENCES `mydb`.`incidente` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_reclamacion_piloto1`
    FOREIGN KEY (`piloto_id`)
    REFERENCES `mydb`.`piloto` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`recurso`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`recurso` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `descripcion` VARCHAR(45) NOT NULL,
  `imagen` VARCHAR(60) NOT NULL,
  `reclamacion_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_recurso_reclamacion1_idx` (`reclamacion_id` ASC),
  CONSTRAINT `fk_recurso_reclamacion1`
    FOREIGN KEY (`reclamacion_id`)
    REFERENCES `mydb`.`reclamacion` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`piloto_carrera`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`piloto_carrera` (
  `piloto_id` INT UNSIGNED NOT NULL,
  `carrera_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`piloto_id`, `carrera_id`),
  INDEX `fk_piloto_has_carrera_carrera1_idx` (`carrera_id` ASC),
  INDEX `fk_piloto_has_carrera_piloto1_idx` (`piloto_id` ASC),
  CONSTRAINT `fk_piloto_has_carrera_piloto1`
    FOREIGN KEY (`piloto_id`)
    REFERENCES `mydb`.`piloto` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_piloto_has_carrera_carrera1`
    FOREIGN KEY (`carrera_id`)
    REFERENCES `mydb`.`carrera` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`piloto_incidente`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`piloto_incidente` (
  `piloto_id` INT UNSIGNED NOT NULL,
  `incidente_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`piloto_id`, `incidente_id`),
  INDEX `fk_piloto_has_incidente_incidente1_idx` (`incidente_id` ASC),
  INDEX `fk_piloto_has_incidente_piloto1_idx` (`piloto_id` ASC),
  CONSTRAINT `fk_piloto_has_incidente_piloto1`
    FOREIGN KEY (`piloto_id`)
    REFERENCES `mydb`.`piloto` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_piloto_has_incidente_incidente1`
    FOREIGN KEY (`incidente_id`)
    REFERENCES `mydb`.`incidente` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
