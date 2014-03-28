<?php
/*  VELOCI - Web application for management races
	Copyright (C) 2014: Adrián Pérez López

	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU Affero General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU Affero General Public License for more details.

	You should have received a copy of the GNU Affero General Public License
	along with this program.  If not, see [http://www.gnu.org/licenses/]. */

	$todocorrecto = false; // Variable que nos permitira continuar la instalación o no.

	//Busca si existe un fichero de configuración y si existe, detiene todo proceso de instalación.

	$ruta = $_SERVER["SERVER_NAME"];
	$subruta = $_SERVER["PHP_SELF"];

	$fin = strripos($subruta, '/');
	$subruta = substr($subruta, 0, $fin);
	$fin = strripos($subruta, '/');
	$subruta = substr($subruta, 0, $fin) . "/config/config.php";
	$ruta = $_SERVER["DOCUMENT_ROOT"] . $subruta;

	// Busca si existe un fichero de configuración y si existe, detiene todo proceso de instalación.
	
	// Ejecución tras presionar el botón instalar.
	if (isset($_POST['instalar'])) {
		echo $host = $_POST['inputHost'];
		echo $database = $_POST['inputBaseDatos'];
		echo $usuario = $_POST['inputUsuarioBase'];
		echo $password = $_POST['inputPassUsuarioBase'];
		echo $nombreApp = $_POST['inputNombreApp'];
		echo $usrADM = $_POST['inputUsuarioAdmin'];
		echo $passADM = $_POST['inputPassAdmin'];
		echo $passTestADM = $_POST['inputPassAdmin2'];
		echo $nombreADM = $_POST['inputNombrePublico'];
		echo $emailADM = $_POST['inputEmailAdmin'];

		$correcto = false;

		// Comprobación de que el usuario ha metido la misma contraseña en los 2 campos.
		if ($passADM != $passTestADM) {
			die (print_r("ERROR: La contraseña del administrador no coincide"));
		}

		// Encriptación de la contraseña
		$passADM = password_hash($passADM, PASSWORD_DEFAULT);

		// Comienzo de la instalación

		try {
			// Conexión, comienzo de transacción, creación de BBDD y entrada a la BBDD.
			$dbh = new PDO('mysql:host=' . $host . ';charset=utf8', $usuario, $password);
			$dbh->beginTransaction();
			$dbh->exec("CREATE DATABASE IF NOT EXISTS `$database` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;") or die(print_r("Error al crear la base de datos, 
				es posible que exista otra base de datos con ese nombre."));
			$dbh->exec("USE `$database`");

			// Creación de TABLAS:

			/* Creación de tabla piloto - usuario */
			$dbh->exec("CREATE TABLE IF NOT EXISTS `$database`.`piloto` (
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
				ENGINE = InnoDB;");

			/* Creación de tabla categoría */
			$dbh->exec("CREATE TABLE IF NOT EXISTS `$database`.`categoria` (
				`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
				`nombre` VARCHAR(45) NOT NULL,
				`imagen` VARCHAR(50) NULL,
				`plazas` INT UNSIGNED NOT NULL,
				`precio_inscripcion` INT UNSIGNED NULL,
				PRIMARY KEY (`id`),
				UNIQUE INDEX `nombre_UNIQUE` (`nombre` ASC))
				ENGINE = InnoDB;");

			/* Creación de tabla circuito */
			$dbh->exec("CREATE TABLE IF NOT EXISTS `$database`.`circuito` (
				`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
				`nombre` VARCHAR(45) NOT NULL,
				`pais` VARCHAR(45) NOT NULL,
				`distancia` INT UNSIGNED NOT NULL,
				PRIMARY KEY (`id`))
				ENGINE = InnoDB;");

			/* Creación de table calendario */
			$dbh->exec("CREATE TABLE IF NOT EXISTS `$database`.`calendario` (
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
				ENGINE = InnoDB;");

			/* Creación de tabla carrera */
			$dbh->exec("CREATE TABLE IF NOT EXISTS `$database`.`carrera` (
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
				ENGINE = InnoDB;");

			/* Creación de tabla incidente */
			$dbh->exec("CREATE TABLE IF NOT EXISTS `$database`.`incidente` (
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
			ENGINE = InnoDB;");

			/* Creación de tabla piloto - categoría */
			$dbh->exec("CREATE TABLE IF NOT EXISTS `$database`.`piloto_categoria` (
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
				ENGINE = InnoDB;");

			/* Creación de tabla reclamación */
			$dbh->exec("CREATE TABLE IF NOT EXISTS `$database`.`reclamacion` (
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
				ENGINE = InnoDB;");

			/* Creación de tabla recurso */
			$dbh->exec("CREATE TABLE IF NOT EXISTS `$database`.`recurso` (
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
				ENGINE = InnoDB;");

			/* Creación de tabla piloto - carrera */
			$dbh->exec("CREATE TABLE IF NOT EXISTS `$database`.`piloto_carrera` (
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
				ENGINE = InnoDB;");

			/* Creación de tabla piloto - incidente */
			$dbh->exec("CREATE TABLE IF NOT EXISTS `$database`.`piloto_incidente` (
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
				ENGINE = InnoDB;");

			$correcto = true; // Si todo ha ido correcto permitiremos la ejecución.

		} catch (PDOException $e) {
			// En casso de error, no hacemos ningún cambio, y detenemos la ejecución.
			//$dbh->rollBack();
			$correcto = false;
			die ($e->getMessage());
		}

		// Sí todo es correcto creamos el usuario de administración.
		if ($correcto) {
			try {
				echo "BIEN";
				$todocorrecto = true;
			} catch (PDOException $e) {
				// Si falla algo, desechamos los cambios y paramos la ejecución.
				$dbh->rollBack();
				$todocorrecto = false;
				die ($e->getMessage());
			}

			// Sí todo es correcto vamos a escribir el fichero de configuración en config/config.php
			if ($todocorrecto) {
				$ruta = $_SERVER["DOCUMENT_ROOT"] . $_SERVER["PHP_SELF"];
				$ruta = dirname($ruta);

				$fin = strripos($ruta, '/');
				$ruta = substr($ruta, 0, $fin) . "/../config/config.php";

				$fp = fopen($ruta, "w+") or die("error");
				// Puede quedar fea una línea tan larga.. sí, pero si no, no funcionaba.
			    $cadena = "<?php ORM::configure('mysql:host=$host;dbname=$database'); ORM::configure('username', '$usuario'); ORM::configure('password', '$password'); ORM::configure('driver_options', array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));";
			    
			    fputs($fp,$cadena);
			    fclose($fp);
			}
		}
	}