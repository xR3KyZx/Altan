<?php
session_start();






if ($_SERVER['REQUEST_METHOD'] == "POST") {

    include('includes/bbdd.php');
    include('includes/variables.php');
    $uduario_sistema = $_POST['usuario'];


    $sql = "SELECT * from CLAVE
    WHERE idclave = 1";
    //echo $sql;
    $resultado = mysqli_query($conexion, $sql);
    while ($row = mysqli_fetch_array($resultado)) {
        $clave = $row['clave'];
    }


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


    if(isset($valores->oldMsisdn) && isset($valores->newMsisdn))
    {
        $valor1 = $valores->oldMsisdn;
        $valor2 = $valores->newMsisdn;


        // Obtenemos la fecha de hoy
        $fecha = date('Ymd');
        // Nombre del archivo con la fecha de hoy
        $archivo = 'archivos/'.$uduario_sistema . $fecha . '.txt';
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
        ('$valor1', '$valor2', '$uduario_sistema', '".$VARIABLES['texto_fecha_hora']."' )";
        //echo $sql;
        $resultado = mysqli_query($conexion, $sql);

        mysqli_close($conexion);

        echo $valor1.' '.$valor2;
    } else { echo ($resp);}

} else {

    if (!isset($_SESSION['WORK_FORM_usuario']))
        header('Location: login');


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

        .container {
            display: flex;
            text-align: center;
            align-items: center;
            /* height: 100vh; */
            flex-direction: column;
        }

        form {
            margin: auto;
        }

        form>div {
            margin: 10px 0px;
        }

        form input[type="text"] {
            width: 300px;
            height: 30px;
        }

        form input[type="submit"],
        form input[type="button"] {
            width: 70px;
            height: 30px;
        }
    </style>

    <body>
        <div class="container" style="text-align: center;">
        <a href="cerrar.php" class="btn btn-default text-danger"><i class="bi bi-x-circle-fill"></i> cerrar sesión</a>
                         

            <form method="get" id="myForm">
                <input type="hidden" name="usuario" value="<?= $_SESSION['WORK_FORM_usuario'] ?>">
                <div>
                    <input type="text" name="value" id="textbox1" value="" placeholder="DN">
                </div>
                <div>
                    <input type="text" name="nir" id="textbox2" value="" placeholder="NIR">
                    <input type="hidden" name="msisdnType" value="1">
                </div>
                <div>
                    <input type="submit" id="send" value="Send">
                    <input type="button" value="Reset" id="reset">
                </div>
            </form>
            <div>
                <h3>Resultado:</h3>
                <div id="result"></div>
            </div>
        </div>
    </body>
    <script>
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

        });
    </script>

    </html>
<?php
}
?>