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
require_once('../Test/calculos.php');
require_once('../../../db.php'); 

$idContrato = $_POST["idContrato"];
$fechaInicio = $_POST["fecha_ini"];
$fechaFin = $_POST["fecha_fin"];
$fechaFinNew = $_POST['fecha_fin_new'];
$valorDia = $_POST["valorDia"];
$disp_presupuestal= $_POST['disp_presupuestal'];
$registro_pptal = $_POST['registro_pptal'];


$fecha_modificacion = date("d",strtotime("-1 days"))."-".date("m")."-".date("Y");
$fecha_suspension = date("d",strtotime("-1 days"))."-".date("m")."-".date("Y");
$fecha_reinicio = date("d",strtotime("-1 days"))."-".date("m")."-".date("Y");
//echo "<br> <strong>Inicio Suspension</strong> :".  $fecha_fin_pre . " // ". "<strong>Reinicio:</strong>". $fecha_fin_new ;

//consultar las últimas proyecciones
$consulta= "SELECT * FROM proyeccion_contractual WHERE idContrato = '$idContrato' AND `prioridad`= 1";
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


// Usamos los metodos de calculos.php
// ------------------------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------------------------------------

echo "<br>El número de días adiccionales <strong>: $fecha_fin_pre </strong>días.<br>";
//Calculamos los dias adiccionales 
$fechaFinPre = date('d-m-Y', strtotime($fecha_fin_pre."1 days"));


echo "<br>El número de días adiccionales <strong>: $fechaFinPre </strong>días.<br>";
echo "<br>El número de días adiccionales <strong>: $fechaFinNew  </strong>días.<br>";
$diasAddicion = calcularDiasRango($fechaFinPre, $fechaFinNew);

//Calculamos la totalidad incluyendo la addicion
$dias = calcularDiasRango($fechaInicio, $fechaFinNew);
$totalActas = ceil($dias/30);

//calculos presupuesto
$presupuesto = calcularPresupuesto($dias,$valorDia);
$valorMes = ceil($valorDia * 30);
$valor = intval($diasAddicion * $valorDia);

//calcula la proyeccion de todas las actas del contrato
$proyeccion = generarActas($fechaInicio, $fechaFinNew,$dias,$valorDia,$presupuesto);


echo "<br>El número de días adiccionales <strong>: $diasAddicion</strong>días.<br>";

echo "El número de días en el rango, excluyendo los días 31 y sumando 2 días a febrero, es<strong>: $dias</strong>días.";
// Mostrar resultados
echo "<br><strong>Número total de actas:</strong> $totalActas";

// Mostrar resultados
echo "<br><strong>Prsupuesto:</strong>".number_format($presupuesto,2)."<br>";

// Mostrar resultados
echo "<strong>Proyección de actas:</strong>\n<br>";
foreach ($proyeccion as $acta) {
    echo "Acta {$acta['acta']}: desde {$acta['inicio']} hasta {$acta['fin']} dias: {$acta['dias']} valordia: {$acta['valorDia']} valorMes: {$acta['valorMes']} Acumulado: {$acta['acumulado']} Saldo: {$acta['saldo']}<br>";
}



//Registro adiccion 
$query = "INSERT INTO `adicion_suspension`(`tipo`, `fecha_modificacion`, `fecha_suspension`, `fecha_reinicio`, `valor`, `dias`, `diasTotal`, `fecha_terminacion_pre`, `fecha_terminacion_new`, `idContrato`, `cdp`, `rp`)
         VALUES ('Adicion','$fecha_modificacion','$fecha_suspension','$fecha_reinicio','$valor','$diasAddicion','$diasAddicion','$fecha_fin_pre','$fechaFinNew','$idContrato','$disp_presupuestal','$registro_pptal')";
$result = mysqli_query($conexion,$query);

/// obtiene el último id del registro insertado
$idModificaciones = $conexion->insert_id;

if(count($proyeccion) > 0){
// if(false){
            
        foreach ($proyeccion as $acta) {
            $query3= "INSERT INTO `proyeccion_contractual`(`num_acta`, `periodo_ini`, `periodo_fin`, `dias`, `valor_dia`, `valor_mes`, `acomulado`, `saldo`, `tipo`, `prioridad`,`idContrato`,`idModificaciones`)
              VALUES ('$acta[acta]','$acta[inicio]','$acta[fin]','$acta[dias]','$acta[valorDia]','$acta[valorMes]','$acta[acumulado]','$acta[saldo]','Adiccion',1,'$idContrato','$idModificaciones')";
            $result3 = mysqli_query($conexion,$query3);
        }
        header("location:listar.php?id=$idContrato&fecha_ini=$fechaInicio&fecha_fin=$fechaFinNew&valorDia=$valorDia");
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