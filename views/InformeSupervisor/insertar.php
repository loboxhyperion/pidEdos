<?php
//seguridad de sessiones paginacion
session_start();
error_reporting(0);

//si no hay algun usuario registradose devuelve al login
if (!isset($_SESSION['rol'])) {
    header("location:../../index.php");
} else {
    //solo tiene permiso el supervisor
    if ($_SESSION['rol'] == 1 or $_SESSION['rol'] == 2 or $_SESSION['rol'] == 4 or $_SESSION['rol'] == 5) {
        header("location:../../index.php");
    }
}
// antes de cualquier línea de código html o php:
//evita que no se buque el header para redirigir a otra pagina
ob_start();
include('../../db.php');
$semestre = $_POST["trimestre"];
//representa los 4 trimestres
//$meses =  array("","Enero-Febrero-Marzo","Abril-Mayo-Junio","Julio-Agosto-Septiembre","Octubre-Noviembre-Diciembre");
$meses =  array("", "Enero-Febrero-Marzo-Abril-Mayo-Junio", "Julio-Agosto-Septiembre-Octubre-Noviembre-Diciembre");
$fecha_create = date('d-m-Y');
$idSupervisor = $_POST['idSupervisor'];

$num_contrato = isset($_POST['num_contrato']) ? $_POST['num_contrato']: 0;
$acomulado = isset($_POST['acumulado']) ? $_POST['acumulado'] : 0;
$saldo = isset($_POST['saldo']) ? $_POST['saldo'] : 0;
$alcances_num = isset($_POST['alcances_num']) ? $_POST['alcances_num']: 0;
$alcances_impactados = isset($_POST['alcances_impactados']) ? $_POST['alcances_impactados'] : 0;
$alcances_avances = isset($_POST['alcances_avances']) ? $_POST['alcances_avances'] : 0;
$seguridad_pagada_avg = isset($_POST['seguridad_pagada_avg']) ? $_POST['seguridad_pagada_avg'] : 0;
$seguridad_real_avg = isset($_POST['seguridad_real_avg']) ? $_POST['seguridad_real_avg'] : 0;
$observaciones = isset($_POST['observaciones']) ? $_POST['observaciones']: '';
$contratista = isset($_POST['contratista']) ? $_POST['contratista'] : '';
$ejecucion_financiera = isset($_POST['ejecucion_financiera']) ? $_POST['ejecucion_financiera'] : 0;
$diferencia = isset($_POST['diferencia']) ? $_POST['diferencia'] : 0;
$valorContrato = isset($_POST['valor_contrato']) ? $_POST['valor_contrato'] : 0;

echo "<br>".$meses[$semestre];
echo "<br>jj ".count($_POST['acumulado']);


$query = "INSERT INTO informe_supervisor (`trimestre`, `meses`, `fecha_create`, `estado`, `idSupervisor`) 
         VALUES ('$semestre','$meses[$semestre]','$fecha_create','Pendiente','$idSupervisor')";

$result = mysqli_query($conexion,$query); //or die ("No se puede establecer conexion con la DB.");
// $result = false;
//$result = true;

//si todo fue correcto pasa
//Para realizar los Alcances---------------------------------------------------------------------------------------------------------------------------------------
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
    $idInformeSupervisor = $conexion->insert_id;
    //insertamos las actividades 
    for ($i = 0; $i < count($num_contrato); $i++) {
        $query2 = "INSERT INTO informe_trimestral (`num_contrato`, `acomulado`, `saldo`, `alcances_num`, `alcances_impactados`, `alcances_avances`, `seguridad_pagada_avg`,seguridad_real_avg,
                  `observaciones`, `contratista`, `idInformeSupervisor`, `ejecucion_financiera`,`diferencia`,`valorContrato`) VALUES ('$num_contrato[$i]','$acomulado[$i]','$saldo[$i]',
                  '$alcances_num[$i]','$alcances_impactados[$i]','$alcances_avances[$i]','$seguridad_pagada_avg[$i]','$seguridad_real_avg[$i]','$observaciones[$i]','$contratista[$i]',
                  '$idInformeSupervisor','$ejecucion_financiera[$i]','$diferencia[$i]','$valorContrato[$i]')";

        $result2 = mysqli_query($conexion, $query2); //or die ("No se puede establecer conexion con la DB.");
        //$result2 = false;
        echo "<br>". $num_contrato[$i];
        echo "<br>". $acomulado[$i];
        echo "<br>". $saldo[$i];
        echo "<br>". $alcances_num[$i];
        echo "<br>". $alcances_impactados[$i];
        echo "<br>". $alcances_avances[$i];
        echo "<br>". $ejecucion_financiera[$i];
        echo "<br>". $seguridad_pagada_avg[$i];
        echo "<br>". $seguridad_real_avg[$i];
        echo "<br>". $contratista[$i];
        
        echo "<br>". $idInformeSupervisor;
        
        echo "<br>". $observaciones[$i];
        echo "<br>". $diferencia[$i];
        echo "<br>". $valorContrato[$i];
    }
    //---------------------------------------------------------------------------------------------------------------------------------------
    if ($result2) {
        header("location:listar.php");
    } else {
        //header("location:listar.php");
?>
        <?php
        ?>
        <h1 class="bad">ERROR EN LA AUTENTIFICACION DEL Informe Semestral</h1>
    <?php
    }
} else {
    ?>
    <?php
    if (mysqli_error($conexion)) {
        $message = "El contrato ya ha sido creado";
        header("location:nuevo.php?mensaje=" . $message);
        //echo "<br>". $message ;
    }
    ?>
    <h1 class="bad">ERROR EN LA AUTENTIFICACION DEL Informe Supervisor</h1>
<?php
}
//mysqli_free_result($resultado);
ob_end_flush();
mysqli_close($conexion);
?>