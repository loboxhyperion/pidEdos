<?php
$idContrato = $_GET["id"];
$NombreContratistas = $_GET["nombre"];
$NombreSupervisor = $_GET["NombreSupervisor"];
//$fechaActa = $_GET["fechaActa"];
$numInforme = $_GET["numInforme"];
$idActa = $_GET["idActa"];
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

$query3 = "SELECT * FROM usuario  WHERE id = $row[idUsuario]";
$result3 = mysqli_query($conexion, $query3) or die("fallo en la conexión");
$rowContratista = mysqli_fetch_array($result3);

//Extracción del  actual acta hecha 
$query4 = "SELECT * FROM acta  WHERE id = $idActa  ";
$result4 = mysqli_query($conexion, $query4) or die("fallo en la conexión acta");
$filas4 = mysqli_fetch_array($result4);

// ---------------------------------------------------------------------------
// ---------------------------------------------------------------------------
// calculos para sacar los items que componen la seguridad social
$salud = round((0.430707739 * $filas4['valorPlanillaReal']), 0, PHP_ROUND_HALF_UP);
$pension = round((0.5513059059  * $filas4['valorPlanillaReal']), 0, PHP_ROUND_HALF_UP);
$arl = (0.01798635518 * $filas4['valorPlanillaReal']);
// ---------------------------------------------------------------------------
// ---------------------------------------------------------------------------
// ---------------------------------------------------------------------------
// ---------------------------------------------------------------------------
//require('fpdf.php');
// Include the main TCPDF library (search for installation path).
require_once('TCPDF/examples/tcpdf_include.php');


//$pdf = new FPDF();
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('Cuenta de Cobro');
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

$pdf->AddPage();

// set text shadow effect
$pdf->setTextShadow(array('enabled' => true, 'depth_w' => 0.2, 'depth_h' => 0.2, 'color' => array(196, 196, 196), 'opacity' => 1, 'blend_mode' => 'Normal'));
//


//--//------------------------------------------------------------------//CUENTA//------------------------------------------------------------
//--//------------------------------------------------------------------//CUENTA//------------------------------------------------------------
//--//------------------------------------------------------------------//CUENTA//------------------------------------------------------------
//Cuenta
$pdf->SetFont('dejavusans', 'B', 6);
$pdf->Cell(192, 8, utf8_decode('Cuenta de cobro No.' . $numInforme), 1, 1, 'C', 0);
//Logo
$pdf->Image('../../../public/img/cuenta.jpg',  15, 37, 192.1);
$pdf->Ln(30);

//--//------------------------------------------------------------------//FILA1//------------------------------------------------------------
//--//------------------------------------------------------------------//FILA1//------------------------------------------------------------
//--//------------------------------------------------------------------//FILA1//------------------------------------------------------------

//Contratista
$pdf->Cell(66, 8, utf8_decode('Nombre del Contratista'), 1, 0, 'C', 0);

//-----------------------------------------DATOS--------------------------------------------------------
// Contratista
$pdf->Cell(126, 8, utf8_decode($NombreContratistas), 1, 1, 'C', 0);

//--//------------------------------------------------------------------//FILA2//------------------------------------------------------------
//--//------------------------------------------------------------------//FILA2//------------------------------------------------------------
//--//------------------------------------------------------------------//FILA2//------------------------------------------------------------

//Email
$pdf->Cell(66, 8, utf8_decode("Correo electronico"), 1, 0, 'C', 0);
//-----------------------------------------DATOS--------------------------------------------------------
//Email
$pdf->Cell(126, 8, utf8_decode($rowContratista['correo']), 1, 1, 'C', 0);


//--//------------------------------------------------------------------//FILA3//------------------------------------------------------------
//--//------------------------------------------------------------------//FILA3//------------------------------------------------------------
//--//------------------------------------------------------------------//FILA3//------------------------------------------------------------

//RUT
$pdf->Cell(66, 8, utf8_decode("RUT"), 1, 0, 'C', 0);
//-----------------------------------------DATOS--------------------------------------------------------
// //RUT
$pdf->Cell(126, 8, utf8_decode($rowContratista['rut']), 1, 1, 'C', 0);

//--//------------------------------------------------------------------//FILA4//------------------------------------------------------------
//--//------------------------------------------------------------------//FILA4//------------------------------------------------------------
//--//------------------------------------------------------------------//FILA4//------------------------------------------------------------

//Actividad
$pdf->Cell(66, 8, utf8_decode("Actividad Economica"), 1, 0, 'C', 0);
//-----------------------------------------DATOS--------------------------------------------------------
//Actividad
$pdf->Cell(126, 8, utf8_decode($rowContratista['actividad_e']), 1, 1, 'C', 0);

//--//------------------------------------------------------------------//FILA5//------------------------------------------------------------
//--//------------------------------------------------------------------//FILA5//------------------------------------------------------------
//--//------------------------------------------------------------------//FILA5//------------------------------------------------------------

//Telefono
//Banco
$pdf->Cell(66, 8, utf8_decode("Banco"), 1, 0, 'C', 0);
//Cuenta 
$pdf->Cell(63, 8, utf8_decode("Cuenta Bancaria"), 1, 0, 'C', 0);
//Tipo 
$pdf->Cell(63, 8, utf8_decode("Tipo"), 1, 1, 'C', 0);
//-----------------------------------------DATOS--------------------------------------------------------
//Banco
$pdf->Cell(66, 8, utf8_decode($rowContratista['banco']), 1, 0, 'C', 0);
//Cuenta
$pdf->Cell(63, 8, utf8_decode($rowContratista['nro_cuenta']), 1, 0, 'C', 0);
//Tipo
$pdf->Cell(63, 8, utf8_decode($rowContratista['tipo_cuenta']), 1, 1, 'C', 0);

//--//------------------------------------------------------------------//FILA6//------------------------------------------------------------
//--//------------------------------------------------------------------//FILA6//------------------------------------------------------------
//--//------------------------------------------------------------------//FILA6//------------------------------------------------------------
//Telefono
$pdf->Cell(66, 8, utf8_decode("Telefono/Telefono Cel"), 1, 0, 'C', 0);
//-----------------------------------------DATOS--------------------------------------------------------
//Telefono
$pdf->Cell(126, 8, utf8_decode($rowContratista['telefono']), 1, 1, 'C', 0);

//--//------------------------------------------------------------------//FILA7//------------------------------------------------------------
//--//------------------------------------------------------------------//FILA7//------------------------------------------------------------
//--//------------------------------------------------------------------//FILA7//------------------------------------------------------------

//Direccion
$pdf->Cell(66, 8, utf8_decode("Direccion"), 1, 0, 'C', 0);
//-----------------------------------------DATOS--------------------------------------------------------
//Direccion
$pdf->Cell(126, 8, utf8_decode($rowContratista['direccion']), 1, 1, 'C', 0);
$pdf->Ln(10);

//---------------------------------------------------//----------------------------------------------------------------------------------------------//-----------------
//---------------------------------------------------//----------------------------------------------------------------------------------------------//-----------------
//---------------------------------------------------//----------------------------------------------------------------------------------------------//-----------------

//--//------------------------------------------------------------------//Cuenta a cobrar//------------------------------------------------------------
//--//------------------------------------------------------------------//Cuenta a cobrar//------------------------------------------------------------
//--//------------------------------------------------------------------//Cuenta a cobrar//------------------------------------------------------------
$pdf->SetFont('dejavusans', 'B', 6);
$sobrecargo = "";
// Set some content to print
$sobrecargo .= '
<table  border ="1" cellpadding="2">
    <tr>
        <th width="50%" align="center"><strong>CONCEPTO</strong></th>
        <th width="10%" align="center"><strong>FECHA DE INICIO</strong></th>
        <th width="10%" align="center"><strong>FECHA DE TERMINACION</strong></th>
        <th width="10%" align="center"><strong>VALOR INGRESADO</strong></th>
        <th width="10%" align="center"><strong>IVA</strong></th>
        <th width="17%" align="center"><strong>TOTAL SERVICIOS</strong></th>
    </tr>';
$sobrecargo  .= '<tr>
                    <td align="justify">' . $row['objeto'] . '</td>
                    <td align="center">' . $filas4['fecha_ini'] . '</td>
                    <td align="center">' . $filas4['fecha_fin'] . '</td>
                    <td align="center">' . number_format($filas4['valor'], 2, ".", ",") . '</td>
                    <td align="center">' . 0 . '</td>
                    <td align="center">' . number_format($filas4['valor'], 2, ".", ",") . '</td>
                </tr>';
$sobrecargo .= '</table><br>';

// Print text using writeHTMLCell()
$pdf->writeHTMLCell(0, 0, '', '', $sobrecargo, 0, 1, 0, true, '', true);

//--//------------------------------------------------------------------//Sumas//------------------------------------------------------------
//--//------------------------------------------------------------------//Sumas//------------------------------------------------------------
//--//------------------------------------------------------------------//Sumas//------------------------------------------------------------
$pdf->SetFont('dejavusans', 'B', 7);
//Total
$pdf->Cell(66, 8, utf8_decode("LA SUMA DE:"), 1, 0, 'C', 0);
//-----------------------------------------DATOS--------------------------------------------------------
//Total
$pdf->Cell(126, 8, utf8_decode("TRES MILLONES TRECIENTOS MIL PESOS"), 1, 1, 'C', 0);
$pdf->Ln(10);

//--//------------------------------------------------------------------//Anexos y Seguridad//------------------------------------------------------------
//--//------------------------------------------------------------------//Anexos y Seguridad//------------------------------------------------------------
//--//------------------------------------------------------------------//Anexos y Seguridad//------------------------------------------------------------

//Anexos
$pdf->SetFont('dejavusans', 'B', 6);
$pdf->Cell(90, 5, utf8_decode('ANEXOS CUENTA DE COBRO'), 1, 0, 'C', 0);
// Calculo Seguridad Social
$pdf->SetX(112);
$pdf->Cell(90, 5, utf8_decode('CALCULO APORTES SEGURIDAD SOCIAL'), 1, 1, 'C', 0);

//-----------------------------------------DATOS Anexo FILA 1--------------------------------------------------------
//Contratista
$pdf->SetFont('dejavusans', 'B', 5);
//Planilla
$pdf->Cell(52, 8, utf8_decode('Solicitud Aportes fondo de pensiones voluntarias.'), 1, 0, 'C', 0);
//respuesta
$pdf->Cell(3, 8, utf8_decode('Si'), 1, 0, 'C', 0);
//Planilla
$pdf->Cell(32, 8, utf8_decode('Solicitud Aportes Cuentas AFC'), 1, 0, 'C', 0);
//respuesta
$pdf->Cell(3, 8, utf8_decode('No'), 1, 0, 'C', 0);

//-----------------------------------------DATOS Seguridad FILA 1--------------------------------------------------------
//Contratista
$pdf->SetX(112);
//Planilla
$pdf->Cell(60, 8, utf8_decode('Aporte obligatorio a salud (12,5%) Minimo'), 1, 0, 'C', 0);
//respuesta
$pdf->Cell(30, 8, utf8_decode('$' . number_format($salud, 0, ".", ",")), 1, 1, 'C', 0);

//-----------------------------------------DATOS Anexo FILA 2--------------------------------------------------------
//Contratista
$pdf->SetFont('dejavusans', 'B', 5);
//Planilla
$pdf->Cell(52, 8, utf8_decode('Solicitud Aportes fondo de pensiones voluntarias'), 1, 0, 'C', 0);
//respuesta
$pdf->Cell(3, 8, utf8_decode('No'), 1, 0, 'C', 0);
//Planilla
$pdf->Cell(32, 8, utf8_decode('Otros'), 1, 0, 'C', 0);
//respuesta
$pdf->Cell(3, 8, utf8_decode('No'), 1, 0, 'C', 0);

//-----------------------------------------DATOS Seguridad FILA 2--------------------------------------------------------
//Contratista
$pdf->SetX(112);
//Planilla
$pdf->Cell(60, 8, utf8_decode('Aporte obligatorio a pension (16%) Minimo'), 1, 0, 'C', 0);
//respuesta
$pdf->Cell(30, 8, utf8_decode('$' . number_format($pension, 0, ".", ",")), 1, 1, 'C', 0);

//-----------------------------------------DATOS Seguridad FILA 3--------------------------------------------------------
//-----------------------------------------DATOS Seguridad FILA 3--------------------------------------------------------
//Contratista
$pdf->SetX(112);
//Planilla
$pdf->Cell(60, 8, utf8_decode('Fondo de solidaridad pensional 1%'), 1, 0, 'C', 0);
//respuesta
$pdf->Cell(30, 8, utf8_decode('0'), 1, 1, 'C', 0);

//-----------------------------------------DATOS Seguridad FILA 4--------------------------------------------------------
//-----------------------------------------DATOS Seguridad FILA 4--------------------------------------------------------
//Contratista
$pdf->SetX(112);
//Planilla
$pdf->Cell(60, 8, utf8_decode('Aportes a Riesgos laborales (ARL)'), 1, 0, 'C', 0);
//respuesta
$pdf->Cell(30, 8, utf8_decode('$' . number_format($arl, 0, ".", ",")), 1, 1, 'C', 0);

//-----------------------------------------DATOS Seguridad FILA 5--------------------------------------------------------
//-----------------------------------------DATOS Seguridad FILA 5--------------------------------------------------------
//Contratista
$pdf->SetX(112);
//Planilla
$pdf->Cell(60, 8, utf8_decode('Caja de compensacion'), 1, 0, 'C', 0);
//respuesta
$pdf->Cell(30, 8, utf8_decode('0'), 1, 1, 'C', 0);

//-----------------------------------------DATOS Seguridad FILA 5--------------------------------------------------------
//-----------------------------------------DATOS Seguridad FILA 5--------------------------------------------------------
//Contratista
$pdf->SetX(112);
//Planilla
$pdf->Cell(60, 8, utf8_decode('Total Aportes Obligatorios'), 1, 0, 'C', 0);
//respuesta
$pdf->Cell(30, 8, utf8_decode('$' . number_format($filas4['valorPlanilla'], 0, ".", ",")), 1, 1, 'C', 0);

$pdf->Ln(8);






//--//------------------------------------------------------------------////------------------------------------------------------------
//--//------------------------------------------------------------------////------------------------------------------------------------
//--//------------------------------------------------------------------////------------------------------------------------------------
//--//------------------------------------------------------------------////------------------------------------------------------------
//--//------------------------------------------------------------------////------------------------------------------------------------
//--//------------------------------------------------------------------////------------------------------------------------------------


//Firmas

//Espacio 
$pdf->SetFont('dejavusans', '', 8);
$pdf->Cell(0, 10, utf8_decode('__________________________'));
$pdf->Ln(5);


//-----------------------------------------DATOS--------------------------------------------------------
//Firma
$pdf->SetFont('dejavusans', 'B', 8);
$pdf->Cell(0, 10, utf8_decode("FIRMA DEL CONTRATISTA"));
$pdf->Ln(3);


//-----------------------------------------DATOS2--------------------------------------------------------
////Cédula
$pdf->SetFont('dejavusans', '', 6);
$pdf->Cell(0, 10, utf8_decode('C.C.'));
$pdf->Ln(8);



//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
// add text
$pdf->SetFont('dejavusans', '', 6);
$pdf->MultiCell(192, 3, utf8_decode('Me permito certificar bajo la gravedad de juramento, que de acuerdo con el Articulo 329 del E.T. Mis ingresos por mi actividad como trabajador independiente corresponden al 80% del total de mis ingresos. Aplicar retencion Art. 383 E.T. dcto 099 de 2013'), 0, '');
$pdf->Ln(3);





$pdf->Output();
