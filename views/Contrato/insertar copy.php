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
// antes de cualquier línea de código html o php:
//evita que no se buque el header para redirigir a otra pagina
ob_start();
include('../middleware/proyeccion.php');
include('../../db.php'); 
$registro_pptal = $_POST['registro_pptal'];
$rubro= $_POST['rubro'];
$disp_presupuestal= $_POST['disp_presupuestal'];
$years= $_POST['año'];
$num_contrato= $_POST['num_contrato'];
$contratante= "INSTITUTO DE DESARROLLO MUNICIPAL DE DOSQUEBRADAS";
$fecha_delegacion= $_POST['fecha_delegacion'];
$num_delegacion= $_POST['num_delegacion'];
$area= $_POST['area'];
$fecha_ini= $_POST['fecha_ini'];
$fecha_fin= $_POST['fecha_fin'];
$valorMes= $_POST['valorMes'];
$valorDia= $valorMes / 30;
$objeto= $_POST['objeto'];
$forma_pago= $_POST['forma_pago'];
$entregables= $_POST['entregables'];
$salud= $_POST['salud'];
$pension= $_POST['pension'];
$arl= $_POST['arl'];
$fecha_activacion= $_POST['fecha_activacion'];
$observaciones= $_POST['observaciones'];
$idUsuario= $_POST['idUsuario'];
$idSupervisor= $_POST['idSupervisor'];
$idRetenciones= $_POST['idRetencion'];
$modalidad = $_POST['modalidad'];
$anticipo = 0;

$fecha_necesidad = $_POST['fecha_necesidad'];
$fecha_firma = $_POST['fecha_firma'];
$idSupervisor2 = $_POST['idSupervisor2'];



$fecha_ini_pro= strtotime($_POST['fecha_ini']);
$fecha_fin_pro= strtotime($_POST['fecha_fin']);
$fecha_suspension = date("d",strtotime("-1 days"))."-".date("m")."-".date("Y");
$fecha_reinicio = date("d",strtotime("-1 days"))."-".date("m")."-".date("Y");


function calcularDiasRango($inicio, $fin) {
    $fechaInicio = new DateTime($inicio);
    $fechaFin = new DateTime($fin);
    $fechaFin->modify('+1 day'); // Incluimos el día final en el rango

    $diasTotales = 0;
    $febreroSumado = false; // Control para sumar los 2 días solo una vez

    while ($fechaInicio < $fechaFin) {
        
         // convierte la fecha en un formato leible 
        // testeo
        // $fechaComoString = $fechaInicio->format('Y-m-d');
        // echo "<br>". $fechaComoString;


        $año = (int)$fechaInicio->format('Y');
        $mes = (int)$fechaInicio->format('m');
        $dia = (int)$fechaInicio->format('d');

        // Excluir días 31
        if ($dia != 31) {
            $diasTotales++;

            // Agregar 2 días adicionales a febrero
            if ($mes == 2 && !$febreroSumado) {
                // determina si es bisiesto seria 1 dia si no lo es son 2
                $diasExtraFebrero = ($año % 4 == 0 && ($año % 100 != 0 || $año % 400 == 0)) ? 1 : 2;
                $diasTotales += $diasExtraFebrero;
                $febreroSumado = true;
            }
        }
       

        $fechaInicio->modify('+1 day'); // Avanzar al siguiente día

    
    }

    return $diasTotales;
}

function generarActas($inicio, $fin, $diasTotales) {

    // Calculamos el número de actas completas de 30 días
    $numeroActas = intdiv($diasTotales, 30);
    $diasRestantes = $diasTotales % 30;

    // Proyección de fechas para cada acta
    $proyeccionActas = [];
    $fechaInicioActa = new DateTime($inicio);

    for ($i = 0; $i < $numeroActas; $i++) {
        $fechaFinActa = clone $fechaInicioActa;
        $fechaFinActa->modify('+29 days'); // Cada acta tiene 30 días
        $proyeccionActas[] = [
            'acta' => $i + 1,
            'inicio' => $fechaInicioActa->format('Y-m-d'),
            'fin' => $fechaFinActa->format('Y-m-d')
        ];
        $fechaInicioActa = clone $fechaFinActa;
        $fechaInicioActa->modify('+1 day'); // La siguiente acta inicia al día siguiente
    }

    // Si quedan días restantes, agregamos una última acta con esos días
    if ($diasRestantes > 0) {
        $fechaFinActa = clone $fechaInicioActa;
        $fechaFinActa->modify('+' . ($diasRestantes - 1) . ' days');
        $proyeccionActas[] = [
            'acta' => $numeroActas + 1,
            'inicio' => $fechaInicioActa->format('Y-m-d'),
            'fin' => $fechaFinActa->format('Y-m-d')
        ];
        $numeroActas++; // Contamos la última acta
    }

    return [
        'numeroActas' => $numeroActas,
        'actas' => $proyeccionActas
    ];
}

//calculos presupuesto
$dias = actas_dias($fecha_ini,$fecha_fin,$valorDia);
$actasNum = 
//$valorMes = $valorDia * 30;
$valor_contrato = $valorDia * $dias;
$acomulado = 0;
$saldo = $dias  * $valorDia;

echo "<br>". $dias;
echo "<br>". $valor_contrato;
echo "<br>". $actasNum;
/*//Calculo de dias y de actas del contrato
//la diferencia entre fechas en segundos
$secs = $fecha_fin_pro - $fecha_ini_pro;
$diasUltimoMes = date("d",strtotime($_POST['fecha_fin'])) == 31 ? 30 : date("d",strtotime($_POST['fecha_fin']));
$actasTemp =  round($secs /86400)/30;
$actaFraccion =  $actasTemp - intval($actasTemp);
$actasNum = $actaFraccion < 0.2 ? intval($actasTemp) : ceil($actasTemp);//operador ternario solo valido hasta 4 dias


$dias = (intval($actasNum - 1) * 30)  + $diasUltimoMes;

//calculos presupuesto
$valorMes = $valorDia * 30;
$valor_contrato = $valorDia * $dias;
$acomulado = 0;
$saldo = $dias * $valorDia;*/

//echo $Alcances[1];
//echo $idRetenciones[1];
//echo $idUsuario ."<br>".  $idSupervisor;
//echo $registro_pptal ."<br>".  $rubro ."<br>". $disp_presupuestal ."<br>". $año;
//echo $num_contrato ."<br>".  $contratante ."<br>". $fecha_delegacion ."<br>". $num_delegacion;
//echo $area ."<br>".  $fecha_ini ."<br>". $fecha_fin ."<br>". $valor_contrato;
//echo $duracion ."<br>". $adicion  ."<br>". $fecha_adicion ."<br>". $objeto;
//echo $forma_pago ."<br>". $entregables  ."<br>". $salud ."<br>". $pension;
//echo $arl ."<br>". $fecha_activacion  ."<br>". $riesgo ."<br>". $observaciones;


$consulta= "SELECT * FROM usuario WHERE id = '$idUsuario'";
$resultado = mysqli_query($conexion,$consulta);
$filas= mysqli_fetch_array($resultado);


$ulti_digi_cedula = $filas['last_num_cc'];
$dia_habil_pago = 0;

if($ulti_digi_cedula > 0 and $ulti_digi_cedula <= 7 ){
    $dia_habil_pago = 2;
}elseif($ulti_digi_cedula > 7 and $ulti_digi_cedula <=14 ){
    $dia_habil_pago = 3;
}elseif($ulti_digi_cedula > 14 and $ulti_digi_cedula <= 21 ){
    $dia_habil_pago = 4;
}elseif($ulti_digi_cedula > 21 and $ulti_digi_cedula <= 28 ){
    $dia_habil_pago = 5;
}elseif($ulti_digi_cedula > 28 and $ulti_digi_cedula <= 35 ){
    $dia_habil_pago = 6;
}elseif($ulti_digi_cedula > 35 and $ulti_digi_cedula <= 42 ){
    $dia_habil_pago = 7;
}elseif($ulti_digi_cedula > 42 and $ulti_digi_cedula <= 49 ){
    $dia_habil_pago = 8;
}elseif($ulti_digi_cedula > 49 and $ulti_digi_cedula <= 56 ){
    $dia_habil_pago = 9;
}elseif($ulti_digi_cedula > 56 and $ulti_digi_cedula <= 63 ){
    $dia_habil_pago = 10;
}elseif($ulti_digi_cedula > 63 and $ulti_digi_cedula <= 70 ){
    $dia_habil_pago = 11;
}elseif($ulti_digi_cedula > 70 and $ulti_digi_cedula <= 75 ){
    $dia_habil_pago = 12;
}elseif($ulti_digi_cedula > 75 and $ulti_digi_cedula <= 81 ){
    $dia_habil_pago = 13;
}elseif($ulti_digi_cedula > 81 and $ulti_digi_cedula <= 87 ){
    $dia_habil_pago = 14;
}elseif($ulti_digi_cedula > 87 and $ulti_digi_cedula <= 93 ){
    $dia_habil_pago = 15;
}elseif($ulti_digi_cedula > 93 and $ulti_digi_cedula <= 99 ){
    $dia_habil_pago = 16;
}




$query = "INSERT INTO contrato (`registro_pptal`, `rubro`, `disp_presupuestal`, `years`, `num_contrato`, `contratante`, `fecha_delegacion`, `num_delegacion`, `area`, `fecha_ini`,
                                `fecha_fin`, `valor_contrato`, `valorDia`, `valorMes`, `duracion`,`objeto`, `forma_pago`, `entregables`, `salud`, `pension`, `arl`, `dia_habil_pago`,
                                `fecha_activacion`, `observaciones`, `num_actas`, `idUsuario`, `idSupervisor`, `modalidad`,`fecha_necesidad`,`fecha_firma`,`anticipo`,`idSupervisor2`) VALUES ('$registro_pptal','$rubro','$disp_presupuestal',
                                '$years','$num_contrato','$contratante','$fecha_delegacion','$num_delegacion','$area','$fecha_ini','$fecha_fin','$valor_contrato','$valorDia','$valorMes',
                                '$dias','$objeto','$forma_pago','$entregables','$salud','$pension','$arl','$dia_habil_pago','$fecha_activacion','$observaciones','$actasNum','$idUsuario',
                                '$idSupervisor','$modalidad','$fecha_necesidad','$fecha_firma','$anticipo','$idSupervisor2')";

$result = mysqli_query($conexion,$query); //or die ("No se puede establecer conexion con la DB.");
//$result = false;

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
if($result){
    /// obtiene el último id del registro insertado
    $idContrato = $conexion->insert_id;
     //insertamos las Retenciones
     for($i = 0; $i < count($idRetenciones); $i++){
        //echo $idEstampillas[$i];
        if($i == 3)
            $query2 = "INSERT INTO contrato_retencion(`idContrato`, `idRetencion`,`impuesto`) VALUES ('$idContrato','$idRetenciones[$i]','Riesgo')";
        else
            $query2 = "INSERT INTO contrato_retencion(`idContrato`, `idRetencion`,`impuesto`) VALUES ('$idContrato','$idRetenciones[$i]','Impuesto')";
        $result2 = mysqli_query($conexion,$query2) or die ("No se puede establecer conexion con la DB.");
    }
    //---------------------------------------------------------------------------------------------------------------------------------------
    if($result2){
       
        if(proyeccionCalculo(0,1,$_POST['fecha_ini'],$_POST['fecha_fin'],$fecha_suspension,$_POST['fecha_fin'],$valorDia,$valorMes,$actasNum,$acomulado,$saldo, $idContrato,"Inicial")){
        //if(false){
            header("location:listar.php");
        }else{
            ?>
<?php
            ?>
<h1 class="bad">ERROR EN LA AUTENTIFICACION PARA LA PROYECCION</h1>
<?php
        } 
    }else{
        ?>
<?php
        ?>
<h1 class="bad">ERROR EN LA AUTENTIFICACION DEL LAS RETENCIONES</h1>
<?php
    }      
}else{
    ?>
<?php
    if(mysqli_error($conexion)){
        $message = "El contrato ya ha sido creado";
        header("location:nuevo.php?mensaje=".$message);
        //echo "<br>". $message ;
    }
    ?>
<h1 class="bad">ERROR EN LA AUTENTIFICACION DEL CONTRATO</h1>
<?php
}
//mysqli_free_result($resultado);
ob_end_flush();
mysqli_close($conexion);
?>