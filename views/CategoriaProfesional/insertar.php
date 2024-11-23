<?php
//si no hay algun usuario registradose devuelve al login
if(!isset($_SESSION['rol'])){
    header("location:../../index.php");
}else{   
    //solo tiene permiso el admin y creador
    if($_SESSION['rol'] == 3 or $_SESSION['rol'] == 4  ){
        header("location:../../index.php");
    }
}
$nombre=$_POST['nombre'];
$valor=$_POST['valor'];



include('../../db.php');

$consulta= "INSERT INTO categoria(`nombre`, `valor`, `tipo` ) VALUES ('$nombre','$valor','Obra')";
$resultado = mysqli_query($conexion,$consulta);


if($resultado){
    header("location:listar.php");
}else{
    ?>
    <?php
    ?>
    <h1 class="bad">ERROR EN LA AUTENTIFICACION</h1>
    <?php
}
//mysqli_free_result($resultado);
mysqli_close($conexion);
