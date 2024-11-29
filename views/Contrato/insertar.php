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
require_once('./Test/calculos.php');
require_once('../../db.php'); 
$registro_pptal = $_POST['registro_pptal'];
$rubro= $_POST['rubro'];
$disp_presupuestal= $_POST['disp_presupuestal'];
$years= $_POST['año'];
$num_contrato= $_POST['num_contrato'];
$contratante= "INSTITUTO DE DESARROLLO MUNICIPAL DE DOSQUEBRADAS";
$fecha_delegacion= $_POST['fecha_delegacion'];
$num_delegacion= $_POST['num_delegacion'];
$area= $_POST['area'];
$fechaInicio= $_POST['fecha_ini'];
$fechaFin= $_POST['fecha_fin'];
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
$idOrdenador = $_POST['idOrdenador'];

// Usamos los metodos de calculos.php
$dias = calcularDiasRango($fechaInicio, $fechaFin);
$totalActas = ceil($dias/30);

//calculos presupuesto
$presupuesto = calcularPresupuesto($dias,$valorDia);

//calcula la proyeccion de todas las actas del contrato
$proyeccion = generarActas($fechaInicio, $fechaFin,$dias,$valorDia,$presupuesto);


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


$consulta= "SELECT * FROM usuario WHERE id = '$idUsuario'";
$resultado = mysqli_query($conexion,$consulta);
$filas= mysqli_fetch_array($resultado);

 //calcular dia habil de pago seguridad social
$ulti_digi_cedula = $filas['last_num_cc'];
$dia_habil_pago = 0;

if ($ulti_digi_cedula >= 0 && $ulti_digi_cedula <= 99) {
    $dia_habil_pago = ceil(($ulti_digi_cedula + 1) / 7) + 1;
}



//almacenar el contrato
$query = "INSERT INTO contrato (`registro_pptal`, `rubro`, `disp_presupuestal`, `years`, `num_contrato`, `contratante`, `fecha_delegacion`, `num_delegacion`, `area`, `fecha_ini`,
                                `fecha_fin`, `valor_contrato`, `valorDia`, `valorMes`, `duracion`,`objeto`, `forma_pago`, `entregables`, `salud`, `pension`, `arl`, `dia_habil_pago`,
                                `fecha_activacion`, `observaciones`, `num_actas`, `idUsuario`, `idSupervisor`, `modalidad`,`fecha_necesidad`,`fecha_firma`,`anticipo`,`idSupervisor2`,`idOrdenador`) VALUES ('$registro_pptal','$rubro','$disp_presupuestal',
                                '$years','$num_contrato','$contratante','$fecha_delegacion','$num_delegacion','$area','$fechaInicio','$fechaFin','$presupuesto','$valorDia','$valorMes',
                                '$dias','$objeto','$forma_pago','$entregables','$salud','$pension','$arl','$dia_habil_pago','$fecha_activacion','$observaciones','$totalActas','$idUsuario',
                                '$idSupervisor','$modalidad','$fecha_necesidad','$fecha_firma','$anticipo','$idSupervisor2','$idOrdenador')";

$result = mysqli_query($conexion,$query); //or die ("No se puede establecer conexion con la DB.");
// $result = false;

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
        
        // si hay proyecciones
        if(count($proyeccion) > 0){
            
            foreach ($proyeccion as $acta) {
                $query3= "INSERT INTO `proyeccion_contractual`(`num_acta`, `periodo_ini`, `periodo_fin`, `dias`, `valor_dia`, `valor_mes`, `acomulado`, `saldo`, `tipo`, `prioridad`,`idContrato`,`idModificaciones`)
                  VALUES ('$acta[acta]','$acta[inicio]','$acta[fin]','$acta[dias]','$acta[valorDia]','$acta[valorMes]','$acta[acumulado]','$acta[saldo]','$acta[prioridad]',1,'$idContrato','$idModificaciones')";
                $result3 = mysqli_query($conexion,$query3);
            }
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