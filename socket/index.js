const io = require('socket.io')();
const mysql = require('mysql2');

// create the connection to database
const connection = mysql.createConnection({
    host: '127.0.0.1',
    user: 'root',
    password: "",
    database: 'ElectricalSystem'
});

const query = "SELECT Instantanea, Diaria, Mensual, Acumulada  FROM Fotovoltaico ORDER BY id DESC LIMIT 1";

io.on('connection', socket => {
    console.log("Client connected");
    setInterval(() => {
        connection.query(query, function(err, results, fields) {
            // results contains rows returned by server
            const data = {
                instantanea: results[0].Instantanea,
                diaria: results[0].Diaria,
                mensual: results[0].Mensual,
                acumulada: results[0].Acumulada
            };
            socket.emit('electric-data', data);
        });
    }, 5 * 60 * 1000);


    socket.on("save-api-data", (data) => {
        console.log("SAVING API DATA")
        connection.query(`INSERT INTO Clima ( Radiacion, Temperatura, Viento, Humedad)
        VALUES ( '${data.uv}','${data.temp}','${data.viento}', '${data.humedad}');`);
    });

});


io.listen(3000);