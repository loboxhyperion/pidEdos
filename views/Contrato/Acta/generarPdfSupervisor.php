<?php
$idContrato = $_GET["id"];
$NombreContratistas = $_GET["nombre"];
$NombreSupervisor = $_GET["NombreSupervisor"];
$numInforme = $_GET["numInforme"];
$fechaInforme = $_GET["fechaInforme"];
$idActa = $_GET["idActa"];
$minimoMensual = 908526;
$minimoDia = $minimoMensual / 30;

include('../../../db.php');

//extraccion información del contrato
$query = "SELECT * FROM contrato  WHERE contrato.id = $idContrato";
$result = mysqli_query($conexion, $query) or die("fallo en la conexión");
$row = mysqli_fetch_array($result);

$query2 = "SELECT * FROM usuario  WHERE id = $row[idSupervisor]";
$result2 = mysqli_query($conexion, $query2) or die("fallo en la conexión");
$rowSupervisor = mysqli_fetch_array($result2);

$query22 = "SELECT * FROM usuario  WHERE id = $row[idSupervisor2]";
$result22 = mysqli_query($conexion, $query22) or die("fallo en la conexión");
$rowSupervisor2 = mysqli_fetch_array($result22);

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


//Extraccion de los alcances
$query6 = "SELECT * FROM alcance WHERE idContrato = $idContrato";
$result6 = mysqli_query($conexion, $query6);

//Extracción de las actuales actas 
$query7 = "SELECT * FROM acta  WHERE idContrato = $idContrato AND num_informe <= $numInforme ";
$result7 = mysqli_query($conexion, $query7) or die("fallo en la conexión ");
$arrayActa = array();
$arrayIdActas = array(); // para usarse en donde se impacto las actas las x





//Guarda los datos de las plantillas pagas y el acumulado del las actas creadas 
//sirve para mostrar en la proyeccion las actas que ya se han hecho
while ($filas7 = mysqli_fetch_assoc($result7)) {
    $arrayIdActas[] = $filas7['id'];
    $arrayActa[] = $filas7['acumulado'];
    $arrayActa[] = $filas7['fechaPlanilla'];
    $arrayActa[] = $filas7['numPlanilla'];
    $arrayActa[] = $filas7['valorPlanilla'];
}
/*for($i=0; $i < count($arrayActa); $i += 4){
    echo "<br>". $arrayActa[$i];
}*/
//Extracción de la proyeccion actual  correspondiente al acta a pagar
$query8 = "SELECT * FROM proyeccion_contractual WHERE idContrato = $idContrato and num_acta = $numInforme ";
$result8 = mysqli_query($conexion, $query8) or die("fallo en la conexión");
$filas8 = mysqli_fetch_array($result8);

//Extracción del  actual acta hecha 
$query9 = "SELECT * FROM acta  WHERE id = $idActa  ";
$result9 = mysqli_query($conexion, $query9) or die("fallo en la conexión acta");
$filas9 = mysqli_fetch_array($result9);

//============================================================+
// File name   : example_001.php
// Begin       : 2008-03-04
// Last Update : 2013-05-14
//
// Description : Example 001 for TCPDF class
//               Default Header and Footer
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

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
$pdf->Image('../../../public/img/actaParcial.jpg', 10, 2, 200);

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
                <th width="33%"><strong>Fecha de Informe</strong></th>
                <th width="33%"><strong>Acta N°</strong></th>
                <th width="34%"><strong>Modalidad Contractual</strong></th>
                </tr>
                <tr>
                <td width="33%">' . $fechaInforme . '</td>
                <td width="33%">' . $numInforme . '</td>
                <td style="text-align:left;" width="34%">' . $row['modalidad'] . '</td>
                </tr>
             </table>';

// Print text using writeHTMLCell()
$pdf->writeHTMLCell(0, 0, '', '', $informe, 0, 1, 0, true, '', true);

$datos = "";
// Set some content to print
$datos .= '

<h4 style="text-align:center">1.Datos Contractuales</h4>
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
        <td width="38%">' . 'Calle 50 NRO 14 -  56 Barrio LOS Naranjos' . '</td>
    </tr>
<!------------------------------------------------------------------------------------->  
<!-------------------------------------------------------------------------------------> 
<!-------------------------------------------------------------------------------------> 
    
<tr>
<td width="37%"><strong>Ordenador del Gasto Hasta Agosto 21-2024</strong></td>
<td width="25%"><strong>Documento No. </strong></td>
<td width="38%"><strong>Cargo</strong></td>
</tr>
<tr>
<td width="37%">' . 'LUIS ERNESTO VALENCIA RAMIREZ' . '</td>
<td width="25%">' . '18510078' . '</td>
<td width="38%">' . 'Gerente' . '</td>
</tr>
<tr>
<td width="37%"><strong>Ordenador del Gasto De Agos 22- A Sept-10-2024</strong></td>
<td width="25%"><strong>Documento No. </strong></td>
<td width="38%"><strong>Cargo</strong></td>
</tr>
<tr>
<td width="37%">' . 'SANDRA MILENA FRANCO BERMUDEZ' . '</td>
<td width="25%">' . '42136044' . '</td>
<td width="38%">' . 'Gerente (E)' . '</td>
</tr>
<tr>
<td width="37%"><strong>Ordenador del Gasto Desde Sept 11-2024</strong></td>
<td width="25%"><strong>Documento No. </strong></td>
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
<td width="15%"><strong>Documento No. </strong></td>
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
<td width="15%"><strong>Documento No.</strong></td>
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
</table>';

// Print text using writeHTMLCell()
$pdf->writeHTMLCell(0, 0, '', '', $datos, 0, 1, 0, true, '', true);

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

//---------------------------------------------2.Informe de Actividades--------------------------------------------------------------

//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
$actividades = "";
// Set some content to print
$actividades  .= '<h3 style="text-align:center"><br><br>2.Informe de Actividades</h3>
<table  cellspacing="3" cellpadding="2">
    <tr>
        <th width="9%"><strong>N° Alcance</strong></th>
        <th width="55%"><strong>Descripción Alcance </strong></th>
        <th width="3%"><strong>1</strong></th>
        <th width="3%"><strong>2</strong></th>
        <th width="3%"><strong>3</strong></th>
        <th width="3%"><strong>4</strong></th>
        <th width="3%"><strong>5</strong></th>
        <th width="3%"><strong>6</strong></th>
        <th width="3%"><strong>7</strong></th>
        <th width="3%"><strong>8</strong></th>
        <th width="3%"><strong>9</strong></th>
        <th width="3%"><strong>10</strong></th>
        <th width="3%"><strong>11</strong></th>
        <th width="3%"><strong>12</strong></th>
    </tr>';
$cont = 1;
//while($filas6=mysqli_fetch_array($result6)){
while ($filas6 = mysqli_fetch_array($result6)) {

    $actividades  .= '<tr>
                    <td width="9%">' . $cont . '</td>
                    <td width="55%">' . $filas6['nombre'] . '</td>';
    //Le da continuidad para que los alcances impactados con x vaya linea y no haya saltos en las actas
    $posActa = 1;
    for ($i = 0; $i < count($arrayIdActas); $i++) {
        //Extracción de las actividades actuales por acta
        $query10 = "SELECT * FROM actividad  WHERE idActa = $arrayIdActas[$i] AND IdAlcance = $filas6[id]";
        $result10 = mysqli_query($conexion, $query10) or die("fallo en la conexión" . $i);
        $filas10 = mysqli_fetch_array($result10);
        // llena los espacios de las actas impactadas con x
        for ($j = $posActa; $j <= 12; $j++) {
            if ($filas10['descripcion'] <> "" && $filas10['numInforme'] == $j) {
                if ($filas10['ubicacion'] <> "") {
                    $actividades .= '<td width="3%">X</td>';
                } else {
                    $actividades .= '<td width="3%"></td>';
                }
                $posActa++;
                break;
            } else {
                $actividades .= '<td width="3%"></td>';
            }
        }
    }

    $actividades .= '</tr>';
    $cont++;
}
$actividades .= '</table>';

// Print text using writeHTMLCell()
$pdf->writeHTMLCell(0, 0, '', '', $actividades, 0, 1, 0, true, '', true);

// ---------------------------------------------------------
// ---------------------------------------------------------
$nota = "";
// Set some content to print
$nota .= '<br><br><br><br><br><br><br><br><br><br><br><br><div>
                <p><h4>Observaciones Contratista</h4>' . $filas9['observaciones'] . ' 
            </div><br>';
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


//---------------------------------------------3.Novedades--------------------------------------------------------


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
$novedad .= '<h4 style="text-align:center">3.Novedades</h4>
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
        <td>' . number_format(intval($filas9['valor'])) . '</td>
        <td>' . number_format(intval($filas9['acumulado'])) . '</td>
        <td>' . number_format(intval($filas9['saldo'])) . '</td> 
        <td>' . $filas9['fechaPlanilla'] . '</td>
        <td>' . $filas9['numPlanilla'] . '</td>
        <td>' . number_format($filas9['valorPlanilla'], 2, ".", ",") . '</td>                 
    </tr>
</table>';


// Print text using writeHTMLCell()
$pdf->writeHTMLCell(0, 0, '', '', $novedad, 0, 1, 0, true, '', true);

//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------

//---------------------------------------------------//----------------------------------------------------------------------------------------------//-----------------
//---------------------------------------------------//----------------------------------------------------------------------------------------------//-----------------
//---------------------------------------------------//----------------------------------------------------------------------------------------------//-----------------
$cumplio = "";
// Set some content to print
$cumplio .= '<div>
                <p>El supervisor certifica que cumplió,durante el periodo del ' . $filas9['fecha_ini'] . ' al ' . $filas9['fecha_fin'] . ' a satisfacción el objeto presente contrato,además
                que ha revisado lo correspondiente a su afiliación al sistema Seguridad Social y ARL y se encuentra al dia durante el mes Mayo de  con dichas obligaciones legales.
                De igual forma certifico que el contratista realizo el respaldo magnetico del informe.</p>
            </div>';
// Print text using writeHTMLCell()
$pdf->writeHTMLCell(0, 0, '', '', $cumplio, 0, 1, 0, true, '', true);

if (isset($rowSupervisor2)) {
    //-----------------------------------------DATOS--------------------------------------------------------
    //Contratista
    //Firmas

    //Espacio Supervisor
    //$pdf->Cell(0, 10, '____________________________________',0,2,);



//Supervisor
    $pdf->SetFont('dejavusans', 'B', 7, '', true);
    $pdf->Cell(0, 10, $rowSupervisor['nombre'] . " " . $rowSupervisor['apellidos'],0,1);

    
    $pdf->SetFont('dejavusans', 'B', 6, '', true);
    $pdf->Cell(0, 0, 'SUPERVISOR',0,1,l);
    $pdf->Ln(5);//salto linea "supervisor";
 
    $pdf->Cell(0, 10, '',0,2);
    //$pdf->Cell(0, 10, '',0,2);
 
    
    //Espacio Contratista
    //$pdf->SetX(100);
    $pdf->Cell(10, 10, '_______________________________________________');
    $pdf->Ln(5);




    //-----------------------------------------DATOS--------------------------------------------------------
    //Supervisor
    //$pdf->SetFont('dejavusans', 'B', 7, '', true);
    //$pdf->Cell(0, 10, $rowSupervisor['nombre'] . " " . $rowSupervisor['apellidos'],0,2);
    //Valor
    
    //$pdf->SetX(100);
    $pdf->SetFont('dejavusans', 'B', 7, '', true);
    $pdf->Cell(0, 10, $rowSupervisor2['nombre'] . " " . $rowSupervisor2['apellidos']);
    $pdf->Ln(5);
    
    //-----------------------------------------DATOS2--------------------------------------------------------
     
    //$pdf->SetFont('dejavusans', '', 6, '', true);
    //$pdf->Cell(0, 10, 'SUPERVISOR',0,2);
    //$pdf->Ln(5);

    //Espacio Contratista
    //$pdf->SetX(100);
    $pdf->Cell(0, 10, utf8_decode('SUPERVISOR 2'));
} else {
    $firma = "";
    $firma .= '  
                <div>
                    <hr style="width:30%;">
                </div>
                
                <label style="text-align:left"><strong>' . $rowSupervisor['nombre'] . " " . $rowSupervisor['apellidos'] . '</strong></label><br>
                <label style="text-align:left">Supervisor</label>';
                
    // Print text using writeHTMLCell()
    $pdf->writeHTMLCell(0, 0, '', '', $firma, 0, 1, 0, true, '', true);
}


// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('generarPdfSupervisor.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
