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
$fecha_fin_new = $_POST['fecha_fin_new'];
$valorDia = $_POST["valorDia"];

$fecha_modificacion = date("d",strtotime("-1 days"))."-".date("m")."-".date("Y");
$fecha_suspension = date("d",strtotime("-1 days"))."-".date("m")."-".date("Y");
$fecha_reinicio = date("d",strtotime("-1 days"))."-".date("m")."-".date("Y");
//echo "<br> <strong>Inicio Suspension</strong> :".  $fecha_fin_pre . " // ". "<strong>Reinicio:</strong>". $fecha_fin_new ;

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
        //echo "<br> <strong>fin </strong> :".$fecha_fin;
    }
}
echo "<br> <strong>Inicio Suspension</strong> :".  $fecha_suspension . " // ". "<strong>Reinicio:</strong>". $fecha_reinicio;

function actas_dias1($fecha_ini,$fecha_fin,$valorDia){
    global $diasUltimoMes,$actasNum;
    $secs = strtotime($fecha_fin) - strtotime($fecha_ini);
    $diasUltimoMes = date("d",strtotime($fecha_fin)) == 31 ? 30 : date("d",strtotime($fecha_fin));
    $actasTemp =  (round($secs /86400)+1)/30;
    $actasTemp2 =  round($secs /86400)+1;
    //addicion de 2 días si el contrato inicia en enero o febrero
    if((intval(date('m', strtotime($fecha_ini))) == 1 && $actasTemp2 >= 58) || (intval(date('m', strtotime($fecha_ini))) == 2)){
        $actasTemp2 =  $actasTemp2 + 2;
    }
    echo "<br> dias resta:".   $actasTemp2 ;
    //echo "<br>".   $actasTemp ;
    $actaFraccion =  $actasTemp - intval($actasTemp);
    $actasNum = $actaFraccion < 0.15 ? intval($actasTemp) : ceil($actasTemp);//operador ternario solo valido hasta 4 dias
    echo "<br> actas: ".   $actasNum ;
    $diasDesc = dias_descontar1($fecha_ini,$fecha_fin,$actasNum,$valorDia);
 
    echo "<br> descontados:". $diasDesc; 
    return $actasTemp2 - $diasDesc ;
}

function dias_descontar1($fecha_ini,$fecha_fin,$actas,$valorDia){
    $mesIni = 0;
    for($i = 0; $i<=abs($actas); $i++){
        if($i < $actas){  //||  intval(date('t', strtotime($fecha_fin))) != 31 ){
            echo "<br> mes:".  $fecha_ini; 
            $mesIni += intval(date('t', strtotime($fecha_ini))) == 31 ? 1 : 0;
            echo "<br> contador descuento:".  $mesIni; 
            $fecha_ini = date('Y-m-d', strtotime($fecha_ini."+ 1 months"));
        }
        else{       
            echo "<br> perdiodo de inicio :".  $fecha_ini . " // ". "perdiodo de fin:". $fecha_fin;
        }
          //sección para evitar que sumen días
        //cuando en la última acta el número de días del mes sea igual a 31
        //if($i == $actas  && intval(date('t', strtotime($fecha_fin))) == 31 && $i!= 1 ){
        /*if($i == $actas  && intval(date('t', strtotime($fecha_ini))) == 31 && $i!= 1 ){
            $mesIni--;
        }*/
        //cuando sea la ultima acta y el mes sea diciembre evita que sume un dia para descontar
        if($i == $actas && intval(date('m', strtotime($fecha_fin))) == 12 ){
            $mesIni--;
        }
    }
    return $mesIni ;
}
//Calculo de dias de la Finalizacion
//invierto las fechas para que no me de negativo 
//llendo primero $fecha_fin_new
//lo multiplico por -1 por el cambio
$diasModi = -1* actas_dias1($fecha_fin_new,$fecha_fin_pre,$valorDia);
$valor = intval($diasModi * $valorDia);
echo "<br>". $diasModi;

$dias = actas_dias1($fecha_ini,$fecha_fin_new,$valorDia);
echo "<br> f:". $dias;
echo "<br> TOTAL:". $diasModi;


//calculos presupuesto
$valorMes = ceil($valorDia * 30);
$acomulado = 0;
$saldo = intval($dias * $valorDia);
$num_actas =  $actasNum;
echo "<br>". $num_actas;
echo "<br>". $saldo;
//echo "<br>". $actasNum;



//Registro adiccion 
$query = "INSERT INTO `adicion_suspension`(`tipo`, `fecha_modificacion`, `fecha_suspension`, `fecha_reinicio`, `valor`, `dias`, `diasTotal`, `fecha_terminacion_pre`, `fecha_terminacion_new`, `idContrato`)
          VALUES ('Finalizar','$fecha_modificacion','$fecha_suspension','$fecha_reinicio','$valor','$diasModi','$dias','$fecha_fin_pre','$fecha_fin_new','$idContrato')";
$result = mysqli_query($conexion,$query);

/// obtiene el último id del registro insertado
$idModificaciones = $conexion->insert_id;
//if(proyeccionCalculo($_POST['fecha_ini'],$_POST['fecha_fin'],$valorDia,$valorMes,$diasUltimoMes,$actasNum,$acomulado,$saldo, $idContrato,"Adiccion")){
if(proyeccionCalculo($idModificaciones,1,$fecha_ini,$fecha_fin_new ,$fecha_suspension,$fecha_fin_new,$valorDia,$valorMes,$num_actas,$acomulado,$saldo, $idContrato,"Finalizar")){
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