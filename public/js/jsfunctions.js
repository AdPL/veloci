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

$('#registerModal').click( function() {
	$('#registerModal').modal();
});

$('#loginModal').click( function() {
	$('#loginModal').modal();
});

function generarCalendario(dia, mes) {
	var request;
    request = $.ajax({
        url: "/calendar.php",
        type: "GET",
        //data: {val: campo.value}
    });

    request.done(function(response, textStatus, jqXHR) {
		carreras = JSON.parse(response);
		dia = new Array();
		mes = new Array();
		for (var i = 0; i < carreras.length; i++) {
			arreglo = carreras[i]['fecha'].substr(5,10);
			dia[i] = arreglo[3] + "" + arreglo[4];
			mes[i] = arreglo[0] + "" + arreglo[1];
			console.log(carreras[i]['nombre'] + " dia " + dia[i] + " mes " + mes[i]);
		};
		return [
			dia, mes
		];
    });
	var fecha = new Date();
	var dias = new Array('L', 'M', 'X', 'J', 'V', 'S', 'D');
	var diasMes = new Array('31', '28', '31', '30', '31', '30', '31', '31', '30', '31', '30', '31');

	if (dia != null) {
		fecha.setDate(dia);
		hoy = dia;
	} else {
		var hoy = fecha.getDate();
	}

	if (mes != null) {
		mes--;
		fecha.setMonth(mes);
	} else {
		mes = fecha.getMonth();
	}

	// Localizo el día del comienzo del mes
	fecha.setDate(1);
	var diaUno = fecha.getDay();

	// Inicializo variables
	var i = 1;
	var j = 1;
	var cadena = "";

	cadena = "<table class='table table-bordered'><tr>"
	while (i <= 7) {
		cadena += "<th style='text-align: center !important;'>" + dias[i-1] + "</th>";
		i++;
	}
	cadena +="</tr><tr>";

	i = 0;
	while(i < diaUno-1) {
		cadena += "<td></td>";
		i++;
	}

	while (j <= diasMes[mes]) {
		if (i == 7) {
			cadena += "</tr><tr>";
			i = 0;
		};
		console.log(j + " vs " + dia[i]);
		if(j == dia[i])
			cadena += "<td class='info' align='right' data-container='body' data-placement='top' data-toggle='popover' title='Día " + j + " de marzo' data-content='20:00  -  Gran Premio de Montmelo<br/>23:00  -  Gran Premio de China'><a href='#'>" + j + "</a></td>";
		else
			cadena += "<td align='right'>" + j + "</td>";

		j++;
		i++;
	}

	while (i < 7) {
		cadena += "<td></td>";
		i++;
	}

	cadena += "</table>"

	document.getElementById('calendario').innerHTML = cadena;
}

function compruebaNombre(campo) {
    var request;
    request = $.ajax({
        url: "/ajax.php",
        type: "GET",
        data: {val: campo.value}
    });

    request.done(function(response, textStatus, jqXHR)
    {
        document.getElementById(campo.name).className = response;
        if (response == "form-group has-error") {
        	document.getElementById('errorUsuario').innerHTML = "Nombre de usuario ya registrado.";
        } else {
        	document.getElementById('errorUsuario').innerHTML = "";
        };
    });
}

function seleccionado(ruta, clase) {
	var radios = document.getElementsByClassName(clase);
	var botones = document.getElementsByClassName('btn-action');
	var error = true;
	
	for (var i = 0; i < radios.length; i++) {
		if (radios[i].checked) {
			for (var j = 0; j <= botones.length; j++) {
				var boton = botones[j];
				var fin = ruta.lastIndexOf("/");
				var enlace = ruta.substr(0, fin) + "/" + radios[i].value;
				boton.href = enlace;
			};
			error = false;
		};
	};

	if (error = true) {
		alert("Debe seleccionar un registro para continuar");
	}
}

function seleccion(radio) {
	var co = document.getElementById("co");
	co.value = radio.value;
}

function responder(idComentario, usuario) {
	document.getElementById('tcomentarios').innerHTML = "<i class='fa fa-pencil-square-o fa-3x'></i> Responder a " + usuario
	+ "<button class='btn btn-danger pull-right btn-sm' onclick='cancelaRespuesta()'>Cancelar respuesta</button>";
	document.getElementById('responde').value = idComentario;
}

function cancelaRespuesta() {
	document.getElementById('tcomentarios').innerHTML = "<i class='fa fa-comment fa-3x'></i> Nuevo comentario</h4>";
	document.getElementById('responde').value = 0;
}

function cambiarRuta(ruta) {
	var formulario = document.getElementById('v');
	formulario.action = ruta;
	formulario.submit();
}

function editarAsistencia(boton, piloto, carrera) {
	boton.parentNode.innerHTML = "<select name=" + piloto + "-" + carrera + ">" +
	"<option value='0'>Selección</option>" +
	"<option value='1'>Asiste</option>" +
	"<option value='2'>Justifica falta</option>" +
	"<option value='3'>Falta sin justificar</option>" +
	"<option value='4'>Sanción</option></select>";
}

function validarFormulario(myform) {
	var elementos = myform.elements.length;
	for (var i = 0; i <= elementos; i++) {
		console.log(myform.elements[i]);
	};

	return false;
}

function validar(campo, c_error, error, tipo) {
	var ayuda = document.getElementById(c_error);

	switch(tipo) {
		case 1: // Nombre de usuario 4 caracteres o más
			if (campo.value.length <= 3) {
				document.getElementById('inputUsuario').className = "form-group has-error";
				ayuda.innerHTML = error;
			} else {
				document.getElementById('inputUsuario').className = "form-group has-success";
			};
		break;

		case 2: // Que las 2 contraseñas se correspondan en el formulario y cumplan el patrón
			if (campo.value.length <= 5) {
				document.getElementById('inputPassword1').className = "form-group has-error";
				ayuda.innerHTML = error;
			} else {
				document.getElementById('inputPassword1').className = "form-group has-success";
				ayuda.innerHTML = "";
			};
		break;

		case 3: // Comprobación de que el campo2 de contraseña coincide con el primero
			var pass1 = document.getElementById('pass1').value;
			console.log(campo.value + " = " + pass1);
			if (campo.value == pass1) {
				document.getElementById('inputPassword2').className = "form-group has-success";
				ayuda.innerHTML = "Las contraseñas coinciden";
			} else {
				document.getElementById('inputPassword2').className = "form-group has-error";
				ayuda.innerHTML = error;
			};
		break;
	}

	return false;
}

function soloTexto(campo) {
	var patron = /[A-z]10/;
	if( campo.value.match(patron) ) {
		alert("bien");
  		return false;
	}
}

function soloTexto(e) {
	tecla = (document.all) ? e.keyCode : e.which; 
	if (tecla == 8) return true;
    if (e.ctrlKey && tecla == 86) { return true;}
    if (e.ctrlKey && tecla == 67) { return true;}
    if (e.ctrlKey && tecla == 88) { return true;}

    patron = /[a-zA-Z]/; //patron

    te = String.fromCharCode(tecla); 
    return patron.test(te);
}