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

include('../../../db.php');
$idAlcance= $_POST['idAlcance'];
$idContrato = $_POST["idContrato"];
$NombreContratistas = $_POST["nombre"];
$Alcance = $_POST["alcance"];

echo $idAlcance;
//$query= "UPDATE usuario SET usuario=$usuario,clave=$contraseña,nombre=$nombre, apellidos=$apellidos, cedula=$cedula, last_num_cc=$last_num_cc, telefono=$telefono, correo=$correo, direccion=$direccion, profesion=$profesion,cargo=$cargo, tipo_persona=$tipo_persona,resp_iva=$resp_iva,idRol=$idRol WHERE id = $idUsuario";
$query = "UPDATE `alcance` SET `nombre`='$Alcance' WHERE id = $idAlcance";
$result = mysqli_query($conexion,$query);



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
