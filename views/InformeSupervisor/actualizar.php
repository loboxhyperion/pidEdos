<?php
//seguridad de sessiones paginacion
session_start();
error_reporting(0);

//si no hay algun usuario registradose devuelve al login
if(!isset($_SESSION['rol'])){
    header("location:../../index.php");
}else{   
    //solo tiene permiso el supervisor
    if($_SESSION['rol'] == 1 or $_SESSION['rol'] == 2 or $_SESSION['rol'] == 4 or $_SESSION['rol'] == 5 ){
        header("location:../../index.php");
    }
}
// antes de cualquier línea de código html o php:
//evita que no se buque el header para redirigir a otra pagina
ob_start();
include('../../db.php');
$idInformeSupervisor = $_POST["idInformeSupervisor"]; 
$idTrimestral = $_POST["idTrimestral"]; 

$saldo=$_POST['saldo'];
$alcances_impactados=$_POST['alcances_impactados'];
$alcances_avances=$_POST['alcances_avances'];
$ejecucion_financiera=$_POST['ejecucion_financiera'];
$seguridad_pagada_avg=$_POST['seguridad_pagada_avg'];
$seguridad_real_avg=$_POST['seguridad_real_avg'];
$diferencia=$_POST['diferencia'];
$observaciones = $_POST['observaciones'];

for($i = 0; $i < count($observaciones); $i++){
    $query = "UPDATE `informe_trimestral` SET `saldo`='$saldo[$i]',  `alcances_impactados`='$alcances_impactados[$i]',  `alcances_avances`='$alcances_avances[$i]',  `ejecucion_financiera`='$ejecucion_financiera[$i]',  `seguridad_pagada_avg`='$seguridad_pagada_avg[$i]',  `seguridad_real_avg`='$seguridad_real_avg[$i]',  `diferencia`='$diferencia[$i]', `observaciones`= '$observaciones[$i]' WHERE id = $idTrimestral[$i] AND idInformeSupervisor = $idInformeSupervisor";
    $result = mysqli_query($conexion,$query); //or die ("No se puede establecer conexion con la DB.");
}

//$result = false;

//si todo fue correcto pasa
//Para realizar los Alcances---------------------------------------------------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------------------------------------------------
if($result){
    header("location:listar.php");     
}else{
    ?>
    <?php
    if(mysqli_error($conexion)){
        $message = "El contrato ya ha sido creado";
        header("location:nuevo.php?mensaje=".$message);
        //echo "<br>". $message ;
    }
    ?>
    <h1 class="bad">ERROR EN LA AUTENTIFICACION DEL Informe Trimestral</h1>
    <?php
}
//mysqli_free_result($resultado);
ob_end_flush();
mysqli_close($conexion);
?>