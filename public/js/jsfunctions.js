$('#registerModal').click( function() {
	$('#registerModal').modal();
});

$('#loginModal').click( function() {
	$('#loginModal').modal();
});

function generarCalendario(dia, mes) {
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
		
		if(j == hoy)
			cadena += "<td class='info' aling='right' data-container='body' data-placement='top' data-toggle='popover' title='Día " + j + " de marzo' data-content='20:00  -  Gran Premio de Montmelo<br/>23:00  -  Gran Premio de China'><a href='#'>" + j + "</a></td>";
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

