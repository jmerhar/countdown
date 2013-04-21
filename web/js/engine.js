
var Engine = function(callback) {
	try {
		this.socket = new WebSocket('ws://' + location.host +  ':8080');
		this.socket.onopen = function(e) {
		};
		this.socket.onerror = function (error) {
			console.log(error);
		};
		this.socket.onclose = function (error) {
			console.log('closed');
		};			
		this.socket.onmessage = function(e) {
			callback(e.data);
		};
	} catch (e) {
		console.log(e);
	}
}

Engine.prototype = {
	send: function(method, values) {
		this.socket.send(method + '(' + values.join(',') + ')');
	},

	callMethod: function(method)
	{
		eng = this;
		return function(values) {
			eng.send(method, values);
		}
	}
};
