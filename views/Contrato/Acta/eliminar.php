<?php
include('../../../db.php'); 
$idContrato = $_GET['id'];
$NombreContratistas = $_GET["nombre"];
$NombreSupervisor = $_GET["nombreSupervisor"];
$idActa = $_GET['idActa'];

$query= "DELETE FROM acta  WHERE id = $idActa";
$result = mysqli_query($conexion,$query) or die("fallo en la conexión");


if($result){
    header("location:listarActas.php?id=".$idContrato ."&nombre=".$NombreContratistas."&NombreSupervisor=".$NombreSupervisor);
}else{
    ?>
    <?php
    ?>
    <h1 class="bad">ERROR EN LA AUTENTIFICACION</h1>
    <?php
}
//mysqli_free_result($resultado);
mysqli_close($conexion);