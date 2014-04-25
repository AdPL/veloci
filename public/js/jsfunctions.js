function generarCalendario() {
	var dias = new Array('L', 'M', 'X', 'J', 'V', 'S', 'D');
	var fecha = new Date();
	var hoy = fecha.getDate();
	fecha.setDate(1);
	var diaUno = fecha.getDay();
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

	while (j <= 31) {
		if (i == 7) {
			cadena += "</tr><tr>";
			i = 0;
		};
		
		if(j == hoy)
			cadena += "<td class='info' aling='right'>" + j + "</td>";
		else
			cadena += "<td align='right'>" + j + "</td>";

		j++;
		i++;
	}
	cadena += "</table>"

	document.getElementById('calendario').innerHTML = cadena;
}