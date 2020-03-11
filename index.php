<?php
$conn = mysqli_connect("localhost", "logger", "energysystem", "ElectricalSystem");
$sql = "SELECT Instantanea, Diaria, Mensual, Acumulada, Arboles, Co2, Hogares  FROM Fotovoltaico ORDER BY id DESC LIMIT 1";
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
                    <input id="jsinstantanea" type="hidden" value="<?php echo $resutCheck['Instantanea'] ?>">
                    <div class="instant-txt"><img src="img/arrow_down_w.png" alt=""><p id="instantanea"><?php echo $resutCheck['Instantanea']; ?> <span>kWh</span></p></div>
                    <p class="principal-l-text">POTENCIA INSTANTÁNEA</p>
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
                    <div class="nrgy-number">370 <span>kW</span></div>
                    <p class="nrgy-text">POTENCIA INSTALADA</p>
                </div>
                <div class="col-3">
                    <input id="jsdiaria" type="hidden" value="<?php echo $resutCheck['Diaria'] ?>">
                    <div class="nrgy-number" id="diaria"><?php echo $resutCheck['Diaria']; ?> <span>kW</span></div>
                    <p class="nrgy-text">ENERGÍA DIARIA</p>
                </div>
                <div class="col-3">
                    <input id="jsmensual" type="hidden" value="<?php echo $resutCheck['Mensual'] ?>">
                    <div class="nrgy-number" id="mensual"><?php echo $resutCheck['Mensual']; ?> <span>kW</span></div>
                    <p class="nrgy-text">ENERGÍA MENSUAL</p>
                </div>
                <div class="col-3">
                    <input id="jsacumulada" type="hidden" value="<?php echo $resutCheck['Acumulada'] ?>">
                    <div class="nrgy-number" id="acumulada"><?php echo $resutCheck['Acumulada']; ?> <span>kWh</span></div>
                    <p class="nrgy-text">ENERGÍA ACUMULADA</p>
                </div>
            </div>
            <div class="row extra-nrgy">
            <div class="col-6">
                <input id="jsarboles" type="hidden" value="<?php echo $resutCheck['Arboles'] ?>">
                <div class="nrgy-number" id="arboles"><?php echo $resutCheck['Arboles']; ?></div>
                    <p class="nrgy-text">Árboles equivalentes</p>
            </div>
            <div class="col-6">
                <input id="jsco2" type="hidden" value="<?php echo $resutCheck['Co2'] ?>">
                <div class="nrgy-number" id="co2"><?php echo $resutCheck['Co2']; ?> <span></span></div>
                <p class="nrgy-text">Ton CO<sub>2</sub></p>
            </div>
            </div>
            <div class="row wearow">
                <div class="col-4 wea-order">
                    <div><img src="img/Sol.png" alt=""></div>
                    <div>
                        <input id="jshogares" type="hidden" value="<?php echo $resutCheck['Hogares'] ?>">
                        <div id="hogares" class="wea-txt"><?php echo $resutCheck['Hogares']; ?></div>
                        <div class="wea-descrip">RADIACIÓN SOLAR</div>
                    </div>
                </div>
                <div class="col-2 wea-order">
                    <div><img src="img/temperatura.png" alt=""></div>
                    <div>
                        <div id="temp" class="wea-txt"></div>
                        <div class="wea-descrip">TEMPERARTURA</div>
                    </div>
                </div>
                <div class="col-4 wea-order">
                    <div><img src="img/aire.png" alt=""></div>
                    <div>
                        <div id="wind" class="wea-txt"></div>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.3.0/socket.io.js"></script>
<script src="js/index.js"></script>
<script>

    window.onload = function() {
        const inputinstantanea = $("#jsinstantanea");
        const inputdiaria = $("#jsdiaria");
        const inputmensual = $("#jsmensual");
        const inputacumulada = $("#jsacumulada");
        const inputarboles = $("#jsarboles");
        const inputco2 = $("#jsco2");
        const inputhogares = $("#jshogares");

        const data = {
            instantanea: inputinstantanea.val(),
            diaria: inputdiaria.val(),
            mensual: inputmensual.val(),
            acumulada: inputacumulada.val(),
            arboles: inputarboles.val(),
            co2: inputco2.val(),
            hogares: inputhogares.val()
        };

        setElectricData(data);

        getAPIsData().then(function(data){
            connect2Socket();
            setApiDataInDom(data);
            uploadAPIData(data);
        })
        .catch(function(err){
            console.log(err);
        });
    }
</script>
</body>
</html>
