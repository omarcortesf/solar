var socket = null;
// CHANGE 
function API_weather() {
    return new Promise((resolve, reject) => {
        $.ajax({
            url: 'http://api.openweathermap.org/data/2.5/weather?lat=20.673924&lon=-103.365449&appid=727b921beb636692dd0606184ecbc711',
            type: 'GET',
            success: function(result, status, xhr) {
                var temperatura = Math.trunc(result.main.temp - 269);
                var viento = result.wind.speed;
                var humedad = result.main.humidity;
                resolve({
                    temperatura: temperatura,
                    viento: viento,
                    humedad: humedad,
                });
            },
            error: function(err) {
                reject(err);
            },
        });
    });
}

// function API_uv() {
//     return new Promise((resolve, reject) => {
//         $.ajax({
//             url: 'http://api.openweathermap.org/data/2.5/uvi?appid=727b921beb636692dd0606184ecbc711&lat=20.673924&lon=-103.365449',
//             type: 'GET',
//             success: function(result2, status, xhr) {
//                 var sol_uv = result2.value;
//                 resolve({ sol_uv: sol_uv });
//             },
//             error: function(err) {
//                 reject(err);
//             },
//         });
//     });
// }

function getAPIsData() {
    return new Promise(function(resolve, reject) {
        var prom1 = API_weather();
        // var prom2 = API_uv();

        // Promise.all([prom1, prom2])
        Promise.all([prom1])
            .then(result => {
                const data = {
                    // uv: result[1].sol_uv,
                    temp: result[0].temperatura,
                    viento: result[0].viento,
                    humedad: result[0].humedad
                };

                resolve(data);
            })
            .catch(error => {
                console.error(error);
                reject(error);
            });

    });
}

function setApiDataInDom(data) {
    // $('#sol_uv').html(data.uv + ' | UV');
    $('#temp').html(data.temp + 'ºC');
    $('#wind').html(data.viento + ' km/h');
    $('#hum').html(data.humedad + '%');
}

function setElectricData(data) {
    var instantanea = addCommas(data.instantanea);
    $("#instantanea").html(instantanea + " <span>kW</span>");
    var diaria = addCommas(data.diaria);
    $("#diaria").html(diaria + " <span>kWh</span>");
    var mensual = addCommas(data.mensual);
    $("#mensual").html(mensual + " <span>kWh</span>");
    var acumulada = addCommas(data.acumulada);
    $("#acumulada").html(acumulada + " <span>MWh</span>");
    var arboles = addCommas(data.arboles);
    $("#arboles").html(arboles );
    var co2 = addCommas(data.co2);
    $("#co2").html(co2);
    var hogares = addCommas(data.hogares);
    $("#hogares").html(hogares + " W/m<sup>2</sup>");
}

function uploadAPIData(data) {
    socket.emit("save-api-data", data)
}

function connect2Socket() {
    socket = io.connect('http://62.151.177.198');

    socket.on('electric-data', function(data) {
        console.log(data);
        setElectricData(data);
        getAPIsData().then(function(data) {
                setApiDataInDom(data);
                uploadAPIData(data);
            })
            .catch(function(err) {
                console.log(err);
            });
    });
}


function addCommas(num) {
    var characters = parseInt(num, 10).toString();
    var output = '';
    for (var offset = characters.length; offset > 0; offset -= 3) {
        output = characters.slice(Math.max(offset - 3, 0), offset) + (output ? ',' + output : '');
    }
    return output;
}