<?php
$idContrato = $_GET["id"];
$NombreContratistas = $_GET["nombre"];
$NombreSupervisor = $_GET["NombreSupervisor"];
$numInforme = $_GET["numInforme"];
$fechaInforme = $_GET["fechaInforme"];
$idActa = $_GET["idActa"];
$minimoMensual = 1000000;
$minimoDia = $minimoMensual / 30;

include('../../../db.php');

//extraccion información del contrato
$query = "SELECT * FROM contrato  WHERE contrato.id = $idContrato";
$result = mysqli_query($conexion, $query) or die("fallo en la conexión");
$row = mysqli_fetch_array($result);

$query2 = "SELECT * FROM usuario  WHERE id = $row[idSupervisor]";
$result2 = mysqli_query($conexion, $query2) or die("fallo en la conexión");
$rowSupervisor = mysqli_fetch_array($result2);

//Extracción del acta actual para comprobar si se realizo por un encargado o no
$query10 = "SELECT encargado FROM acta  WHERE idContrato = $idContrato AND num_informe = $numInforme ";
$result10 = mysqli_query($conexion, $query10) or die("fallo en la conexión acta ");
$rowEncargado = mysqli_fetch_array($result10);

//Si vacaciones y el acta estan en Si recalcula la consulta con los datos del  encargado
if ($rowSupervisor["vacaciones"] == "Si" && $rowEncargado["encargado"] == "Si") {
    $query2 = "SELECT * FROM usuario  WHERE usuario.id = $rowSupervisor[idEncargado]";
    $result2 = mysqli_query($conexion, $query2) or die("fallo en la conexión supervisor");
    $rowSupervisor = mysqli_fetch_array($result2);
}

$query3 = "SELECT * FROM usuario  WHERE id = $row[idUsuario]";
$result3 = mysqli_query($conexion, $query3) or die("fallo en la conexión");
$rowContratista = mysqli_fetch_array($result3);

//Extraccion de los registros de adiciones y suspensiones
$query4 = "SELECT * FROM adicion_suspension WHERE idContrato = $idContrato";
$result4 = mysqli_query($conexion, $query4);


//extraccion proyeccion
$query5 = "SELECT * FROM proyeccion_contractual WHERE idContrato = $idContrato and prioridad = 1";
$result5 = mysqli_query($conexion, $query5);

//Extraccion de los alcances
$query6 = "SELECT * FROM alcance WHERE idContrato = $idContrato";
$result6 = mysqli_query($conexion, $query6);

//Extracción de las actuales actas 
$query7 = "SELECT * FROM acta  WHERE idContrato = $idContrato AND num_informe <= $numInforme ";
$result7 = mysqli_query($conexion, $query7) or die("fallo en la conexión ");
$arrayActa = array();

$query10 = "SELECT * FROM usuario  WHERE id = $row[idOrdenador]";
$result10 = mysqli_query($conexion, $query10) or die("fallo en la conexión");
$rowOrdenador = mysqli_fetch_array($result10);



//Guarda los datos de las plantillas pagas y el acumulado del las actas creadas 
//sirve para mostrar en la proyeccion las actas que ya se han hecho
while ($filas7 = mysqli_fetch_assoc($result7)) {
    $arrayActa[] = $filas7['acumulado'];
    $arrayActa[] = $filas7['fechaPlanilla'];
    $arrayActa[] = $filas7['numPlanilla'];
    $arrayActa[] = $filas7['valorPlanilla'];
    $arrayActa[] = $filas7['estado'];
    if ($numInforme == $filas7['num_informe']) {
        $fechaTerminacion = $filas7['fecha_fin'];
    }
}
/*for($i=0; $i < count($arrayActa); $i += 4){
    echo "<br>". $arrayActa[$i];
}*/
//Extracción de la proyeccion actual  correspondiente al acta a pagar
$query8 = "SELECT * FROM proyeccion_contractual WHERE idContrato = $idContrato and num_acta = $numInforme ";
$result8 = mysqli_query($conexion, $query8) or die("fallo en la conexión");
$filas8 = mysqli_fetch_array($result8);

//Extracción del actual acta hecha 
$query9 = "SELECT * FROM acta  WHERE id = $idActa  ";
$result9 = mysqli_query($conexion, $query9) or die("fallo en la conexión acta");
$filas9 = mysqli_fetch_array($result9);

//extracción el número de actas ,uso saber si es el acta final o no
$query11 = "SELECT SUM(CASE WHEN prioridad='1' THEN 1 ELSE 0 END)  num_actas FROM proyeccion_contractual WHERE idContrato = $idContrato and prioridad = 1";
$result11 = mysqli_query($conexion, $query11);
$rowNumActas = mysqli_fetch_array($result11);
/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: Default Header and Footer
 * @author Nicola Asuni
 * @since 2008-03-04
 */

// Include the main TCPDF library (search for installation path).
require_once('TCPDF/examples/tcpdf_include.php');

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('Acta Parcial');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001', PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
$pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));

// set header and footer fonts
//$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
    require_once(dirname(__FILE__) . '/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set default font subsetting mode
$pdf->setFontSubsetting(true);

// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
$pdf->SetFont('dejavusans', '', 6, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage();

// set text shadow effect
$pdf->setTextShadow(array('enabled' => true, 'depth_w' => 0.2, 'depth_h' => 0.2, 'color' => array(196, 196, 196), 'opacity' => 1, 'blend_mode' => 'Normal'));
//
if ($numInforme == $rowNumActas["num_actas"]) {
    //Logo
    $pdf->Image('../../../public/img/actaFinal.jpg', 10, 2, 195);
} else {
    //Logo
    $pdf->Image('../../../public/img/informeActividades.jpg', 10, 2, 200);
}


//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------

//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------


//---------------------------------------------1.Datos Contractuales--------------------------------------------------------------


//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------

//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
$informe = "";
$informe .= '<table style="text-align:center;" border="1" cellpadding="2">
                <tr>
                <th width="33%"><strong>Fecha de Informe</strong></th>';
if ($numInforme == $rowNumActas["num_actas"]) {
    $informe .= '<th width="33%"><strong>Acta Final</strong></th>';
} else {
    $informe .= '<th width="33%"><strong>Acta N°</strong></th>';
}
$informe .= '<th width="34%"><strong>Modalidad Contractual</strong></th>
                </tr>
                <tr>
                <td width="33%">' . $fechaInforme . '</td>
                <td width="33%">' . $numInforme . '</td>
                <td style="text-align:left;" width="34%">' . $row['modalidad'] . '</td>
                </tr>
             </table><br>';

// Print text using writeHTMLCell()
$pdf->writeHTMLCell(0, 0, '', '', $informe, 0, 1, 0, true, '', true);

$datos = "";
// Set some content to print
$datos .= '

<h2 style="text-align:center">1.Datos Contractuales</h2>
<table style="text-align:center;" border="1" cellpadding="2">
    <tr>
        <th width="25%"><strong>Registro PPTAL</strong></th>
        <th width="25%"><strong>CDP</strong></th>
        <th width="25%"><strong>Año</strong></th>
        <th width="25%"><strong>Contrato N°</strong></th>
    </tr>
    <tr>
        <td width="25%">' . $row['registro_pptal'] . '</td>
        <td width="25%">' . $row['disp_presupuestal'] . '</td>
        <td width="25%">' . $row['years'] . '</td>
        <td width="25%">' . $row['num_contrato'] . '</td>
    </tr>

    <!------------------------------------------------------------------------------------->  
    <!-------------------------------------------------------------------------------------> 
    <!-------------------------------------------------------------------------------------> 
    
    <tr>
        <td width="100%"><strong>Rubro</strong></td>
    </tr>
    <tr>
        <td width="100%">' . $row['rubro'] . '</td>
    </tr>

    <!------------------------------------------------------------------------------------->  
    <!-------------------------------------------------------------------------------------> 
    <!-------------------------------------------------------------------------------------> 
    
    <tr>
        <td width="37%"><strong>Contratante</strong></td>
        <td width="25%"><strong>Nit</strong></td>
        <td width="38%"><strong>Dirección</strong></td>
    </tr>
    <tr>
        <td width="37%">' . 'Empresa de Desarrollo Urbano y Rural de Dosquebras' . '</td>
        <td width="25%">' . '816005795-1' . '</td>
        <td width="38%">' . 'Calle 50 No. 14 -  56 Barrio Los Naranjos' . '</td>
    </tr>

<!------------------------------------------------------------------------------------->  
<!-------------------------------------------------------------------------------------> 
<!-------------------------------------------------------------------------------------> 
    
<tr>
<td width="37%"><strong>Ordenador del Gasto Hasta Agosto 21 - 2024</strong></td>
<td width="25%"><strong>No. de Identidad</strong></td>
<td width="38%"><strong>Cargo</strong></td>
</tr>
<tr>
<td width="37%">' . $rowOrdenador['nombre']." ". $rowOrdenador['apellidos']. '</td>
<td width="25%">' . $rowOrdenador['cedula'] . '</td>
<td width="38%">' . $rowOrdenador['cargo'] . '</td>
</tr>
<tr>
<td width="37%"><strong>Ordenador del Gasto Desde Agos 22- A Sept-10-2024</strong></td>
<td width="25%"><strong>No. de Identidad</strong></td>
<td width="38%"><strong>Cargo</strong></td>
</tr>
<tr>
<td width="37%">' . 'SANDRA MILENA FRANCO BERMUDEZ' . '</td>
<td width="25%">' . '42136044' . '</td>
<td width="38%">' . 'Gerente (E)' . '</td>
</tr>
<tr>
<td width="37%"><strong>Ordenador del Gasto Desde Sept 11-2024</strong></td>
<td width="25%"><strong>No. de Identidad</strong></td>
<td width="38%"><strong>Cargo</strong></td>
</tr>
<tr>
<td width="37%">' . 'MANUEL ALBERTO RAMIREZ URIBE' . '</td>
<td width="25%">' . '10100227' . '</td>
<td width="38%">' . 'Gerente' . '</td>
</tr>
<!------------------------------------------------------------------------------------->  
<!-------------------------------------------------------------------------------------> 
<!------------------------------------------------------------------------------------->
<tr>';
if ($rowEncargado["encargado"] == "Si") {
    $datos .= '<td width="25%"><strong>Supervisor Encargado</strong></td>';

    
} else {
    $datos .= '<td width="25%"><strong>Supervisor</strong></td>';
}
$datos .= '
<td width="15%"><strong>No. de Identidad</strong></td>
<td width="20%"><strong>Cargo</strong></td>
<td width="20%"><strong>Fecha Delegación</strong></td>
<td width="20%"><strong>N° Delegación</strong></td>
</tr>
<tr>
<td width="25%">' . $rowSupervisor['nombre'] . " " . $rowSupervisor['apellidos'] . '</td> 
<td width="15%">' . $rowSupervisor['cedula'] . '</td>
<td width="20%">' . $rowSupervisor['cargo'] . '</td>
<td width="20%">' . $row['fecha_delegacion'] . '</td>
<td width="20%">' . $row['num_delegacion'] . '</td>
</tr>

<!------------------------------------------------------------------------------------->  
<!-------------------------------------------------------------------------------------> 
<!-------------------------------------------------------------------------------------> 
    
<tr>
<td width="25%"><strong>Contratista</strong></td>
<td width="15%"><strong>No. de Identidad</strong></td>
<td width="20%"><strong>Profesión</strong></td>
<td width="40%"><strong>Dirección</strong></td>
</tr>
<tr>
<td width="25%">' . $NombreContratistas . '</td>
<td width="15%">' . $rowContratista['cedula'] . '</td>
<td width="20%">' . $rowContratista['profesion'] . '</td>
<td width="40%">' . $rowContratista['direccion'] . '</td>
</tr>

<!------------------------------------------------------------------------------------->  
<!-------------------------------------------------------------------------------------> 
<!------------------------------------------------------------------------------------->  

<tr>
<td width="20%"><strong>Tipo de persona</strong></td>
<td width="20%"><strong>Resp de iva</strong></td>
<td width="20%"><strong>Razón social</strong></td>
<td width="20%"><strong>NIT</strong></td>
<td width="20%"><strong>Telefono</strong></td>
</tr>
<tr>
<td width="20%">' . $rowContratista['tipo_persona'] . '</td>
<td width="20%">' . $rowContratista['resp_iva'] . '</td>
<td width="20%">' . $rowContratista['razon_social'] . '</td>
<td width="20%">' . $rowContratista['nit'] . '</td>
<td width="20%">' . $rowContratista['telefono'] . '</td>
</tr>

<!------------------------------------------------------------------------------------->  
<!-------------------------------------------------------------------------------------> 
<!-------------------------------------------------------------------------------------> 


<tr>
<td width="30%"><strong>Correo</strong></td>
<td width="14%"><strong>Fecha Necesidad</strong></td>
<td width="11%"><strong>Fecha de Inicio</strong></td>
<td width="10%"><strong>Fecha de Fin</strong></td>
<td width="10%"><strong>Duración</strong></td>
<td width="10%"><strong>Valor</strong></td>
<td width="15%"><strong>Fecha de Firma de Contrato</strong></td>
</tr>
<tr>
<td width="30%">' . $rowContratista['correo'] . '</td>
<td width="14%">' . $row['fecha_necesidad'] . '</td>
<td width="11%">' . $row['fecha_ini'] . '</td>
<td width="10%">' . $row['fecha_fin'] . '</td>
<td width="10%">' . $row['duracion'] . '</td>
<td width="10%">' . number_format(intval($row['valor_contrato'])) . '</td>
<td width="15%">' . $row['fecha_firma'] . '</td>
</tr>

<!------------------------------------------------------------------------------------->  
<!-------------------------------------------------------------------------------------> 
<!------------------------------------------------------------------------------------->  

<tr>
<td style="text-align:left" width="100%"><strong>Objeto: </strong>' . $row['objeto'] . '</td>
</tr>

<!------------------------------------------------------------------------------------->  
<!-------------------------------------------------------------------------------------> 
<!-------------------------------------------------------------------------------------> 

<tr>
<td style="text-align:left" width="100%"><strong>Forma de pago: </strong>' . $row['forma_pago'] . '</td>
</tr>

<!------------------------------------------------------------------------------------->  
<!-------------------------------------------------------------------------------------> 
<!-------------------------------------------------------------------------------------> 

<tr>
<td style="text-align:left" width="100%"><strong>Entregables: </strong>' . $row['entregables'] . '</td>
</tr>

<!------------------------------------------------------------------------------------->  
<!-------------------------------------------------------------------------------------> 
<!-------------------------------------------------------------------------------------> 

<tr>
<td style="text-align:left" width="100%"><strong>Observaciones: </strong>' . $row['observaciones'] . '</td>
</tr>

<!------------------------------------------------------------------------------------->  
<!-------------------------------------------------------------------------------------> 
<!-------------------------------------------------------------------------------------> 

</table><br>';


// Print text using writeHTMLCell()
$pdf->writeHTMLCell(0, 0, '', '', $datos, 0, 1, 0, true, '', true);


$sobrecargo = "";
// Set some content to print
$sobrecargo .= '
<table  border ="1" cellpadding="2">
    <tr>
        <th width="9%" align="center"><strong>N° Modificación</strong></th>
        <th width="8%" align="center"><strong>Tipo</strong></th>
        <th width="5%" align="center"><strong>CDP</strong></th>
        <th width="5%" align="center"><strong>RP</strong></th>
        <th width="10%" align="center"><strong>Fecha de modifiación</strong></th>
        <th width="9%" align="center"><strong>Fecha de suspension</strong></th>
        <th width="9%" align="center"><strong>Fecha de reinicio</strong></th>
        <th width="5%" align="center"><strong>Dias</strong></th>
        <th width="10%" align="center"><strong>Valor</strong></th>
        <th width="15%" align="center"><strong>Fecha de terminación Anterior</strong></th>
        <th width="15%" align="center"><strong>Nueva fecha de terminación</strong></th>
    </tr>';
$cont = 1;
//while($filas6=mysqli_fetch_array($result6)){
while ($filas4 = mysqli_fetch_array($result4)) {

    $sobrecargo  .= '<tr>
                    <td align="center">' . $cont . '</td>
                    <td align="center">' . $filas4['tipo'] . '</td>
                    <td align="center">' . $filas4['cdp'] . '</td>
                    <td align="center">' . $filas4['rp'] . '</td>';
                    
    if ($filas4['tipo'] == "Suspension") {
        $sobrecargo .= '<td>' . $filas4['fecha_modificacion'] . '</td>
                                        <td>' . $filas4['fecha_suspension'] . '</td>
                                        <td>' . $filas4['fecha_reinicio'] . '</td>';
    } else {
        $sobrecargo .= '<td>NA</td>
                                        <td>NA</td> 
                                        <td>NA</td>';
    }
    $sobrecargo  .= '
                    <td align="center">' . round($filas4['dias']) . '</td>
                    <td>' . number_format(round($filas4['valor']), 2, ".", ",") . '</td>
                    <td>' . $filas4['fecha_terminacion_pre'] . '</td>
                    <td>' . $filas4['fecha_terminacion_new'] . '</td>
                    ';
    $sobrecargo .= '</tr>';
    $cont++;
}
$sobrecargo .= '</table><br>';


// Print text using writeHTMLCell()
$pdf->writeHTMLCell(0, 0, '', '', $sobrecargo, 0, 1, 0, true, '', true);

//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------

//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------


//---------------------------------------------2.Seguridad Social E Impuestos--------------------------------------------------------------


//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------

//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
$query= "SELECT r.id,r.nombre,r.porcentaje,r.tipo,r.orden FROM contrato AS c 
INNER JOIN  contrato_retencion AS cr ON c.id = cr.idContrato 
INNER JOIN retencion AS r ON cr.idRetencion = r.id  
WHERE c.id = $idContrato AND (r.orden = 1 OR r.orden = 2 OR r.orden = 3) ORDER BY r.orden ASC";

$retenciones = mysqli_query($conexion,$query);

$retencionesTabla= "";
// Set some content to print
$retencionesTabla .= '<h2 style="text-align:center">2.Seguridad Social E Impuestos</h2><br><br>
<h3 style="text-align:center">Seguridad Social</h3>
<table border ="1" cellpadding="2">
    <tr>
        <th width="9%" style="text-align:center;"><strong>Item</strong></th>
        <th width="4%" style="text-align:center;"><strong>%</strong></th>
        <th width="9%" style="text-align:center;"><strong>Pagado por la entidad</strong></th>
        <th width="8%" style="text-align:center;"><strong>Tipo</strong></th>
        <th width="5%" style="text-align:center;"><strong>Dias a Pagar</strong></th>
        <th width="10%" style="text-align:center;"><strong>Correspondiente al Acta</strong></th>
        <th width="13%" style="text-align:center;"><strong>Salud</strong></th>
        <th width="12%" style="text-align:center;"><strong>Pensión</strong></th>
        <th width="12%" style="text-align:center;"><strong>ARL</strong></th>
        <th width="9%" style="text-align:center;"><strong>Fecha afiliación</strong></th>
        <th width="8%" style="text-align:center;"><strong>Día Habil Pago</strong></th>
    </tr>';
    

$count = 1;
$pagoTotalActa = 0;
$seguridadSocial = 0;
$baseCotizacion = 0;

while($retencion=mysqli_fetch_array($retenciones)){ 

    if($count == 1){
            // sacamos la base de cotización
            $baseCotizacion = round(($retencion['porcentaje']/100) * ($filas8['valor_dia'] * $filas8['dias']));
            $baseCotizacion =  $baseCotizacion > $minimoMensual ? $baseCotizacion : $minimoMensual;
    }else{
        $seguridadSocial = round(($retencion['porcentaje'] / 100) * $baseCotizacion);
    }

    // para el caso que sea riesgo 5
    $entidadPaga = $retencion['nombre'] == "Riesgo 5"
    ? $seguridadSocial  //si lo es
    : 0;

    $retencionesTabla .= '<tr>
                    <td>' . $retencion['nombre'] . '</td>
                    <td>' . $retencion['porcentaje'] . '</td>
                    <td>' .  number_format($entidadPaga,2). '</td>
                    <td>' . $retencion['tipo'] . '</td>
                    ';
    // Para los días
    if ($count == 1){
        $retencionesTabla .= '<td rowspan="4" style="text-align:center; vertical-align:middle;">
                                <h1>'. $filas8['dias'] .'</h1>
                              </td>';
    }
    $retencionesTabla .= '<td>';
    // correspondiente al acta
    if($count == 1){ 
        $retencionesTabla .= ''.number_format($baseCotizacion,2).'';
    }elseif($entidadPaga > 0){
        $retencionesTabla .= ''.number_format(0,2).'';
    }else{
        $retencionesTabla .= ''.number_format($seguridadSocial,2).'';
        $pagoTotalActa  = $pagoTotalActa + $seguridadSocial;
    } 
    $retencionesTabla .= '</td>';
    

    if ($count == 1){
        $retencionesTabla .= '
        <td rowspan="4" style="text-align:center; vertical-align:middle;">
            <h4>'. $row['salud'] . '</h4>
        </td>
        <td rowspan="4" style="text-align:center; vertical-align:middle;">
            <h4>'. $row['pension'] . '</h4>
        </td>
        <td rowspan="4" style="text-align:center; vertical-align:middle;">
            <h4>'. $row['arl'] . '</h4>
        </td>
        <td rowspan="4" style="text-align:center; vertical-align:middle;">
            <h4>'.date('d-m-Y', strtotime($row['fecha_activacion'])) . '</h4>
        </td>
        <td rowspan="4" style="text-align:center; vertical-align:middle;">
            <h1>'. $row['dia_habil_pago'] . '</h1>
        </td>';   
    }
    
    $retencionesTabla .= '</tr>';
    $count++;
}
// Para la fila de totales
$retencionesTabla .= '<tr>
                    <td colspan="6" style="text-align:center; vertical-align:middle;">
                        <h3>Total a Pagar:</h3>
                    </td>
                    <td colspan="5" style="text-align:center; vertical-align:middle;">
                        <h3>' . number_format($pagoTotalActa,2) . '</h3>
                    </td>';

$retencionesTabla .= '</tr>';
$retencionesTabla .= '</table>';

// Nota aclaratoria si pagas mas
$retencionesTabla .= '
                    <p>
                        <h4>Observación</h4>'
                        .$filas9['mas_cotizacion'].
                    '</p>';

// Print text using writeHTMLCell()
$pdf->writeHTMLCell(0, 0, '', '', $retencionesTabla, 0, 1, 0, true, '', true);


//---------------------------------------------2. Impuestos--------------------------------------------------------------


//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------

$query= "SELECT r.id,r.nombre,r.porcentaje,r.tipo,r.orden FROM contrato AS c 
INNER JOIN  contrato_retencion AS cr ON c.id = cr.idContrato 
INNER JOIN retencion AS r ON cr.idRetencion = r.id  
WHERE c.id = $idContrato AND r.orden = 4  ORDER BY r.orden ASC";

$impuestos = mysqli_query($conexion,$query);

$impuestosTabla= "";
// Set some content to print
$impuestosTabla .= '<h3 style="text-align:center">Impuestos</h3>
<table border ="1" cellpadding="2">
    <tr>
        <th width="30%" style="text-align:center;"><strong>Item</strong></th>
        <th width="10%" style="text-align:center;"><strong>%</strong></th>
        <th width="20%" style="text-align:center;"><strong>Tipo</strong></th>
        <th width="20%" style="text-align:center;"><strong>Dias a Pagar</strong></th>
        <th width="20%" style="text-align:center;"><strong>Correspondiente al Acta</strong></th>
    </tr>';
    

$count = 1;
$pagoTotalImpuestos = 0;

while($impuesto=mysqli_fetch_array($impuestos)){ 

    
    //La condicional verdadera solo se hace en la primera acta
    $estampilla = $numInforme == 1 && $impuesto['nombre'] == "Hospital Universitario - Homeris" 
    ? round(($impuesto['porcentaje'] / 100) * ($row['valor_contrato']))// Se descuenta el impuesto de la estampilla hospital del valor total del contrato
    : round(($impuesto['porcentaje'] / 100) *  ($filas8['valor_dia'] * $filas8['dias']));// de no ser la primera acta sacamos el calculo de la estampilla normal segun los dias a pagar

    // solo deja pasar 1 vez el impuesto hospital en el acta 1 
    if($impuesto["nombre"] != "Hospital Universitario - Homeris"  || $numInforme == 1){

        $impuestosTabla .= '<tr style="text-align:center;">
        <td>' . $impuesto['nombre'] . '</td>
        <td>' . $impuesto['porcentaje'] . '</td>
        <td>' . $impuesto['tipo'] . '</td>
        ';
        // Para los días
        if ($count == 1){
            $impuestosTabla .= '<td rowspan="4" style="text-align:center; vertical-align:middle;">
                                    <h1>'. $filas8['dias'] .'</h1>
                                </td>';
        }
        $impuestosTabla .= '<td>';
        // correspondiente al acta
        if($impuesto['nombre'] != "Hospital Universitario - Homeris"){
            $impuestosTabla .= ''.number_format($estampilla,2).'';
            $pagoTotalImpuestos   = $pagoTotalImpuestos  + $estampilla;
        }
        // Solo lo hace en la primera acta 
        // Ya que la estampilla también debemos mostrarla y agregarlo al total del pago
        if($numInforme == 1 && $impuesto['nombre'] == "Hospital Universitario - Homeris"){ 
            $impuestosTabla .= ''.number_format($estampilla,2).'';
            $pagoTotalImpuestos   = $pagoTotalImpuestos + $estampilla;
        } 
        $impuestosTabla .= '</td>'; 
        $impuestosTabla .= '</tr>';
        $count++;
        
    }
    
}
// Para la fila de totales
$impuestosTabla .= '<tr>
                    <td colspan="4" style="text-align:center; vertical-align:middle;">
                        <h3>Total de Impuestos en Acta:</h3>
                    </td>
                    <td colspan="1" style="text-align:center; vertical-align:middle;">
                        <h3>' . number_format($pagoTotalImpuestos,2) . '</h3>
                    </td>';

$impuestosTabla .= '</tr>';
$impuestosTabla .= '</table>';

$impuestosTabla .= '
                    <p>
                        <h4>Nota Aclaratoria</h4>
                        En relación con los contratos de prestación de servicios, se informa que el software refleja los descuentos correspondientes a las estampillas en el apartado de impuestos. Sin embargo, el descuento por concepto de Industria y Comercio no se encuentra incluido en el cálculo del programa, debido a que este porcentaje varía según la actividad económica de cada contratista. Es importante tener en cuenta que dicho impuesto sí se aplica y descuenta directamente en la nómina correspondiente.
                    </p>
                 ';

// Print text using writeHTMLCell()
$pdf->writeHTMLCell(0, 0, '', '', $impuestosTabla, 0, 1, 0, true, '', true);

// ---------------------------------------------------------

//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------


//---------------------------------------------3.Proyeccion contractual--------------------------------------------------------------


//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------

//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------

$proyeccion = "";
// Set some content to print
$proyeccion  .= '<h2 style="text-align:center">3.Proyeccion contractual</h2>
<table  border ="1" cellpadding="2">
    <tr>
        <th width="5%" align="center"><strong>#</strong></th>
        <th width="8%" align="center"><strong>Periodo Inicio</strong></th>
        <th width="8%" align="center"><strong>Periodo Fin</strong></th>
        <th width="6%" align="center"><strong>Dias</strong></th>
        <th width="9%" align="center"><strong>Valor Dia</strong></th>
        <th width="10%" align="center"><strong>Valor</strong></th>
        <th width="10%" align="center"><strong>Acumulado</strong></th>
        <th width="10%" align="center"><strong>Saldo</strong></th>
        <th width="8%" align="center"><strong>Fecha de pago de Planilla</strong></th>
        <th width="8%" align="center"><strong>Planilla</strong></th>
        <th width="10%" align="center"><strong>Valor Pagado</strong></th>
        <th width="8%" align="center"><strong>Estado Del Acta</strong></th>
    </tr>';
$cont = 1;
//while($filas6=mysqli_fetch_array($result6)){
while ($filas5 = mysqli_fetch_array($result5)) {

    $proyeccion  .= '<tr>
                    <td align="center">' . $cont . '</td>
                    <td>' . $filas5['periodo_ini'] . '</td>
                    <td>' . $filas5['periodo_fin'] . '</td>
                    <td align="center">' . round($filas5['dias']) . '</td>
                    <td>' . number_format(intval($filas5['valor_dia'])) . '</td>
                    <td>' . number_format(intval($filas5['valor_mes'])) . '</td>
                    <td>' . number_format(round($filas5['acomulado']), 2, ".", ",") . '</td>
                    <td>' . number_format(round($filas5['saldo']), 2, ".", ",") . '</td>
                    ';
    /*--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
                    -------------MUESTRA LA INFORMACION DE LAs PLANILLA YA REALIZADAS-------------------------------------------------------------------------------------------------------------------------------------------------------->
                    --------------------------------------------------------------------------------------------------------------------------------------------------------------------->*/
    $sinData = true;
    for ($i = 0; $i < count($arrayActa); $i += 5) {
        if ($arrayActa[$i] == round($filas5['acomulado'])) {
            $sinData = false;
            $proyeccion  .= '
                              <td>' . $arrayActa[$i + 1] . '</td>
                              <td>' . $arrayActa[$i + 2] . '</td>
                              <td>' . number_format($arrayActa[$i + 3], 2, ".", ",") . '</td>
                              <td>' . $arrayActa[$i + 4] . '</td>
                              ';
        }
    }
    if ($sinData) {
        $proyeccion  .= '
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>Pendiente</td>
                        ';
    }
    $proyeccion .= '</tr>';
    $cont++;
}
$proyeccion .= '</table><br>';


// Print text using writeHTMLCell()
$pdf->writeHTMLCell(0, 0, '', '', $proyeccion, 0, 1, 0, true, '', true);
// $pdf->AddPage();

//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------

//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------


//---------------------------------------------4.Informe de Actividades--------------------------------------------------------------


//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------

//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
$actividades = "";
// Set some content to print
$actividades  .= '<h2 style="text-align:center">4.Informe de Actividades</h2>
<table  border ="1" cellpadding="2">
    <tr>
        <th width="6%"><strong>N° Alcance</strong></th>
        <th width="30%" align="center"><strong>Descripción Alcance </strong></th>
        <th width="32%" align="center"><strong>Actividades Realizadas</strong></th>
        <th width="32%" align="center"><strong>Registro</strong></th>
    </tr>';
$cont = 1;
//while($filas6=mysqli_fetch_array($result6)){
while ($filas6 = mysqli_fetch_array($result6)) {
    //Extracción de las actividades actuales
    $query8 = "SELECT * FROM actividad  WHERE idActa = $idActa AND IdAlcance = $filas6[id]";
    $result8 = mysqli_query($conexion, $query8) or die("fallo en la conexión");
    $filas8 = mysqli_fetch_array($result8);

    $actividades  .= '<tr>
                    <td width="6%" align="center">' . $cont . '</td>
                    <td width="30%" align="left">' . $filas6['nombre'] . '</td>
                    <td width="32%">' . $filas8['descripcion'] . '</td>
                    <td width="32%">' . $filas8['ubicacion'] . '</td>';

    $actividades .= '</tr>';
    $cont++;
}
$actividades .= '</table><br>';


// Print text using writeHTMLCell()
$pdf->writeHTMLCell(0, 0, '', '', $actividades, 0, 1, 0, true, '', true);

// ---------------------------------------------------------
$nota = "";
// Set some content to print
$nota .= '<div>
                <p><h4>Observaciones</h4>' . $filas9['observaciones'] . ' 
            </div><br><br><br><br>';
// Print text using writeHTMLCell()
$pdf->writeHTMLCell(0, 0, '', '', $nota, 0, 1, 0, true, '', true);

//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------

//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------


//---------------------------------------------5.Novedades--------------------------------------------------------


//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------

//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------

//---------------------------------------------------//----------------------------------------------------------------------------------------------//-----------------
//---------------------------------------------------//----------------------------------------------------------------------------------------------//-----------------
//---------------------------------------------------//----------------------------------------------------------------------------------------------//-----------------
$novedad = "";
// Set some content to print
$novedad .= '<h2 style="text-align:center">5.Novedades</h2>
<table border="1" cellpadding="2">
    <tr>
        <th><strong>Periodo Inicio</strong></th>
        <th><strong>Periodo Fin</strong></th>
        <th><strong>Dias</strong></th>
        <th><strong>Valor</strong></th>
        <th><strong>Acumulado</strong></th>
        <th><strong>Saldo</strong></th>
        <th><strong>Fecha de pago de Planilla</strong></th>
        <th><strong>N° Planilla</strong></th>
        <th><strong>Valor Pagado</strong></th>
    </tr>
    <tr>
        <td>' . $filas9['fecha_ini'] . '</td>
        <td>' . $filas9['fecha_fin'] . '</td>
        <td>' . $filas9['diasPagos'] . '</td>
        <td>' . number_format($filas9['valor'], 2, ".", ",") . '</td>
        <td>' . number_format($filas9['acumulado'], 2, ".", ",") . '</td>
        <td>' . number_format($filas9['saldo'], 2, ".", ",") . '</td> 
        <td>' . $filas9['fechaPlanilla'] . '</td>
        <td>' . $filas9['numPlanilla'] . '</td>
        <td>' . number_format($filas9['valorPlanilla'], 2, ".", ",") . '</td>                 
    </tr>
</table><br>';


// Print text using writeHTMLCell()
$pdf->writeHTMLCell(0, 0, '', '', $novedad, 0, 1, 0, true, '', true);

//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------

//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------

//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------

//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------

//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//MUESTRA EL TEXTO DEL ACTA FINAL SI ES CIERTO LA SENTENCIA
//PARA CUANDO  EL ACTA ACTUAL SEA IGUAL A LA ULTIMA ACTA DEL CONTRATO
if ($numInforme == $rowNumActas["num_actas"]) {

    $cumplio = "";
    // Set some content to print
    $cumplio .= '<div>
    <p>Para la cancelación de la presente acta se tuvo en cuenta entre otras las actividades mostradas en el informe anexo y si es contrato de obra se anexa el cuadro de 
    obras ejecutadas.Estas actividades se realizaron en el periodo comprendido entre el: ' . $row['fecha_ini'] . ' y el ' . $fechaTerminacion . ' El contratista presentó al día los pagos de seguridad social incluyendo la tasa
    correspondiente a su nivel de riesgo y documentos requeridos, como consta en los anexos. Se firma en Dosquebradas el día:
    </div><br><br><br><br>';
    // Print text using writeHTMLCell()
    $pdf->writeHTMLCell(0, 0, '', '', $cumplio, 0, 1, 0, true, '', true);
    //-----------------------------------------DATOS--------------------------------------------------------
    //Contratista
    //Firmas

    //Espacio Supervisor
    $pdf->Cell(0, 10, '____________________________________');


    //Espacio Contratista
    $pdf->SetX(100);
    $pdf->Cell(0, 10, '_______________________________________________');
    $pdf->Ln(5);

    //-----------------------------------------DATOS--------------------------------------------------------
    //Supervisor
    $pdf->SetFont('dejavusans', 'B', 7, '', true);
    $pdf->Cell(0, 10, $NombreContratistas);

    //Valor
    $pdf->SetX(100);
    $pdf->Cell(0, 10, $NombreSupervisor);
    $pdf->Ln(5);
    //-----------------------------------------DATOS2--------------------------------------------------------
    $pdf->SetFont('dejavusans', '', 6, '', true);
    $pdf->Cell(0, 10, 'CONTRATISTA');


    //Espacio Contratista
    $pdf->SetX(100);
    $pdf->Cell(0, 10, utf8_decode('SUPERVISOR'));
} else {
    $cumplio = "";
    // Set some content to print
    $cumplio .= '<div>
    <p>El supervisor certifica que cumplió,durante el periodo del ' . $filas9['fecha_ini'] . ' al ' . $filas9['fecha_fin'] . ' a satisfacción el objeto presente contrato,además
    que ha revisado lo correspondiente a su afiliación al sistema Seguridad Social y ARL y se encuentra al dia durante el mes de Junio  con dichas obligaciones legales.
    De igual forma certifico que el contratista realizo el respaldo magnetico del informe.
    </div><br><br><br><br>';
    // Print text using writeHTMLCell()
    $pdf->writeHTMLCell(0, 0, '', '', $cumplio, 0, 1, 0, true, '', true);

    //-----------------------------------------DATOS--------------------------------------------------------
    //Contratista

    $firma = "";
    $firma .= '  
                <div>
                    <hr style="width:30%;">
                </div>
                <label style="text-align:left"><strong>' . $NombreContratistas . '</strong></label><br>
                <label style="text-align:left">CONTRATISTA</label>';



    // Print text using writeHTMLCell()
    $pdf->writeHTMLCell(0, 0, '', '', $firma, 0, 1, 0, true, '', true);
}

//--//------------------------------------------------------------------////------------------------------------------------------------
//--//------------------------------------------------------------------////------------------------------------------------------------
//--//------------------------------------------------------------------////------------------------------------------------------------
//--//------------------------------------------------------------------////------------------------------------------------------------
//--//------------------------------------------------------------------////------------------------------------------------------------
//--//------------------------------------------------------------------////------------------------------------------------------------



// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('generarPdfSupervisor.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+


$pdf->Output();