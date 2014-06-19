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
    if(isset($_SESSION['id'])) {
        if(esAdmin($_SESSION['id'])) {
            $usuarios = cargarUsuarios();
            $categorias = cargarCategorias();
            $circuitos = cargarCircuitos();
            $carreras = cargarCarreras();
            $notificacionesNoLeidas = notificacionesNoLeidas();

            $app->render('admin.html.twig', array('usuarios' => $usuarios, 'id' => $_SESSION['id'], 'categorias' => $categorias, 'circuitos' => $circuitos, 'carreras' => $carreras, 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol'], 'notificacionesNoLeidas' => $notificacionesNoLeidas));
        } else {
            $app->Redirect('/');
        }
    } else {
        $app->Redirect('/');
    }
})->name('admin');

$app->get('/categorias/nueva', function() use ($app) {
    $notificacionesNoLeidas = notificacionesNoLeidas();
    if(isset($_SESSION['id'])) {
        if(esAdmin($_SESSION['id'])) {
            $app->render('nuevaCategoria.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol'], 'notificacionesNoLeidas' => $notificacionesNoLeidas));
        } else {
            $app->Redirect('/');
        }
    } else {
        $app->Redirect('/');
    }
})->name('nuevaCategoria');

$app->post('/categorias/nueva', function() use ($app) {
    $notificacionesNoLeidas = notificacionesNoLeidas();
    if(isset($_SESSION['id'])) {
        if(esAdmin($_SESSION['id'])) {
            crearCategoria($app, $_POST['inputNombre'], $_POST['inputPlazas'], $_POST['inputPrecio']);
            $categorias = cargarCategorias();
            $app->render('listaCategorias.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol'], 'categorias' => $categorias, 'notificacionesNoLeidas' => $notificacionesNoLeidas));
        } else {
            $app->Redirect('/');
        }
    } else {
        $app->Redirect('/');
    }
})->name('crearCategoria');

$app->get('/categorias/lista', function() use ($app) {
    $notificacionesNoLeidas = notificacionesNoLeidas();
    if(isset($_SESSION['id'])) {
        if(esAdmin($_SESSION['id'])) {
            $categorias = cargarCategorias();
            $app->render('listaCategorias.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol'], 'categorias' => $categorias, 'notificacionesNoLeidas' => $notificacionesNoLeidas));
        } else {
            $app->Redirect('/');
        }
    } else {
        $app->Redirect('/');
    }
})->name('listaCategorias');

$app->get('/editar/:idCat', function($idCat) use ($app) {
    $notificacionesNoLeidas = notificacionesNoLeidas();
    if(isset($_SESSION['id'])) {
        if(esAdmin($_SESSION['id'])) {
            $categoria = cargarCategoria($idCat);

            $app->render('editarCategoria.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol'], 'categoria' => $categoria, 'notificacionesNoLeidas' => $notificacionesNoLeidas));
        } else {
            $app->Redirect('/');
        }
    } else {
        $app->Redirect('/');
    }
})->name('editarCategoria');

$app->post('/listaCategorias', function() use ($app) {
    if(isset($_SESSION['id'])) {
        if(esAdmin($_SESSION['id'])) {
            if (isset($_POST['inputNombre'])) {
                editarCategoria($app, $_POST['id'], $_POST['inputNombre'], $_POST['inputPlazas'], $_POST['inputPrecio']);
            } else {
                eliminarCategoria($app, $_POST['id']);
            }

            $app->Redirect('categorias/lista');
        } else {
            $app->Redirect('/');
        }
    } else {
        $app->Redirect('/');
    }
})->name('editarCategoriaPost');

$app->get('/carreras/nueva', function() use ($app) {
    $notificacionesNoLeidas = notificacionesNoLeidas();
    if(isset($_SESSION['id'])) {
        if(esAdmin($_SESSION['id'])) {
            $categorias = cargarCategorias();
            $circuitos = cargarCircuitos();
            $app->render('nuevaCarrera.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'circuitos' => $circuitos, 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol'], 'categorias' => $categorias, 'notificacionesNoLeidas' => $notificacionesNoLeidas));
        } else {
            $app->Redirect('/');
        }
    } else {
        $app->Redirect('/');
    }
})->name('nuevaCarrera');

$app->post('/carreras/nueva', function() use ($app) {
    if(isset($_SESSION['id'])) {
        if(esAdmin($_SESSION['id'])) {
            crearCarrera($app, $_POST['inputNombre'], $_POST['primerCompuesto'], $_POST['segundoCompuesto'], $_POST['inputVueltas'], $_POST['inputFecha'], $_POST['inputHora'], $_POST['inputCategoria'], $_POST['inputCircuito']);
            $app->Redirect('/carreras/lista');
        } else {
            $app->Redirect('/');
        }
    } else {
        $app->Redirect('/');
    }
})->name('crearCarrera');

$app->get('/carreras/lista', function() use ($app) {
    $notificacionesNoLeidas = notificacionesNoLeidas();
    if(isset($_SESSION['id'])) {
        if(esAdmin($_SESSION['id'])) {
            $carreras = cargarCarreras();
            $app->render('listaCarreras.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol'], 'carreras' => $carreras, 'notificacionesNoLeidas' => $notificacionesNoLeidas));
        } else {
            $app->Redirect('/');
        }
    } else {
        $app->Redirect('/');
    }
})->name('listaCarreras');

$app->get('/carrera/editar/:idCarrera', function($idCarrera) use ($app) {
    $notificacionesNoLeidas = notificacionesNoLeidas();
    if(isset($_SESSION['id'])) {
        if(esAdmin($_SESSION['id'])) {
            $carrera = cargarECarrera($idCarrera);
            $categorias = cargarCategorias();
            $circuitos = cargarCircuitos();

            $app->render('editarCarrera.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol'], 'carrera' => $carrera, 'categorias' => $categorias, 'circuitos' => $circuitos, 'notificacionesNoLeidas' => $notificacionesNoLeidas));
        } else {
            $app->Redirect('/');
        }
    } else {
        $app->Redirect('/');
    }
})->name('editarCarrera');

$app->post('/carrera/editar/e:idCarrera', function($idCarrera) use ($app) {
    $notificacionesNoLeidas = notificacionesNoLeidas();
    if(isset($_SESSION['id'])) {
        if(esAdmin($_SESSION['id'])) {
            editarCarrera($idCarrera, $_POST['inputNombre'], $_POST['primerCompuesto'], $_POST['segundoCompuesto'], $_POST['inputVueltas'], $_POST['inputFecha'], $_POST['inputHora'], $_POST['inputFLimite'], $_POST['inputCategoria'], $_POST['inputCircuito']);
            $carrera = cargarECarrera($idCarrera);
            $categorias = cargarCategorias();
            $circuitos = cargarCircuitos();

            $app->render('editarCarrera.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol'], 'carrera' => $carrera, 'categorias' => $categorias, 'circuitos' => $circuitos, 'notificacionesNoLeidas' => $notificacionesNoLeidas));
        } else {
            $app->Redirect('/');
        }
    } else {
        $app->Redirect('/');
    }
})->name('modificarCarrera');

$app->post('/carreras/lista', function() use ($app) {
    $notificacionesNoLeidas = notificacionesNoLeidas();
    if(isset($_SESSION['id'])) {
        if(esAdmin($_SESSION['id'])) {
                eliminarCarrera($_POST['co']);
                $carreras = cargarCarreras();
                $app->render('listaCarreras.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol'], 'carreras' => $carreras, 'notificacionesNoLeidas' => $notificacionesNoLeidas));
            } else {
                $app->Redirect('/');
        }
    } else {
        $app->Redirect('/');
    }
})->name('borrarCarrera');

$app->get('/circuitos/nuevo', function() use ($app) {
    $notificacionesNoLeidas = notificacionesNoLeidas();
    if(isset($_SESSION['id'])) {
        if(esAdmin($_SESSION['id'])) {
            $app->render('nuevoCircuito.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol'], 'notificacionesNoLeidas' => $notificacionesNoLeidas));
        } else {
            $app->Redirect('/');
        }
    } else {
        $app->Redirect('/');
    }
})->name('nuevoCircuito');

$app->post('/circuitos/nuevo', function() use ($app) {
    $notificacionesNoLeidas = notificacionesNoLeidas();
    if(isset($_SESSION['id'])) {
        if(esAdmin($_SESSION['id'])) {
            crearCircuito($app, $_POST['inputNombre'], $_POST['inputPais'], $_POST['inputDistancia']);
            $app->Redirect('/circuitos/lista');
        } else {
            $app->Redirect('/');
        }
    } else {
        $app->Redirect('/');
    }
})->name('crearCircuito');

$app->get('/circuitos/lista', function() use ($app) {
    $notificacionesNoLeidas = notificacionesNoLeidas();
    if(isset($_SESSION['id'])) {
        if(esAdmin($_SESSION['id'])) {
            $circuitos = cargarCircuitos();

            $app->render('listaCircuitos.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol'], 'circuitos' => $circuitos, 'notificacionesNoLeidas' => $notificacionesNoLeidas));
        } else {
            $app->Redirect('/');
        }
    } else {
        $app->Redirect('/');
    }
})->name('listaCircuitos');

$app->get('/circuito/editar/:idCircuito', function($idCircuito) use ($app) {
    $notificacionesNoLeidas = notificacionesNoLeidas();
    if(isset($_SESSION['id'])) {
        if(esAdmin($_SESSION['id'])) {
            $circuito = cargarCircuito($idCircuito);

            $app->render('editarCircuito.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol'], 'circuito' => $circuito, 'notificacionesNoLeidas' => $notificacionesNoLeidas));
        } else {
            $app->Redirect('/');
        }
    } else {
        $app->Redirect('/');
    }
})->name('editarCircuito');

$app->post('/circuitos/borrar', function() use ($app) {
    $notificacionesNoLeidas = notificacionesNoLeidas();
    if(isset($_SESSION['id'])) {
        if(esAdmin($_SESSION['id'])) {
                eliminarCircuito($_POST['co']);
                $circuitos = cargarCircuitos();
                $app->render('listaCircuitos.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol'], 'circuitos' => $circuitos, 'notificacionesNoLeidas' => $notificacionesNoLeidas));
            } else {
                $app->Redirect('/');
        }
    } else {
        $app->Redirect('/');
    }
})->name('borrarCircuito');

$app->post('/circuito', function() use ($app) {
    $notificacionesNoLeidas = notificacionesNoLeidas();
    if(isset($_SESSION['id'])) {
        if(esAdmin($_SESSION['id'])) {
            if (isset($_POST['inputNombre'])) {
                editarCircuito($_POST['id'], $_POST['inputNombre'], $_POST['inputPais'], $_POST['inputDistancia']);
                $app->Redirect('/circuitos/lista');
            } else {
                $app->Redirect('/');
            }
        }
    } else {
        $app->Redirect('/');
    }
})->name('editarCircuitoPost');

$app->get('/usuarios', function() use ($app) {
    $notificacionesNoLeidas = notificacionesNoLeidas();
    if(isset($_SESSION['id'])) {
        if(esAdmin($_SESSION['id'])) {
            $usuarios = cargarUsuarios();
            $app->render('listaUsuarios.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol'], 'usuarios' => $usuarios, 'notificacionesNoLeidas' => $notificacionesNoLeidas));
        } else {
            $app->Redirect('/');
        }
    } else {
        $app->Redirect('/');
    }
})->name('listaUsuarios');

$app->get('/asistencias/control', function() use ($app) {
    $notificacionesNoLeidas = notificacionesNoLeidas();
    if(isset($_SESSION['id'])) {
        if(esAdmin($_SESSION['id'])) {
            $carreras = cargarCarreras();
            $pilotos = cargarUsuariosOficiales();
            $estados = carreraControlAsistencia();
            $categorias = cargarCategorias();
            
            $app->render('controlAsistencia.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol'], 'carreras' => $carreras, 'pilotos' => $pilotos, 'estados' => $estados, 'notificacionesNoLeidas' => $notificacionesNoLeidas, 'categorias' => $categorias, 'tabla' => false));
        } else {
            $app->render('principal.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol']));
        }
    } else {
        $app->Redirect('/');
    }
})->name('controlAsistencia');

$app->get('/asistencias/control/:idCategoria', function($idCategoria) use ($app) {
    $notificacionesNoLeidas = notificacionesNoLeidas();
    if(isset($_SESSION['id'])) {
        if(esAdmin($_SESSION['id'])) {
            $carreras = cargarCarrerasCategoria($idCategoria);
            $pilotos = cargarUsuariosOficialesCategoria($idCategoria);
            $estados = carreraControlAsistencia();
            $categorias = cargarCategorias();
            
            $app->render('controlAsistencia.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol'], 'carreras' => $carreras, 'pilotos' => $pilotos, 'estados' => $estados, 'notificacionesNoLeidas' => $notificacionesNoLeidas, 'categorias' => $categorias, 'tabla' => true, 'c' => $idCategoria));
        } else {
            $app->render('principal.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol']));
        }
    } else {
        $app->Redirect('/');
    }
})->name('controlAsistenciasCategoria');

function carreraControlAsistencia() {
    return ORM::for_table('piloto_carrera')->find_many();
}

$app->post('/asistencias/control/:idCategoria', function($idCategoria) use ($app) {
    $notificacionesNoLeidas = notificacionesNoLeidas();
    if(isset($_SESSION['id'])) {
        if(esAdmin($_SESSION['id'])) {
            $carreras = cargarCarrerasCategoria($idCategoria);
            $pilotos = cargarUsuariosOficialesCategoria($idCategoria);
            $categorias = cargarCategorias();
            guardarAsistencias($_POST);
            $estados = carreraControlAsistencia();

            $app->render('controlAsistencia.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol'], 'carreras' => $carreras, 'pilotos' => $pilotos, 'categorias' => $categorias, 'estados' => $estados, 'notificacionesNoLeidas' => $notificacionesNoLeidas, 'c' => $idCategoria, 'tabla' => true));
        } else {
            $app->Redirect('/');
        }
    } else {
        
    }
})->name('controlAsistenciaGuardar');

$app->get('/usuarios/editar/:idUser', function($idUser) use ($app) {
    $notificacionesNoLeidas = notificacionesNoLeidas();
    if(isset($_SESSION['id'])) {
        if(esAdmin($_SESSION['id'])) {
            $piloto = cargarUsuario($idUser);
            $categorias = cargarCategorias();
            $participa = cargarCategoriasUsuario($idUser);

            $app->render('editarUsuario.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol'], 'piloto' => $piloto, 'categorias' => $categorias, 'participa' => $participa, 'notificacionesNoLeidas' => $notificacionesNoLeidas));
        } else {
            $app->Redirect('/');
        }
    } else {
        $app->Redirect('/');
    }
})->name('editarUsuario');

$app->post('/usuarios/editar/:idUser', function($idUser) use ($app) {
    $notificacionesNoLeidas = notificacionesNoLeidas();
    if(isset($_SESSION['id'])) {
        if(esAdmin($_SESSION['id'])) {
            if (isset($_POST['inputSubmit'])) {
                editarUsuario($idUser, $_POST['inputNombre'], $_POST['inputEmail'], $_POST['inputActivo']);
                if(isset($_POST['inputCategoriasA'])) {
                    asignarCategorias($idUser, $_POST['inputCategoriasA']); 
                }
                if(isset($_POST['inputCategoriasQ'])) {
                    quitarCategorias($idUser, $_POST['inputCategoriasQ']);
                }
            }
            
            $piloto = cargarUsuario($idUser);
            $categorias = cargarCategorias();
            $participa = cargarCategoriasUsuario($idUser);

            $app->render('editarUsuario.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol'], 'piloto' => $piloto, 'categorias' => $categorias, 'participa' => $participa, 'notificacionesNoLeidas' => $notificacionesNoLeidas));
        } else {
            $app->Redirect('/');
        }
    } else {
        $app->Redirect('/');
    }
})->name('editarUsuarioPost');

$app->post('/usuarios/editar/banear/:idUser', function($idUser) use ($app) {
    if(isset($_SESSION['id'])) {
        if(esAdmin($_SESSION['id'])) {
            if (isset($_POST['idUser']) && isset($_POST['banear'])) {
                banearUsuario($_POST['idUser']);
            }
            if (isset($_POST['idUser']) && isset($_POST['quitarbaneo'])) {
                desbanearUsuario($_POST['idUser']);
            }
            
            $piloto = cargarUsuario($idUser);
            $categorias = cargarCategorias();
            $participa = cargarCategoriasUsuario($idUser);

            $app->Redirect('/usuarios/editar/' . $idUser);
        } else {
            $app->Redirect('/');
        }
    } else {
        $app->Redirect('/');
    }
})->name('banearUsuario');

$app->post('/usuarios/borrar', function() use ($app) {
    $notificacionesNoLeidas = notificacionesNoLeidas();
    if(isset($_SESSION['id'])) {
        if(esAdmin($_SESSION['id'])) {
            if (isset($_POST['borralo'])) {
                eliminarUsuario($_POST['co']);
            } else {
                echo "<script type='text/javascript'>alertify.error('No tiene permiso para realizar esta acción');</script>";
            }

            $app->Redirect('/usuarios');
        } else {
            $app->Redirect('/');
        }
    } else {
        $app->Redirect('/');
    }
})->name('borrarUsuario');

$app->get('/noticias/lista', function() use ($app) {
    $notificacionesNoLeidas = notificacionesNoLeidas();
    if(isset($_SESSION['id'])) {
        if(esAdmin($_SESSION['id'])) {
            $noticias = cargarNoticias();
            $app->render('listaNoticias.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol'], 'noticias' => $noticias, 'notificacionesNoLeidas' => $notificacionesNoLeidas));
        } else {
            $app->Redirect('/');
        }
    } else {
        $app->Redirect('/');
    }
})->name('listaNoticias');

$app->get('/noticias/nueva', function() use ($app) {
    $notificacionesNoLeidas = notificacionesNoLeidas();
    if(isset($_SESSION['id'])) {
        if(esAdmin($_SESSION['id'])) {
            $noticias = cargarNoticias();
            $app->render('nuevaNoticia.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol'], 'noticias' => $noticias, 'notificacionesNoLeidas' => $notificacionesNoLeidas));
        } else {
            $app->Redirect('/');
        }
    } else {
        $app->Redirect('/');
    }
})->name('nuevaNoticia');

$app->post('/noticias/lista', function() use ($app) {
    $notificacionesNoLeidas = notificacionesNoLeidas();
    if(isset($_SESSION['id'])) {
        if(esAdmin($_SESSION['id'])) {
            crearNoticia($_POST['inputTitulo'], $_POST['inputTexto'], $_POST['inputRango'], $_POST['inputEstado'], $_SESSION['id']);
            $noticias = cargarNoticias();
            $app->render('listaNoticias.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol'], 'noticias' => $noticias, 'notificacionesNoLeidas' => $notificacionesNoLeidas));
        } else {
            $app->Redirect('/');
        }
    } else {
        $app->Redirect('/');
    }
})->name('crearNoticia');

$app->get('/noticias/editar/:idNoticia', function($idNoticia) use ($app) {
    $notificacionesNoLeidas = notificacionesNoLeidas();
    if(isset($_SESSION['id'])) {
        if(esAdmin($_SESSION['id'])) {
            $noticia = cargarNoticia($idNoticia);
            $app->render('editarNoticia.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol'], 'noticia' => $noticia, 'notificacionesNoLeidas' => $notificacionesNoLeidas));
        } else {
            $app->Redirect('/');
        }
    } else {
        $app->Redirect('/');
    }
})->name('editarNoticia');

$app->post('/noticias/editar', function() use ($app) {
    if(isset($_SESSION['id'])) {
        if(esAdmin($_SESSION['id'])) {
            editarNoticia($_POST['idNoticia'], $_POST['inputTitulo'], $_POST['inputTexto'], $_POST['inputRango'], $_POST['inputEstado']);
            $app->Redirect('/noticias/lista');
        } else {
            $app->Redirect('/');
        }
    } else {
        $app->Redirect('/');
    }
})->name('editarNoticiaPost');

$app->post('/noticias/lista/borrar', function() use ($app) {
    if(isset($_SESSION['id'])) {
        if(esAdmin($_SESSION['id'])) {
            if (isset($_POST['borralo'])) {
                eliminarNoticia($_POST['co']);
            } else {
                echo "<script type='text/javascript'>alertify.error('No tiene permiso para realizar esta acción');</script>";
            }

            $app->Redirect('/noticias/lista');
        } else {
            $app->Redirect('/');
        }
    } else {
        $app->Redirect('/');
    }
})->name('borrarNoticia');

$app->post('/categorias/borrar', function() use ($app) {
    if(isset($_SESSION['id'])) {
        if(esAdmin($_SESSION['id'])) {
            if (isset($_POST['borralo'])) {
                eliminarCategoria($_POST['co']);
            } else {
                echo "<script type='text/javascript'>alertify.error('No tiene permiso para realizar esta acción');</script>";
            }

            $app->Redirect('/categorias/lista');
        } else {
            $app->Redirect('/');
        }
    } else {
        $app->Redirect('/');
    }
})->name('borrarCategoria');

$app->get('/configuracion', function() use ($app) {
    $notificacionesNoLeidas = notificacionesNoLeidas();
    $configuracion = datosApp();

    if(isset($_SESSION['id'])) {
        if(esAdmin($_SESSION['id'])) {
            $app->render('configuracionDelSitio.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol'], 'configuracion' => $configuracion, 'notificacionesNoLeidas' => $notificacionesNoLeidas));
        } else {
            $app->Redirect('/');
        }
    } else {
        $app->Redirect('/');
    }
})->name('configuracionDelSitio');

$app->post('/configuracion/guardar', function() use ($app) {
    $notificacionesNoLeidas = notificacionesNoLeidas();
    actualizarConfiguracion($_POST['inputNombre'], $_POST['inputDescripcion'], $_POST['activacion'], $_POST['normativa'], $_POST['pago'],
        $_POST['inputTeamspeak'], $_POST['inputTwitter'], $_POST['inputFacebook'], $_POST['inputYoutube'], $_POST['inputVimeo'], /*$_POST['permite'],*/ $_POST['inputTema']);

    $configuracion = datosApp();
    
    if(isset($_SESSION['id'])) {
        if(esAdmin($_SESSION['id'])) {
            $app->render('configuracionDelSitio.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol'], 'configuracion' => $configuracion, 'notificacionesNoLeidas' => $notificacionesNoLeidas));
        } else {
            $app->Redirect('/');
        }
    } else {
        $app->Redirect('/');
    }
})->name('guardarConfiguracion');

$app->get('/notificaciones', function() use ($app) {
    if(isset($_SESSION['id'])) {
        if(esAdmin($_SESSION['id'])) {
            $notificacionesNoLeidas = notificacionesNoLeidas();
            $notificaciones = cargarNotificaciones();

            $app->render('notificaciones.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol'], 'notificacionesNoLeidas' => $notificacionesNoLeidas, 'notificaciones' => $notificaciones));
        } else {    
            $app->Redirect('/');
        }
    } else {
        $app->Redirect('/');
    }
})->name('notificaciones');

function datosApp() {
    return ORM::for_Table('configuracion')->find_one(1);
}

function actualizarConfiguracion($titulo, $slogan, $activacion, $normativa, $pago, $teamspeak, $twitter, $facebook, $youtube, $vimeo, /*$permite,*/ $tema) {
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
    $datos->permite_plantillas = 0;
    if ($tema != "bootstrap") {
        $datos->plantilla_defecto = $tema . "_bootstrap.css";
    } else {
        $datos->plantilla_defecto = "bootstrap.css";
    }
    
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
    select_many('piloto.id', 'email', 'avatar', 'nombre_completo', 'escuderia', 'activo', 'rol', 'expulsado')->find_one($idUser);
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
    return ORM::for_table('piloto_categoria')->
    join('categoria', array('categoria.id', '=', 'piloto_categoria.categoria_id'))->
    where('piloto_id', $idUser)->find_many();
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
    return ORM::for_table('categoria')->raw_query('select nombre, imagen, plazas, (plazas-count(*)) as plazas_libres, precio_inscripcion from categoria join piloto_categoria ON categoria.id = piloto_categoria.categoria_id group by nombre')->find_many();
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
    join('piloto', array('piloto.id', '=', 'noticia.piloto_id'))->
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
    join('piloto', array('piloto.id', '=', 'noticia.piloto_id'))->
    select_many('noticia.id', 'titulo', 'texto', 'fecha_publicacion', 'rango_requerido', 'estado', 'piloto.nombre_completo', 'n_comentarios')->
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
    join('piloto', array('piloto.id', '=', 'noticia.piloto_id'))->
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
    $noticia->piloto_id = $_SESSION['id'];
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
    $categoria->imagen = '/images/defecto.jpg';
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

function cargarECarrera($idCarrera) {
    return ORM::for_table('carrera')->find_one($idCarrera);
}

function editarCarrera($idCarrera, $nombre, $n1, $n2, $vueltas, $fecha, $hora, $fecha_limite, $categoria, $circuito) {
    $carrera = ORM::for_table('carrera')->find_one($idCarrera);
    $carrera->nombre = $nombre;
    $carrera->neumatico1 = $n1;
    $carrera->neumatico2 = $n2;
    $carrera->vueltas = $vueltas;
    $carrera->fecha = $fecha;
    $carrera->hora = $hora;
    $carrera->fecha_limite = $fecha_limite;
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
function eliminarCarrera($idRace) {
    $carrera = ORM::for_table('carrera')->find_one($idRace);
    $carrera->delete();
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

function eliminarCircuito($idCircuito) {
    $circuito = ORM::for_table('circuito')->find_one($idCircuito);
    $circuito->delete();
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
        $catPiloto = ORM::for_table('piloto_categoria')->
        where('piloto_id', $idUser)->
        where('categoria_id', $categorias[$i])->find_one();
        if (!$catPiloto) {
            $catPiloto = ORM::for_table('piloto_categoria')->create();
            $catPiloto->id = null;
            $catPiloto->categoria_id = $categorias[$i];
            $catPiloto->piloto_id = $idUser;
            $catPiloto->save();
        }
    }
}

function quitarCategorias($idUser, $categorias) {
    for ($i=0; $i < count($categorias); $i++) { 
        $catPiloto = ORM::for_table('piloto_categoria')->
        where('piloto_id', $idUser)->
        where('categoria_id', $categorias[$i])->find_one();
        $catPiloto->delete();
    }
}

function notificacionesNoLeidas() {
    return ORM::for_table('notificacion')->where('leida', 0)->count();
}

function cargarNotificaciones() {
    return ORM::for_table('notificacion')->
    where('rango_requerido', 5)->
    where('objetivo', 0)->
    order_by_desc('fecha')->
    find_many();
}

/*function agregarRecurso($imagen, $idCategoria) {
    $maximo = ORM::for_table('categoria')->MAX('id');
    $maximo++;
    if ($_FILES['inputRecurso']['error'] > 0) {
        //echo "error";
    } else {
        $ok = array("image/jpg", "image/jpeg", "image/gif", "image/png");
        $limite_kb = 100;
        
        $ext = imgExt($_FILES['inputRecurso']['name']);

        if (in_array($_FILES['inputRecurso']['type'], $ok) && $_FILES['inputRecurso']['size'] <= $limite_kb * 1024) {
            $ruta = "images/recursos/" . $maximo . $ext;
            
                $resultado = @move_uploaded_file($_FILES['inputRecurso']['tmp_name'], $ruta);
                if ($resultado) {
                    $recurso = ORM::for_table('recurso')->create();
                    $recurso->id = null;
                    $recurso->descripcion = "texto";
                    $recurso->imagen = $ruta;
                    $recurso->reclamacion_id = $idReclamacion;
                    $recurso->save();
                } else {
                    //echo "ERROR";
                }
        } else {
            //echo "Archivo no permitido";
        }
    }
}*/

function editarUsuario($idUsuario, $nombre_completo, $email, $activo) {
    $usuario = ORM::for_table('piloto')->find_one($idUsuario);
    $usuario->nombre_completo = $nombre_completo;
    $usuario->email = $email;
    $usuario->activo = $activo;
    $usuario->save();
}

function banearUsuario($idUsuario) {
    $usuario = ORM::for_table('piloto')->find_one($idUsuario);
    $usuario->activo = 0;
    $usuario->expulsado = 1;
    $usuario->save();
}

function desbanearUsuario($idUsuario) {
    $usuario = ORM::for_table('piloto')->find_one($idUsuario);
    $usuario->activo = 1;
    $usuario->expulsado = 0;
    $usuario->save();
}

function cargarUsuariosOficiales() {
    return ORM::for_table('piloto')->
    join('piloto_categoria', array('piloto.id', '=', 'piloto_categoria.piloto_id'))->
    select_many('piloto.id', 'piloto.nombre_completo')->distinct()->find_many();
}

function cargarUsuariosOficialesCategoria($idCategoria) {
    return ORM::for_table('piloto')->
    join('piloto_categoria', array('piloto.id', '=', 'piloto_categoria.piloto_id'))->
    where('piloto_categoria.categoria_id', $idCategoria)->
    select_many('piloto.id', 'piloto.nombre_completo')->distinct()->find_many();
}

function cargarCarrerasCategoria($idCategoria) {
    return ORM::for_table('carrera')->
    where('categoria_id', $idCategoria)->find_many();
}