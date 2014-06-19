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
        $app->redirect($app->urlFor('princial'));
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
    $configuracion = datosApp();
    $carrera = cargarCarrera();
    $noticias = cargarNoticiasPaginacion(4,0);
    $nNoticias = cargarNnoticias(4);
    $categorias = cargarCategorias();
    $reclamaciones = cargarReclamacionesRecientes();

    if(!isset($_SESSION['id'])) {
        if (isset($_COOKIE['nombre'])) {
            $nombreGuardado = $_COOKIE['nombre'];
            $app->render('principal.html.twig', array('carrera' => $carrera, 'noticias' => $noticias, 'categorias' => $categorias, 'nNoticias' => $nNoticias, 'pagina' => 1, 'reclamaciones' => $reclamaciones, 'nombreGuardado' => $nombreGuardado, 'configuracion' => $configuracion));
        } else {
            $app->render('principal.html.twig', array('carrera' => $carrera, 'noticias' => $noticias, 'categorias' => $categorias, 'nNoticias' => $nNoticias, 'pagina' => 1, 'reclamaciones' => $reclamaciones, 'configuracion' => $configuracion));
        }
    } else {
        $app->render('principal.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol'], 'carrera' => $carrera, 'noticias' => $noticias, 'categorias' => $categorias, 'nNoticias' => $nNoticias, 'pagina' => 1, 'reclamaciones' => $reclamaciones, 'configuracion' => $configuracion));
    }
    
    if(registrarUsuario($app, $_POST['inputUsuario'], $_POST['inputEmail'], $_POST['inputPassword'], $_POST['inputPassword2'], $_POST['inputNombreCompleto'])) {
        echo "<script>alertify.alert('Registro completado con éxito, ahora puede acceder a su cuenta.');</script>";    
    } else {
        echo "<script>alertify.alert('Ocurrio un error durante el registro, por favor intentelo de nuevo.');</script>";
    }
    
})->name('registroUsuario');

$app->get('/perfil', function() use ($app) {
    if(!isset($_SESSION['id'])) {
        $app->redirect($app->urlFor('principal'));
    }

    $configuracion = datosApp();
    $usuario = datosUsuario($_SESSION['id']);

    $app->render('perfil.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol'], 'datosUsuario' => $usuario, 'configuracion' => $configuracion));
})->name('perfil');

$app->post('/perfil', function() use ($app) {
    if(!isset($_SESSION['id'])) {
        $app->redirect($app->urlFor('principal'));
    }

    $configuracion = datosApp();

    if (editarPerfil($_POST['inputNombreCompleto'], $_POST['visibilidad'])) {
        imagenPerfil($app, $_FILES['inputFoto']);
        $usuario = datosUsuario($_SESSION['id']);

        $_SESSION['nombre_completo'] = $_POST['inputNombreCompleto'];
        $_SESSION['avatar'] = $usuario['avatar'];

        $app->render('perfil.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol'], 'datosUsuario' => $usuario, 'configuracion' => $configuracion));
        echo "<script type='text/javascript'>alertify.success('Cambios guardados con éxito');</script>";
    } else {
        $usuario = datosUsuario($_SESSION['id']);
        $app->render('perfil.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol'], 'datosUsuario' => $usuario, 'configuracion' => $configuracion));
        echo "<script type='text/javascript'>alertify.alert('Ocurrio un error en el envio del formulario, por favor, revise los campos.');</script>";    
    }
})->name('cambiarAvatar');

$app->get('/perfil/:idUsuario', function($idUsuario) use ($app) {
    $configuracion = datosApp();
    $usuario = datosUsuario($idUsuario);
    $competidas = nCarreras($idUsuario, 0);
    $justificadas = nCarreras($idUsuario, 2);
    $injustificadas = nCarreras($idUsuario, 3);
    $sancionado = nCarreras($idUsuario, 4);
    $configuracion = datosApp();
    
    if(isset($_SESSION['id'])) {
        if($usuario['privacidad_perfil'] == 1 || esAdmin($_SESSION['id'])) {
            $app->render('perfilUsuario.html.twig', array('id' => $_SESSION['id'], 'usuario' => $_SESSION['nombre_completo'], 'avatar' => $_SESSION['avatar'], 'rol' => $_SESSION['rol'], 'user' => $usuario, 'competidas' => $competidas, 'justificadas' => $justificadas, 'injustificadas' => $injustificadas, 'sancionado' => $sancionado, 'configuracion' => $configuracion));
        } else {
            $app->render('perfilUsuario.html.twig', array('user' => $usuario, 'alert' => "El perfil de este usuario es privado", 'configuracion' => $configuracion));
        }
    } else {
        if($usuario['privacidad_perfil'] == 1) {
            $app->render('perfilUsuario.html.twig', array('user' => $usuario, 'competidas' => $competidas, 'justificadas' => $justificadas, 'injustificadas' => $injustificadas, 'sancionado' => $sancionado, 'configuracion' => $configuracion));
        } else {
            $app->render('perfilUsuario.html.twig', array('user' => $usuario, 'alert' => "El perfil de este usuario es privado", 'configuracion' => $configuracion));
        }
    }
})->name('perfilUsuario');

$app->get('/complete/:token', function($token) use ($app) {
    $configuracion = datosApp();
    $autenticar = autenticarNuevaCuenta($token);

    if ($autenticar) {
        $app->render('autenticarCuenta.html.twig', array('resultado' => 1, 'configuracion' => $configuracion));    
    } else {
        $app->render('autenticarCuenta.html.twig', array('configuracion' => $configuracion));
    }
})->name('autenticarCuenta');

/**
* Realiza el registro de usuarios en la BBDD
*
* @category Usuarios
* @example principal.php registrarUsuario('pepito', 'pepito@gmail.com', '123456', '123456', 'Pepito Pérez')
*
* @param string $usuario Nombre de usuario para identificarse
* @param string $email Correo eléctronico del usuario
* @param string $password Contraseña
* @param string $passwordCheck Contraseña para comprobación
* @param string $nombreCompleto Nombre completo del usuario
*
* @return boolean
*/
function registrarUsuario($app, $usuario, $email, $password, $passwordCheck, $nombreCompleto) {
    if ($password == $passwordCheck) {
        $token = generarToken(100);
        $user = ORM::for_table('piloto')->create();
        $user->id = null;
        $user->nombre = strtolower($usuario);
        $user->password = password_hash($password, PASSWORD_DEFAULT);
        $user->avatar = '/images/default.png';
        $user->escuderia = 'Ninguna';
        $user->nombre_completo = $nombreCompleto;
        $user->email = $email;
        $user->token = $token;
        $user->activo = 0;
        $user->rol = 1;
        $user->save();

        $mail = new PHPMailer;

        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.googlemail.com;smtp.googlemail.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'adrianpelopez@gmail.com';                 // SMTP username
        $mail->Password = 'lnhgctnevnljjock';                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable encryption, 'ssl' also accepted

        $mail->From = 'F1MallorcaSimRacing@f1m.com';
        $mail->FromName = 'F1Mallorca Sim Racing';
        $mail->addAddress($email, $nombreCompleto);     // Add a recipient
        $mail->addReplyTo('fiatotalcontrol@hotmail.es', 'FIA TOTAL CONTROL');

        $mail->WordWrap = 50;                                 // Set word wrap to 50 characters
        $mail->isHTML(true);                                  // Set email format to HTML

        $mail->Subject = 'Bienvenido a F1M';
        $mail->Body    = 'Gracias por registrarse en F1Mallorca, le recordamos que con el registro usted afirma haber leido los términos y condiciones de uso de la aplicación 
        y la liga.<hr/><br/>Para completar su registro debe acceder al siguiente enlace para autenticar su cuenta:<br/>http://' . $_SERVER['SERVER_NAME'] . '/complete/' . $token . '<hr/>Muchas gracias por su registro.<br/>';

        if(!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            echo 'Message has been sent';
        }

        return true;
    } else {
        return false;
    }
}

/**
* Cambia la imagen de perfil del usuario
*
* @category Usuarios
* @example principal.php imagenPerfil(archivo)
*
* @param file $imagen Imagen que el usuario carga a través de un formulario
*
* @return void
*/
function imagenPerfil($app, $imagen) {
    if ($_FILES['inputFoto']['error'] > 0) {
        //echo "error";
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

                    $usuario->avatar = "/" . $ruta;
                    $usuario->save();

                    $_SESSION['avatar'] = $ruta;
                } else {
                    echo "ERROR";
                }
        } else {
            //echo "Archivo no permitido";
        }
    }
}

function imgExtension($cadena) {
    $pos = stripos($cadena, '.');
    return substr($_FILES['inputFoto']['name'], $pos);
}

/**
* Genera un Token único para el usuario que será utilizado para recuperar su contraseña o cambiar información
*
* @category Usuarios
* @example principal.php generarToken(250)
*
* @param integer $logintud Longitud del token
*
* @return string
*/
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

/**
* Devuelve todo los datos del usuario indicado
*
* @category Usuarios
* @example principal.php datosUsuario(36)
*
* @param integer $id ID del usuario
*
* @return object
*/
function datosUsuario($id) {
    return ORM::for_table('piloto')->find_one($id);
}

function nCarreras($id, $estado) {
    if ($estado == 0) {
        return ORM::for_table('piloto_carrera')->
        where('piloto_id', $id)->
        where_not_equal('estado', 2)->
        where_not_equal('estado', 3)->
        count();
    }
    
    return ORM::for_table('piloto_carrera')->
    where('piloto_id', $id)->
    where('estado', $estado)->count();
}

function editarPerfil($nombre_completo, $visibilidad) {
    $usuario = ORM::for_table('piloto')->find_one($_SESSION['id']);

    if (texto($nombre_completo) && is_numeric($visibilidad) && entre($visibilidad, 1, 2)) {
        $usuario->nombre_completo = $nombre_completo;
        $usuario->privacidad_perfil = $visibilidad;
        $usuario->save();

        return true;
    } else {
        return false;
    }
}

function autenticarNuevaCuenta($tk) {
    $cuenta = ORM::for_table('piloto')->where('token', $tk)->find_one();
    if ($cuenta) {
        $cuenta->activo = 1;
        $cuenta->save();
        return true;
    } else {
        return false;
    }
}