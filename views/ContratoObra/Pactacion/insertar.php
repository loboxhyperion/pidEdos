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
$Item = $_POST["item"];
$Codigo = $_POST["codigo"];
$Descripcion = $_POST["descripcion"];
$Unidad = $_POST["unidad"];
$Cantidad = $_POST["cantidad"];
$Valor = $_POST["valor"];
$ValorTotal = $Cantidad * $Valor;

echo "<br>".$idContrato;
echo "<br>".$NombreContratistas;
echo "<br>".$Item;
echo "<br>".$Codigo;
echo "<br>".$Descripcion;
echo "<br>".$Unidad;
echo "<br>".$Valor;
echo "<br>".$ValorTotal;


//Registro Alcance
$query = "INSERT INTO pactacion(`num_item`, `codigo`, `descripcion`, `unidad`, `cantidad`, `valor`, `valorTotal`, `impacto`, `idContrato`) VALUES ('$Item','$Codigo','$Descripcion','$Unidad','$Cantidad','$Valor','$ValorTotal','No','$idContrato')";
$result = mysqli_query($conexion,$query) or die ("No se puede establecer conexion con la DB.");
// $result = false;


if($result){
    header("location:listar.php?id=".$idContrato."&nombre=".$NombreContratistas);
}else{
    ?>
    <?php
    ?>
    <h1 class="bad">ERROR EN LA AUTENTIFICACION PARA EL ITEM</h1>
    <?php
}
ob_end_flush();
mysqli_close($conexion);
?>