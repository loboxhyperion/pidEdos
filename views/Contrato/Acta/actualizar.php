<?php
// antes de cualquier línea de código html o php:
//evita que no se buque el header para redirigir a otra pagina
ob_start();
include('../../../db.php');
$idContrato = $_POST['idContrato'];
$NombreContratistas = $_POST["nombre"];
$NombreSupervisor = $_POST["nombreSupervisor"];
$idActa = $_POST['idActa'];
$fecha_informe= $_POST['fecha_informe'];
$numPlanilla = $_POST['numPlanilla'];
$fechaPlanilla = $_POST['fechaPlanilla'];
$valorPlanilla = $_POST['valorPlanilla'];
$idAlcances =$_POST['idAlcance'];
$Actividades= $_POST['actividad'];
$Ubicaciones = $_POST['ubicacion'];
$observaciones = $_POST['observaciones'];
$valorPlanillaReal = $_POST['valorPlanillaReal'];

$mas_cotizacion = $_POST['mas_cotizacion'];

/*echo "<br>". $fecha_informe;
echo "<br>". $NombreContratistas;
echo "<br>". $NombreSupervisor ;
echo "<br>". $numPlanilla;
echo "<br>". $fechaPlanilla;
echo "<br>". $valorPlanilla;
echo "<br>". $idContrato;
echo "<br>". $idActa ;
for($i = 0; $i < count($idAlcances); $i++){
    echo "<br> alcance:". $idAlcances[$i];
    echo "<br> Actividad:". $Actividades[$i];
    echo "<br> Ubicacion:". $Ubicaciones[$i];
}
*/

$query = "UPDATE `acta` SET `fecha_informe`='$fecha_informe',`numPlanilla`='$numPlanilla',`fechaPlanilla`='$fechaPlanilla',`valorPlanilla`='$valorPlanilla',
         `valorPlanillaReal`='$valorPlanillaReal',`observaciones`='$observaciones',`mas_cotizacion`='$mas_cotizacion' WHERE id = $idActa";

$result = mysqli_query($conexion,$query) or die ("No se puede establecer conexion con la DB en acta e.");


//si todo fue correcto pasa
//Para realizar las Actividades---------------------------------------------------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------------------------------------------------
if($result){

    //insertamos las actividades 
    for($i = 0; $i < count($idAlcances); $i++){
        //echo $Alcances[$i];
        $query1 = "UPDATE `actividad` SET `descripcion`='$Actividades[$i]', `ubicacion`='$Ubicaciones[$i]' WHERE idActa = $idActa AND idAlcance = $idAlcances[$i]";
        $result1 = mysqli_query($conexion,$query1) or die ("No se puede establecer conexion con la DB en actividades.");

        //Comprueba si el alcance se impacto
        if($Actividades[$i] <> "" && $Ubicaciones[$i] <> ""){
            //actualizar el valor de si se impacto o no el alcance
            $query2 = "UPDATE `alcance` SET `impacto`='Si' WHERE id = $idAlcances[$i]";
            $result2 = mysqli_query($conexion,$query2);
        }else{
            //actualizar el valor de si se impacto o no el alcance
            $query2 = "UPDATE `alcance` SET `impacto`='No' WHERE id = $idAlcances[$i]";
            $result2 = mysqli_query($conexion,$query2);
        }
    }
    //Para realizar las Retenciones---------------------------------------------------------------------------------------------------------------------------------------
    //---------------------------------------------------------------------------------------------------------------------------------------
    //---------------------------------------------------------------------------------------------------------------------------------------
    //---------------------------------------------------------------------------------------------------------------------------------------
    //---------------------------------------------------------------------------------------------------------------------------------------
    //---------------------------------------------------------------------------------------------------------------------------------------
    //---------------------------------------------------------------------------------------------------------------------------------------
    //---------------------------------------------------------------------------------------------------------------------------------------
    //---------------------------------------------------------------------------------------------------------------------------------------
    if($result1){
        header("location:listarActas.php?id=".$idContrato ."&nombre=".$NombreContratistas."&NombreSupervisor=".$NombreSupervisor);     
    }else{
        ?>
<?php
        ?>
<h1 class="bad">ERROR EN LA AUTENTIFICACION PARA LAS ACTIVIDADES</h1>
<?php
    }

}else{
    ?>
<?php
    ?>
<h1 class="bad">ERROR EN LA AUTENTIFICACION PARA LAS ACTAS</h1>
<?php
}
//mysqli_free_result($resultado);
ob_end_flush();
mysqli_close($conexion);
?>