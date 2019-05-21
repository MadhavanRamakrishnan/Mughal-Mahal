var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);

http.listen(3333, function() {
	 console.log('listening on localhost:3333');
});

app.get('/', function(req, res) {
	 res.sendFile(__dirname + '/index.html');
});


// socket connection and set user name for arrived user
var drivers = [];

// socket connection all events
io.sockets.on('connection', function (socket) {
	 //console.log("connected");
	// connect driver and create room same as driver id
	socket.on('new driver' , function (driver_id) {		
		console.log("New driver"+driver_id);	
		socket.nickname  = driver_id;
		drivers[socket.nickname] = socket;
		//Creation of room with the name of driver
		socket.join(driver_id);
	});

	// connect new customer and join customer with driver
	socket.on('new customer' , function (driver_id, callback) {
		console.log(drivers);
		if(driver_id in drivers) {
			console.log('A customer connected to '+driver_id);
		 	socket.join(driver_id);
			socket.room = driver_id;
		} else {
			socket.emit('connection_success', false);
			console.log('Driver does not exists '+driver_id);
		}    
	});

	// socket send location event for sending the current location of driver
	socket.on('send location' , function (data) {
		//console.log("Location update "+socket.nickname);
		io.sockets.to(socket.nickname).emit('new location from driver', data);
		// io.sockets.emit('new location from driver', data);
	});

	//remove customer from room when he goes out of the order screen
	socket.on('disconnect from driver' , function () {
		socket.leave(socket.room);
	});

	//Called when socket is disconnected
	socket.on('disconnect' , function (data) {
		if (socket.nickname){
			/*io.sockets.clients(socket.nickname).forEach(function(s){
			    s.leave(socket.nickname);
			});*/
			console.log(Object.keys(io.of('/').in(socket.nickname).clients));
			/*io.of('/').in(socket.nickname).clients.forEach(function(s){
			    s.leave(socket.nickname);
		    });*/
			delete drivers[socket.nickname];
		}
		if(socket.room)
			socket.leave(socket.room);
	 });

});
