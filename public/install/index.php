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

		ul>li {
			float: left;
		}
		</style>
		<link href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,700|Open+Sans:300italic,400,300,700" rel="stylesheet" type="text/css">
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
		<script type="text/javascript" src="js/semantic.js"></script>
		<script type="text/javascript">
		    $(function() {
		    	$('document').ready(function() {
		    		maximaCarga = $('.completo').innerWidth()-13;
		    	})

    			$( "#tabs" ).tabs();
    			
    			$('.ustedaqui').popup({
					on: 'hover'
				});

				$('.completo').popup({
					on: 'hover'
				});

				$('#i1').click( function() {
					$('#i2').removeClass("active");
					$('#i3').removeClass("active");
					$('#i4').removeClass("active");
					$('#i1').addClass("active");
				});
				
				$('#i2').click( function() {
					$('#i1').removeClass("active");
					$('#i3').removeClass("active");
					$('#i4').removeClass("active");
					$('#i2').addClass("active");
				});
				
				$('#i3').click( function() {
					$('#i2').removeClass("active");
					$('#i1').removeClass("active");
					$('#i4').removeClass("active");
					$('#i3').addClass("active");
				});
				
				$('#i4').click( function() {
					$('#i2').removeClass("active");
					$('#i3').removeClass("active");
					$('#i1').removeClass("active");
					$('#i4').addClass("active");
				});

				$('input').focus( function() {
					$(this).addClass('iselected');
				})

				$('input').blur( function() {
					var progreso = $('#progreso').width();
					var valor = $('.iselected').val();
					$('.iselected').removeClass('iselected');
					if (valor != "" && !$(this).hasClass('iready') && progreso < maximaCarga) {
						$('#progreso').width('+=500');
						$(this).addClass('iready');
						var progreso = $('#progreso').width();
						if (progreso > maximaCarga) {
							$('#progreso').width(maximaCarga);
						};
					};
				})
  			});
		</script>
	</head>
	<body>
		<div class="ui grid">
			<div class="fiveten wide column">
					<div class="ui header aligned center">Asistente de instalación de veloci</div>
					<div class="ui fluid divider"></div>
					<div class="ui successful progress completo" data-content="Esta barra muestra el progreso de la instalación">
						<div id="progreso" class="bar completo" data-content="Completado: 0%" style="width:2%"></div>
					</div>
					<div class="ui fluid divider"></div>
					
					<form method="post" action="instalar.php">
					<div id="tabs">
						<div class="ui tabular menu">
						<ul>
					    	<li><a href="#tabs-1" id="i1" class="item active">Inicio</a></li>
							<li><a href="#tabs-2" id="i2" class="item i2">Base de datos</a></li>
							<li><a href="#tabs-3" id="i3" class="item i3">Datos de la aplicación</a></li>
							<li><a href="#tabs-4" id="i4" class="item i4">Datos del administrador</a></li>
						</ul>
						</div>
						<div id="tabs-1">
							<div class="ui two grid column">
								<div class="column">
									<div class="ui segment blue">
										<div class="ui header">
											Bienvenido al asistente de instalación
										</div>
										<div class="ui fluid divider"></div>
										<p>Este es el asistente de instalación para la aplicación Veloice, utilizada para gestionar
											ligas de carreras online, como por ejemplo, ligas de formula 1, turismos, entre otras, es decir,
											cualquier competición dedicada a carreras.</p>
										<div class="ui header">
											Comenzando la instalación...
										</div>
										<div class="ui fluid divider"></div>
										<p>¡Ya esta listo para comenzar el proceso de instalación, el asistente le guiará a través
											de una serie de pasos, se le pedirá cierta información y se instalará la aplicación. Si
											tiene todo preparado proceda a instalar, a través de las pestañas superiores.
										</p>
									</div>
								</div>
								<div class="column">
									<div class="ui segment green">
										<div class="ui header">
											Funcionalidades actuales
										</div>
										<div class="ui fluid divider"></div>
										<p>veloci está preparado para gestionar cualquier tipo de competición de carreras. La versión 1
										de veloci tiene capacidad para:</p>
										<ol>
											<li>Control de asistencias a carreras</li>
											<li>Control de reclamaciones</li>
											<li>Control de categorías</li>
											<li>Gestión de carreras, categorías, calendarios y circuitos</li>
										</ol>
										<div class="ui fluid divider"></div>
										<div class="ui header">
											¿Qué más tendrá veloci?
										</div>
										<p>La aplicación pretende llegar más lejos, por lo que en un futuro podrá ser actualizada
											a través de modulos, los cuales se implementarán a traves de un asistente y se añadirán
											a la aplicación instalada en ese momento.</p>
									</div>
								</div>
							</div>
						</div>
						<div id="tabs-2">
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
													<input type="text" name="inputHost" placeholder="Servidor">
													<i class="signal icon"></i>
												</div>
											</div>
											<div class="field">
												<label>Nombre de la base de datos</label>
												<div class="ui left labeled icon input">
													<input type="text" name="inputBaseDatos" placeholder="Si no existe, se creará automáticamente">
													<i class="edit icon"></i>
												</div>
											</div>
											<div class="field">
												<label>Usuario</label>
												<div class="ui left labeled icon input">
													<input type="text" name="inputUsuarioBase" placeholder="Usuario con permiso de acceso a la base de datos">
													<i class="user icon"></i>
												</div>
											</div>
											<div class="field">
												<label>Contraseña</label>
												<div class="ui left labeled icon input">
													<input type="text" name="inputPassUsuarioBase" placeholder="Contraseña del usuario">
													<i class="legal icon"></i>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div id="tabs-3">
							<div class="ui two grid column">
								<div class="column">
									<div class="ui segment blue">
										<div class="ui header">
											Información sobre la Aplicación
										</div>
										<div class="ui fluid divider"></div>
										<p>Este paso es muy simple, es más, simplemente, <br/>¡pongalé nombre a su aplicación!</p>
									</div>
								</div>
								<div class="column">
									<div class="ui segment green">
										<div class="ui header">
											Te voy a llamar...
										</div>
										<div class="ui form red segment">
											<div class="field">
												<label>Nombre de la aplicación</label>
												<div class="ui left labeled icon input">
													<input type="text" name="inputNombreApp">
													<i class="smile icon"></i>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div id="tabs-4">
							<div class="ui two grid column">
								<div class="column">
									<div class="ui segment blue">
										<div class="ui header">
											Información sobre la cuenta
										</div>
										<div class="ui fluid divider"></div>
										<p>Y para terminar, rellene el formulario con los datos para la cuenta del administrador, es decir
											la cuenta que tendrá permiso para hacer cualquier cosa, es como ser un DIOS. Algo parecido, sí...</p>
											<button type="submit" name="instalar" class="ui animated positive fade button floated right">
												<div class="visible content">Completar instalación</div>
												<div class="hidden content">
													VELOCI
												</div>
											</button>
									</div>
								</div>
								<div class="column">
									<div class="ui segment green">
										<div class="ui header">
											Datos de la cuenta
										</div>
										<div class="ui form red segment">
											<div class="field">
												<label>Nombre de usuario</label>
												<div class="ui left labeled icon input">
													<input type="text" name="inputUsuarioAdmin" placeholder="Nombre que se utilizará para acceder a la aplicación">
													<i class="user icon"></i>
												</div>
											</div>
											<div class="field">
												<label>Contraseña</label>
												<div class="ui left labeled icon input">
													<input type="password" name="inputPassAdmin" placeholder="Contraseña de la cuenta">
													<i class="lock icon"></i>
												</div>
											</div>
											<div class="field">
												<label>Repita la contraseña</label>
												<div class="ui left labeled icon input">
													<input type="password" name="inputPassAdmin2" placeholder="Vuelve a escribir la contraseña">
													<i class="lock icon"></i>
												</div>
											</div>
											<div class="field">
												<label>Email</label>
												<div class="ui left labeled icon input">
													<input type="text" name="inputEmailAdmin" placeholder="Escriba la dirección de correo a usar por la cuenta">
													<i class="lock icon"></i>
												</div>
											</div>
											<div class="field">
												<label>Nombre público (Por ejemplo: FIA)</label>
												<div class="ui left labeled icon input">
													<input type="text" name="inputNombrePublico" placeholder="Deje este campo en blanco para utilizar el de usuario">
													<i class="edit icon"></i>
												</div>
											</div>
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