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

$app->get('/admin', function() use ($app) {
    if(!isset($_SESSION['id'])) {
        $app->render('principal.html.twig');
    } else {
        if($_SESSION['rol'] == 5) {
            $usuarios = cargarUsuarios();

            $app->render('admin.html.twig', array('usuarios' => $usuarios));
        } else {
            $app->render('principal.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol']));
        }
    }
})->name('admin');

function cargarUsuarios() {
    return ORM::for_table('piloto')->select_many('id', 'email', 'nombre_completo', 'rol')->find_many();
}