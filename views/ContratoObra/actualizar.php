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
$fecha_delegacion= $_POST['fecha_delegacion'];
$num_delegacion= $_POST['num_delegacion'];
$area= $_POST['area'];
$fecha_ini= $_POST['fecha_ini'];
$fecha_fin= $_POST['fecha_fin'];
$valorContrato= (int)$_POST['valorContrato'];
$valorMes = $valorContrato/12;
$valorDia= ($valorContrato/12) / 30;
$objeto= $_POST['objeto'];
$forma_pago= $_POST['forma_pago'];
$entregables= $_POST['entregables'];
$observaciones= $_POST['observaciones'];
$idUsuario= $_POST['idUsuario'];
$idSupervisor= $_POST['idSupervisor'];
$idRetencion= $_POST['idRetencion'];
$modalidad = $_POST['modalidad'];
$idCategoria =$_POST['idCategoria'];

$idContrato= $_POST['idContrato'];
$modificar = $_POST['modificar'];

$fecha_necesidad = $_POST['fecha_necesidad'];
$fecha_firma = $_POST['fecha_firma'];
$anticipo = $_POST['anticipo'];

echo $modificar;


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

$query = "UPDATE `contrato` SET `registro_pptal`='$registro_pptal',`rubro`='$rubro',`disp_presupuestal`='$disp_presupuestal',`years`='$years',`num_contrato`='$num_contrato',`fecha_delegacion`='$fecha_delegacion',`num_delegacion`='$num_delegacion',`area`='$area',
                            `fecha_ini`='$fecha_ini',`fecha_fin`='$fecha_fin',`valor_contrato`='$valorContrato',`valorDia`='$valorDia',`valorMes`='$valorMes',`duracion`='$dias',`objeto`='$objeto',`forma_pago`='$forma_pago',`entregables`='$entregables', 
                            `observaciones`='$observaciones',`num_actas`='$actasNum',`idUsuario`='$idUsuario',`idSupervisor`='$idSupervisor',`modalidad`='$modalidad',`fecha_necesidad`='$fecha_necesidad',`fecha_firma`='$fecha_firma',`anticipo`='$anticipo' WHERE id = $idContrato";
$result = mysqli_query($conexion,$query) or die ("No se puede establecer conexion con la DBs.");
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

if($result && $modificar == "No"){
    $query2 = "UPDATE `contrato_retencion` SET `idRetencion`='$idRetencion' WHERE idContrato = '$idContrato' and impuesto = 'Riesgo'";
    $result2 = mysqli_query($conexion,$query2) or die ("No se puede establecer conexion con la DB.");

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
    ?>
    <h1 class="bad">ERROR EN LA AUTENTIFICACION DEL CONTRATO</h1>
    <?php
}
//mysqli_free_result($resultado);
ob_end_flush();
mysqli_close($conexion);
?>