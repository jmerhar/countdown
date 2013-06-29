;(function ($, window, document, undefined) {

	var pluginName = "keyboard",
		defaults = {
			keys: [[]],
			min: 1,
			max: 0,
			animate: 500,
			callback: function(values) {}
		};

	function Plugin(element, options) {
		this.element = element;
		this.options = $.extend({}, defaults, options);
		this._defaults = defaults;
		this._name = pluginName;
		this.init();
	}

	Plugin.prototype = {

		init: function() {
			var el = $(this.element);
			var nr_keys = 0;
			for (var i = 0; i < this.options.keys.length; i++) nr_keys += this.options.keys[i].length;
			var time = nr_keys ? (this.options.animate / nr_keys) : 0;
			$.each(this.options.keys, function(nr, row) {
				var p = $('<p class="keys row-' + (nr + 1) + '">').appendTo(el);
				$.each(row, function(index, value) {
					var picker = $('<a href="#" class="small button">' + value + '</a>').hide().appendTo(p)
					el.queue('pickers', function(){
						picker.show(time, function() { el.dequeue('pickers'); });
					});
				});
			});
			this.pickers = el.find('.button');
			var clear = $('<a href="#" class="small alert button">Clear</a>').hide()
				.appendTo(el.children('.keys').last()).click(this, this.clearClick);
			el.queue('pickers', function(){
				clear.show(time, function() { el.dequeue('pickers'); });
			}).dequeue('pickers');
			el.append('<a href="#" class="small alert button go">GO</a><p class="picked"></p>');
			this.go = el.find('.go').hide().click(this, this.goClick);
			this.pickers.click(this, this.pickerClick);
			el.find('.picked').on('click', '.button', this, this.pickedClick);
		},

		pickerClick: function(e) {
			e.preventDefault();
			if ($(this).hasClass('disabled')) return;
			var el = e.data.element;
			var picked = $(el).find('.picked');
			$('<input type="button" class="small success button" value="' + $(this).text() + '">').hide()
				.appendTo(picked).show('fast');
			var nr = picked.children().length;
			if ((e.data.options.max > 0) && (nr >= e.data.options.max)) e.data.pickers.addClass('disabled');
			if (nr >= e.data.options.min) e.data.go.show('fast');
		},

		clearClick: function(e) {
			e.preventDefault();
			$(e.data.element).find('.picked>*').hide('fast', function() { $(this).remove() });
			e.data.pickers.removeClass('disabled');
			e.data.go.hide('fast');
		},

		pickedClick: function(e) {
			e.preventDefault();
			$(this).hide('fast', function() { $(this).remove() });
			e.data.pickers.removeClass('disabled');
			if ($(e.data.element).find('.picked>*').length <= e.data.options.min) e.data.go.hide('fast');
		},

		goClick: function(e) {
			e.preventDefault();
			var values = [];
			valid = true;
			$(e.data.element).find('input').each(function() {
				var val = $(this).val();
				if (!val) valid = false;
				values.push(val);
			});
			if (valid) {
				e.data.options.callback(values);
			} else {
				Engine.message('Please enter a target value');
			}
		}
	};

	$.fn[pluginName] = function (options) {
		return this.each(function () {
			if (!$.data(this, "plugin_" + pluginName)) {
				$.data(this, "plugin_" + pluginName, new Plugin(this, options));
			}
		});
	};

})(jQuery, window, document);
