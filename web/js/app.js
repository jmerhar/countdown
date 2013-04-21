jQuery(document).ready(function ($) {
	$('.numbers').keyboard({
		keys: [
			['1', '2', '3', '4', '5', '6', '7', '8', '9', '10'],
			['25', '50', '75', '100']
		],
		max: 6
	});
	$('.letters').keyboard({
		keys: [
			['Q', 'W', 'E', 'R', 'T', 'Y', 'U', 'I', 'O', 'P'],
			['A', 'S', 'D', 'F', 'G', 'H', 'J', 'K', 'L'],
			['Z', 'X', 'C', 'V', 'B', 'N', 'M']
		],
		max: 9
	});
});
