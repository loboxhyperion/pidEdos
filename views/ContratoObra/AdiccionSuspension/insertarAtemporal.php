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
include('../../middleware/proyeccion2.php');
include('../../middleware/calculoDias.php');
include('../../../db.php'); 

$idContrato = $_POST["idContrato"];
$fecha_ini = $_POST["fecha_ini"];//del contrato
$fecha_corte = $_POST['fecha_corte'];//del corte
$valorDia = $_POST["valorDia"];
echo "<br> idContrato:".$idContrato;

$fecha_modificacion = date("d",strtotime("-1 days"))."-".date("m")."-".date("Y");
$fecha_suspension = date("d",strtotime("-1 days"))."-".date("m")."-".date("Y");
$fecha_reinicio = date("d",strtotime("-1 days"))."-".date("m")."-".date("Y");
//echo "<br> <strong>Inicio Suspension</strong> :".  $fecha_fin_pre . " // ". "<strong>Reinicio:</strong>". $fecha_fin_new ;

//consultar las ultima acta hechas
$consulta= "SELECT * FROM acta WHERE idContrato = '$idContrato' ORDER BY id DESC LIMIT 1";
$resultado = mysqli_query($conexion,$consulta);
//si hay algun acta creada se guarda la fecha de inicio post la última acta creada
if($numRow = mysqli_fetch_array($resultado)){
    $fecha_ini_post_acta = date('d-m-Y', strtotime($numRow["fecha_fin"]."1 days"));
}else{//usar la fecha inicial en el caso de que no hayan actas creadas
    $fecha_ini_post_acta = $fecha_ini;
}

echo "<br> <strong>Fecha un dia despues ultima acta:</strong> :".  $fecha_ini_post_acta . " // ". "<strong>fecha de corte:</strong>". $fecha_corte;
//consultar las últimas proyecciones
$consulta= "SELECT * FROM proyeccion_contractual WHERE idContrato = '$idContrato' AND prioridad = 1";
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
        $num_actas = $cont;
        //echo "<br> <strong>fin </strong> :".$fecha_fin;
    }
}
echo "<br> <strong>Inicio Suspension</strong> :".  $fecha_suspension . " // ". "<strong>Reinicio:</strong>". $fecha_reinicio;

function actas_dias1($fecha_ini,$fecha_fin,$valorDia){
    global $actasNum;
    echo "<br>Este proceso";
    $secs = strtotime($fecha_fin) - strtotime($fecha_ini);
    $diasUltimoMes = date("d",strtotime($fecha_fin)) == 31 ? 30 : date("d",strtotime($fecha_fin));
    $actasTemp =  (round($secs /86400)+1)/30;
    $actasTemp2 =  round($secs /86400)+1;
    //addicion de 2 días si el contrato inicia en enero o febrero
    if((intval(date('m', strtotime($fecha_ini))) == 1 && $actasTemp2 >= 58) || (intval(date('m', strtotime($fecha_ini))) == 2)){
        $actasTemp2 =  $actasTemp2 + 2;
    }
    echo "<br> dias tempo:".   $actasTemp ;
    echo "<br> dias resta:".   $actasTemp2 ;
    //echo "<br>".   $actasTemp ;
    $actaFraccion =  $actasTemp - intval($actasTemp);
    $actasNum = $actaFraccion < 0.15 ? intval($actasTemp) : ceil($actasTemp);//operador ternario solo valido hasta 4 dias
    if($actasTemp2 <= 4){$actasNum = 1;}
    echo "<br> actas: ".   $actasNum ;
    $diasDesc = dias_descontar1($fecha_ini,$fecha_fin,$actasNum,$valorDia);
 
    echo "<br> descontados:". $diasDesc; 
    return $actasTemp2 - $diasDesc ;
}

function dias_descontar1($fecha_ini,$fecha_fin,$actas,$valorDia){
    $mesIni = 0;
    for($i = 1; $i<=abs($actas); $i++){
        if($i < $actas){  //||  intval(date('t', strtotime($fecha_fin))) != 31 ){
            echo "<br> mes:".  $fecha_ini; 
            $mesIni += intval(date('t', strtotime($fecha_ini))) == 31 ? 1 : 0;
            echo "<br> contador descuento:".  $mesIni; 
            $fecha_ini = date('Y-m-d', strtotime($fecha_ini."+ 1 months"));
        }
        else{       
            echo "<br> perdiodo de inicio :".  $fecha_ini . " // ". "perdiodo de fin:". $fecha_fin;
        }
        //para cuando sea una sola acta
        // se evalua que sea solo 1 acta , fecha de inicio tenga 31 dias y que los meses de la fecha de inicio y de fin sean diferentes
        if($actas == 1 && intval(date('t', strtotime($fecha_ini))) == 31 && intval(date('m', strtotime($fecha_fin))) != intval(date('m', strtotime($fecha_ini))) ){
            $mesIni++;
        }
          //sección para evitar que sumen días
        //cuando en la última acta el número de días del mes sea igual a 31
        //if($i == $actas  && intval(date('t', strtotime($fecha_fin))) == 31 && $i!= 1 ){
        /*if($i == $actas  && intval(date('t', strtotime($fecha_ini))) == 31 && $i!= 1 ){
            $mesIni--;
        }*/
        //cuando sea la ultima acta y el mes sea diciembre evita que sume un dia para descontar
        if($i == $actas && intval(date('m', strtotime($fecha_fin))) == 12 && intval(date('d', strtotime($fecha_fin))) == 31 ){
            $mesIni--;
        }
    }
    return $mesIni ;
}
//Calculo de dias de la Finalizacion
//invierto las fechas para que no me de negativo 
//llendo primero $fecha_fin_new
//lo multiplico por -1 por el cambio
$diasModi = actas_dias1($fecha_ini_post_acta,$fecha_corte,$valorDia);
$valor = intval($diasModi * $valorDia);
echo "<br> Dias del corte:". $diasModi;

//$SumDias = intval(date('d', strtotime($fecha_fin_pre ))) + 30;
//$fecha_fin_new = intval(date('t', strtotime($fecha_fin_pre ))) == 31 && $SumDias > 30 ? date('Y-m-d', strtotime($fecha_fin_pre."+ ". (30 + 1) ." days")) : date('Y-m-d', strtotime($fecha_fin_pre."+ ". (30) ." days"));
$fecha_fin_new = $fecha_fin_pre;
echo "<br> nueva fecha fin:".$fecha_fin_new;
//calculos presupuesto
$valorMes = ceil($valorDia * 30);
$acomulado = 0;
$saldo = $valor_contrato;
$num_actas = $num_actas + $actasNum; 

echo "<br> N° actas:". $num_actas;
echo "<br>ValorMes:". $valorMes;
echo "<br> saldo:". $saldo;



//Registro adiccion 
$query = "INSERT INTO `adicion_suspension`(`tipo`, `fecha_modificacion`, `fecha_suspension`, `fecha_reinicio`, `valor`, `dias`, `diasTotal`, `fecha_terminacion_pre`, `fecha_terminacion_new`, `idContrato`)
          VALUES ('Corte Atemporal','$fecha_modificacion','$fecha_corte','$fecha_ini_post_acta','$valor','$diasModi','$diasModi','$fecha_fin_pre','$fecha_fin_pre','$idContrato')";
$result = mysqli_query($conexion,$query);

/// obtiene el último id del registro insertado
$idModificaciones = $conexion->insert_id;

if(proyeccionCalculo($idModificaciones,1,$fecha_ini,$fecha_fin_new ,$fecha_corte,$fecha_fin_new,$valorDia,$valorMes,$num_actas,$acomulado,$saldo, $idContrato,"Corte Atemporal")){
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