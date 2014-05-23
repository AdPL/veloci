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
    });
}

function seleccionar(idUsuario) {
	var botones = document.getElementsByClassName('btn-action');
	var nBotones = botones.length;

	for (var i = 0; i < nBotones; i++) {
		var boton = botones[i];
		var fin = boton.href.lastIndexOf("/");
		var enlace = boton.href.substr(0, fin) + "/" + idUsuario;
		boton.href = enlace;
	}
}

function seleccionado(ruta) {
	alert(ruta.indexOf(':idUser'));
	if (ruta.indexOf(':idUser')) {
		document.stop();
	};
}