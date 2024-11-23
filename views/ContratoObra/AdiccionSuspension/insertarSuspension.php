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
// antes de cualquier línea de código html o php:
//evita que no se buque el header para redirigir a otra pagina
ob_start();
include('../../middleware/proyeccion.php');
include('../../middleware/calculoDias.php');
include('../../../db.php'); 

$idContrato = $_POST["idContrato"];
$fecha_ini = $_POST["fecha_ini"];
$valorDia = $_POST["valorDia"];


$fecha_modificacion = date("d",strtotime("-1 days"))."-".date("m")."-".date("Y");
$fecha_suspension = $_POST['fecha_suspension'];
$fecha_reinicio = $_POST['fecha_reinicio'];

//consultar las últimas proyecciones
$consulta= "SELECT * FROM proyeccion_contractual WHERE idContrato = '$idContrato'";
$resultado = mysqli_query($conexion,$consulta);
$numRow = mysqli_num_rows($resultado);
$cont = 0;


while($filas=mysqli_fetch_array($resultado)){
    $query = "UPDATE `proyeccion_contractual` SET `prioridad`= 0 WHERE id = $filas[id]";
    $result = mysqli_query($conexion,$query);
    if(++$cont == $numRow)
    {
        $fecha_fin_pre = $filas['periodo_fin'];
        $valor_contrato = $filas['acomulado'];
        echo "<br> <strong>fin </strong> :".$fecha_fin_pre ;
    }
}
echo "<br> <strong>Inicio Suspension</strong> :".  $fecha_suspension . " // ". "<strong>Reinicio:</strong>". $fecha_reinicio;


//Calculo de dias y de actas del contrato
//la diferencia entre fechas en segundos
function actas_dias1($fecha_ini,$fecha_fin,$valorDia){
   // global $diasUltimoMes,$actasNum;
    global $diasTemp;
    //resta las fechas
    $secs = strtotime($fecha_fin) - strtotime($fecha_ini);
    //$diasUltimoMes = date("d",strtotime($fecha_fin)) == 31 ? 30 : date("d",strtotime($fecha_fin));
    //saca las actas temporales
    $actasTemp =  (round($secs /86400)+1)/30;
    //saca los dias temporales de la resta de fechas
    $diasTemp =  round($secs /86400)+1;
    echo "<br> <strong>dias temporales:</strong>".   $diasTemp ;
    //echo "<br>".   $actasTemp ;
    $actaFraccion =  $actasTemp - intval($actasTemp);
    $actasNum = $actaFraccion < 0.19 ? intval($actasTemp) : ceil($actasTemp);//operador ternario solo valido hasta 4 dias
    echo "<br> <strong> N° actas:</strong> ".   $actasNum ;
    $diasDesc = dias_descontar($fecha_ini,$fecha_fin,$actasNum,$valorDia);
 
    echo "<br> descontados:". $diasDesc; 
    return $diasTemp - $diasDesc ;
}

function dias_descontar1($fecha_ini,$fecha_fin,$actas,$valorDia){
    $mesIni = 0;
    for($i = 1; $i<=$actas; $i++){
        
        if($i < $actas  ||  intval(date('t', strtotime($fecha_fin))) != 31  ){
            echo "<br> mes:".  $fecha_ini; 
            $mesIni += intval(date('t', strtotime($fecha_ini))) == 31 ? 1 : 0;
            $fecha_ini = date('Y-m-d', strtotime($fecha_ini."+ 1 months"));
        }
        else{    
            echo "<br> perdiodo de inicio :".  $fecha_ini . " // ". "perdiodo de fin:". $fecha_fin;
        }
    }
    return $mesIni ;
}


//Calculo de dias de la adiccion
$diasModi = actas_dias1($fecha_suspension,$fecha_reinicio,true,$valorDia);
$valor = $diasModi * $valorDia;
echo "<br>". $diasModi . "<br>";
//echo "<br>". $valor;
echo "<br> <strong>Inicio contrato</strong> :".  $fecha_ini . " // ". "<strong>Fin del contrato:</strong>". $fecha_fin_pre;
$SumDias = intval(date('d', strtotime($fecha_fin_pre ))) + $diasModi;
//tener en cuenta febrero para luego porque es  de 28 dias
//si la suma de los dias de suspension + los dias de la ultima fecha de fin esmayor a 30 
// y el mes es de 31 dias entonces se le suma 1 dia más
$fecha_fin_new = intval(date('t', strtotime($fecha_fin_pre ))) == 31 && $SumDias > 30 ? date('Y-m-d', strtotime($fecha_fin_pre."+ ". ($diasModi + 1) ." days")) : date('Y-m-d', strtotime($fecha_fin_pre."+ ". ($diasModi) ." days"));
//$fechga_fin_new = 
echo "<br>". $fecha_fin_new;
//calculos presupuesto
$valorMes = $valorDia * 30;
$acomulado = 0;
$saldo = $valor_contrato;
echo "<br><strong>Valor contrato</strong> :". $valor_contrato;
echo "<br><strong>n° actas</strong> :". $num_actas;



//Registro suspensión
/*$query = "INSERT INTO `adicion_suspension`(`tipo`, `fecha_modificacion`, `fecha_suspension`, `fecha_reinicio`, `valor`, `dias`, `fecha_terminacion_pre`, `fecha_terminacion_new`, `idContrato`)
          VALUES ('Suspension','$fecha_modificacion','$fecha_suspension','$fecha_reinicio','$valor','$diasModi','$fecha_fin_pre','$fecha_fin_new','$idContrato')";*/
$query = "INSERT INTO `adicion_suspension`(`tipo`, `fecha_modificacion`, `fecha_suspension`, `fecha_reinicio`, `valor`, `dias`, `diasTotal`, `fecha_terminacion_pre`, `fecha_terminacion_new`, `idContrato`) 
          VALUES ('Suspension','$fecha_modificacion','$fecha_suspension','$fecha_reinicio','$valor','$diasModi','$diasTemp','$fecha_fin_pre','$fecha_fin_new','$idContrato')";
$result = mysqli_query($conexion,$query);

/// obtiene el último id del registro insertado
$idModificaciones = $conexion->insert_id;

//if(proyeccionCalculo($fecha_ini,$fecha_fin_pre,$fecha_suspension,$fecha_fin_new,$valorDia,$valorMes,$diasUltimoMes,$num_actas,$acomulado,$saldo, $idContrato,$diasModi,$mesDesc,$valorDesc ,"Suspension")){
if(proyeccionCalculo($idModificaciones,1,$fecha_ini,$fecha_fin_pre,$fecha_suspension,$fecha_fin_new,$valorDia,$valorMes,$num_actas,$acomulado,$saldo, $idContrato,"Suspension")){
//if(false){
    header("location:listar.php?id=$idContrato&fecha_ini=$fecha_ini&valorDia=$valorDia");
}else{
    ?>
    <?php
    ?>
    <h1 class="bad">ERROR EN LA AUTENTIFICACION PARA LA PROYECCION</h1>
    <?php
}
ob_end_flush();
mysqli_close($conexion);
?>