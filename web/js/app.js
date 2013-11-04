jQuery(document).ready(function ($) {
	var socket = new WebSocket('ws://' + location.host +  ':8142');
	var results = new Results();
	$('.numbers').keyboard({
		keys: [
			['1', '2', '3', '4', '5', '6', '7', '8', '9', '10'],
			['25', '50', '75', '100']
		],
		min: 6,
		max: 6,
		callback: (new Engine(results, socket)).callMethod('numbers')
	});
	$('.letters').keyboard({
		keys: [
			['Q', 'W', 'E', 'R', 'T', 'Y', 'U', 'I', 'O', 'P'],
			['A', 'S', 'D', 'F', 'G', 'H', 'J', 'K', 'L'],
			['Z', 'X', 'C', 'V', 'B', 'N', 'M']
		],
		min: 5,
		max: 9,
		callback: (new Engine(results, socket)).callMethod('letters')
	});

	$('#kill').click(function(e) {
		e.preventDefault();
		socket.send('KILL');
		Engine.stop();
	});
	Engine.spinner = new Spinner();

});
