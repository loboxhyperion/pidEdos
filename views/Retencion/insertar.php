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
$porcentaje=$_POST['porcentaje'];
$tipo=$_POST['tipo'];
switch($tipo){
    case "Base":
        $orden=1;
    break;
    case "Impuesto":
        $orden=2;
    break;
    case "ARL":
        $orden=3;
    break;
    case "Estampilla":
        $orden=4;
    break;
}





include('../../db.php');

$consulta= "INSERT INTO retencion(`nombre`, `porcentaje`, `tipo`, `orden`) VALUES ('$nombre','$porcentaje','$tipo','$orden')";
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
