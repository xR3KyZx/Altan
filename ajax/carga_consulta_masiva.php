<?php

include('../includes/variables.php');


$usuario = $_POST['usuario'];
$nir = $_POST['nir'];
$lineas = explode("\n", $_POST['valores']);
$lineas = array_map('trim', $lineas);

$i = 0;
foreach ($lineas as $value){
    if(trim($value) != ''){
        $listacarga[$i]['nir'] = $nir;
        $listacarga[$i]['dn'] = $value;
        $i++;
    }

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


