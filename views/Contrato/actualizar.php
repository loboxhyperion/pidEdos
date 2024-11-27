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
$fecha_delegacion= $_POST['fecha_delegacion'];
$num_delegacion= $_POST['num_delegacion'];
$area= $_POST['area'];
$fechaInicio= $_POST['fecha_ini'];
$fechaFin= $_POST['fecha_fin'];
$valorMes= (int)$_POST['valorMes'];
echo "<br>".$valorMes;
$valorDia= $valorMes / 30;
echo "<br>".$valorDia;
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
$idRetencion= $_POST['idRetencion'];
$modalidad = $_POST['modalidad'];
$idCategoria =$_POST['idCategoria'];

$idContrato= $_POST['idContrato'];
$modificar = $_POST['modificar'];

$fecha_necesidad = $_POST['fecha_necesidad'];
$fecha_firma = $_POST['fecha_firma'];

$idSupervisor2 = $_POST['idSupervisor2'] ;

echo $modificar;

// Usamos los metodos de calculos.php
$dias = calcularDiasRango($fechaInicio, $fechaFin);
$totalActas = ceil($dias/30);

//calculos presupuesto
$presupuesto = calcularPresupuesto($dias,$valorDia);

//calcula la proyeccion de todas las actas del contrato
$proyeccion = generarActas($fechaInicio, $fechaFin,$dias,$valorDia,$presupuesto);

//solo admin puede modificar todo cuando ya se creo una acta menos las fechas y el presupuesto

if($modificar == "Si"){
    $query = "UPDATE `contrato` SET `registro_pptal`='$registro_pptal',`rubro`='$rubro',`disp_presupuestal`='$disp_presupuestal',`years`='$years',`num_contrato`='$num_contrato',`fecha_delegacion`='$fecha_delegacion',`num_delegacion`='$num_delegacion',`area`='$area',
                                `valor_contrato`='$valor_contrato',`objeto`='$objeto',`forma_pago`='$forma_pago',`entregables`='$entregables',`salud`='$salud',`pension`='$pension',`arl`='$arl',`fecha_activacion`='$fecha_activacion',`observaciones`='$observaciones',
                                `num_actas`='$totalActas',`idUsuario`='$idUsuario',`idSupervisor`='$idSupervisor',`modalidad`='$modalidad',`fecha_necesidad`='$fecha_necesidad',`fecha_firma`='$fecha_firma',`idSupervisor2`='$idSupervisor2'  WHERE id = $idContrato";
}else{
    $query = "UPDATE `contrato` SET `registro_pptal`='$registro_pptal',`rubro`='$rubro',`disp_presupuestal`='$disp_presupuestal',`years`='$years',`num_contrato`='$num_contrato',`fecha_delegacion`='$fecha_delegacion',`num_delegacion`='$num_delegacion',`area`='$area',
                                `fecha_ini`='$fechaInicio',`fecha_fin`='$fechaFin',`valor_contrato`='$presupuesto',`valorDia`='$valorDia',`valorMes`='$valorMes',`duracion`='$dias',`objeto`='$objeto',`forma_pago`='$forma_pago',`entregables`='$entregables', 
                                `salud`='$salud',`pension`='$pension',`arl`='$arl',`fecha_activacion`='$fecha_activacion',`observaciones`='$observaciones',`num_actas`='$totalActas',`idUsuario`='$idUsuario',`idSupervisor`='$idSupervisor',`modalidad`='$modalidad',
                                `fecha_necesidad`='$fecha_necesidad',`fecha_firma`='$fecha_firma',`idSupervisor2`='$idSupervisor2'  WHERE id = $idContrato";
}
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
        //consultar las últimas proyecciones
        $consulta= "DELETE FROM proyeccion_contractual WHERE idContrato = '$idContrato'";
        $resultado = mysqli_query($conexion,$consulta);

        if(count($proyeccion) > 0){
        //if(false){       
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
}else if($result && $modificar == "Si"){
    header("location:listar.php");
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