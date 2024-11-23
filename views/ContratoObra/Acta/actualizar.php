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
$fecha_corte= $_POST['fecha_corte'];
$valorBruto= $_POST['valorBruto'];
$anticipo = $_POST['anticipo'];
$amortizacion = $_POST['amortizacion'];
$valorPagado = $_POST['valorPagado'];
$saldo = $_POST['saldo'];
$observaciones = $_POST['observaciones'];


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


$query = "UPDATE `acta` SET `fecha_informe`='$fecha_informe',`fecha_corte`='$fecha_corte',`valorBruto`='$valorBruto',`anticipo`='$anticipo',`amortizacion`= $amortizacion,`valorPagado`= $valorPagado,`saldo`= $saldo,`observaciones`='$observaciones' WHERE id = $idActa";
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
    header("location:listarActas.php?id=".$idContrato ."&nombre=".$NombreContratistas."&NombreSupervisor=".$NombreSupervisor); 
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