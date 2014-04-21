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
            $categorias = cargarCategorias();
            $circuitos = cargarCircuitos();

            $app->render('admin.html.twig', array('usuarios' => $usuarios, 'id' => $_SESSION['id'], 'categorias' => $categorias, 'circuitos' => $circuitos, 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol']));
        } else {
            $app->render('principal.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol']));
        }
    }
})->name('admin');

$app->get('/nuevacategoria', function() use ($app) {
    if(!isset($_SESSION['id'])) {
        $app->render('principal.html.twig');
    } else {
        if($_SESSION['rol'] == 5) {
            $app->render('nuevaCategoria.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol']));
        } else {
            $app->render('principal.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol']));
        }
    }
})->name('nuevaCategoria');

$app->post('/nuevacategoria', function() use ($app) {
    if(!isset($_SESSION['id'])) {
        $app->render('principal.html.twig');
    } else {
        if($_SESSION['rol'] == 5) {
            crearCategoria($app, $_POST['inputNombre'], $_POST['inputPlazas'], $_POST['inputPrecio']);
            $app->Redirect('categorias');
            $app->render('nuevaCategoria.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol']));
        } else {
            $app->render('principal.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol']));
        }
    }
})->name('crearCategoria');

$app->get('/categorias', function() use ($app) {
    if(!isset($_SESSION['id'])) {
        $app->render('principal.html.twig');
    } else {
        if($_SESSION['rol'] == 5) {
            $categorias = cargarCategorias();

            $app->render('listaCategorias.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol'], 'categorias' => $categorias));
        } else {
            $app->render('principal.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol']));
        }
    }
})->name('listaCategorias');

$app->get('/editar/:idCat', function($idCat) use ($app) {
    if(!isset($_SESSION['id'])) {
        $app->render('principal.html.twig');
    } else {
        if($_SESSION['rol'] == 5) {
            $categoria = cargarCategoria($idCat);

            $app->render('editarCategoria.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol'], 'categoria' => $categoria));
        } else {
            $app->render('principal.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol']));
        }
    }
})->name('editarCategoria');

$app->post('/categorias', function() use ($app) {
    if(!isset($_SESSION['id'])) {
        $app->render('principal.html.twig');
    } else {
        if($_SESSION['rol'] == 5) {
            if (isset($_POST['inputNombre'])) {
                editarCategoria($app, $_POST['id'], $_POST['inputNombre'], $_POST['inputPlazas'], $_POST['inputPrecio']);   
            } else {
                eliminarCategoria($app, $_POST['id']);
            }

            $app->Redirect('categorias');
        } else {
            $app->render('principal.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol']));
        }
    }
})->name('editarCategoriaPost');

$app->get('/nuevacarrera', function() use ($app) {
    if(!isset($_SESSION['id'])) {
        $app->render('principal.html.twig');
    } else {
        if($_SESSION['rol'] == 5) {
            $categorias = cargarCategorias();
            $circuitos = cargarCircuitos();
            $app->render('nuevaCarrera.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'circuitos' => $circuitos, 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol'], 'categorias' => $categorias));
        } else {
            $app->render('principal.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol']));
        }
    }
})->name('nuevaCarrera');

$app->post('/nuevacarrera', function() use ($app) {
    if(!isset($_SESSION['id'])) {
        $app->render('principal.html.twig');
    } else {
        if($_SESSION['rol'] == 5) {
            crearCarrera($app, $_POST['inputNombre'], $_POST['primerCompuesto'], $_POST['segundoCompuesto'], $_POST['inputVueltas'], $_POST['inputFecha'], $_POST['inputHora'], $_POST['inputCategoria'], $_POST['inputCircuito']);
            $app->Redirect('categorias');
            $app->render('nuevaCategoria.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol']));
        } else {
            $app->render('principal.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol']));
        }
    }
})->name('crearCarrera');

function cargarUsuarios() {
    return ORM::for_table('piloto')->select_many('id', 'email', 'nombre_completo', 'rol')->find_many();
}

function cargarCategorias() {
    return ORM::for_table('categoria')->select_many('id', 'nombre', 'imagen', 'plazas', 'precio_inscripcion')->order_by_asc('nombre')->find_many();
}

function cargarCategoria($idCat) {
    return ORM::for_table('categoria')->select_many('id', 'nombre', 'imagen', 'plazas', 'precio_inscripcion')->where('id', $idCat)->find_one();
}

function cargarCircuitos() {
    return ORM::for_table('circuito')->select_many('id', 'nombre', 'pais', 'distancia')->order_by_asc('nombre')->find_many();   
}

function cargarCarreras() {
    return ORM::for_table('carrera')->select_many('nombre', 'imagen', 'plazas', 'precio_inscripcion')->find_many();
}

function crearCategoria($app, $nombre, $plazas, $precio) {
    $categoria = ORM::for_table('categoria')->create();
    $categoria->id = null;
    $categoria->imagen = "images/defecto.jpeg";
    $categoria->nombre = $nombre;
    $categoria->plazas = $plazas;
    $categoria->precio_inscripcion = $precio;
    $categoria->save();
}

function editarCategoria($app, $idCat, $nombre, $plazas, $precio) {
    $categoria = ORM::for_table('categoria')->where('id', $idCat)->find_one();
    $categoria->nombre = $nombre;
    $categoria->plazas = $plazas;
    $categoria->precio_inscripcion = $precio;
    $categoria->save();
}

function eliminarCategoria($app, $idCat) {
    $categoria = ORM::for_table('categoria')->where('id', $idCat)->find_one();
    $categoria->delete();
}

function crearCarrera($app, $nombre, $primerCompuesto, $segundoCompuesto, $vueltas, $fecha, $hora, $categoria, $circuito) {
    $carrera = ORM::for_table('carrera')->create();
    $carrera->id = null;
    $carrera->nombre = $nombre;
    $carrera->neumatico1 = $primerCompuesto;
    $carrera->neumatico2 = $segundoCompuesto;
    $carrera->vueltas = $vueltas;
    $carrera->fecha = $fecha;
    $carrera->hora = $hora;
    $carrera->categoria_id = $categoria;
    $carrera->circuito_id = $circuito;
    $carrera->save();
}