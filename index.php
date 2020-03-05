<?php 
    $conn = mysqli_connect("localhost","root","", "ElectricalSystem");
    $sql = "SELECT Instantanea, Diaria, Mensual, Acumulada  FROM Fotovoltaico ORDER BY id DESC LIMIT 1";
    $result = mysqli_query($conn, $sql);
    $resutCheck = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ElectricalSystem</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container-main">
        <div class="content-principal">
            <div class="row">
                <div class="col-6 principal-l">
                    <div class="instant-txt"><img src="img/arrow_down_w.png" alt=""><p><?php echo $resutCheck['Instantanea'];?> <span>kW</span></p></div>
                    <p class="principal-l-text">INSTANTÁNEA</p>
                </div>
                <div class="col-6 principal-r">
                    <div class="principal-r-top">
                        <div class="r-txt">PANEL DE MONITOREO</div>
                        <div><img src="img/energon_logo.png" alt=""></div>
                    </div>
                    <div class="principal-r-buttom">
                        <img src="img/logos_gob.png" alt="">
                    </div>
                </div>
            </div>
            <div class="row nrgyrow">
                <div class="col-3">
                    <div class="nrgy-number">370<span>kW</span></div>
                    <p class="nrgy-text">INSTALADA</p>
                </div>
                <div class="col-3">
                    <div class="nrgy-number"><?php echo $resutCheck['Diaria'];?> <span>kW</span></div>
                    <p class="nrgy-text">DIARIA</p>
                </div>
                <div class="col-3">
                    <div class="nrgy-number"><?php echo $resutCheck['Mensual'];?> <span>kW</span></div>
                    <p class="nrgy-text">MENSUAL</p>
                </div>
                <div class="col-3">
                    <div class="nrgy-number"><?php echo $resutCheck['Acumulada'];?> <span>kW</span></div>
                    <p class="nrgy-text">POTENCIA ACUMULADA</p>
                </div>
            </div>
            <div class="row wearow">
                <div class="col-4 wea-order">
                    <div><img src="img/Sol.png" alt=""></div>
                    <div>
                        <div id="sol_uv" class="wea-txt"></div>
                        <div class="wea-descrip">RADIACIÓN SOLAR</div>
                    </div>
                </div>
                <div class="col-2 wea-order">
                    <div><img src="img/temperatura.png" alt=""></div>
                    <div>
                        <div id="temp" class="wea-txt">23ºC</div>
                        <div class="wea-descrip">TEMPERARTURA</div>
                    </div>
                </div>
                <div class="col-4 wea-order">
                    <div><img src="img/aire.png" alt=""></div>
                    <div>
                        <div id="wind" class="wea-txt">5 km/h</div>
                        <div class="wea-descrip">VELOCIDAD DE VIENTO</div>
                    </div>
                </div>
                <div class="col-2 wea-order wea-order-got">
                    <div><img src="img/gota.png" alt=""></div>
                    <div>
                        <div id="hum" class="wea-txt">40%</div>
                        <div class="wea-descrip">HUMEDAD</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="js/jquery-3.4.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
<script>    
// CHANGE 


function API_weather() {
    return new Promise((resolve, reject) => {
        $.ajax({
            url:
                'http://api.openweathermap.org/data/2.5/weather?lat=20.673924&lon=-103.365449&appid=727b921beb636692dd0606184ecbc711',
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
            url:
                'http://api.openweathermap.org/data/2.5/uvi?appid=727b921beb636692dd0606184ecbc711&lat=20.673924&lon=-103.365449',
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
window.onload = function() {
    var prom1 = API_weather();
    var prom2 = API_uv();
    Promise.all([prom1, prom2])
        .then(result => {
            $('#sol_uv').html(result[1].sol_uv+ '| UV');
            $('#temp').html(result[0].temperatura + 'ºC');
            $('#wind').html(result[0].viento + 'km/h');
            $('#hum').html(result[0].humedad + '%');
        })
        .catch(error => {
            console.error(error);
        });
};

</script>
</body>
</html>