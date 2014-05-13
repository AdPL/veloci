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

$app->post('/login', function() use ($app) {
    $acceso = testAccess($app, $_POST['inputUsuario'], $_POST['inputPassword']);

    if (!$acceso) {
        $app->redirect($app->urlFor('login'));
    }
    $app->render('principal.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol']));
})->name('accederLogin');

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
    $app->render('principal.html.twig', array('alertLogin' => 'Registro completado con éxito'));
})->name('registroUsuario');

$app->get('/perfil', function() use ($app) {
    if(!isset($_SESSION['id'])) {
        $app->redirect($app->urlFor('principal'));
    }

    $app->render('perfil.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol']));
})->name('perfil');

$app->post('/perfil', function() use ($app) {
    if(!isset($_SESSION['id'])) {
        $app->redirect($app->urlFor('principal'));
    }

    imagenPerfil($app, $_FILES['inputFoto']);

    $app->render('perfil.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol'], 'alert' => 'Cambios guardados con éxito'));
})->name('cambiarAvatar');

$app->get('/perfil/:idUsuario', function($idUsuario) use ($app) {
    $usuario = datosUsuario($idUsuario);
    $competidas = nCarreras($idUsuario, 0);
    $justificadas = nCarreras($idUsuario, 2);
    $injustificadas = nCarreras($idUsuario, 3);
    $sancionado = nCarreras($idUsuario, 4);

    if(!isset($_SESSION['id'])) {
        $app->redirect($app->urlFor('principal'));
    }

    $app->render('perfilUsuario.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol'], 'user' => $usuario, 'competidas' => $competidas, 'justificadas' => $justificadas, 'injustificadas' => $injustificadas, 'sancionado' => $sancionado));
})->name('perfilUsuario');

function registrarUsuario($app, $usuario, $email, $password, $passwordCheck, $nombreCompleto) {
    if ($password == $passwordCheck) {
        $token = generarToken(100);
        $user = ORM::for_table('piloto')->create();
        $user->id = null;
        $user->nombre = strtolower($usuario);;
        $user->password = password_hash($password, PASSWORD_DEFAULT);
        $user->avatar = 'images/default.png';
        $user->escuderia = 'Ninguna';
        $user->nombre_completo = $nombreCompleto;
        $user->email = $email;
        $user->token = $token;
        $user->activo = 0;
        $user->rol = 1;
        $user->save();

        return true;   
    } else {
        return false;
    }
}

function imagenPerfil($app, $imagen) {
    if ($_FILES['inputFoto']['error'] > 0) {
        echo "error";
    } else {
        $ok = array("image/jpg", "image/jpeg", "image/gif", "image/png");
        $limite_kb = 100;
        
        $ext = imgExtension($_FILES['inputFoto']['name']);

        if (in_array($_FILES['inputFoto']['type'], $ok) && $_FILES['inputFoto']['size'] <= $limite_kb * 1024) {
            $ruta = "images/" . $_SESSION['id'] . $ext;
            
                $resultado = @move_uploaded_file($_FILES['inputFoto']['tmp_name'], $ruta);
                if ($resultado) {
                    $usuario = ORM::for_table('piloto')->
                    where('id', $_SESSION['id'])->find_one();

                    $usuario->avatar = $ruta;
                    $usuario->save();

                    $_SESSION['avatar'] = $ruta;
                } else {
                    echo "ERROR";
                }
        } else {
            echo "Archivo no permitido";
        }
    }
}

function imgExtension($cadena) {
    $pos = stripos($cadena, '.');
    return substr($_FILES['inputFoto']['name'], $pos);
}

function generarToken($longitud) {
    $cadena = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $token = "";

    while($longitud > 0) {
        $num = mt_rand(1,62);
        $token .= $cadena[$num-1];
        $longitud--;
    }

    return $token;
}

function datosUsuario($id) {
    return ORM::for_table('piloto')->find_one($id);
}

function nCarreras($id, $estado) {
    if ($estado == 0) {
        return ORM::for_table('piloto_carrera')->where('piloto_id', $id)->count();
    }
    
    return ORM::for_table('piloto_carrera')->
    where('piloto_id', $id)->
    where('estado', $estado)->count();
}