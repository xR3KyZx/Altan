<?php
session_start();
include('includes/bbdd.php');


$usuario = '';
$error = '';

if (isset($_POST['usuario'])) {
    $error = 'Usuario o contraseña incorrectos';
    $usuario = $_POST['usuario'];
    $password = hash('sha256', $_POST['password']);
    $sql = "SELECT * from USUARIO
    WHERE usuario = '$usuario' AND password = '$password' AND status = 1";
    //echo $sql;
    $resultado = mysqli_query($conexion, $sql);
    while ($row = mysqli_fetch_array($resultado)) {
        $error = '';
        $admin = $row['admin'];
        $_SESSION['WORK_FORM_usuario'] = $row['usuario'];

        if ($admin == 1) {
            header('Location: admin');
        }
        if ($admin == 0) {
            header('Location: index.php');
        }
    }
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
</head>
<style>

</style>

<body style="background-color: #efefef;">
    <div class="container">
        <div class="row justify-content-md-center mt-5">
            <div class="col-lg-3">
                <div class="card w-100 shadow p-3">
                    <!--
                    <img src="..." class="card-img-top" alt="...">
-->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12 text-center">
                                <form action="" method="POST" class="row g-3 needs-validation" novalidate>
                                    <h4>Iniciar sesión</h4>
                                    <hr>

                                    <div class="text-danger lh-1 p-0 m-0"><?= $error ?></div>

                                    <input type="text" name="usuario" value="<?= $usuario ?>" placeholder="usuario" class="form-control" required>
                                    <div class="invalid-feedback">
                                        Ingresar usuario
                                    </div>

                                    <input type="password" name="password" placeholder="contraseña" class="form-control" required>
                                    <div class="invalid-feedback">
                                        Ingresar contraseña
                                    </div>


                                    <input type="submit" class="btn btn-primary" style="background-color: #20A3FF;">


                               

                                </form>
                            </div>
                        </div>




                    </div>
                </div>
            </div>
        </div>

    </div>
    <script>
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