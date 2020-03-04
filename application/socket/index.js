var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);
var mysql = require('mysql');
var users = {};
var resName = {};
var uids = [];

http.listen(3333, function() {
	 console.log('listening on localhost:3333');
});

app.get('/', function(req, res) {
	 res.sendfile(__dirname + '/index.html');
});


// socket connection and set user name for arrived user
users = [];
io.on('connection', function(socket) {
 	//console.log('A user connected');
	socket.on('setUsername', function(data) {
		console.log(data);
	});
});

// socket connection all events
io.sockets.on('connection', function (socket) {
	 
	// connect driver and create room same as driver id
	//socket.on('new user' , function (data , callBack) {
	socket.on('new driver' , function (data , callBack) {			
		if(data in users) {
			//callBack(false);
		 	//console.log("User exsits");
		} else {
			//callBack(true);
			console.log('A driver connected with name aaa '+data);
			socket.nickname  = data;
			//users[socket.nickname] = socket;
			//Creation of room with the name of driver
			socket.join(data);
			//updateNicknames();
		}
	});

	// socket send location event for sending the current location of driver
	socket.on('send location' , function (data) {
		console.log("New location");
		var obj = JSON.parse(data);
		console.log(obj);
		var did = obj.user_id;
		//var lat = obj.latitude;
		//var lon = obj.longitude;
		//Send drivers new location to all the customer who has joined the room
		io.sockets.in(did).emit('new location from driver', data);
	});

	// connect new customer and join customer with driver
	socket.on('new customer' , function (driver_id) {
		//var obj = JSON.parse(data);
		console.log('A customer connected to '+driver_id);
		console.log(users);
		if(driver_id in users) {
		 	socket.join(driver_id);
			socket.room = driver_id;
		} else {
			console.log('Driver not found '+driver_id);
			//callBack(true);
			//socket.nickname  = data;
			//users[socket.nickname] = socket;
			//updateNicknames();
		}    
	});

	// new order event triggered when customer track the specific order, send order id
	//socket.on('connect to driver' , function (orderid) {
		/*var db = mysql.createConnection({
			host: 'localhost',
			user: 'root',
			password: 'Oneclick1@',
			database: 'mughal_mahal'
		});

		db.connect(function(err){
			if (err){
				console.log(err);
				socket.emit("Server Error", {message: 'Database Error'});
				return;
			}else{
				db.query('SELECT delivered_by FROM tbl_orders WHERE order_id='+orderid, function(error, rows) {
					if(error){
						console.log(error);
						socket.emit("Server Error", {message: 'Query Error'});
						return;
					}else{
						driverid = rows[0]['delivered_by'];
						if(driverid in users){
							socket.join(driverid);
						}else{
							socket.emit("Implementation Error", {message: 'Driver not connected'});
						}
					}
				});
			}
		});*/
	//});

	//remove customer from room when he goes out of the order screen
	socket.on('disconnect from driver' , function () {
		socket.leave(socket.room);
	});

	/*socket.on('send location', function(data , callBack) {
			console.log("message before trim "+data);
			var msg = data.trim();   
			if (msg.substr(0,3) === '/w ') {
				msg = msg.substr(3);
				var ind = msg.indexOf(' ');
				var badArray = new Array();
				if (ind !== -1) {
							 //console.log(msg);
					var name = msg.substring(0 , ind);
					console.log("Send message to "+name);
					var msg = msg.substring(ind + 1);
					console.log("message is "+msg);
					if (name in users) {

						 users[name].emit('newmsg', '{"msg": '+msg+', "nick": "'+socket.nickname+'" }');
						 console.log("message sent to "+name);
					} else {
									console.log("b");
									 callBack('Error no user found');
							 }
						} else {
							 console.log("c");
								callBack('ENter valid massage');
						}
				 } else {
						//console.log("e");
						//Send message to everyone
						console.log(msg);
			
					 io.sockets.emit('newmsg', {msg: msg, nick: socket.nickname});
			}
	 });*/

	 /*socket.on('send msg', function(data , callBack) {
			var msg = data.trim();
			if (msg.substr(0,3) === '/w ') {
				msg = msg.substr(3);
				var ind = msg.indexOf(' ');
				if (ind !== -1) {
					var name = msg.substring(0 , ind);
					var msg = msg.substring(ind + 1);
					if (name in users) {
									users[name].emit('whisper', {msg: msg, nick: socket.nickname});
									// socket.broadcast.emit('whisper', {msg: msg, nick: socket.nickname});
									console.log('whisper');
						console.log(msg);
									
					} else {
						callBack('Error no user found');
					}
				} else {
					callBack('ENter valid massage');
				}
			} else {
			//Send message to everyone
				 io.sockets.emit('newmsg', {msg: msg, nick: socket.nickname});
			}
	 });*/

	 // function updateNicknames() {
		// 	io.sockets.emit('username' , Object.keys(users));
	 // }
	 socket.on('disconnect' , function (data) {
			if (socket.nickname)
				delete users[socket.nickname];
			if(socket.room)
				socket.leave(socket.room);
			//1updateNicknames();
	 })

});

