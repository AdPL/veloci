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
            $carreras = cargarCarreras();

            $app->render('admin.html.twig', array('usuarios' => $usuarios, 'id' => $_SESSION['id'], 'categorias' => $categorias, 'circuitos' => $circuitos, 'carreras' => $carreras, 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol']));
        } else {
            $app->render('principal.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol']));
        }
    }
})->name('admin');

$app->get('/categorias/nueva', function() use ($app) {
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

$app->post('/categorias/nueva', function() use ($app) {
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

$app->get('/categorias/lista', function() use ($app) {
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

$app->post('/listaCategorias', function() use ($app) {
    if(!isset($_SESSION['id'])) {
        $app->render('principal.html.twig');
    } else {
        if($_SESSION['rol'] == 5) {
            if (isset($_POST['inputNombre'])) {
                editarCategoria($app, $_POST['id'], $_POST['inputNombre'], $_POST['inputPlazas'], $_POST['inputPrecio']);   
            } else {
                eliminarCategoria($app, $_POST['id']);
            }

            $app->Redirect('listaCategorias');
        } else {
            $app->render('principal.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol']));
        }
    }
})->name('editarCategoriaPost');

$app->get('/carreras/nueva', function() use ($app) {
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

$app->post('/carreras/nueva', function() use ($app) {
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

$app->get('/carreras/lista', function() use ($app) {
    if(!isset($_SESSION['id'])) {
        $app->render('principal.html.twig');
    } else {
        if($_SESSION['rol'] == 5) {
            $carreras = cargarCarreras();

            $app->render('listaCarreras.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol'], 'carreras' => $carreras));
        } else {
            $app->render('principal.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol']));
        }
    }
})->name('listaCarreras');

$app->post('/carreras/lista', function() use ($app) {
    if(!isset($_SESSION['id'])) {
        $app->render('principal.html.twig');
    } else {
        if($_SESSION['rol'] == 5) {
            if (isset($_POST['inputNombre'])) {
                editarCategoria($app, $_POST['id'], $_POST['inputNombre'], $_POST['inputPlazas'], $_POST['inputPrecio']);   
            } else {
                eliminarCarrera($app, $_POST['id']);
            }

            $app->Redirect('listaCarreras');
        } else {
            $app->render('principal.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol']));
        }
    }
})->name('editarCarreraPost');

$app->get('/circuitos/nuevo', function() use ($app) {
    if(!isset($_SESSION['id'])) {
        $app->render('principal.html.twig');
    } else {
        if($_SESSION['rol'] == 5) {
            $app->render('nuevoCircuito.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol']));
        } else {
            $app->render('principal.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol']));
        }
    }
})->name('nuevoCircuito');

$app->post('/circuitos/nuevo', function() use ($app) {
    if(!isset($_SESSION['id'])) {
        $app->render('principal.html.twig');
    } else {
        if($_SESSION['rol'] == 5) {
            crearCircuito($app, $_POST['inputNombre'], $_POST['inputPais'], $_POST['inputDistancia']);
            $app->Redirect('circuitos');
            $app->render('nuevaCircuito.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol']));
        } else {
            $app->render('principal.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol']));
        }
    }
})->name('crearCircuito');

$app->get('/circuitos/lista', function() use ($app) {
    if(!isset($_SESSION['id'])) {
        $app->render('principal.html.twig');
    } else {
        if($_SESSION['rol'] == 5) {
            $circuitos = cargarCircuitos();

            $app->render('listaCircuitos.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol'], 'circuitos' => $circuitos));
        } else {
            $app->render('principal.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol']));
        }
    }
})->name('listaCircuitos');

$app->get('/usuarios', function() use ($app) {
    if(!isset($_SESSION['id'])) {
        $app->render('principal.html.twig');
    } else {
        if($_SESSION['rol'] == 5) {
            $usuarios = cargarUsuarios();

            $app->render('listaUsuarios.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol'], 'usuarios' => $usuarios));
        } else {
            $app->render('principal.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol']));
        }
    }
})->name('listaUsuarios');

$app->get('/asistencias/control', function() use ($app) {
    if(!isset($_SESSION['id'])) {
        $app->render('principal.html.twig');
    } else {
        if($_SESSION['rol'] == 5) {
            $carreras = cargarCarreras();
            $pilotos = cargarUsuarios();
            
            $app->render('controlAsistencia.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol'], 'carreras' => $carreras, 'pilotos' => $pilotos));
        } else {
            $app->render('principal.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol']));
        }
    }
})->name('controlAsistencia');

$app->post('/asistencias/control', function() use ($app) {
    if(!isset($_SESSION['id'])) {
        $app->render('principal.html.twig');
    } else {
        if($_SESSION['rol'] == 5) {
            $carreras = cargarCarreras();
            $pilotos = cargarUsuarios();
            
            guardarAsistencias($_POST);

            $app->render('controlAsistencia.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol'], 'carreras' => $carreras, 'pilotos' => $pilotos));
        } else {
            $app->render('principal.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol']));
        }
    }
})->name('controlAsistenciaGuardar');

$app->get('/usuarios/editar/:idUser', function($idUser) use ($app) {
    if(!isset($_SESSION['id'])) {
        $app->render('principal.html.twig');
    } else {
        if($_SESSION['rol'] == 5) {
            $piloto = cargarUsuario($idUser);
            
            $app->render('editarUsuario.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol'], 'piloto' => $piloto));
        } else {
            $app->render('principal.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol']));
        }
    }
})->name('editarUsuario');

$app->get('/usuarios/editar/:idUser', function($idUser) use ($app) {
    if(!isset($_SESSION['id'])) {
        $app->render('principal.html.twig');
    } else {
        if($_SESSION['rol'] == 5) {
            if (isset($_POST['inputSubmit'])) {
                //editarCategoria($app, $_POST['id'], $_POST['inputNombre'], $_POST['inputPlazas'], $_POST['inputPrecio']);   
            } else {
                //eliminarCategoria($app, $_POST['id']);
            }

            $app->Redirect('listaCategorias');
        } else {
            $app->render('principal.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol']));
        }
    }
})->name('editarUsuarioPost');

$app->get('/noticias/lista', function() use ($app) {
    if(!isset($_SESSION['id'])) {
        $app->render('principal.html.twig');
    } else {
        $noticias = cargarNoticias();
        $app->render('listaNoticias.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol'], 'noticias' => $noticias));
    }
})->name('listaNoticias');

$app->get('/noticias/nueva', function() use ($app) {
    if(!isset($_SESSION['id'])) {
        $app->render('principal.html.twig');
    } else {
        $noticias = cargarNoticias();
        $app->render('nuevaNoticia.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol'], 'noticias' => $noticias));
    }
})->name('nuevaNoticia');

$app->post('/noticias/lista', function() use ($app) {
    if(!isset($_SESSION['id'])) {
        $app->render('principal.html.twig');
    } else {
        crearNoticia($_POST['inputTitulo'], $_POST['inputTexto'], $_POST['inputRango'], $_POST['inputEstado'], $_SESSION['id']);
        $noticias = cargarNoticias();
        $app->render('listaNoticias.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol'], 'noticias' => $noticias));
    }
})->name('crearNoticia');

$app->get('/categorias/asignar/:idUser', function($idUser) use ($app) {
    $piloto = cargarUsuario($idUser);
    $categorias = cargarCategorias();
    if(!isset($_SESSION['id'])) {
        $app->render('principal.html.twig');
    } else {
        $app->render('asignarCategorias.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol'], 'categorias' => $categorias, 'piloto' => $piloto));
    }
})->name('asignarCategorias');

$app->post('/categorias/asignar', function() use ($app) {
    $categorias = cargarCategorias();
    if(!isset($_SESSION['id'])) {
        $app->render('principal.html.twig');
    } else {
        asignarCategorias($_POST['idUser'], $_POST['idCategoria']);
        $piloto = cargarUsuario($_POST['idUser']);
        $app->render('asignarCategorias.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol'], 'categorias' => $categorias, 'piloto' => $piloto));
    }
})->name('asignaCategorias');

function cargarUsuario($idUser) {
    return ORM::for_table('piloto')->
    //join('piloto_categoria', array('piloto.id', '=', 'piloto_categoria.piloto_id'))->
    //join('categoria', array('piloto_categoria.categoria_id', '=', 'categoria.id'))->
    select_many('piloto.id', 'email', 'avatar', 'nombre_completo', 'escuderia', 'activo', 'rol')->find_one($idUser);
}

function cargarCategoriasUsuario($idUser) {
    return ORM::for_table('piloto_categoria')->where('piloto_id', $idUser)->find_many();
}

function cargarUsuarios() {
    return ORM::for_table('piloto')->select_many('id', 'email', 'avatar', 'escuderia', 'nombre_completo', 'rol')->find_many();
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
    return ORM::for_table('carrera')->order_by_asc('fecha')->find_many();
}

function cargarNoticias() {
    return ORM::for_Table('noticia')->
    join('piloto', array('piloto.id', '=', 'noticia.usuario_id'))->
    select_many('noticia.id', 'titulo', 'texto', 'fecha_publicacion', 'rango_requerido', 'estado', 'piloto.nombre_completo')->
    order_by_desc('fecha_publicacion')->find_many();
}

function cargarNoticiasPaginacion($nNoticias, $pagina) {
    return ORM::for_Table('noticia')->
    join('piloto', array('piloto.id', '=', 'noticia.usuario_id'))->
    select_many('noticia.id', 'titulo', 'texto', 'fecha_publicacion', 'rango_requerido', 'estado', 'piloto.nombre_completo')->
    order_by_desc('fecha_publicacion')->limit($nNoticias)->offset($pagina * $nNoticias)->find_many();
}

function cargarNoticia($idNoticia) {
    return ORM::for_Table('noticia')->
    join('piloto', array('piloto.id', '=', 'noticia.usuario_id'))->
    select_many('noticia.id', 'titulo', 'texto', 'fecha_publicacion', 'rango_requerido', 'estado', 'piloto.nombre_completo')->
    where('id', $idNoticia)->find_one();
}

function crearNoticia($titulo, $texto, $rango, $estado, $usuarioID) {
    $noticia = ORM::for_table('noticia')->create();
    $noticia->id = null;
    $noticia->titulo = $titulo;
    $noticia->texto = $texto;
    $noticia->fecha_publicacion = date("Y-n-d H:i:s");
    $noticia->rango_requerido = $rango;
    $noticia->estado = $estado;
    $noticia->usuario_id = $usuarioID;
    $noticia->save();
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

function eliminarCarrera($app, $idCat) {
    $categoria = ORM::for_table('carrera')->where('id', $idCat)->find_one();
    $categoria->delete();
}

function crearCircuito($app, $nombre, $pais, $distancia) {
    $circuito = ORM::for_table('circuito')->create();
    $circuito->id = null;
    $circuito->nombre = $nombre;
    $circuito->pais = $pais;
    $circuito->distancia = $distancia;
    $circuito->save();
}

function controlAsistencia($piloto_id, $carrera_id, $estado) {
    $asistencia = ORM::for_table('piloto_carrera')->create();
    $asistencia->id = null;
    $asistencia->piloto_id = $piloto_id;
    $asistencia->carrera_id = $carrera_id;
    $asistencia->estado = $estado;
    $asistencia->save();
}

function guardarAsistencias($formulario) {
    $npilotos = ORM::for_table('piloto')->max('id');
    $ncarreras = ORM::for_table('carrera')->max('id');

    for ($i=1 ; $i <= $npilotos; $i++) {
        for ($j=1 ; $j <= $ncarreras; $j++) {
            $cadena = $i . "-" . $j;
            echo "cadena: " . $cadena . "<br/>";
            
            if (isset($_POST[$cadena])) {
                echo $cadena . "<br/>";
                controlAsistencia($i, $j, $_POST[$cadena]);
            }
        }
        $j = 0;
    }
}

function asignarCategorias($idUser, $categorias) {
    for ($i=0; $i < count($categorias); $i++) { 
        $catPiloto = ORM::for_table('piloto_categoria')->create();
        $catPiloto->id = null;
        $catPiloto->categoria_id = $categorias[$i];
        $catPiloto->piloto_id = $idUser;
        $catPiloto->save();
    }
}