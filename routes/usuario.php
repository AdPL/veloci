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
  
$app->get('/login', function() use ($app) {
    $app->render('login.html.twig');
})->name('login');

$app->get('/registro', function() use ($app) {
    if(isset($_SESSION['id'])) {
        $app->redirect($app->urlFor('principal'));
    }
    
    $app->render('registro.html.twig');
})->name('registro');

$app->post('/registro', function() use ($app) {
    if(registrarUsuario($app, $_POST['inputUsuario'], $_POST['inputEmail'], $_POST['inputPassword'], $_POST['inputPassword2'], $_POST['inputNombreCompleto'])) {
        // MENSAJE DE REGISTRO CORRECTO
    } else {
        // MENSAJE DE REGISTRO INCORRECTO
    }
    $app->render('registro.html.twig');
})->name('registroUsuario');

function registrarUsuario($app, $usuario, $email, $password, $passwordCheck, $nombreCompleto) {
    if ($password == $passwordCheck) {
        $user = ORM::for_table('piloto')->create();
        $user->id = null;
        $user->nombre = strtolower($usuario);;
        $user->password = password_hash($password, PASSWORD_DEFAULT);
        $user->escuderia = 'Ninguna';
        $user->nombre_completo = $nombreCompleto;
        $user->email = $email;
        $user->rol = 1;
        $user->save();

        return true;   
    } else {
        return false;
    }
}