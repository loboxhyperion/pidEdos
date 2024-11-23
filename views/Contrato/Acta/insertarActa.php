<?php
// antes de cualquier línea de código html o php:
//evita que no se buque el header para redirigir a otra pagina
ob_start();
include('../../../db.php');
$NombreContratistas = $_POST["nombre"];
$NombreSupervisor = $_POST["nombreSupervisor"];
$numInforme = $_POST['num_informe'];
$idContrato = $_POST['idContrato'];
$idSupervisor = $_POST['idSupervisor'];
$fecha_informe = $_POST['fecha_informe'];
$fecha_ini = $_POST['fecha_ini'];
$fecha_fin = $_POST['fecha_fin'];
$diasPagos = $_POST['diasPagos'];
$valor = $_POST['valor'];
$acumulado = $_POST['acumulado'];
$saldo = $_POST['saldo'];
$numPlanilla = $_POST['numPlanilla'];
$fechaPlanilla = $_POST['fechaPlanilla'];
$valorPlanilla = $_POST['valorPlanilla'];
$idAlcances = $_POST['idAlcance'];
$Actividades = $_POST['actividad'];
$Ubicaciones = $_POST['ubicacion'];
$observaciones = $_POST['observaciones'];
$valorPlanillaReal = $_POST['valorPlanillaReal'];
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

// Rectifica que la fecha del informe no sea inferior a la fecha fin del periodo
$fecha_fin_periodo = date("Y-m-d", strtotime($fecha_fin));
echo "<br>" . $fecha_fin_periodo;
if ($fecha_informe < $fecha_fin_periodo) {
    $message = "La fecha del informe debe cumplir el periodo de vencimiento";
    header("location:nuevaActa.php?id=" . $idContrato . "&nombre=" . $NombreContratistas . "&NombreSupervisor=" . $NombreSupervisor . "&num_informe=" . $numInforme . "&mensaje=" . $message);
} else {
    $query = "INSERT INTO acta (`num_informe`,`fecha_informe`, `fecha_ini`, `fecha_fin`, `diasPagos`, `valor`, `acumulado`, `saldo`, `numPlanilla`, `fechaPlanilla`, `valorPlanilla`, `estado`, `observaciones`, `idSupervisor`,`idContrato`,`valorPlanillaReal`, `encargado`, `idUsuario`) 
                      VALUES ('$numInforme','$fecha_informe','$fecha_ini','$fecha_fin','$diasPagos','$valor','$acumulado','$saldo','$numPlanilla','$fechaPlanilla','$valorPlanilla','Pendiente','$observaciones','$idSupervisor','$idContrato','$valorPlanillaReal','$encargado', '$idUsuario')";

    $result = mysqli_query($conexion, $query); //or die ("No se puede establecer conexion con la DB en acta.");
    // $result = false;
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
    if ($result) {

        /// obtiene el último id del registro insertado
        $idActa = $conexion->insert_id;

        //insertamos las actividades 
        for ($i = 0; $i < count($idAlcances); $i++) {
            //echo $Alcances[$i];
            $query1 = "INSERT INTO actividad(`descripcion`, `ubicacion`, `numInforme`,`idActa`, `idAlcance`, `idContrato`) VALUES ('$Actividades[$i]','$Ubicaciones[$i]','$numInforme','$idActa','$idAlcances[$i]','$idContrato')";
            $result1 = mysqli_query($conexion, $query1) or die("No se puede establecer conexion con la DB en actividades.");
            //Comrpueba si el alcance se impacto
            if ($Actividades[$i] <> "" && $Ubicaciones[$i] <> "") {
                //actualizar el valor de si se impacto o no el alcance
                $query2 = "UPDATE `alcance` SET `impacto`='Si' WHERE id = $idAlcances[$i]";
                $result2 = mysqli_query($conexion, $query2);
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

        if ($result1) {
            header("location:listarActas.php?id=" . $idContrato . "&nombre=" . $NombreContratistas . "&NombreSupervisor=" . $NombreSupervisor);
        } else {
?>
            <?php
            ?>
            <h1 class="bad">ERROR EN LA AUTENTIFICACION PARA LAS ACTIVIDADES</h1>
        <?php
        }
    } else {
        ?>
        <?php
        if (mysqli_error($conexion)) {
            $message = "El acta ya ha sido creado";
            header("location:nuevaActa.php?id=" . $idContrato . "&nombre=" . $NombreContratistas . "&NombreSupervisor=" . $NombreSupervisor . "&num_informe=" . $numInforme . "&mensaje=" . $message);
            //echo "<br>". $message ;
        }
        ?>
        <h1 class="bad">ERROR EN LA AUTENTIFICACION PARA LAS ACTAS</h1>
<?php
    }
}



//mysqli_free_result($resultado);
ob_end_flush();
mysqli_close($conexion);
?>