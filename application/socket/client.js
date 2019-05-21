var socket = io.connect('http://localhost:3000');
socket.emit('join', {email: 'user1@example.com'});
socket.on("new_msg", function(data) {
    alert(data.msg);
});
