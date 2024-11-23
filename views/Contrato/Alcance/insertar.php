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
$NombreContratistas = $_POST["nombre"];
$Alcance = $_POST["alcance"];

//Registro Alcance
$query = "INSERT INTO alcance(`nombre`, `idContrato`, `impacto`) VALUES ('$Alcance','$idContrato','No')";
$result = mysqli_query($conexion,$query) or die ("No se puede establecer conexion con la DB.");


if($result){
    header("location:listar.php?id=".$idContrato."&nombre=".$NombreContratistas);
}else{
    ?>
    <?php
    ?>
    <h1 class="bad">ERROR EN LA AUTENTIFICACION PARA EL ALCANCE</h1>
    <?php
}
ob_end_flush();
mysqli_close($conexion);
?>