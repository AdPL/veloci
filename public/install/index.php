<!--  VELOCI - Web application for management races
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
	along with this program.  If not, see [http://www.gnu.org/licenses/]. -->

<!DOCTYPE html>
<html>
	<head>
		<title>Instalador</title>
		<meta charset="utf-8"/>
		<link href="css/bootstrap.css" rel="stylesheet" type="text/css">
		<link href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,700|Open+Sans:300italic,400,300,700" rel="stylesheet" type="text/css">
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
		<script type="text/javascript" src="js/bootstrap.js"></script>
		<script type="text/javascript" src="js/myscript.js"></script>
	</head>
	<body>
		<nav class="navbar navbar-default" role="navigation">
			<div class="container-fluid">
				<p class="navbar-text">Asistente de instalación de la aplicación VELOCI</p>
			</div>
		</nav>
		<div class="container col-lg-12">
			<div class="panel panel-default">
			<div class="panel-body">
			<form method="post" action="instalar.php" class="form-horizontal" role="form">
				<div id="tabs">
					<ul class="nav nav-tabs">
				    	<li class="active"><a href="#tabs-1" data-toggle="tab">Inicio</a></li>
						<li><a href="#tabs-2" id="i2" data-toggle="tab">Base de datos</a></li>
						<li><a href="#tabs-3" id="i3" data-toggle="tab">Datos de la aplicación</a></li>
						<li><a href="#tabs-4" id="i4" data-toggle="tab">Datos del administrador</a></li>
					</ul>
					
					<div id="tabs-1">
						<div class="row">
						<div class="col-lg-6">
							<h2>Bienvenido al asistente de instalación</h2>
							<p>Este es el asistente de instalación para la aplicación Veloice, utilizada para gestionar
								ligas de carreras, como por ejemplo, ligas de formula 1, turismos, entre otras, es decir,
								cualquier competición dedicada a carreras.</p>
							<h2>Comenzando la instalación</h2>
							<p>¡Ya esta listo para comenzar el proceso de instalación, el asistente le guiará a través
								de una serie de pasos, en los que se le pedirá cierta información y se procederá a instalar la aplicación. Si
								tiene todo preparado ya puede empezar, continue a través de las pestañas superiores.
							</p>
						</div>
						
						<div class="col-lg-6">
							<h2>Funcionalidades actuales</h2>
							<p>Veloci está preparado para gestionar cualquier tipo de competición de carreras. La versión 1
							de Veloci tiene capacidad para:</p>
							<ol>
								<li>Control de asistencias a carreras</li>
								<li>Control de reclamaciones</li>
								<li>Control de categorías</li>
								<li>Gestión de carreras, categorías, noticias y circuitos</li>
							</ol>
							<h2>¿Qué más tendrá Veloci?</h2>
							<p>La aplicación pretende llegar más lejos, por lo que en un futuro podrá ser actualizada
								a través de modulos, los cuales se implementarán a traves de un asistente y se añadirán
								a la aplicación instalada en ese momento.
							</p>
						</div>
						</div>
					</div>
					<div id="tabs-2">
						<div class="row">
						<div class="col-lg-6">
						<h2>Información sobre la Base de datos</h2>
							<p>Los datos que debe rellenar a continuación son los indicados por su servicio de hosting,
								por ejemplo, si usted esta instalando la aplicación en su propio ordenador el host a utilizar normalmente será "localhost".
							</p>
							<p>También debe indicar su usuario y contraseña con el que se conecta a la base de datos, y por último el nombre de la base de datos a crear, donde se alamacenarán las tablas de la aplicación.
							</p>
						</div>
						<div class="col-lg-6">
							<h2>Formulario</h2>
							<div class="form-group">
								<label for="inputHost" class="col-sm-2 control-label">Host</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" id="inputHost" name="inputHost" placeholder="Servidor">
								</div>
							</div>
							<div class="form-group">
								<label for="inputBaseDatos" class="col-sm-2 control-label">Nombre</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" id="inputBaseDatos" name="inputBaseDatos" placeholder="Nombre de la base de datos donde guardar las tablas">
								</div>
							</div>
							<div class="form-group">
								<label for="inputUsuario" class="col-sm-2 control-label">Usuario</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" id="inputUsuario" name="inputUsuarioBase" placeholder="Usuario con permiso de acceso a la base de datos">
								</div>
							</div>
							<div class="form-group">
								<label for="inputPassUsuarioBase" class="col-sm-2 control-label">Contraseña</label>
								<div class="col-sm-10">
									<input type="password" class="form-control" id="inputPassUsuarioBase" name="inputPassUsuarioBase" placeholder="Contraseña del usuario">
								</div>
							</div>
						</div>
						</div>
					</div>					
					<div id="tabs-3">
						<h2>Información sobre la Aplicación</h2>
						<p>Este paso es muy simple, es más, simplemente, ¡pongalé nombre a su aplicación!</p>
						<h2>Te voy a llamar...</h2>
						<div class="form-group">
							<label for="inputNombreApp" class="col-sm-2 control-label">Nombre de la aplicación</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" id="inputNombreApp" name="inputNombreApp">
							</div>
						</div>
					</div>
					<div id="tabs-4">
						<div class="row">
						<div class="col-lg-4">
						<h2>Información sobre la cuenta</h2>
						<p>Y para terminar, rellene el formulario con los datos para la cuenta del administrador, es decir
							la cuenta que tendrá permiso para hacer cualquier cosa. Es la cuenta de Super Administrador.
						</p>
						<button type="submit" name="instalar" class="btn btn-success">Instalar</button>
						</div>
						<div class="col-lg-8">
						<h2>Datos de la cuenta</h2>
						<div class="form-group">
							<label for="inputUsuarioAdmin" class="col-sm-2 control-label">Nombre de usuario</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" id="inputUsuarioAdmin" name="inputUsuarioAdmin" placeholder="Nombre que se utilizará para acceder a la aplicación">
							</div>
						</div>
						<div class="form-group">
							<label for="inputPassAdmin" class="col-sm-2 control-label">Contraseña</label>
							<div class="col-sm-10">
								<input type="password" class="form-control" id="inputPassAdmin" name="inputPassAdmin" placeholder="Contraseña de la cuenta">
							</div>
						</div>
						<div class="form-group">
							<label for="inputPassAdmin2" class="col-sm-2 control-label">Repita la contraseña</label>
							<div class="col-sm-10">
								<input type="password" class="form-control" id="inputPassAdmin2" name="inputPassAdmin2" placeholder="Vuelve a escribir la contraseña">
							</div>
						</div>
						<div class="form-group">
							<label for="inputEmailAdmin" class="col-sm-2 control-label">Email</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" id="inputEmailAdmin" name="inputEmailAdmin" placeholder="Escriba la dirección de correo a usar por la cuenta">
							</div>
						</div>
						<div class="form-group">
							<label for="inputNombrePublico" class="col-sm-2 control-label">Nombre público (Por ejemplo: FIA)</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" id="inputNombrePublico" name="inputNombrePublico" placeholder="Deje este campo en blanco para utilizar el de usuario">
							</div>
						</div>
						</div>
					</div>
				</div>
			</form>
			</div>
			</div>
		</div>
	</body>
</html>