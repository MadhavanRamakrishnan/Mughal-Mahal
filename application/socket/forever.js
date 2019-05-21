
var forever = require('forever-monitor');

var child = new (forever.Monitor)('index_clean.js', {
  max: 300,
  silent: true,
  args: []
});

child.on('exit', function () {
  console.log('index.js has exited after 300 restarts');
});

console.log('Default console');

child.start();
