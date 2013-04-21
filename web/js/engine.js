
var Engine = function(method, callback) {
	this.method = method;
	this.callback = callback;
}

Engine.callMethod = function(method, callback)
{
	return function(values) {
		(new Engine(method, callback)).send(values);
	}
}

Engine.prototype = {
	send: function(values) {
		var conn = new WebSocket('ws://' + location.host +  ':8080');
		var method = this.method;
		conn.onopen = function(e) {
			conn.send(method + '(' + values.join(',') + ')');
		};
		var callback = this.callback;
		conn.onmessage = function(e) {
			callback(e.data);
		};
	}
};
