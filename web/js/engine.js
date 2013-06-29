
var Engine = function(results, socket) {
	try {
		this.socket = socket;
		this.results = results;
		this.connected = false;
		var eng = this;
		this.socket.onopen = function(e) {
			eng.connected = true;
		};
		this.socket.onerror = function (error) {
			Engine.message(error);
		};
		this.socket.onclose = function (error) {
			eng.connected = false;
			Engine.message('Connection to server lost');
		};			
		this.socket.onmessage = function(e) {
			results.draw(e.data);
		};
	} catch (e) {
		Engine.message(e);
	}
}

Engine.prototype = {
	send: function(method, values) {
		Engine.start();
		this.socket.send(method + '(' + values.join(',') + ')');
		this.results.init(method);
	},

	callMethod: function(method)
	{
		eng = this;
		return function(values) {
			if (!eng.connected) {
				Engine.message('No connection to server. Try reloading the page.');
			} else if (Engine.running) {
				Engine.message('Operation in progress.');
			} else {
				eng.send(method, values);
			}
		}
	}
};

Engine.message = function(msg) {
	$('#message').text(msg).fadeIn('slow').delay(2000).fadeOut('slow');
}

Engine.start = function()
{
	Engine.running = true;
	$('#kill').show();
	Engine.spinner.spin($('#spinner').get(0));
}

Engine.stop = function()
{
	Engine.running = false;
	$('#kill').hide();
	Engine.spinner.stop();
}
