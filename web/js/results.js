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
		var answer = item.infix;
		if (item.delta > 0) answer = '[Δ = ' + item.delta + '] ' + answer;
		var p = $('<p>')
			.text(answer)
			.data('sort', item.sort)
//			.prepend($('<span>').addClass('right').text(item.postfix))
			.addClass((item.delta > 0) ? 'approx' : 'exact');
		var content = $('#results .content');
		var el = content.children().first();
		while ((el.length) && (el.data('sort') < item.sort)) el = el.next();
		if (el.length) {
			el.before(p);
		} else {
			content.append(p);
		}
		if (item.delta == 0) content.find('.approx').slideUp('slow');
		$('html,body').scrollTop($('#results').offset().top);
	},

	drawEnd: function(item)
	{
		Engine.stop();
		Engine.message('Found ' + item.answers + ' answer(s) in ' + item.time + ' second(s)');
	}
};
