<?php
// antes de cualquier línea de código html o php:
//evita que no se buque el header para redirigir a otra pagina
ob_start();
$idContrato = $_GET['id'];
$NombreContratistas = $_GET["nombre"];
$NombreSupervisor = $_GET["NombreSupervisor"];
$idActa=$_GET['idActa'];
$estado = $_GET['estado'];
$actaPendiente = $_GET['actaPendiente'];
$observaciones = $_GET['observaciones'];
echo "<br>".$observaciones;
echo $actaPendiente;
include('../../../db.php');
echo $idContrato ;

$query = "UPDATE `acta` SET `estado`='$estado',`observaciones`='$observaciones' WHERE id = $idActa";
$result = mysqli_query($conexion,$query);
//$result = false;


if($result){
    if($actaPendiente == "Si"){
        header("location:actasPendientes.php");   
    }else{
        header("location:listarActas.php?id=".$idContrato."&nombre=".$NombreContratistas ."&NombreSupervisor=".$NombreSupervisor);   
    }     
}else{
    ?>
<?php
    ?>
<h1 class="bad">ERROR EN LA EN EL PROCESO</h1>
<?php
}
//mysqli_free_result($resultado);
ob_end_flush();
mysqli_close($conexion);

?>