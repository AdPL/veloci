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
	if(!isset($_SESSION['id'])) {
		$app->render('principal.html.twig');
	} else {
		$app->render('principal.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol']));
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