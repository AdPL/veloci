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

/**
*  @author Adrián Pérez <adrianpelopez@gmail.com>
*/

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
            $categorias = cargarCategorias();
            $app->render('listaCategorias.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol'], 'categorias' => $categorias));
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

            $app->Redirect('categorias/lista');
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
            $app->Redirect('/carreras/lista');
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
            $app->Redirect('/circuitos/lista');
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

$app->get('/circuito/editar/:idCircuito', function($idCircuito) use ($app) {
    if(!isset($_SESSION['id'])) {
        $app->render('principal.html.twig');
    } else {
        if($_SESSION['rol'] == 5) {
            $circuito = cargarCircuito($idCircuito);

            $app->render('editarCircuito.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol'], 'circuito' => $circuito));
        } else {
            $app->render('principal.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol']));
        }
    }
})->name('editarCircuito');

$app->post('/circuito', function() use ($app) {
    if(!isset($_SESSION['id'])) {
        $app->render('principal.html.twig');
    } else {
        if($_SESSION['rol'] == 5) {
            if (isset($_POST['inputNombre'])) {
                editarCircuito($_POST['id'], $_POST['inputNombre'], $_POST['inputPais'], $_POST['inputDistancia']);
            } else {
                //eliminarCategoria($app, $_POST['id']);
            }

            $app->Redirect('/circuitos/lista');
        } else {
            $app->render('principal.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol']));
        }
    }
})->name('editarCircuitoPost');

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
            $estados = carreraControlAsistencia();
            
            $app->render('controlAsistencia.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol'], 'carreras' => $carreras, 'pilotos' => $pilotos, 'estados' => $estados));
        } else {
            $app->render('principal.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol']));
        }
    }
})->name('controlAsistencia');

function carreraControlAsistencia() {
    return ORM::for_table('piloto_carrera')->find_many();
}

$app->post('/asistencias/control', function() use ($app) {
    if(!isset($_SESSION['id'])) {
        $app->render('principal.html.twig');
    } else {
        if($_SESSION['rol'] == 5) {
            $carreras = cargarCarreras();
            $pilotos = cargarUsuarios();
            
            guardarAsistencias($_POST);
            $estados = carreraControlAsistencia();

            $app->render('controlAsistencia.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol'], 'carreras' => $carreras, 'pilotos' => $pilotos, 'estados' => $estados));
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
            $categorias = cargarCategorias();
            
            $app->render('editarUsuario.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol'], 'piloto' => $piloto, 'categorias' => $categorias));
        } else {
            $app->render('principal.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol']));
        }
    }
})->name('editarUsuario');

$app->post('/usuarios/editar/:idUser', function($idUser) use ($app) {
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

$app->post('/usuarios/borrar', function() use ($app) {
    if(!isset($_SESSION['id'])) {
        $app->render('principal.html.twig');
    } else {
        if($_SESSION['rol'] == 5) {
            if (isset($_POST['borralo'])) {
                eliminarUsuario($_POST['co']);
            } else {
                echo "<script type='text/javascript'>alertify.error('No tiene permiso para realizar esta acción');</script>";
            }

            $app->Redirect('/usuarios');
        } else {
            $app->render('principal.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol']));
        }
    }
})->name('borrarUsuario');

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

$app->get('/noticias/editar/:idNoticia', function($idNoticia) use ($app) {
    if(!isset($_SESSION['id'])) {
        $app->render('principal.html.twig');
    } else {
        $noticia = cargarNoticia($idNoticia);
        $app->render('editarNoticia.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol'], 'noticia' => $noticia));
    }
})->name('editarNoticia');

$app->post('/noticias/editar', function() use ($app) {
    if(!isset($_SESSION['id'])) {
        $app->render('principal.html.twig');
    } else {
        editarNoticia($_POST['idNoticia'], $_POST['inputTitulo'], $_POST['inputTexto'], $_POST['inputRango'], $_POST['inputEstado']);
        $app->Redirect('/noticias/lista');
    }
})->name('editarNoticiaPost');

$app->post('/noticias/lista', function() use ($app) {
    if(!isset($_SESSION['id'])) {
        $app->render('principal.html.twig');
    } else {
        if($_SESSION['rol'] == 5) {
            if (isset($_POST['borralo'])) {
                eliminarNoticia($_POST['co']);
            } else {
                echo "<script type='text/javascript'>alertify.error('No tiene permiso para realizar esta acción');</script>";
            }

            $app->Redirect('/noticias/lista');
        } else {
            $app->render('principal.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol']));
        }
    }
})->name('borrarNoticia');

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

$app->post('/categorias/borrar', function() use ($app) {
    if(!isset($_SESSION['id'])) {
        $app->render('principal.html.twig');
    } else {
        if($_SESSION['rol'] == 5) {
            if (isset($_POST['borralo'])) {
                eliminarCategoria($_POST['co']);
            } else {
                echo "<script type='text/javascript'>alertify.error('No tiene permiso para realizar esta acción');</script>";
            }

            $app->Redirect('/categorias/lista');
        } else {
            $app->render('principal.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol']));
        }
    }
})->name('borrarCategoria');

$app->get('/configuracion', function() use ($app) {
    $configuracion = datosApp();

    if(!isset($_SESSION['id'])) {
        $app->render('principal.html.twig');
    } else {
        if($_SESSION['rol'] == 5) {
            $app->render('configuracionDelSitio.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol'], 'configuracion' => $configuracion));
        } else {
            $app->render('admin.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol']));
        }
    }
})->name('configuracionDelSitio');

$app->post('/configuracion/guardar', function() use ($app) {
    actualizarConfiguracion($_POST['inputNombre'], $_POST['inputDescripcion'], $_POST['activacion'], $_POST['normativa'], $_POST['pago'],
        $_POST['inputTeamspeak'], $_POST['inputTwitter'], $_POST['inputFacebook'], $_POST['inputYoutube'], $_POST['inputVimeo']);

    $configuracion = datosApp();
    
    if(!isset($_SESSION['id'])) {
        $app->render('principal.html.twig');
    } else {
        if($_SESSION['rol'] == 5) {
            $app->render('configuracionDelSitio.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol'], 'configuracion' => $configuracion));
        } else {
            $app->render('admin.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol']));
        }
    }
})->name('guardarConfiguracion');

function datosApp() {
    return ORM::for_Table('configuracion')->find_one(1);
}

function actualizarConfiguracion($titulo, $slogan, $activacion, $normativa, $pago, $teamspeak, $twitter, $facebook, $youtube, $vimeo) {
    $datos = ORM::for_table('configuracion')->find_one(1);
    $datos->nombre = $titulo;
    $datos->descripcion = $slogan;
    $datos->requiere_activacion = $activacion;
    $datos->requiere_normativa = $normativa;
    $datos->teamspeak = $teamspeak;
    $datos->twitter = $twitter;
    $datos->facebook = $facebook;
    $datos->youtube = $youtube;
    $datos->vimeo = $vimeo;
    $datos->save();
}

/**
* Obtiene los datos del usuario especificado
* 
* @category Usuarios
* @example admin.php cargarUsuario(9)
* @param integer $idUser ID del usuario del cual se recogerá la información.
*
* @return object
*/
function cargarUsuario($idUser) {
    return ORM::for_table('piloto')->
    select_many('piloto.id', 'email', 'avatar', 'nombre_completo', 'escuderia', 'activo', 'rol')->find_one($idUser);
}

/**
* Obtiene las categorías del usuario especificado
* 
* @category Usuarios
* @example admin.php cargarCategoriasUsuario(9)
* @param integer $idUser ID del usuario del cual se recogerá la información.
*
* @return object
*/
function cargarCategoriasUsuario($idUser) {
    return ORM::for_table('piloto_categoria')->where('piloto_id', $idUser)->find_many();
}

/**
* Obtiene todos los usuarios de la BBDD
* 
* @category Usuarios
* @example admin.php cargarUsuarios())
*
* @return object
*/
function cargarUsuarios() {
    return ORM::for_table('piloto')->select_many('id', 'email', 'avatar', 'escuderia', 'nombre_completo', 'rol')->find_many();
}

/**
* Carga los datos del usuario especificado
* 
* @category Usuarios
* @example admin.php cargarCategorias()
*
* @return object
*/
function cargarCategorias() {
    return ORM::for_table('categoria')->order_by_asc('nombre')->find_many();
}

function eliminarUsuario($idUsuario) {
    $borrar = ORM::for_Table('piloto')->find_one($idUsuario);
    $borrar->delete();
}

/**
* Carga los datos de la categoría especificada
* 
* @category Categorías
* @example admin.php cargarCategoria(9)
* @param integer $idCat ID de la categoría.
*
* @return object
*/
function cargarCategoria($idCat) {
    return ORM::for_table('categoria')->select_many('id', 'nombre', 'imagen', 'plazas', 'precio_inscripcion')->where('id', $idCat)->find_one();
}

/**
* Obtiene los datos de todos los circuitos de la BBDD
* 
* @category Circuitos
* @example admin.php cargarCircuitos()
*
* @return object
*/
function cargarCircuitos() {
    return ORM::for_table('circuito')->order_by_asc('nombre')->find_many();   
}

function cargarCircuito($circuito) {
    return ORM::for_table('circuito')->find_one($circuito);   
}

function editarCircuito($idCircuito, $nombre, $pais, $distancia) {
    $circuito = ORM::for_table('circuito')->find_one($idCircuito);
    $circuito->nombre = $nombre;
    $circuito->pais = $pais;
    $circuito->distancia = $distancia;
    $circuito->save();
}

/**
* Otiene los datos de todas las carreras de la BBDD ordenados por fecha.
* 
* @category Carreras
* @example admin.php cargarCarreras()
*
* @return object
*/
function cargarCarreras() {
    return ORM::for_table('carrera')->order_by_asc('fecha')->find_many();
}

/**
* Obtiene todas las noticias almacenadas en la BBDD ordenadas por fecha.
* 
* @category Noticias
* @example admin.php cargarNoticias()
*
* @return object
*/
function cargarNoticias() {
    return ORM::for_Table('noticia')->
    join('piloto', array('piloto.id', '=', 'noticia.usuario_id'))->
    select_many('noticia.id', 'titulo', 'texto', 'fecha_publicacion', 'rango_requerido', 'estado', 'piloto.nombre_completo')->
    order_by_desc('fecha_publicacion')->find_many();
}

/**
* Obtiene todas las noticias de la BBDD, con paginación.
* 
* Devuelve las noticias paginadas con los parámetros configurados en la aplicación.
*
* @category Noticias
* @example admin.php cargarNoticiasPaginacion(6, 2)
* @param integer $nNoticias Número de noticias por página.
* @param integer $pagina Página a mostrar.
*
* @return object
*/
function cargarNoticiasPaginacion($nNoticias = 5, $pagina = 0) {
    return ORM::for_Table('noticia')->
    join('piloto', array('piloto.id', '=', 'noticia.usuario_id'))->
    select_many('noticia.id', 'titulo', 'texto', 'fecha_publicacion', 'rango_requerido', 'estado', 'piloto.nombre_completo')->
    order_by_desc('fecha_publicacion')->limit($nNoticias)->offset($pagina * $nNoticias)->find_many();
}

/**
* Calcula el número de páginas para las noticias
* 
* Devuelve el número de páginas en el que serán divididas las noticias según
* la páginación asignada por el administrador
*
* @category Noticias
* @example admin.php cargarNnoticias(7)
* @param integer $porPagina Número de noticias por página
*
* @return integer
*/
function cargarNnoticias($porPagina) {
    $total = ORM::for_Table('noticia')->count();
    return round($total / $porPagina, 0, PHP_ROUND_HALF_UP);
}

/**
* Obtiene una noticia determinada a traves de su ID
*
* @category Noticias
* @example admin.php cargarNoticia(6)
* @param integer $idNoticia ID de la noticia a obtener la información
*
* @return object
*/
function cargarNoticia($idNoticia) {
    return ORM::for_Table('noticia')->
    join('piloto', array('piloto.id', '=', 'noticia.usuario_id'))->
    select_many('noticia.id', 'titulo', 'texto', 'fecha_publicacion', 'rango_requerido', 'estado', 'piloto.nombre_completo')->
    where('id', $idNoticia)->find_one();
}

/**
* Creación de una nueva noticia
* 
* Recoge los parámetros de un formulario para procesarlos y crear una nueva noticia
*
* @category Noticias
* @example admin.php crearNoticia('Bienvenido a la web', 'Hoy se puede ver la documentación', '0', '1', '1')
* @param string $titulo Título de la noticia.
* @param string $texto Contenido, cuerpo de la noticia.
* @param integer $rango Permisos requeridos para visualizar la noticia.
* @param integer $estado 0 : 1 - No publicada o publicada
*
* @return @void
*/
function crearNoticia($titulo, $texto, $rango, $estado, $usuarioID) {
    $noticia = ORM::for_table('noticia')->create();
    $noticia->id = null;
    $noticia->titulo = $titulo;
    $noticia->texto = $texto;
    $noticia->fecha_publicacion = date("Y-n-d H:i:s");
    $noticia->rango_requerido = $rango;
    $noticia->estado = $estado;
    $noticia->usuario_id = $_SESSION['id'];
    $noticia->save();
}

function editarNoticia($idNoticia, $titulo, $texto, $rango, $estado) {
    $noticia = ORM::for_table('noticia')->find_one($idNoticia);
    $noticia->titulo = $titulo;
    $noticia->texto = $texto;
    $noticia->fecha_publicacion = date("Y-n-d H:i:s");
    $noticia->rango_requerido = $rango;
    $noticia->estado = $estado;
    $noticia->save();
}

function eliminarNoticia($idNoticia) {
    $borrar = ORM::for_Table('noticia')->find_one($idNoticia);
    $borrar->delete();
}

/**
* Creación de una nueva categoría
* 
* Recoge los parámetros de un formulario para procesarlos y crear una nueva categoría
*
* @category Categorías
* @example admin.php crearCategoria('Formula 1', 24, 15)
* @param string $nombre Nombre de la categoría
* @param integer $plazas Plazas totales de la categoría
* @param integer $precio Coste de inscripción en la categoría
*
* @return @void
*/
function crearCategoria($app, $nombre, $plazas, $precio) {
    $categoria = ORM::for_table('categoria')->create();
    $categoria->id = null;
    $categoria->imagen = "images/defecto.jpeg";
    $categoria->nombre = $nombre;
    $categoria->plazas = $plazas;
    $categoria->precio_inscripcion = $precio;
    $categoria->save();
}

/**
* Edición de una categoría
* 
* Recoge los parámetros de un formulario para procesarlos y editar una categoría determinada
*
* @category Categorías
* @example admin.php editarCategoria(1, 'Nueva formula 1', 24, 20)
* @param integer $idCat ID de la categoría
* @param string $nombre Nuevo nombre de la categoría
* @param integer $plazas Nuevas plazas totales de la categoría
* @param integer $precio Nuevo coste de inscripción en la categoría
*
* @return @void
*/
function editarCategoria($app, $idCat, $nombre, $plazas, $precio) {
    $categoria = ORM::for_table('categoria')->where('id', $idCat)->find_one();
    $categoria->nombre = $nombre;
    $categoria->plazas = $plazas;
    $categoria->precio_inscripcion = $precio;
    $categoria->save();
}

/**
* Borrado de una categoría
* 
* Recoge el identificador de la categoría y la borra.
*
* @category Categorías
* @example admin.php eliminarCategoria(9)
* @param integer $idCat ID de la categoría
*
* @return @void
*/
function eliminarCategoria($idCat) {
    $categoria = ORM::for_table('categoria')->find_one($idCat);
    $categoria->delete();
}

/**
* Creación de una carrera
* 
* Recoge los parámetros de un formulario para procesarlos y crear una carrera
*
* @category Carreras
* @example admin.php crearCarrera(1, 'Nueva formula 1', 24, 20)
* @param integer $idCat ID de la categoría
* @param string $nombre Nuevo nombre de la categoría
* @param integer $plazas Nuevas plazas totales de la categoría
* @param integer $precio Nuevo coste de inscripción en la categoría
*
* @return @void
*/
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

/**
* Borrado de una carrera
* 
* Recoge el identificador de la carrera y la borra.
*
* @category Carreras
* @example admin.php eliminarCarrera(9)
* @param integer $idRace ID de la carrera
*
* @return @void
*/
function eliminarCarrera($app, $idRace) {
    $categoria = ORM::for_table('carrera')->where('id', $idRace)->find_one();
    $categoria->delete();
}

/**
* Creación de un circuito
* 
* Recoge los parámetros de un formulario para procesarlos y crear un circuito
*
* @category Circuitos
* @example admin.php crearCircuito('Circuito de Montmelo', 'España', 4627)
* @param string $nombre Nombre del circuito
* @param string $pais País donde se encuentra del circuito
* @param integer $distancia Distancia en KilóMetros del circuito
*
* @return @void
*/
function crearCircuito($app, $nombre, $pais, $distancia) {
    $circuito = ORM::for_table('circuito')->create();
    $circuito->id = null;
    $circuito->nombre = $nombre;
    $circuito->pais = $pais;
    $circuito->distancia = $distancia;
    $circuito->save();
}

/**
* Controla la asistencia y sanciones de los pilotos
*
* @category Asistencias
* @example admin.php controlAsistencia(1, 2, 1)
* @param integer $piloto_id ID del piloto
* @param integer $carrera_id ID de la carrera
* @param integer $estado Estado del piloto para la carrera (Asiste, no justifica...)
*
* @return @void
*/
function controlAsistencia($piloto_id, $carrera_id, $estado) {
    if ($estado != 0) {
        $asistencia = ORM::for_table('piloto_carrera')->
        where('piloto_id', $piloto_id)->
        where('carrera_id', $carrera_id)->
        find_one();

        if ($asistencia) {
            $asistencia->estado = $estado;
        } else {
            $asistencia = ORM::for_table('piloto_carrera')->create();
            $asistencia->id = null;
            $asistencia->piloto_id = $piloto_id;
            $asistencia->carrera_id = $carrera_id;
            $asistencia->estado = $estado;
        }

        $asistencia->save();
    }
}

/**
* Guarga la asistencia
*
* @category Asistencias
* @example admin.php guardarAsistencias(this)
* @param object $formulario Formulario que contiene todos los datos de asistencia
*
* @return @void
*/
function guardarAsistencias($formulario) {
    $npilotos = ORM::for_table('piloto')->max('id');
    $ncarreras = ORM::for_table('carrera')->max('id');

    for ($i=1 ; $i <= $npilotos; $i++) {
        for ($j=1 ; $j <= $ncarreras; $j++) {
            $cadena = $i . "-" . $j;
            
            if (isset($_POST[$cadena])) {
                controlAsistencia($i, $j, $_POST[$cadena]);
            }
        }
        $j = 0;
    }
}

/**
* Asignación de categorías a pilotos
* 
* Asigna de forma recursiva las categorías seleccionadas al piloto seleccionado
*
* @category Categorías
* @example admin.php asignarCategorias(3, [3, 5, 7])
* @param integer $idUser ID del piloto
* @param Array $categorias Categorías
*
* @return @void
*/
function asignarCategorias($idUser, $categorias) {
    for ($i=0; $i < count($categorias); $i++) { 
        $catPiloto = ORM::for_table('piloto_categoria')->create();
        $catPiloto->id = null;
        $catPiloto->categoria_id = $categorias[$i];
        $catPiloto->piloto_id = $idUser;
        $catPiloto->save();
    }
}