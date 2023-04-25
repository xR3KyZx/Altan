<?php

include('../includes/variables.php');
include('../includes/bbdd.php');


$usuario = $_POST['usuario'];



        $sql = "UPDATE CARGA
        set status = 10
        WHERE usuario = '$usuario' AND status = 0";
        //echo $sql;
        $resultado = mysqli_query($conexion, $sql);


$sql = "SELECT * from CARGA
    WHERE usuario = '$usuario' AND status = 0 ORDER BY idcarga";
    //echo $sql;
    $resultado = mysqli_query($conexion, $sql);
    while ($row = mysqli_fetch_array($resultado)) {
        $listacarga[$row['idcarga']]['nir'] = $row['nir'];
        $listacarga[$row['idcarga']]['dn'] = $row['dn'];
    }



?>

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
        if(isset($listacarga))
        foreach ($listacarga as $key=>$value){
    ?>
        <tr>
        <td><?= $value['nir'] ?></td>
        <td><?= $value['dn'] ?></td>
        <td><div id="resultado_<?= $key ?>"></div></td>
        </tr>
    <?php
        
    }
    ?>
</table>

</div>


<?php
mysqli_close($conexion);
?>