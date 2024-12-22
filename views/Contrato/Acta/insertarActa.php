<?php
// Evitar problemas de redirección
ob_start();
include('../../../db.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Validar y sanitizar datos
$NombreContratistas = $_POST["nombre"] ?? '';
$NombreSupervisor = $_POST["nombreSupervisor"] ?? '';
$numInforme = $_POST['num_informe'] ?? '';
$idContrato = $_POST['idContrato'] ?? '';
$idSupervisor = $_POST['idSupervisor'] ?? '';
$fecha_informe = $_POST['fecha_informe'] ?? '';
$fecha_ini = $_POST['fecha_ini'] ?? '';
$fecha_fin = $_POST['fecha_fin'] ?? '';
$diasPagos = $_POST['diasPagos'] ?? '';
$valor = $_POST['valor'] ?? '';
$acumulado = $_POST['acumulado'] ?? '';
$saldo = $_POST['saldo'] ?? '';
$numPlanilla = $_POST['numPlanilla'] ?? '';
$fechaPlanilla = $_POST['fechaPlanilla'] ?? '';
$valorPlanilla = $_POST['valorPlanilla'] ?? '';
$idAlcances = $_POST['idAlcance'] ?? [];
$Actividades = $_POST['actividad'] ?? [];
$Ubicaciones = $_POST['ubicacion'] ?? [];
$observaciones = $_POST['observaciones'] ?? 'NA';
$valorPlanillaReal = $_POST['valorPlanillaReal'] ?? '';
$encargado = $_POST['encargado'] ?? '';
$idUsuario = $_POST['idUsuario'] ?? '';
$mas_cotizacion = $_POST['mas_cotizacion'] ?? 'NA';

// Hacer un var_dump para ver el contenido de las variables
// echo "<pre>";
// var_dump([
//     'NombreContratistas' => $NombreContratistas,
//     'NombreSupervisor' => $NombreSupervisor,
//     'numInforme' => $numInforme,
//     'idContrato' => $idContrato,
//     'idSupervisor' => $idSupervisor,
//     'fecha_informe' => $fecha_informe,
//     'fecha_ini' => $fecha_ini,
//     'fecha_fin' => $fecha_fin,
//     'diasPagos' => $diasPagos,
//     'valor' => $valor,
//     'acumulado' => $acumulado,
//     'saldo' => $saldo,
//     'numPlanilla' => $numPlanilla,
//     'fechaPlanilla' => $fechaPlanilla,
//     'valorPlanilla' => $valorPlanilla,
//     'idAlcances' => $idAlcances,
//     'Actividades' => $Actividades,
//     'Ubicaciones' => $Ubicaciones,
//     'observaciones' => $observaciones,
//     'valorPlanillaReal' => $valorPlanillaReal,
//     'encargado' => $encargado,
//     'idUsuario' => $idUsuario,
//     'mas_cotizacion' => $mas_cotizacion
// ]);
// echo "</pre>";
exit; // Detener la ejecución para inspeccionar el contenido

// Validar fechas
if (strtotime($fecha_informe) < strtotime($fecha_fin)) {
    $message = "La fecha del informe debe cumplir el periodo de vencimiento";
    header("location:nuevaActa.php?id=$idContrato&nombre=$NombreContratistas&NombreSupervisor=$NombreSupervisor&num_informe=$numInforme&mensaje=$message");
    exit;
}

// Validar y procesar ubicaciones
foreach ($Ubicaciones as $i => $ubicacion) {
    // Si el campo está vacío, asignar "NA"
    $Ubicaciones[$i] = !empty(trim($ubicacion)) ? $ubicacion : 'NA';
}


// Iniciar transacción
//Usa transacciones para garantizar que todas las operaciones se ejecuten correctamente o se reviertan en caso de fallo:
mysqli_begin_transaction($conexion);

try {
    // Insertar acta
    $query = "INSERT INTO acta (`num_informe`, `fecha_informe`, `fecha_ini`, `fecha_fin`, `diasPagos`, `valor`, `acumulado`, `saldo`, `numPlanilla`, `fechaPlanilla`,
             `valorPlanilla`, `estado`, `observaciones`, `idSupervisor`, `idContrato`, `valorPlanillaReal`, `encargado`, `idUsuario`, `mas_cotizacion`) 
             VALUES ('$numInforme', '$fecha_informe', '$fecha_ini', '$fecha_fin', '$diasPagos', '$valor', '$acumulado', '$saldo', '$numPlanilla', '$fechaPlanilla', 
             '$valorPlanilla', 'Pendiente', '$observaciones', '$idSupervisor', '$idContrato', '$valorPlanillaReal', '$encargado', '$idUsuario', '$mas_cotizacion')";
    $result = mysqli_query($conexion, $query);
    if (!$result) {
        die("Error en la consulta de insertar acta: " . mysqli_error($conexion));
    }
    $idActa = $conexion->insert_id;

    // Insertar actividades y actualizar impacto
    foreach ($idAlcances as $i => $alcance) {
        $actividad = mysqli_real_escape_string($conexion, $Actividades[$i]);
        $ubicacion = mysqli_real_escape_string($conexion, $Ubicaciones[$i]);

        $query1 = "INSERT INTO actividad (descripcion, ubicacion, numInforme, idActa, idAlcance, idContrato) 
                   VALUES ('$actividad', '$ubicacion', '$numInforme', '$idActa', '$alcance', '$idContrato')";
       
       $result1 = mysqli_query($conexion, $query1);
        if (!$result1) {
            die("Error en la consulta de insertar actividad: " . mysqli_error($conexion));
        }

        // Verificar si la actividad tiene datos válidos para actualizar el impacto
        // Actualizar impacto si la ubicación no está vacía ni igual a "NA"
        if ($ubicacion !== 'NA') {
            $query2 = "UPDATE alcance SET impacto = 'Si' WHERE id = '$alcance'";
            $result2 = mysqli_query($conexion, $query2);
            
            if (!$result2) {
                die("Error en la consulta de actualizar impacto: " . mysqli_error($conexion));
            }
        }
    }

    // Confirmar transacción
    mysqli_commit($conexion);
    header("location:listarActas.php?id=$idContrato&nombre=$NombreContratistas&NombreSupervisor=$NombreSupervisor");
    exit;
} catch (Exception $e) {
    mysqli_rollback($conexion);
    die("Error en la transacción: " . $e->getMessage());
}

// Cerrar conexión
mysqli_close($conexion);
ob_end_flush();
?>