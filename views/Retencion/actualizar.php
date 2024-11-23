<?php
//seguridad de sessiones paginacion
session_start();
error_reporting(0);

//si no hay algun usuario registradose devuelve al login
if(!isset($_SESSION['rol'])){
    header("location:../../index.php");
}else{   
    //solo tiene permiso el admin y creador
    if($_SESSION['rol'] == 3 or $_SESSION['rol'] == 4  ){
        header("location:../../index.php");
    }
}
$id=$_POST['id'];
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

//$query= "UPDATE usuario SET usuario=$usuario,clave=$contraseña,nombre=$nombre, apellidos=$apellidos, cedula=$cedula, last_num_cc=$last_num_cc, telefono=$telefono, correo=$correo, direccion=$direccion, profesion=$profesion,cargo=$cargo, tipo_persona=$tipo_persona,resp_iva=$resp_iva,idRol=$idRol WHERE id = $idUsuario";
$query = "UPDATE `retencion` SET `nombre`='$nombre',`porcentaje`='$porcentaje',`tipo`='$tipo',`orden`='$orden' WHERE id = $id";
$result = mysqli_query($conexion,$query);


if($result){
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
