<?php

$servidor = 'pandora.pe';
$usuario = 'u254837950_w_form_user';
$password = '4fNJ8yzw7!K';
$database = 'u254837950_work_form';

$conexion = mysqli_connect($servidor, $usuario, $password);
//Seleccionar la base de datos
mysqli_select_db($conexion,$database) or die ("Ninguna DB seleccionada");


?>