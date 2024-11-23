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
$idModificaciones = $_GET['id'];
$valorDia = $_GET["valorDia"];
$idContrato = $_GET["idContrato"];
$fecha_ini =  $_GET["fecha_ini"];

include('../../../db.php');
//elimina las modificaciones contractuales
$query= "DELETE FROM `proyeccion_contractual` WHERE idModificaciones = $idModificaciones AND idModificaciones != 0";
$result = mysqli_query($conexion,$query) or die("fallo en la conexión1");

if($result){
    $query2= "DELETE FROM `adicion_suspension` WHERE id = $idModificaciones";
    $result2 = mysqli_query($conexion,$query2) or die("fallo en la conexión");

    if($result2){
         //consulto la ultima modificacion para actualizarla a prioridad 1
        $query3 = "SELECT * FROM  `adicion_suspension` WHERE idContrato = $idContrato  ORDER BY id DESC LIMIT 1";
        $result3 = mysqli_query($conexion,$query3) or die("fallo en la conexión2");
        
        //siempre y cuando haya modificaciones
        if($numRow = mysqli_fetch_array($result3)){  
            echo $numRow["id"];
            $query4 = "UPDATE `proyeccion_contractual` SET `prioridad`= 1 WHERE idModificaciones = $numRow[id]";
            $result4 = mysqli_query($conexion,$query4) or die("fallo en la conexión3");
        }else{//para el caso de la proyeccion inicial
            $query4 = "UPDATE `proyeccion_contractual` SET `prioridad`= 1 WHERE idContrato = $idContrato";
            $result4 = mysqli_query($conexion,$query4) or die("fallo en la conexión4");
        }
    }
    header("location:listar.php?id=".$idContrato."&fecha_ini=".$fecha_ini."&valorDia=".$valorDia);
}else{
    ?>
    <?php
    ?>
    <h1 class="bad">ERROR EN LA AUTENTIFICACION PARA LA MODIFICACION CONTRACTUAL</h1>
    <?php
}
//mysqli_free_result($resultado);
mysqli_close($conexion);