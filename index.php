<?php
session_start();

include('includes/bbdd.php');
include('includes/variables.php');


$sql = "SELECT * from CLAVE
WHERE idclave = 1";
//echo $sql;
$resultado = mysqli_query($conexion, $sql);
while ($row = mysqli_fetch_array($resultado)) {
    $clave = $row['clave'];
}



$tab_home = 'active';
$pane_tab_home = 'show active';
$tab_archivo = '';
$pane_tab_archivo = '';

if (isset($_GET['tab']))
    if ($_GET['tab'] == 'archivo') {
        $tab_home = '';
        $pane_tab_home = '';
        $tab_archivo = 'active';
        $pane_tab_archivo = 'show active';
    }


$alerta = '';

$usuario = $_SESSION['WORK_FORM_usuario'];
$cargaPendiente = 0;
$sql = "SELECT * from CARGA
    WHERE usuario = '$usuario' AND status = 0 ORDER BY idcarga";
//echo $sql;
$resultado = mysqli_query($conexion, $sql);
while ($row = mysqli_fetch_array($resultado)) {
    $cargaPendiente = 1;
    $listacarga[$row['idcarga']]['nir'] = $row['nir'];
    $listacarga[$row['idcarga']]['dn'] = $row['dn'];
}




if ($_SERVER['REQUEST_METHOD'] == "POST") {


    $uduario_sistema = $_POST['usuario'];





    $value = $_POST['value'];
    $nir = $_POST['nir'];
    $msisdnType = $_POST['msisdnType'];

    $data = "value=$value&nir=$nir&msisdnType=$msisdnType";

    $url = "https://360.altanredes.com/operations/call/cambioMSISDN?$data";

    //echo $url;

    $curl = curl_init($url);

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $cookie = $clave;

    $headers = array(
        "Content-Type: application/x-www-form-urlencoded",
        "Cookie: $cookie"
    );

    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

    $resp = curl_exec($curl);
    curl_close($curl);





    $valores = json_decode($resp);


    if (isset($valores->oldMsisdn) && isset($valores->newMsisdn)) {
        $valor1 = $valores->oldMsisdn;
        $valor2 = $valores->newMsisdn;


        // Obtenemos la fecha de hoy
        $fecha = date('Ymd');
        // Nombre del archivo con la fecha de hoy
        $archivo = 'archivos/' . $uduario_sistema . $fecha . '.txt';
        // Palabra a guardar en el archivo
        $palabra = $valor1 . ' ' . $valor2;

        // Si el archivo no existe, lo creamos
        if (!file_exists($archivo)) {
            $archivo_abierto = fopen($archivo, 'w');
            fclose($archivo_abierto);
        }

        // Agregamos la palabra al archivo en una nueva línea
        file_put_contents($archivo, $palabra . "\n", FILE_APPEND);

        $sql = "INSERT INTO GENERADOR
        (valor1, valor2, usuario, fecha)
        VALUES
        ('$valor1', '$valor2', '$uduario_sistema', '" . $VARIABLES['texto_fecha_hora'] . "' )";
        //echo $sql;
        $resultado = mysqli_query($conexion, $sql);



        echo $valor1 . ' ' . $valor2;
    } else {
        echo ($resp);
    }
} else {

    if (!isset($_SESSION['WORK_FORM_usuario']))
        header('Location: login');

    $usuario = $_SESSION['WORK_FORM_usuario'];


?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Cambio de NIR Bait</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.4/font/bootstrap-icons.css">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    </head>
    <style>
        body {
            margin: 0px;
        }

        form {
            margin: auto;
        }

        form>div {
            margin: 10px 0px;
        }
    </style>

    <body>





        <div class="container">
            <div class="row justify-content-md-center mt-5">
                <div class="col-lg-8">
                    <div class="card w-100 shadow p-3">
                        <!--
                    <img src="..." class="card-img-top" alt="...">
-->
                        <div class="card-body">

                            <div class="row">
                                <div id="alerta" class="col-lg-12 text-center">
                                    <h5><?= $alerta ?></h5>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-8 text-left">
                                    <h4>Bienvenido <b><?= $usuario ?></b></h4>
                                </div>

                                <div class="col-lg-4 text-right">
                                    <a href="cerrar.php" class="btn btn-default text-danger"><i class="bi bi-x-circle-fill"></i> cerrar sesión</a>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-lg-12">
                                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link <?= $tab_home ?>" id="home-tab" data-bs-toggle="tab" data-bs-target="#usuarios-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Consulta simple</button>
                                        </li>

                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#masivo-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">Consulta masiva</button>
                                        </li>

                                        <li class="nav-item" role="presentation">
                                            <button onclick="location.href='?tab=archivo'" class="nav-link <?= $tab_archivo ?>" id="profile-tab" data-bs-toggle="tab" data-bs-target="#archivos-tab-pane" role="tab" aria-controls="profile-tab-pane" aria-selected="false">Archivos</button>
                                        </li>

                                        <!--
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#actualizar-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">Cambiar contraseña</button>
                                        </li>

    -->
                                    </ul>
                                    <div class="tab-content" id="myTabContent">
                                        <div class="tab-pane fade  <?= $pane_tab_home ?>" id="usuarios-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">

                                            <div class="row p-3">
                                                <div class="col-lg-5 text-left">

                                                    <form method="get" id="myForm">
                                                        <input type="hidden" name="usuario" value="<?= $usuario ?>">
                                                        <div>
                                                            <input type="text" class="form-control form-control-lg" name="value" id="textbox1" value="" placeholder="DN">
                                                        </div>
                                                        <div>
                                                            <input type="text" class="form-control form-control-lg" name="nir" id="textbox2" value="" placeholder="NIR">
                                                            <input type="hidden" name="msisdnType" value="1">
                                                        </div>
                                                        <div>
                                                            <input type="submit" class="btn btn-primary" id="send" value="Send">
                                                            <input type="button" class="btn btn-link" value="Reset" id="reset">
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="mt-3">
                                                        <h3>Resultado:</h3>
                                                        <div id="result"></div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="masivo-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">

                                            <div class="row p-3">
                                                <div class="col-lg-5 text-left">
                                                    <form method="POST" id="myFormMasivo">
                                                        <input type="hidden" name="usuario" value="<?= $usuario ?>">
                                                        <div>
                                                            <textarea name="valores" rows="7" class="form-control" placeholder="Pegar DNs" required></textarea>
                                                        </div>
                                                        <div>
                                                            <input type="text" class="form-control form-control-lg" name="nir" value="" placeholder="NIR" required>
                                                            <input type="hidden" name="msisdnType" value="1">
                                                        </div>
                                                        <div>
                                                            <button type="submit" class="btn btn-primary" id="sendMasivo">Consultar</button>
                                                            <input type="button" onclick="borrarMasivo(); return false" class="btn btn-link" value="Reset" id="resetMasivo">
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="col-lg-7">
                                                    <div id="resultMasiva">

                                                        <?php if ($cargaPendiente == 1) { ?>


                                                            <script>
                                                                var listaCarga = <?= json_encode($listacarga) ?>;
                                                                var continuar = true;
                                                                var index = 0;
                                                            </script>

                                                            <a href="" onclick="iniciarCargarMasiva(); return false" class="btn btn-primary">Procesar carga</a>

                                                            <div id="resultado_valores" class="mt-4" style="height: 400px; overflow-y: scroll;">

                                                                <table class="table mt-2">
                                                                    <tr>
                                                                        <th>NIR</th>
                                                                        <th>DN</th>
                                                                        <th>RESULTADO</th>
                                                                    </tr>
                                                                    <?php
                                                                    if (isset($listacarga))
                                                                        foreach ($listacarga as $key => $value) {
                                                                    ?>
                                                                        <tr>
                                                                            <td><?= $value['nir'] ?></td>
                                                                            <td><?= $value['dn'] ?></td>
                                                                            <td>
                                                                                <div id="resultado_<?= $key ?>"></div>
                                                                            </td>
                                                                        </tr>
                                                                    <?php

                                                                        }
                                                                    ?>
                                                                </table>

                                                            </div>

                                                        <?php } ?>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="tab-pane fade  <?= $pane_tab_archivo ?>" id="archivos-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">

                                            <div class="row p-3">


                                                <div class="col-lg-12">
                                                    <?php

                                                    // Directorio donde se encuentran los archivos
                                                    $directorio = 'archivos/';

                                                    // Obtiene una lista de todos los archivos .txt en el directorio
                                                    $archivos = glob($directorio . "*.txt");

                                                    // Lee el contenido de cada archivo
                                                    foreach ($archivos as $archivo) {

                                                        if (strpos(strtoupper($archivo), strtoupper($usuario))) {
                                                    ?>

                                                            <a href="<?= $archivo ?>" download><i class="bi bi-file-earmark"></i></a> <?= str_replace('archivos/', '', $archivo) ?><br>

                                                    <?php
                                                        }
                                                    }

                                                    ?>
                                                </div>
                                            </div>


                                        </div>

                                        <div class="tab-pane fade" id="actualizar-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
                                            <div class="row justify-content-md-center mt-5">
                                                <div class="col-lg-6">
                                                    <form action="" method="POST" class="row g-3 needs-validation" novalidate>
                                                        Cambiar contraseña
                                                        <input name="password" class="form-control">
                                                        <button type="submit" class="btn btn-lg btn-primary" style="background-color: #20A3FF;">
                                                            Cambiar contraseña
                                                        </button>

                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>




                        </div>
                    </div>
                </div>
            </div>

        </div>





    </body>
    <script>
        var continuar = true;
        var index = 0;

        function iniciarCargarMasiva() {


            realizarConsulta();



        }



        function realizarConsulta() {
            if (!continuar) {
                return false; // Si continuar es falso, detener el bucle
            }

            var keys = Object.keys(listaCarga);
            var promesas = [];

            for (var i = index; i < index + 15 && i < keys.length; i++) {
                var key = keys[i];
                var value = listaCarga[key];

                var promesa = $.ajax({
                    url: 'ajax/consulta_masiva.php',
                    type: 'POST',
                    data: {
                        nir: value.nir,
                        dn: value.dn,
                        key: key,
                        msisdnType: 1,
                        usuario: '<?= $usuario ?>',
                        clave: '<?= $clave ?>'
                    },
                    dataType: 'json'
                });

                promesas.push(promesa);
            }

            $.when.apply($, promesas).done(function() {
                var resultados = arguments;

                for (var i = 0; i < resultados.length; i++) {
                    var resultado = resultados[i][0];
                    var key = keys[index + i];

                    if (resultado.status == 'ok') {
                        $('#resultado_' + key).html(resultado.valor);
                        var resultadoDiv = document.getElementById('resultado_' + key);
                        resultadoDiv.scrollIntoView();
                    } else {
                        alert('Se detuvo el proceso');
                        continuar = false;
                        break;
                    }
                }

                index += promesas.length; // Incrementar el índice para continuar con el siguiente grupo de elementos
                if (index < keys.length && continuar) {
                    setTimeout(realizarConsulta, 500); // Esperar 1 segundo para el siguiente grupo de elementos
                }
            }).fail(function() {
                alert('error2');
                continuar = false;
            });
        }


        function borrarMasivo() {

            // get the form data as a URL-encoded string
            const formData = $('#myFormMasivo').serialize();

            // send an AJAX request to the server with headers
            $.ajax({
                    url: "ajax/reset_consulta_masiva.php",
                    type: "POST",
                    crossDomain: true,
                    data: formData,
                })
                .done(function(response) {
                    $('#resultMasiva').html(response);
                    document.getElementById('myFormMasivo').reset();
                })

        }


        // get form data as URLSearchParams object
        $(document).ready(function() {

            $('#reset').on('click', function() {
                $('#textbox1').val('');
                $('#result').html('');
            })

            // attach a submit event handler to the form
            $("#myForm").submit(function(event) {
                // prevent the default form submission
                event.preventDefault();

                // get the form data as a URL-encoded string
                const formData = $(this).serialize();

                // send an AJAX request to the server with headers
                $.ajax({
                        url: "",
                        type: "POST",
                        crossDomain: true,
                        data: formData,
                    })
                    .done(function(response) {
                        console.log("----------------------", response);
                        $('#result').html(response);
                    })
                    .fail(function(jqXHR, textStatus, errorThrown) {
                        console.error(errorThrown);
                    });
            });



            // attach a submit event handler to the form
            $("#myFormMasivo").submit(function(event) {
                // prevent the default form submission
                event.preventDefault();

                // get the form data as a URL-encoded string
                const formData = $(this).serialize();

                // send an AJAX request to the server with headers
                $.ajax({
                        url: "ajax/carga_consulta_masiva.php",
                        type: "POST",
                        crossDomain: true,
                        data: formData,
                    })
                    .done(function(response) {
                        $('#resultMasiva').html(response);
                    })
            });

        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>

    </html>
<?php
}

mysqli_close($conexion);

?>