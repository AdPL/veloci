<?php
	$todocorrecto = false; // Variable que nos permitira continuar la instalación o no.

	//Busca si existe un fichero de configuración y si existe, detiene todo proceso de instalación.

	/*$ruta = $_SERVER["SERVER_NAME"];
	$subruta = $_SERVER["PHP_SELF"];

	$fin = strripos($subruta, '/');
	$subruta = substr($subruta, 0, $fin);
	$fin = strripos($subruta, '/');
	$subruta = substr($subruta, 0, $fin) . "/config/config.php";
	$ruta = $_SERVER["DOCUMENT_ROOT"] . $subruta;*/

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
die();
		$usrADM = $_POST['inputUserAdm'];
		$passADM = $_POST['inputPassAdm'];
		$passTestADM = $_POST['inputPassAdm2'];
		$emailADM = $_POST['inputEmail'];
		$correcto = false;
		$todocorrecto = false;

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

			$dbh->exec("CREATE TABLE IF NOT EXISTS `$database`.`alumno` (
			`id` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID se corresponde con el NIE (Número identificador de Estudiante)',
			`nombre` VARCHAR(45) NOT NULL COMMENT 'Nombre completo del alumno',
			`fechanacimiento` VARCHAR(45) NOT NULL COMMENT 'Fecha de nacimiento del alumno',
			`dni` VARCHAR(9) NULL COMMENT 'DNI Alumno',
			`repetidor` TINYINT(1) NOT NULL,
			`domicilio` VARCHAR(45) NOT NULL,
			`telefono1` CHAR(9) NOT NULL,
			`telefono2` CHAR(9) NULL,
			PRIMARY KEY (`id`))
			ENGINE = InnoDB;");

			$dbh->exec("CREATE TABLE IF NOT EXISTS `$database`.`nivel` (
			`id` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'id del curso',
			`nombre` VARCHAR(10) NOT NULL COMMENT 'Nombre del curso, nivel (1ºESO.. etc)',
			PRIMARY KEY (`id`))
			ENGINE = InnoDB;");

			$dbh->exec("CREATE TABLE IF NOT EXISTS `$database`.`asignatura` (
			`id` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'id asignatura',
			`nombre` VARCHAR(45) NOT NULL COMMENT 'Nombre de la asignatura',
			`nivel_id` INT UNSIGNED NOT NULL,
			PRIMARY KEY (`id`),
			INDEX `fk_asignatura_nivel` (`nivel_id` ASC),
			CONSTRAINT `fk_asignatura_nivel`
			FOREIGN KEY (`nivel_id`)
			REFERENCES `$database`.`nivel` (`id`)
			ON DELETE NO ACTION
			ON UPDATE NO ACTION)
			ENGINE = InnoDB;");

			$dbh->exec("CREATE TABLE IF NOT EXISTS `$database`.`libro` (
			`id` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'id libro',
			`nombre` VARCHAR(45) NOT NULL COMMENT 'nombre del libro',
			`isbn` VARCHAR(45) NOT NULL,
			`editorial` VARCHAR(45) NOT NULL,
			`asignatura_id` INT UNSIGNED NOT NULL COMMENT 'ID de la asignatura a la que pertenece el libro',
			PRIMARY KEY (`id`),
			INDEX `fk_libro_asignatura1_idx` (`asignatura_id` ASC),
			CONSTRAINT `fk_libro_asignatura1`
			FOREIGN KEY (`asignatura_id`)
			REFERENCES `$database`.`asignatura` (`id`)
			ON DELETE NO ACTION
			ON UPDATE NO ACTION)
			ENGINE = InnoDB;");

			$dbh->exec("CREATE TABLE IF NOT EXISTS `$database`.`ejemplar` (
			`id` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'id ejemplar del libro',
			`codigo` varchar(20) NOT NULL,
			`estado` ENUM('Bueno','Regular','Malo','Perdido','Baja') NOT NULL COMMENT 'Estado en el que se devuelve un libro: bueno, regular, malo, perdido, o baja.',
			`libro_id` INT UNSIGNED NOT NULL COMMENT 'ID Del libro al que pertenece el ejemplar',
			`alumno_id` INT UNSIGNED,
			PRIMARY KEY (`id`),
			INDEX `fk_ejemplar_libro_id` (`libro_id` ASC),
			INDEX `fk_ejemplar_alumno` (`alumno_id` ASC),
			CONSTRAINT `fk_ejemplar_libro`
			FOREIGN KEY (`libro_id`)
			REFERENCES `$database`.`libro` (`id`)
			ON DELETE CASCADE
			ON UPDATE CASCADE,
			CONSTRAINT `fk_ejemplar_alumno`
			FOREIGN KEY (`alumno_id`)
			REFERENCES `$database`.`alumno` (`id`)
			ON DELETE NO ACTION
			ON UPDATE NO ACTION)
			ENGINE = InnoDB;");

			$dbh->exec("CREATE TABLE IF NOT EXISTS `$database`.`usuario` (
			`id` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de usuario',
			`usuario` VARCHAR(45) NOT NULL COMMENT 'Usuario de acceso',
			`password` VARCHAR(255) NOT NULL COMMENT 'Password de acceso',
			`email` VARCHAR(45) NOT NULL,
			`nombre_completo` VARCHAR(45) NOT NULL COMMENT 'NOmbre completo del usuario',
			`administrador` TINYINT(1) NOT NULL,
			PRIMARY KEY (`id`))
			ENGINE = InnoDB;");

			$dbh->exec("CREATE TABLE `$database`.`historial` (
			`id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID historial',
  			`fecha` datetime NOT NULL,
  			`descripcion` varchar(200) NOT NULL COMMENT 'Descripción e información acerca de la devolución del libro.',
  			`estado` enum('Bueno','Regular','Malo','Perdido','Baja') NOT NULL,
  			`ejemplar_id` int(10) unsigned NOT NULL COMMENT 'Foreign Key del Ejemplar',
  			`usuario_id` int(10) unsigned DEFAULT NULL,
  			`alumno_id` int(10) unsigned DEFAULT NULL,
			PRIMARY KEY (`id`),
			INDEX `fk_historial_ejemplar1_idx` (`ejemplar_id` ASC),
			INDEX `fk_historial_usuario1` (`usuario_id` ASC),
			INDEX `fk_historial_alumno1` (`alumno_id` ASC),
			CONSTRAINT `fk_historial_ejemplar1`
			FOREIGN KEY (`ejemplar_id`)
			REFERENCES `$database`.`ejemplar` (`id`)
			ON DELETE CASCADE
			ON UPDATE CASCADE,
			CONSTRAINT `fk_historial_usuario1`
			FOREIGN KEY (`usuario_id`)
			REFERENCES `$database`.`usuario` (`id`)
			ON DELETE NO ACTION
			ON UPDATE NO ACTION,
			CONSTRAINT `fk_historial_alumno1` FOREIGN KEY (`alumno_id`) REFERENCES `$database`.`alumno` (`id`)
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