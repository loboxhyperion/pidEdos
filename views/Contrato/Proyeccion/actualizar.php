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
$idProyeccion= $_POST['idProyeccion'];
$idContrato = $_POST["idContrato"];
$NombreContratistas = $_POST["nombre"];
$periodo_ini = date("d-m-Y", strtotime($_POST['periodo_ini']));
$periodo_fin = date("d-m-Y", strtotime($_POST['periodo_fin']));
$dias = $_POST["dias"];
$valor_dia = $_POST["valor_dia"];
$valor_mes = $_POST["valor_mes"];
$acumulado = $_POST["acomulado"];
$saldo = $_POST["saldo"];

//$query= "UPDATE usuario SET usuario=$usuario,clave=$contraseña,nombre=$nombre, apellidos=$apellidos, cedula=$cedula, last_num_cc=$last_num_cc, telefono=$telefono, correo=$correo, direccion=$direccion, profesion=$profesion,cargo=$cargo, tipo_persona=$tipo_persona,resp_iva=$resp_iva,idRol=$idRol WHERE id = $idUsuario";
$query = "UPDATE `proyeccion_contractual` SET `periodo_ini`='$periodo_ini', `periodo_fin`='$periodo_fin', `dias`='$dias', `valor_dia`='$valor_dia', `valor_mes`='$valor_mes', `acomulado`='$acumulado', `saldo`='$saldo' WHERE id = $idProyeccion";
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
