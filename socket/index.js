const io = require('socket.io')();
const mysql = require('mysql2');
 
// create the connection to database
const connection = mysql.createConnection({
  host: '127.0.0.1',
  user: 'root',
  password:"",
  database: 'ElectricalSystem'
});
const query = "SELECT Instantanea, Diaria, Mensual, Acumulada  FROM Fotovoltaico ORDER BY id DESC LIMIT 1";
connection.query(query, function(err, results, fields) {
    if(err){
        console.error(err);
        return;
    }
    console.log(results); // results contains rows returned by server
    console.log(fields); // fields contains extra meta data about results, if available
    //socket.emit('messages', `This is a message from server ${counter}`);
  }
);
// io.on('connection', socket => { 
//     console.log("Client connected");
//     let counter = 0;
//     connection.query(query, function(err, results, fields) {
//         console.log(results); // results contains rows returned by server
//         console.log(fields); // fields contains extra meta data about results, if available
//         socket.emit('messages', `This is a message from server ${counter}`);
//       }
//     );
//     counter++;
//  });
// io.listen(3000);