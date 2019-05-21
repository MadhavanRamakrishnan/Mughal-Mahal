var con = mysql.createConnection({
                       host: "localhost",
                       user: "root",
                       password: "Oneclick1@",
                       database: "mughal_mahal"
                     });
                     con.connect(function(err) {
                      //socket.join(user);
                       if (err) throw err;
                       var b = "SELECT user_id FROM tbl_orders WHERE delivered_by = "+user+" AND order_status < 7";
                        con.query(b, function (err, result, fields) {
                         if (err) throw err;
                         //res.json(result);
                         for(var i = 0; i < result.length; i++)
                         {
                           var dataarr = [result[i].user_id];
                           //var dataarr = [result[i].user_id,user,lat,lon];
                           badArray[i] = dataarr; 
                         }
                        //io.to(user).emit(badArray);
                        //console.log(badArray);
                         var res = JSON.stringify(badArray);
                        /* console.log(msg);
                         console.log(socket.nickname);
                         console.log(res);
                         console.log(lat);
                         console.log(lon);
                         console.log(user);*/
                          /*io.to('some room').emit('whisper', {msg: msg, nick: socket.nickname, res:res, latitude:lat, longitude:lon, driver_id:user });*/
                        // socket.broadcast.emit('whisper', {msg: msg, nick: socket.nickname});
                        /*console.log('whisper');
                        console.log(msg)*/;
                       });
                     });