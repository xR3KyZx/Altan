<?php

include('../includes/variables.php');


$value = $_POST['dn'];
$nir = $_POST['nir'];
$key = $_POST['key'];
$msisdnType = $_POST['msisdnType'];
$uduario_sistema = $_POST['usuario'];
$clave = $_POST['clave'];

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
    $archivo = '../archivos/' . $uduario_sistema . $fecha . '.txt';
    // Palabra a guardar en el archivo
    $palabra = $valor1 . ' ' . $valor2;

    // Si el archivo no existe, lo creamos
    if (!file_exists($archivo)) {
        $archivo_abierto = fopen($archivo, 'w');
        fclose($archivo_abierto);
    }

    // Agregamos la palabra al archivo en una nueva línea
    file_put_contents($archivo, $palabra . "\n", FILE_APPEND);

    $respuesta['status'] = 'ok';
    $respuesta['valor'] = $valor2;

    echo json_encode($respuesta);
} else {

    if (isset($valores->error))
        if (strpos($valores->error, 'It is not possible to make the change with the requested NIR')) {

            $respuesta['status'] = 'error';
            echo json_encode($respuesta);
        } else {

            $respuesta['status'] = 'ok';
            $respuesta['valor'] = 'Error';

            echo json_encode($respuesta);
        }

    if (!isset($valores->error)) {
        $respuesta['status'] = 'ok';
        $respuesta['valor'] = 'Vacio';

        echo json_encode($respuesta);
    }
}


?>



