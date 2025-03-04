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
$valorContrato= $_POST['valorContrato'];
$valorMes = $valorContrato/12;
$valorDia= ($valorContrato/12) / 30;
$objeto= $_POST['objeto'];
$forma_pago= $_POST['forma_pago'];
$entregables= $_POST['entregables'];
$salud= "N/A";
$pension= "N/A";
$arl= "N/A";
$fecha_activacion= $_POST['fecha_ini'];
$observaciones= $_POST['observaciones'];
$idUsuario= $_POST['idUsuario'];
$idSupervisor= $_POST['idSupervisor'];
$idRetenciones= $_POST['idRetencion'];
$modalidad = $_POST['modalidad'];

$fecha_necesidad = $_POST['fecha_necesidad'];
$fecha_firma = $_POST['fecha_firma'];
$anticipo = $_POST['anticipo'];
echo "<br>".$anticipo;


//Calculo de dias y de actas del contrato
//la diferencia entre fechas en segundos
function actas_dias($fecha_ini,$fecha_fin,$valorDia){
    global $diasUltimoMes,$actasNum;
    $secs = strtotime($fecha_fin) - strtotime($fecha_ini);
    $diasUltimoMes = date("d",strtotime($fecha_fin)) == 31 ? 30 : date("d",strtotime($fecha_fin));
    $actasTemp =  (round($secs /86400))/30;
    $actasTemp2 =  round($secs /86400)+1;
    echo "<br> perdiodo de inicio :".  $fecha_ini . " // ". "perdiodo de fin:". $fecha_fin;
    echo "<br> dias totales :".   $actasTemp2 ;
    //addicion de 2 días si el contrato inicia en enero o febrero
    if((intval(date('m', strtotime($fecha_ini))) == 1 && $actasTemp2 >= 58) || (intval(date('m', strtotime($fecha_ini))) == 2)){
        $actasTemp2 =  $actasTemp2 + 2;
    }
    echo "<br> dias totales sumando febrero :".   $actasTemp2 ;
    echo "<br> Actas Temporales ".   $actasTemp ;
    $actaFraccion =  $actasTemp - intval($actasTemp);
    $actasNum = $actaFraccion < 0.1 ? intval($actasTemp) : ceil($actasTemp);//operador ternario solo valido hasta 4 dias
    echo "<br> actas: ".   $actasNum ;
    echo "<br> ----------------------apartado para descontar los 31------------------- ";
    $diasDesc = dias_descontar($fecha_ini,$fecha_fin,$actasNum,$valorDia);
 
    echo "<br> descontados:". $diasDesc; 
    return $actasTemp2 - $diasDesc ;
}

function dias_descontar($fecha_ini,$fecha_fin,$actas,$valorDia){
    //Suma los dias a descontar
    $mesIni = 0;
    /*if(intval(date('d', strtotime($fecha_ini))) == 1 ){
        $mesIni--;
        echo "<br> pase"; 
    }*/
    for($i = 1; $i<=$actas; $i++){
        //if($i < $actas  ||  intval(date('t', strtotime($fecha_fin))) /*!=*/== 31 ){
        if($i <= $actas && intval(date('d', strtotime($fecha_ini))) != 1 && intval(date('m', strtotime($fecha_ini))) != 12){
            echo "<br> mes:".  $fecha_ini; 
            $mesIni += intval(date('t', strtotime($fecha_ini))) == 31 ? 1 : 0;
            echo "<br> mes:".  $mesIni; 
            $fecha_ini = date('Y-m-d', strtotime($fecha_ini."+ 1 months"));
        }else if($i < $actas && intval(date('d', strtotime($fecha_ini))) == 1){
            echo "<br> mes:".  $fecha_ini; 
            $mesIni += intval(date('t', strtotime($fecha_ini))) == 31 ? 1 : 0;
            echo "<br> mes:".  $mesIni; 
            $fecha_ini = date('Y-m-d', strtotime($fecha_ini."+ 1 months"));
        }else{       
            echo "<br> perdiodo de inicio :".  $fecha_ini . " // ". "perdiodo de fin:". $fecha_fin;
        }
        //sección para evitar que sumen días
        //cuando en la última acta el número de días del mes sea igual a 31
        if($i == $actas  && intval(date('d', strtotime($fecha_fin))) == 31 ){
            $mesIni--;
        }
        //cuando sea la ultima acta y el mes sea diciembre evita que sume un dia para descontar
        /*if($i == $actas && intval(date('m', strtotime($fecha_fin))) == 12 ){
            $mesIni--;
        }*/
    }
    return $mesIni ;
}

//calculos presupuesto
$dias = actas_dias($fecha_ini,$fecha_fin,$valorDia);


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
                                `fecha_activacion`, `observaciones`, `num_actas`, `idUsuario`, `idSupervisor`, `modalidad`,`fecha_necesidad`,`fecha_firma`,`anticipo`) VALUES ('$registro_pptal','$rubro','$disp_presupuestal',
                                '$years','$num_contrato','$contratante','$fecha_delegacion','$num_delegacion','$area','$fecha_ini','$fecha_fin','$valorContrato','$valorDia','$valorMes',
                                '$dias','$objeto','$forma_pago','$entregables','$salud','$pension','$arl','$dia_habil_pago','$fecha_activacion','$observaciones','$actasNum','$idUsuario',
                                '$idSupervisor','$modalidad','$fecha_necesidad','$fecha_firma','$anticipo')";

$result = mysqli_query($conexion,$query); //or die ("No se puede establecer conexion con la DB.");


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
        header("location:listar.php");
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