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
// antes de cualquier línea de código html o php:
//evita que no se buque el header para redirigir a otra pagina
ob_start();

include('../../../db.php'); 

$idContrato = $_POST["idContrato"];
$fecha_ini = $_POST["fecha_ini"];
$valorDia = $_POST["valorDia"];

//del Formulario
$idUsuario = $_POST["idUsuario"];


$fecha_modificacion = date("d",strtotime("-1 days"))."-".date("m")."-".date("Y");
$fecha_suspension = date("d",strtotime("-1 days"))."-".date("m")."-".date("Y");
$fecha_reinicio = date("d",strtotime("-1 days"))."-".date("m")."-".date("Y");
//echo "<br> <strong>Inicio Suspension</strong> :".  $fecha_fin_pre . " // ". "<strong>Reinicio:</strong>". $fecha_fin_new ;



$dias = 0;
$valor = '0';




 //Registro Cesion 
 $query = "INSERT INTO `adicion_suspension`(`tipo`, `fecha_modificacion`, `fecha_suspension`, `fecha_reinicio`, `valor`, `dias`, `diasTotal`, `fecha_terminacion_pre`, `fecha_terminacion_new`, `idContrato`)
 VALUES ('Cesion','$fecha_modificacion','$fecha_suspension','$fecha_reinicio','$valor','$dias','$dias','$fecha_modificacion','$fecha_modificacion','$idContrato')";
 $result = mysqli_query($conexion,$query);

 if($result){
 //if(false){
    $query2 = "UPDATE `contrato` SET `idUsuario`='$idUsuario' WHERE id = $idContrato";
    $result2 = mysqli_query($conexion,$query2) or die ("No se puede establecer conexion con la DBs.");
    header("location:listar.php?id=$idContrato&fecha_ini=$fecha_ini&valorDia=$valorDia");
 }else{
      ?>
      <?php
      ?>
      <h1 class="bad">ERROR EN adicion_suspension </h1>
      <?php
  }


ob_end_flush();
mysqli_close($conexion);
?>