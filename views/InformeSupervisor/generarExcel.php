<?php
include('../../db.php');

$idInforme = $_GET["id"];
$cuatrimestre = $_GET["cuatrimestre"];

$query= "SELECT * FROM informe_trimestral WHERE idInformeSupervisor = $idInforme ";

$result = mysqli_query($conexion,$query);

while ($rows = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
    $data[] = $rows; 
}

//Define the filename with current date
$fileName = "InformeCuatrimestral".$cuatrimestre."-".date('d-m-Y').".xls";

//Set header information to export data in excel format
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename='.$fileName);

//Set variable to false for heading
$heading = false;

foreach($data as $item) {
    if(!$heading) {
        echo implode("\t", array_keys($item)) . "\n";
        $heading = true;
    }
    echo implode("\t", array_values($item)) . "\n";
}
exit();

?>