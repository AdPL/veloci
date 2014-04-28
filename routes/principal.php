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

$app->get('/', function() use ($app) {
	$carrera = cargarCarrera();

	if(!isset($_SESSION['id'])) {
		$app->render('principal.html.twig');
	} else {
		$app->render('principal.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol'], 'carrera' => $carrera));
	}
})->name('principal');

$app->post('/', function() use ($app) {
	if (isset($_POST['login'])) {
		$acceso = testAccess($app, $_POST['inputUsuario'], $_POST['inputPassword']);	
	}

	if (!$acceso) {
		$app->redirect($app->urlFor('principal'));
	}
	$app->render('principal.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol']));
})->name('accederPrincipal');

$app->get('/salir', function() use ($app) {
	session_destroy();
	$app->redirect($app->urlFor('principal'));

	$app->render('principal.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol']));
})->name('cerrarSesion');

$app->get('/pilotos', function() use ($app) {
	$usuarios = cargarUsuarios();

	if(!isset($_SESSION['id'])) {
		$app->render('pilotos.html.twig', array('usuarios' => $usuarios));
	} else {
		$app->render('pilotos.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol'], 'usuarios' => $usuarios));
	}
})->name('pilotos');

$app->get('/categorias', function() use ($app) {
	$categorias = cargarCategorias();

	if(!isset($_SESSION['id'])) {
		$app->render('categorias.html.twig', array('categorias' => $categorias));
	} else {
		$app->render('categorias.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol'], 'categorias' => $categorias));
	}
})->name('categorias');

$app->get('/carreras', function() use ($app) {
	$carreras = cargarCarreras();

	if(!isset($_SESSION['id'])) {
		$app->render('carreras.html.twig', array('carreras' => $carreras));
	} else {
		$app->render('carreras.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol'], 'carreras' => $carreras));
	}
})->name('carreras');

$app->get('/reclamaciones', function() use ($app) {
	$carreras = cargarCarrerasReclamacion();

	if(!isset($_SESSION['id'])) {
		$app->render('reclamaciones.html.twig', array('carreras' => $carreras));
	} else {
		$app->render('reclamaciones.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol'], 'carreras' => $carreras));
	}
})->name('reclamaciones');

function testAccess($app, $usuario, $pass) {
	$user = ORM::for_table('piloto')->where('nombre', $usuario)->find_one();
	if ($user['nombre'] == $usuario && password_verify($pass, $user['password'])) {
		$_SESSION['id'] = $user['id'];
		$_SESSION['nombre_completo'] = $user['nombre_completo'];
		$_SESSION['rol'] = $user['rol'];
		$_SESSION['avatar'] = $user['avatar'];
		return true;
	} else {
		return false;
	}
}

function cargarCarrera() {
	return ORM::for_table('carrera')->where_gt('fecha', date("Y-m-d"))->order_by_asc('fecha')->limit(1)->find_many();
}

function cargarCarrerasReclamacion() {
    return ORM::for_table('carrera')->where_gt('fecha', date("Y-m-d"))->where_lt('fecha', calcularFecha('days', 4, date("Y-m-d")))->
    order_by_asc('fecha')->find_many();
}

function calcularFecha($modo, $valor, $fecha_inicio = false){
 
   if ($fecha_inicio != false) {
          $fecha_base = strtotime($fecha_inicio);
   } else {
          $time = time();
          $fecha_actual = date("Y-m-d",$time);
          $fecha_base = strtotime($fecha_actual);
   }
 
   $calculo = strtotime("$valor $modo","$fecha_base");
 
   return date("Y-m-d", $calculo);
}