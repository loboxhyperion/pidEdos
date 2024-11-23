<?php
//seguridad de sessiones paginacion
session_start();
error_reporting(0);

//si no hay algun usuario registradose devuelve al login
if(!isset($_SESSION['rol'])){
    header("location:../../../index.php");
}else{   
    //solo tiene permiso el admin y creador
    if($_SESSION['rol'] == 3 or $_SESSION['rol'] == 4  ){
        header("location:../../../index.php");
    }
}
$idEstampilla = $_GET['idEstampilla'];
$idContrato=$_GET['id'];
$NombreContratistas = $_GET["nombre"];

include('../../../db.php');

$query= "DELETE FROM `contrato_retencion` WHERE idRetencion = $idEstampilla";
$result = mysqli_query($conexion,$query) or die("fallo en la conexión");


if($result){
    header("location:listar.php?id=".$idContrato."&nombre=".$NombreContratistas);
}else{
    ?>
    <?php
    ?>
    <h1 class="bad">ERROR EN LA AUTENTIFICACION PARA EL ALCANCE</h1>
    <?php
}
//mysqli_free_result($resultado);
mysqli_close($conexion);