<script src="https://code.jquery.com/jquery-1.11.1.js"></script>
<script type="text/javascript" src="http://localhost:3333/socket.io/socket.io.js"></script>
<script type="text/javascript">

	var driverId = 130;
	jQuery(function ($) {

		var index = Math.floor(Math.random() * 3);
		var locations = [{"lat":"23.0347","lon":"72.5105"},{"lat":"23.0381","lon":"72.5119"},{"lat":"23.0353","lon":"72.4937"}];
		
		var socket = io.connect('http://localhost:3333');
		socket.emit('new driver',driverId);
		socket.emit('send location',{"user_id":driverId,"latitude":locations[index].lat,"longitude":locations[index].lon});
		/*setInterval(function(){ 
			var socket = io.connect('http://localhost:3333');
			socket.emit('new driver',driverId);
			socket.emit('send location',{"user_id":driverId,"latitude":"23.0347","longitude":"72.5105"});
		 }, 5000);*/
		

	});
	setInterval(function(){ location.reload(); },5000);
</script>