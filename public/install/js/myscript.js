$(function() {
	$('document').ready(function() {
		maximaCarga = $('.completo').innerWidth()-13;
	})

	$( "#tabs" ).tabs();
	
	$('.ustedaqui').popup({
		on: 'hover'
	});

	$('.completo').popup({
		on: 'hover'
	});

	$('#i1').click( function() {
		$('#i2').removeClass("active");
		$('#i3').removeClass("active");
		$('#i4').removeClass("active");
		$('#i1').addClass("active");
	});
	
	$('#i2').click( function() {
		$('#i1').removeClass("active");
		$('#i3').removeClass("active");
		$('#i4').removeClass("active");
		$('#i2').addClass("active");
	});
	
	$('#i3').click( function() {
		$('#i2').removeClass("active");
		$('#i1').removeClass("active");
		$('#i4').removeClass("active");
		$('#i3').addClass("active");
	});
	
	$('#i4').click( function() {
		$('#i2').removeClass("active");
		$('#i3').removeClass("active");
		$('#i1').removeClass("active");
		$('#i4').addClass("active");
	});

	$('input').focus( function() {
		$(this).addClass('iselected');
	})

	$('input').blur( function() {
		var progreso = $('#progreso').width();
		var valor = $('.iselected').val();
		$('.iselected').removeClass('iselected');
		if (valor != "" && !$(this).hasClass('iready') && progreso < maximaCarga) {
			$('#progreso').width('+=500');
			$(this).addClass('iready');
			var progreso = $('#progreso').width();
			if (progreso > maximaCarga) {
				$('#progreso').width(maximaCarga);
			};
		};
	})
});