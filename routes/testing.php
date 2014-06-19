<?php

function esAdmin($idUsuario) {
    $usuario = ORM::for_table('piloto')->find_one($idUsuario);
    if ($usuario['rol'] >= 4) {
        return true;
    } else {
        return false;
    }
}

function esPublicador($idUsuario) {
    $usuario = ORM::for_table('piloto')->find_one($idUsuario);
    if ($usuario['rol'] == 3) {
        return true;
    } else {
        return false;
    }
}

function esArbitro($idUsuario) {
    $usuario = ORM::for_table('piloto')->find_one($idUsuario);
    if ($usuario['rol'] == 2) {
        return true;
    } else {
        return false;
    }
}

function esRegistrado($idUsuario) {
    $usuario = ORM::for_table('piloto')->find_one($idUsuario);
    if ($usuario['rol'] == 1) {
        return true;
    } else {
        return false;
    }
}

function texto($cadena) {
	$patron = "/^[A-z áéíóúñ]+$/";
	$r =  preg_match($patron, $cadena) ? true : false;

	return $r;
}

function entre($v, $minimo, $maximo) {
	if ($minimo >= $v || $v <= $maximo) {
		return true;
	} else {
		return false;
	}
}

function longitudMinima($texto, $pMinimas) {
	$cuenta = count(explode(" ", $texto));
	
	if ($cuenta >= $pMinimas) {
		return true;
	} else {
		return false;
	}
}