var socket = null;
// CHANGE 
function API_weather() {
    return new Promise((resolve, reject) => {
        $.ajax({
            url: 'http://api.openweathermap.org/data/2.5/weather?lat=20.673924&lon=-103.365449&appid=727b921beb636692dd0606184ecbc711',
            type: 'GET',
            success: function(result, status, xhr) {
                var temperatura = Math.trunc(result.main.temp - 273);
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

function API_uv() {
    return new Promise((resolve, reject) => {
        $.ajax({
            url: 'http://api.openweathermap.org/data/2.5/uvi?appid=727b921beb636692dd0606184ecbc711&lat=20.673924&lon=-103.365449',
            type: 'GET',
            success: function(result2, status, xhr) {
                var sol_uv = result2.value;
                resolve({ sol_uv: sol_uv });
            },
            error: function(err) {
                reject(err);
            },
        });
    });
}

function getAPIsData() {
    return new Promise(function(resolve, reject) {
        var prom1 = API_weather();
        var prom2 = API_uv();

        Promise.all([prom1, prom2])
            .then(result => {
                const data = {
                    uv: result[1].sol_uv,
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
    $('#sol_uv').html(data.uv + ' | UV');
    $('#temp').html(data.temp + 'ºC');
    $('#wind').html(data.viento + ' km/h');
    $('#hum').html(data.humedad + '%');
}

function setElectricData(data) {
    $("#instantanea").html(data.instantanea + " <span>kW</span>");
    $("#diaria").html(data.diaria + " <span>kW</span>");
    $("#mensual").html(data.mensual + " <span>kW</span>");
    $("#acumulada").html(data.acumulada + " <span>kW</span>");
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
