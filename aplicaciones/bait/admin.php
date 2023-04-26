<?php
session_start();
include('includes/bbdd.php');
include('includes/variables.php');

if (!isset($_SESSION['WORK_FORM_usuario']))
    header('Location: login');


$usuario = '';
$alerta = '';
$tab_inicio = 'active';
$tab_archivo = '';

$tab_pane_inicio =  'show active';
$tab_pane_archivo = '';




if (isset($_POST['usuario']))
    if ($_POST['accion'] == 'crear') {
        $existe = 0;
        $alerta = '';
        $usuario = $_POST['usuario'];
        $password = hash('sha256', $_POST['password']);
        $sql = "SELECT * from USUARIO
    WHERE usuario = '$usuario' AND status = 1";
        //echo $sql;
        $resultado = mysqli_query($conexion, $sql);
        while ($row = mysqli_fetch_array($resultado)) {
            $existe = 1;
            $alerta = '<span class="text-danger"><b>ERROR:</b> Usuario ya existente</span>';
        }

        if ($existe == 0) {
            $alerta = '<span class="text-primary">Usuario <b>' . $usuario . '</b> creado correctamente</span>';
            $sql = "INSERT INTO USUARIO (usuario, password, status, admin)
        VALUES
        ('$usuario', '$password', 1, 0)";
            //echo $sql;
            $resultado = mysqli_query($conexion, $sql);
        }
    }



if (isset($_POST['usuario']))
    if ($_POST['accion'] == 'editar') {
        $alerta = '';
        $usuario = $_POST['usuario'];
        $idusuario = $_POST['idusuario'];
        $password = hash('sha256', $_POST['password']);
        $sql = "UPDATE USUARIO
    SET password = '$password'
    WHERE idusuario = $idusuario";
        //echo $sql;
        $resultado = mysqli_query($conexion, $sql);


        $alerta = '<span class="text-primary">Contraseña actualizada para <b>' . $usuario . '</b></span>';
    }



if (isset($_POST['usuario']))
    if ($_POST['accion'] == 'eliminar') {
        $alerta = '';
        $usuario = $_POST['usuario'];
        $idusuario = $_POST['idusuario'];
        $sql = "UPDATE USUARIO
    SET status = 0
    WHERE idusuario = $idusuario";
        //echo $sql;
        $resultado = mysqli_query($conexion, $sql);


        $alerta = '<span class="text-primary">Usuario <b>' . $usuario . '</b> eliminado</span>';
    }



    if (isset($_POST['clave'])) {
        $alerta = '';
        $clave = $_POST['clave'];
        $sql = "UPDATE CLAVE
    SET clave = '$clave'
    WHERE idclave = 1";
        //echo $sql;
        $resultado = mysqli_query($conexion, $sql);
        $alerta = '<span class="text-primary">Se actualizo la clave</span>';
    }

    if (isset($_POST['archivo'])) {
        $alerta = '';
        $archivo_origen = $_POST['archivo'];
        $fecha = date('Ymd_His');
        $archivo_destino = str_replace('.txt', '_'.$fecha.'.txt', str_replace('archivos/', 'archivos/eliminados/', $archivo_origen));
        if (rename($archivo_origen, $archivo_destino)) {
            $alerta = '<span class="text-primary">Se eliminó el archivo '.str_replace('', '', str_replace('archivos/', '', $archivo_origen)).'</span>';
        } else {
            $alerta = '<span class="text-danger"><b>ERROR:</b>No se pudo eliminar el archivo</span>';
        }
        $tab_inicio = '';
        $tab_archivo = 'active';

        
        $tab_pane_inicio = '';
        $tab_pane_archivo = 'show active';
        
    }



// DATOS

$sql = "SELECT * from USUARIO
    WHERE status = 1";
//echo $sql;
$resultado = mysqli_query($conexion, $sql);
while ($row = mysqli_fetch_array($resultado)) {
    $USUARIOS[$row['idusuario']] = $row['usuario'];
}

$sql = "SELECT * from USUARIO
    WHERE status = 1 and admin = 0";
//echo $sql;
$resultado = mysqli_query($conexion, $sql);
while ($row = mysqli_fetch_array($resultado)) {
    $USUARIOS2[$row['idusuario']] = $row['usuario'];
}


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

</style>

<body style="background-color: #efefef;">
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
                                <h3>Administración de usuarios</h3>
                            </div>

                            <div class="col-lg-4 text-right">
                                <a href="cerrar.php" class="btn btn-default text-danger"><i class="bi bi-x-circle-fill"></i> cerrar sesión</a>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-lg-12">
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link <?= $tab_inicio ?>" id="home-tab" data-bs-toggle="tab" data-bs-target="#usuarios-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Cambiar contraseña</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="home-tab" data-bs-toggle="tab" data-bs-target="#eliminar-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Eliminar usuario</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#crear-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">Crear usuario</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link <?= $tab_archivo ?>" id="profile-tab" data-bs-toggle="tab" data-bs-target="#archivos-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">Archivos</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#clave-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">Cookie</button>
                                    </li>
                                </ul>
                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade <?= $tab_pane_inicio ?>" id="usuarios-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">

                                        <div class="row p-3">
                                            <div class="col-lg-12 text-left">


                                                <?php foreach ($USUARIOS as $key => $value) { ?>

                                                    <div class="col-lg-12 text-left mt-2">
                                                        <form action="" method="POST" class="row needs-validation" novalidate>
                                                            <input type="hidden" name="idusuario" value="<?= $key ?>">
                                                            <input type="hidden" name="accion" value="editar">
                                                            <input type="hidden" name="usuario" value="<?= $value ?>">
                                                            <div class="col-lg-3">
                                                                <div class="mt-2">
                                                                    <?= $value ?>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-5">

                                                                <input type="password" name="password" class="form-control" placeholder="contraseña" required>

                                                            </div>
                                                            <div class="col-lg-3">
                                                                <button type="submit" class="btn btn-primary">cambiar</button>
                                                            </div>

                                                        </form>
                                                    </div>




                                                <?php } ?>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="eliminar-tab-pane" role="tabpanel" aria-labelledby="contact-tab" tabindex="0">

                                        <div class="row p-3">
                                            <div class="col-lg-12 text-left">


                                                <?php foreach ($USUARIOS2 as $key => $value) { ?>

                                                    <div class="col-lg-12 text-left mt-2">
                                                        <form action="" method="POST" class="row needs-validation" novalidate>
                                                            <input type="hidden" name="idusuario" value="<?= $key ?>">
                                                            <input type="hidden" name="accion" value="eliminar">
                                                            <input type="hidden" name="usuario" value="<?= $value ?>">
                                                            <div class="col-lg-3">
                                                                <div class="mt-2">
                                                                    <?= $value ?>
                                                                </div>
                                                            </div>

                                                            <div class="col-lg-3" id="eliminar_<?= $key ?>">
                                                                <button onclick="eliminar(<?= $key ?>); return false" class="btn btn-danger">eliminar</button>

                                                            </div>

                                                            <div class="col-lg-6" id="confirmar_<?= $key ?>" style="display: none;">
                                                                <button onclick="cancelar(<?= $key ?>); return false" class="btn btn-default">cancelar</button>

                                                                <button type="submit" class="btn btn-danger">confirmar</button>
                                                            </div>

                                                        </form>
                                                    </div>




                                                <?php } ?>
                                                </table>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="tab-pane fade" id="crear-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">

                                        <div class="row justify-content-md-center mt-5">
                                            <div class="col-lg-6">
                                                <form action="" method="POST" class="row g-3 needs-validation" novalidate>
                                                    <input type="hidden" name="accion" value="crear">
                                                    <div class="form-floating mb-3 m-0 p-0">
                                                        <input type="text" name="usuario" class="form-control" id="floatingInput" placeholder="usuario" required>
                                                        <label for="floatingInput">Usuario</label>
                                                    </div>
                                                    <div class="form-floating m-0 p-0">
                                                        <input type="password" name="password" class="form-control" id="floatingPassword" placeholder="Password" required>
                                                        <label for="floatingPassword">Password</label>
                                                    </div>

                                                    <button type="submit" class="btn btn-lg btn-primary" style="background-color: #20A3FF;">
                                                        Crear usuario
                                                    </button>


                                                </form>
                                            </div>
                                        </div>


                                    </div>

                                    <div class="tab-pane fade <?= $tab_pane_archivo ?>" id="archivos-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">

                                        <div class="row p-3">
                                            <div class="col-lg-12">



                                                <?php

                                                // Directorio donde se encuentran los archivos
                                                $directorio = 'archivos/';

                                                // Obtiene una lista de todos los archivos .txt en el directorio
                                                $archivos = glob($directorio . "*.txt");

                                                // Lee el contenido de cada archivo
                                                foreach ($archivos as $archivo) {

                                                ?>
                                                    <form action="" method="POST">
                                                        <input type="hidden" name="archivo" value="<?= $archivo ?>">
                                                        <div class="row mt-1 p-2" id="linea_<?= str_replace('archivos/', '', $archivo) ?>">
                                                            <div class="col-lg-1" style="text-align: right;">
                                                                <a href="<?= $archivo ?>" download><i class="bi bi-file-earmark"></i></a>
                                                            </div>
                                                            <div class="col-lg-4">
                                                                <?= str_replace('archivos/', '', $archivo) ?>
                                                            </div>
                                                            <div class="col-lg-2" id="eliminar_<?= str_replace('.txt', '', str_replace('archivos/', '', $archivo)) ?>">
                                                                <a href="" onclick="eliminar('<?= str_replace('.txt', '', str_replace('archivos/', '', $archivo)) ?>'); return false" class="btn btn-sm btn-danger">eliminar</a>
                                                            </div>
                                                            <div class="col-lg-4" id="confirmar_<?= str_replace('.txt', '', str_replace('archivos/', '', $archivo)) ?>" style="display: none;">
                                                                <button onclick="cancelar('<?= str_replace('.txt', '', str_replace('archivos/', '', $archivo)) ?>'); return false" class="btn btn-sm btn-default">cancelar</button>
                                                                <button type="submit" class="btn btn-sm btn-danger">confirmar</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                <?php
                                                }

                                                ?>
                                            </div>
                                        </div>


                                    </div>

                                    <div class="tab-pane fade" id="clave-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
                                        <div class="row justify-content-md-center mt-5">
                                            <div class="col-lg-6">
                                                <form action="" method="POST" class="row g-3 needs-validation" novalidate>
                                                    Pegar aquí
                                                    <textarea rows="5" name="clave" class="form-control"></textarea>
                                                    <button type="submit" class="btn btn-lg btn-primary" style="background-color: #20A3FF;">
                                                        Cargar cookie
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
    <script>
        setTimeout(function() {
            $('#alerta').fadeOut()
        }, 1000);

        function eliminar(a) {
            $('#eliminar_' + a).hide();
            $('#confirmar_' + a).show();
        }

        function cancelar(a) {
            $('#eliminar_' + a).show();
            $('#confirmar_' + a).hide();
        }


        (() => {
            'use strict'

            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            const forms = document.querySelectorAll('.needs-validation')

            // Loop over them and prevent submission
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }

                    form.classList.add('was-validated')
                }, false)
            })
        })()
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</body>

</html>