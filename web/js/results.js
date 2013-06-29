var Results = function() {}

Results.prototype = {
	init: function(method)
	{
		if (method == 'letters') {
			$('#results').show().find('.content').html('<ul class="large-block-grid-4"><li></li><li></li><li></li><li></li></ul>');
		}
		if (method == 'numbers') {
			$('#results').show().find('.content').html('');
		}
	},

	draw: function(msg)
	{
		var item = $.parseJSON(msg);
		switch (item.type) {
			case 'end'     : this.drawEnd(item);     break;
			case 'letters' : this.drawLetters(item); break;
			case 'numbers' : this.drawNumbers(item); break;
		}
	},

	drawLetters: function(item)
	{
		var dist = [40, 60, 80, 100];
		var i = 0;
		while ((item.size >= dist[i]) && (i < dist.length)) i++;
		var meta = '[' + item.size + ',' + item.word.length + '] ';
		var p = $('<p>')
			.addClass('l' + item.word.length)
			.addClass('s' + item.size)
			.data('sort', item.sort)
			.text(meta + item.word + item.variant);
		var col = $('#results .content li').eq(i);
		var el = col.children().first();
		while ((el.length) && (el.data('sort') > item.sort)) el = el.next();
		if (el.length) {
			el.before(p);
		} else {
			col.append(p);
		}
		$('html,body').scrollTop($('#results').offset().top);
	},

	drawNumbers: function(item)
	{
		var text = item.index + ': ' + item.infix + ' (Δ = ' + item.delta + ')';
		$('<p>')
			.text(text)
			.prepend($('<span>').addClass('right').text(item.postfix))
			.addClass((item.delta > 0) ? 'approx' : 'exact')
			.prependTo('#results .content');
		$('html,body').scrollTop($('#results').offset().top);
	},

	drawEnd: function(item)
	{
		Engine.stop();
		Engine.message('Calculation took ' + item.time + ' second(s)');
	}
};
