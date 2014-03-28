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

	// Busca si existe un fichero de configuración y si existe, detiene todo proceso de instalación.

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
		$host = $_POST['inputHost'];
		$database = $_POST['inputDB'];
		$usuario = $_POST['inputUser'];
		$password = $_POST['inputPass'];

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
				$insertADM = $dbh->prepare("INSERT INTO usuario (id, usuario, password, email, nombre_completo, administrador) VALUES ('0', :usrADM, :passADM, :emailADM, 'Admin', '1')");
				$insertADM->bindValue(':usrADM', $usrADM, PDO::PARAM_STR);
				$insertADM->bindValue(':passADM', $passADM, PDO::PARAM_STR);
				$insertADM->bindValue(':emailADM', $emailADM, PDO::PARAM_STR);
				$insertADM->execute() or die(print_r("Error en la creación del usuario administrador"));

				$dbh->exec("INSERT INTO `$database`.`nivel` VALUES (null, '1ESO')");
				$dbh->exec("INSERT INTO `$database`.`nivel` VALUES (null, '2ESO')");
				$dbh->exec("INSERT INTO `$database`.`nivel` VALUES (null, '3ESO')");
				$dbh->exec("INSERT INTO `$database`.`nivel` VALUES (null, '4ESO')");

				$dbh->exec("INSERT INTO `$database`.`asignatura` VALUES (null, 'Matemáticas 1 ESO', '1')");
				$dbh->exec("INSERT INTO `$database`.`asignatura` VALUES (null, 'Lengua 1 ESO', '1')");
				$dbh->exec("INSERT INTO `$database`.`asignatura` VALUES (null, 'Conocimiento del medio 1 ESO', '1')");
				$dbh->exec("INSERT INTO `$database`.`asignatura` VALUES (null, 'Tecnología 1 ESO', '1')");

				$dbh->exec("INSERT INTO `$database`.`asignatura` VALUES (null, 'Matemáticas 2 ESO', '2')");
				$dbh->exec("INSERT INTO `$database`.`asignatura` VALUES (null, 'Lengua 2 ESO', '2')");
				$dbh->exec("INSERT INTO `$database`.`asignatura` VALUES (null, 'Conocimiento del medio 2 ESO', '2')");
				$dbh->exec("INSERT INTO `$database`.`asignatura` VALUES (null, 'Tecnología 2 ESO', '2')");

				$dbh->exec("INSERT INTO `$database`.`asignatura` VALUES (null, 'Matemáticas 3 ESO', '3')");
				$dbh->exec("INSERT INTO `$database`.`asignatura` VALUES (null, 'Lengua 3 ESO', '3')");
				$dbh->exec("INSERT INTO `$database`.`asignatura` VALUES (null, 'Conocimiento del medio 3 ESO', '3')");
				$dbh->exec("INSERT INTO `$database`.`asignatura` VALUES (null, 'Tecnología 3 ESO', '3')");

				$dbh->exec("INSERT INTO `$database`.`asignatura` VALUES (null, 'Matemáticas 4 ESO', '4')");
				$dbh->exec("INSERT INTO `$database`.`asignatura` VALUES (null, 'Lengua 4 ESO', '4')");
				$dbh->exec("INSERT INTO `$database`.`asignatura` VALUES (null, 'Conocimiento del medio 4 ESO', '4')");
				$dbh->exec("INSERT INTO `$database`.`asignatura` VALUES (null, 'Tecnología 4 ESO', '4')");

				$dbh->exec("INSERT INTO `$database`.`libro` VALUES(null, 'Libro1', '123456789', 'Anaya', '1')");
				$dbh->exec("INSERT INTO `$database`.`libro` VALUES(null, 'Libro2', '987654321', 'RA-MA', '4')");

				$dbh->exec("INSERT INTO `$database`.`alumno` VALUES (null, 'Chuck Norris', '14/05/1993', '26258394', '0', 'Calle falsa 123', '953658394', '')");
				$dbh->exec("INSERT INTO `$database`.`alumno` VALUES (null, 'Pepito', '22/08/1991', '26342394', '1', 'Calle de la piruleta 21', '959878594', '')");

				$dbh->exec("INSERT INTO `$database`.`ejemplar` VALUES (null, 'cod-1', 'Bueno', '1', '1')");
				$dbh->exec("INSERT INTO `$database`.`ejemplar` VALUES (null, 'cod-2', 'Bueno', '2', '2')");
				$dbh->exec("INSERT INTO `$database`.`ejemplar` VALUES (null, 'cod-3', 'Bueno', '2', '2')");

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
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Instalador</title>
		<meta charset="utf-8"/>
		<link href="css/semantic.css" rel="stylesheet" type="text/css">
		<style>
		body {
			display: inherit;
			margin: 0;
			font-family: "Open Sans";
		}

		.noline {
			text-decoration: none;
		}
		</style>
		<link href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,700|Open+Sans:300italic,400,300,700" rel="stylesheet" type="text/css">
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script type="text/javascript" src="js/semantic.js"></script>
		<script type="text/javascript">
			$(document).ready(function() {
				$('.ustedaqui').popup({
					on: 'hover'
				});
			});
		</script>
	</head>
	<body>
		<div class="ui inverted menu fixed">
			<div class="item active">
				<a href="{{ urlFor('principal') }}" class="noline">Inicio</a>
			</div>
		</div>

		<div class="ui grid">
			<div class="fiveten wide column">
				<div class="ui segment aligned center">
					<div class="ui header">Asistente de instalación de Velocie</div>
					<div class="ui fluid divider"></div>
					<div class="ui steps">
						<div class="ui step disabled">
							Inicio
						</div>
						<div class="ui step active ustedaqui" data-content="¡Te encuentras aquí!">
							Base de datos
						</div>
						<div class="ui step disabled">
							Datos de la aplicación
						</div>
						<div class="ui step disabled">
							Datos del administrador
						</div>
						<div class="ui step disabled">
							Fin
						</div>
					</div>
					<div class="ui two grid column">
						<div class="column">
							<div class="ui segment blue">
								<div class="ui header">
									Información sobre la Base de datos
								</div>
								<div class="ui fluid divider"></div>
								<p>Los datos que debe rellenar a continuación son los indicados por su servicio de hosting,
									por ejemplo, si usted esta instalando la aplicación en su propio ordenador el host a utilizar normalmente será "localhost".</p>
								<p>También debe indicar su usuario y contraseña con el que se conecta a la base de datos, y por último el nombre de la base de datos a crear, donde se alamacenarán las tablas de la aplicación.</p>
							</div>
						</div>
						<div class="column">
							<div class="ui segment green">
								<div class="ui header">
									Formulario
								</div>
								<div class="ui form red segment">
									<div class="field">
										<label>Host</label>
										<div class="ui left labeled icon input">
											<input type="test" placeholder="Servidor">
											<i class="signal icon"></i>
										</div>
									</div>
									<div class="field">
										<label>Nombre de la base de datos</label>
										<div class="ui left labeled icon input">
											<input type="test" placeholder="Si no existe, se creará automáticamente">
											<i class="edit icon"></i>
										</div>
									</div>
									<div class="field">
										<label>Usuario</label>
										<div class="ui left labeled icon input">
											<input type="test" placeholder="Usuario con permiso de acceso a la base de datos">
											<i class="user icon"></i>
										</div>
									</div>
									<div class="field">
										<label>Contraseña</label>
										<div class="ui left labeled icon input">
											<input type="test" placeholder="Contraseña del usuario">
											<i class="legal icon"></i>
										</div>
									</div>
									<a href="datosapp.php" class="ui animated green fade button floated right">
										<div class="visible content">Continuar</div>
										<div class="hidden content">¡Perfecto!</div>
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
