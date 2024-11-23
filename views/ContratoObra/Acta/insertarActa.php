<?php
// antes de cualquier línea de código html o php:
//evita que no se buque el header para redirigir a otra pagina
ob_start();
include('../../../db.php'); 
$NombreContratistas = $_POST["nombre"];
$NombreSupervisor = $_POST["nombreSupervisor"];
$numInforme = $_POST['num_informe'];
$fecha_informe= $_POST['fecha_informe'];
$fecha_corte= $_POST['fecha_corte'];
$valorBruto= $_POST['valorBruto'];
$anticipo = $_POST['anticipo'];
$amortizacion = $_POST['amortizacion'];
$valorPagado = $_POST['valorPagado'];
$saldo = $_POST['saldo'];
$observaciones = $_POST['observaciones'];
$idContrato = $_POST['idContrato'];
$idSupervisor = $_POST['idSupervisor'];
$encargado = $_POST['encargado'];
$idUsuario = $_POST['idUsuario'];
 


//echo "<br>". $fecha_informe;
/*echo "<br>". $fecha_ini;
echo "<br>". $fecha_fin;
echo "<br>". $diasPagos;
echo "<br>". $valor;
echo "<br>". $acumulado;
echo "<br>". $saldo;
echo "<br>". $numPlanilla;
echo "<br>". $fechaPlanilla;
echo "<br>". $valorPlanilla;
echo "<br>". $idContrato;
echo "<br>". $idSupervisor;
for($i = 0; $i < count($idAlcances); $i++){
    echo "<br> alcance:". $idAlcances[$i];
    echo "<br> Actividad:". $Actividades[$i];
    echo "<br> Ubicacion:". $Ubicaciones[$i];
}*/




$query = "INSERT INTO acta_obra (`num_informe`,`fecha_informe`, `fecha_corte`, `valorBruto`, `anticipo`, `amortizacion`, `valorPagado`, `saldo`, `estado`, `observaciones`, `idSupervisor`,`idContrato`, `encargado`, `idUsuario`) 
                      VALUES ('$numInforme','$fecha_informe','$fecha_corte','$valorBruto','$anticipo','$amortizacion','$valorPagado','$saldo','Pendiente','$observaciones','$idSupervisor','$idContrato','$encargado', '$idUsuario')";

$result = mysqli_query($conexion,$query); //or die ("No se puede establecer conexion con la DB en acta.");
//echo mysqli_error($conexion);

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
        if(mysqli_error($conexion)){
            $message = "El acta ya ha sido creado";
            header("location:nuevaActa.php?id=".$idContrato ."&nombre=".$NombreContratistas."&NombreSupervisor=".$NombreSupervisor."&num_informe=".$numInforme."&mensaje=".$message);
            //echo "<br>". $message ;
        }
    ?>
    <h1 class="bad">ERROR EN LA AUTENTIFICACION PARA LAS ACTAS</h1>
    <?php
}
//mysqli_free_result($resultado);
ob_end_flush();
mysqli_close($conexion);
?>