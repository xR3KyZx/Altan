<?php


setlocale(LC_TIME, 'es_PE'); 
date_default_timezone_set('America/Mexico_City'); 
$VARIABLES['servidor'] = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].'/';
$VARIABLES['texto_fecha_hora'] = date('Y-m-d H:i:s');

?>