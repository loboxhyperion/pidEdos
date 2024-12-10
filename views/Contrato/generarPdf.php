<?php
//seguridad de sessiones paginacion
session_start();
error_reporting(0);

$idContrato = $_GET["id"];
$NombreContratistas = $_GET["nombre"];
$NombreSupervisor = $_GET["NombreSupervisor"];
$fechaActa = $_GET["fechaActa"];

include('../../db.php');

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

$query4 = "SELECT * FROM usuario  WHERE id = $row[idOrdenador]";
$result4 = mysqli_query($conexion, $query4) or die("fallo en la conexión");
$rowOrdenador = mysqli_fetch_array($result4);

require('fpdf.php');


$pdf = new FPDF();
$pdf->AddPage();

//Logo
$pdf->Image('../../public/img/actaInicio.jpg', 10, 2, 200);
$pdf->Ln(12);





//FECHA 
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(56, 8, mb_convert_encoding('Fecha de Acta', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', 0);
// Acta N 
$pdf->Cell(56, 8, mb_convert_encoding('Acta N° ', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', 0);
//  Modalidad Contractual
$pdf->Cell(80, 8, mb_convert_encoding('Modalidad Contractual ', 'ISO-8859-1', 'UTF-8'), 1, 1, 'C', 0);


//-----------------------------------------DATOS--------------------------------------------------------
//FECHA 
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(56, 8, mb_convert_encoding($fechaActa, 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', 0);
//Acta N 
$pdf->Cell(56, 8, mb_convert_encoding('1', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', 0);
// Modalidad Contractual
$pdf->Cell(80, 8, mb_convert_encoding($row['modalidad'], 'ISO-8859-1', 'UTF-8'), 1, 1, 'C', 0);

//---------------------------------------------------//----------------------------------------------------------------------------------------------//-----------------
//---------------------------------------------------//----------------------------------------------------------------------------------------------//-----------------
//---------------------------------------------------//----------------------------------------------------------------------------------------------//-----------------

//--//------------------------------------------------------------------//FILA1//------------------------------------------------------------
//--//------------------------------------------------------------------//FILA1//------------------------------------------------------------
//--//------------------------------------------------------------------//FILA1//------------------------------------------------------------
//Registro PPTAL 
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(50, 5, mb_convert_encoding('Registro PPTAL', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', 0);
//  CDP  
$pdf->Cell(46, 5, mb_convert_encoding('CDP', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', 0);
//Año
$pdf->Cell(46, 5, mb_convert_encoding('Año', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', 0);
//Contrato N° 
$pdf->Cell(50, 5, mb_convert_encoding('Contrato N°', 'ISO-8859-1', 'UTF-8'), 1, 1, 'C', 0);


//-----------------------------------------DATOS--------------------------------------------------------

//Registro PPTAL 
$pdf->SetFont('Arial', 'B', 5);
$pdf->Cell(50, 5, mb_convert_encoding($row['registro_pptal'], 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', 0);
//  CDP  
$pdf->Cell(46, 5, mb_convert_encoding($row['disp_presupuestal'], 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', 0);
//Año
$pdf->Cell(46, 5, mb_convert_encoding($row['years'], 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', 0);
//Contrato N° 
$pdf->Cell(50, 5, mb_convert_encoding($row['num_contrato'], 'ISO-8859-1', 'UTF-8'), 1, 1, 'C', 0);

//--//------------------------------------------------------------------//FILA2//------------------------------------------------------------
//--//------------------------------------------------------------------//FILA2//------------------------------------------------------------
//--//------------------------------------------------------------------//FILA2//------------------------------------------------------------
//  Rubro 
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(192, 5, mb_convert_encoding('Rubro', 'ISO-8859-1', 'UTF-8'), 1, 1, 'C', 0);
//-----------------------------------------DATOS--------------------------------------------------------

//  Rubro 
$pdf->SetFont('Arial', 'B', 5);
$pdf->Cell(192, 5, mb_convert_encoding($row['rubro'], 'ISO-8859-1', 'UTF-8'), 1, 1, 'C', 0);



//--//------------------------------------------------------------------//FILA4//------------------------------------------------------------
//--//------------------------------------------------------------------//FILA4//------------------------------------------------------------
//--//------------------------------------------------------------------//FILA4//------------------------------------------------------------
//Contratante
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(85, 5, mb_convert_encoding('Contratante', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', 0);
//  Nit  
$pdf->Cell(27, 5, mb_convert_encoding('Nit', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', 0);
//Dirección
$pdf->Cell(80, 5, mb_convert_encoding('Dirección', 'ISO-8859-1', 'UTF-8'), 1, 1, 'C', 0);

//-----------------------------------------DATOS--------------------------------------------------------

//Registro PPTAL 
$pdf->SetFont('Arial', 'B', 5);
$pdf->Cell(85, 5, mb_convert_encoding('Empresa de Desarrollo Urbano y Rural de Dosquebras', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', 0);
//  Nit  
$pdf->Cell(27, 5, mb_convert_encoding('816005795-1', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', 0);
//Dirección
$pdf->Cell(80, 5, mb_convert_encoding('Calle 50 NRO 14 -  56 Barrio LOS Naranjos', 'ISO-8859-1', 'UTF-8'), 1, 1, 'C', 0);



//--//------------------------------------------------------------------//FILA5//------------------------------------------------------------
//--//------------------------------------------------------------------//FILA5//------------------------------------------------------------
//--//------------------------------------------------------------------//FILA5//------------------------------------------------------------
//Ordenador del Gasto
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(65, 5, mb_convert_encoding('Ordenador del Gasto', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', 0);
//Identificacion  
$pdf->Cell(57, 5, mb_convert_encoding('No. de Identificación', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', 0);
//Cargo
$pdf->Cell(70, 5, mb_convert_encoding('Cargo', 'ISO-8859-1', 'UTF-8'), 1, 1, 'C', 0);


//-----------------------------------------DATOS--------------------------------------------------------

//Ordenador del Gasto
$pdf->SetFont('Arial', 'B', 5);
$pdf->Cell(65, 5, mb_convert_encoding($rowOrdenador['nombre']." ". $rowOrdenador['apellidos'], 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', 0);
//Identificacion  
$pdf->Cell(57, 5, mb_convert_encoding($rowOrdenador['cedula'], 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', 0);
//Cargo
$pdf->Cell(70, 5, mb_convert_encoding($rowOrdenador['cargo'], 'ISO-8859-1', 'UTF-8'), 1, 1, 'C', 0);


//--//------------------------------------------------------------------//FILA6//------------------------------------------------------------
//--//------------------------------------------------------------------//FILA6//------------------------------------------------------------
//--//------------------------------------------------------------------//FILA6//------------------------------------------------------------
//Supervisor
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(52, 5, mb_convert_encoding('Supervisor', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', 0);
//Identificacion  
$pdf->Cell(25, 5, mb_convert_encoding('No. Identificación', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', 0);
//Cargo
$pdf->Cell(45, 5, mb_convert_encoding('Cargo', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', 0);
//Fecha Delegación
$pdf->Cell(37, 5, mb_convert_encoding('Fecha Delegación', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', 0);
//N° Delegación
$pdf->Cell(33, 5, mb_convert_encoding('N° Delegación', 'ISO-8859-1', 'UTF-8'), 1, 1, 'C', 0);

//-----------------------------------------DATOS--------------------------------------------------------

//Supervisor
$pdf->SetFont('Arial', 'B', 5);
$pdf->Cell(52, 5, mb_convert_encoding($NombreSupervisor, 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', 0);
//Identificacion  
$pdf->Cell(25, 5, mb_convert_encoding($rowSupervisor['cedula'], 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', 0);
//Cargo
$pdf->Cell(45, 5, mb_convert_encoding($rowSupervisor['cargo'], 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', 0);
//Fecha Delegación
$pdf->Cell(37, 5, mb_convert_encoding($row['fecha_delegacion'], 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', 0);
//N° Delegación
$pdf->Cell(33, 5, mb_convert_encoding($row['num_delegacion'], 'ISO-8859-1', 'UTF-8'), 1, 1, 'C', 0);

//--//------------------------------------------------------------------//FILA6.5//------------------------------------------------------------
//--//------------------------------------------------------------------//FILA6.5//------------------------------------------------------------
//--//------------------------------------------------------------------//FILA6.5//------------------------------------------------------------
// solo aplica si se le definio un segundo supervisor
if (isset($rowSupervisor2) and $rowSupervisor2['id'] != 0) {
    //Supervisor
    $pdf->SetFont('Arial', 'B', 6);
    $pdf->Cell(52, 5, mb_convert_encoding('Supervisor 2', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', 0);
    //Identificacion  
    $pdf->Cell(25, 5, mb_convert_encoding('No. Identificación', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', 0);
    //Cargo
    $pdf->Cell(45, 5, mb_convert_encoding('Cargo', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', 0);
    //Fecha Delegación
    $pdf->Cell(37, 5, mb_convert_encoding('Fecha Delegación', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', 0);
    //N° Delegación
    $pdf->Cell(33, 5, mb_convert_encoding('N° Delegación', 'ISO-8859-1', 'UTF-8'), 1, 1, 'C', 0);

    //-----------------------------------------DATOS--------------------------------------------------------

    //Supervisor
    $pdf->SetFont('Arial', 'B', 5);
    $pdf->Cell(52, 5, mb_convert_encoding(($rowSupervisor2['nombre'] . "" . $rowSupervisor2['apellidos']), 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', 0);
    //Identificacion  
    $pdf->Cell(25, 5, mb_convert_encoding($rowSupervisor2['cedula'], 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', 0);
    //Cargo
    $pdf->Cell(45, 5, mb_convert_encoding($rowSupervisor2['cargo'], 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', 0);
    //Fecha Delegación
    $pdf->Cell(37, 5, mb_convert_encoding($row['fecha_delegacion'], 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', 0);
    //N° Delegación
    $pdf->Cell(33, 5, mb_convert_encoding($row['num_delegacion'], 'ISO-8859-1', 'UTF-8'), 1, 1, 'C', 0);
}

//--//------------------------------------------------------------------//FILA7//------------------------------------------------------------
//--//------------------------------------------------------------------//FILA7//------------------------------------------------------------
//--//------------------------------------------------------------------//FILA7//------------------------------------------------------------
//Contratista
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(52, 5, mb_convert_encoding('Contratista', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', 0);
//Identificacion  
$pdf->Cell(25, 5, mb_convert_encoding('No. de Identificación', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', 0);
//Profesión
$pdf->Cell(45, 5, mb_convert_encoding('Profesión', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', 0);
//Dirección
$pdf->Cell(70, 5, mb_convert_encoding('Dirección', 'ISO-8859-1', 'UTF-8'), 1, 1, 'C', 0);


//-----------------------------------------DATOS--------------------------------------------------------

//Contratista
$pdf->SetFont('Arial', 'B', 5);
$pdf->Cell(52, 5, mb_convert_encoding($NombreContratistas, 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', 0);
//Identificacion  
$pdf->Cell(25, 5, mb_convert_encoding($rowContratista['cedula'], 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', 0);
$pdf->Cell(45, 5, mb_convert_encoding($rowContratista['profesion'], 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', 0);
//Dirección
$pdf->Cell(70, 5, mb_convert_encoding($rowContratista['direccion'], 'ISO-8859-1', 'UTF-8'), 1, 1, 'C', 0);


//--//------------------------------------------------------------------//FILA8//------------------------------------------------------------
//--//------------------------------------------------------------------//FILA8//------------------------------------------------------------
//--//------------------------------------------------------------------//FILA8//------------------------------------------------------------
//Tipo de persona
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(42, 5, mb_convert_encoding('Tipo de persona', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', 0);
//Responsable de iva 
$pdf->Cell(40, 5, mb_convert_encoding('Resp de iva ', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', 0);
//Razón social
$pdf->Cell(50, 5, mb_convert_encoding('Razón social', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', 0);
//nit
$pdf->Cell(25, 5, mb_convert_encoding('NIT', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', 0);
//Telefono
$pdf->Cell(35, 5, mb_convert_encoding('Telefono', 'ISO-8859-1', 'UTF-8'), 1, 1, 'C', 0);


//-----------------------------------------DATOS--------------------------------------------------------

//Tipo de persona
$pdf->SetFont('Arial', 'B', 5);
$pdf->Cell(42, 5, mb_convert_encoding($rowContratista['tipo_persona'], 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', 0);
//Responsable de iva
$pdf->Cell(40, 5, mb_convert_encoding($rowContratista['resp_iva'], 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', 0);
//Razón social
$pdf->Cell(50, 5, mb_convert_encoding($rowContratista['razon_social'], 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', 0);
//NIT
$pdf->Cell(25, 5, mb_convert_encoding($rowContratista['nit'], 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', 0);
//Telefono
$pdf->Cell(35, 5, mb_convert_encoding($rowContratista['telefono'], 'ISO-8859-1', 'UTF-8'), 1, 1, 'C', 0);


//--//------------------------------------------------------------------//FILA9//------------------------------------------------------------
//--//------------------------------------------------------------------//FILA9//------------------------------------------------------------
//--//------------------------------------------------------------------//FILA9//------------------------------------------------------------
//Correo
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(60, 5, mb_convert_encoding('Correo', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', 0);
//Fecha de Necesidad
$pdf->Cell(20, 5, mb_convert_encoding('Fecha Necesidad', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', 0);
//Fecha de Inicio
$pdf->Cell(17, 5, mb_convert_encoding('Fecha de Inicio', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', 0);
//Fecha de Fin
$pdf->Cell(17, 5, mb_convert_encoding('Fecha de Fin', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', 0);
//Duración
$pdf->Cell(20, 5, mb_convert_encoding('Duración', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', 0);
//Valor
$pdf->Cell(28, 5, mb_convert_encoding('Valor', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', 0);
//Fecha de Firma 
$pdf->Cell(30, 5, mb_convert_encoding('Fecha de Firma de Contrato', 'ISO-8859-1', 'UTF-8'), 1, 1, 'C', 0);

//-----------------------------------------DATOS--------------------------------------------------------

//Correo
$pdf->SetFont('Arial', 'B', 5);
$pdf->Cell(60, 5, mb_convert_encoding($rowContratista['correo'], 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', 0);
//Fecha de Necesidad
$pdf->Cell(20, 5, mb_convert_encoding($row['fecha_necesidad'], 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', 0);
//Fecha de Inicio
$pdf->Cell(17, 5, mb_convert_encoding($row['fecha_ini'], 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', 0);
//Fecha de Fin
$pdf->Cell(17, 5, mb_convert_encoding($row['fecha_fin'], 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', 0);
//Duración
$pdf->Cell(20, 5, mb_convert_encoding($row['duracion'], 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', 0);
//Valor
$pdf->Cell(28, 5, number_format(intval($row['valor_contrato'])), 1, 0, 'C', 0);
//Fecha de Firma
$pdf->Cell(30, 5, mb_convert_encoding($row['fecha_firma'], 'ISO-8859-1', 'UTF-8'), 1, 1, 'C', 0);
$pdf->Ln(4);
//--//------------------------------------------------------------------//FILA11//------------------------------------------------------------
//--//------------------------------------------------------------------//FILA11//------------------------------------------------------------
//--//------------------------------------------------------------------//FILA11//------------------------------------------------------------
//Objeto
$pdf->MultiCell(192, 3, mb_convert_encoding('Objeto: ' . $row['objeto'], 'ISO-8859-1', 'UTF-8'), 1, 'J');
//Forma de pago
$pdf->MultiCell(192, 3, mb_convert_encoding('Forma de pago: ' . $row['forma_pago'], 'ISO-8859-1', 'UTF-8'), 1, 'J');
// Garantia
$pdf->MultiCell(192, 3, mb_convert_encoding('Garantias: ' . $row['garantia'], 'ISO-8859-1', 'UTF-8'), 1, 'J');
//--//------------------------------------------------------------------//FILA12//------------------------------------------------------------
//--//------------------------------------------------------------------//FILA12//------------------------------------------------------------
//--//------------------------------------------------------------------//FILA12//------------------------------------------------------------
$pdf->SetFont('Arial', 'B', 5);
$pdf->Cell(96, 5, mb_convert_encoding('Cobertura Desde', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', 0);
$pdf->Cell(96, 5, mb_convert_encoding('Cobertura hasta', 'ISO-8859-1', 'UTF-8'), 1, 1, 'C', 0);
//-----------------------------------------DATOS--------------------------------------------------------
$pdf->SetFont('Arial', 'B', 5);
$pdf->Cell(96, 5, mb_convert_encoding($row['cobertura_desde'], 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', 0);
$pdf->Cell(96, 5, mb_convert_encoding($row['cobertura_hasta'], 'ISO-8859-1', 'UTF-8'), 1, 1, 'C', 0);
//Observación
$pdf->MultiCell(192, 3, mb_convert_encoding('Observaciones: ' . $row['observaciones'], 'ISO-8859-1', 'UTF-8'), 1, 1);
$pdf->Ln(4);




//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
// add text
$pdf->SetFont('Arial', '', 6);
$pdf->MultiCell(192, 3, mb_convert_encoding('El ' . date('d-m-Y',strtotime($fechaActa)) . ' en el municipio de Dosquebradas Risaralda se reunieron en las oficionas de la EMPRESA DE DESARROLLO URBANO RURA EDOS, el supervisor ' . $NombreSupervisor . ' y el/ella contratista ' . $NombreContratistas . ' con el fin de iniciar las obras y/o actividades correspondientes al contrato de la referencia, lo anterior se motiva en las siguientes consideraciones:', 'ISO-8859-1', 'UTF-8'), 0, 'J');
$pdf->Ln(3);

$pdf->MultiCell(192, 3, mb_convert_encoding('1. El contratista entegó completa la documentación requerida para el inicio del contrato, incluyendo la afilación a ARL.', 'ISO-8859-1', 'UTF-8'), 0, 'J');
$pdf->MultiCell(192, 3, mb_convert_encoding('2. El contratista recibió la induccción repectiva para la ejecución de sus actividades, así como del sistema integral de gestión.', 'ISO-8859-1', 'UTF-8'), 0, 'J');
$pdf->Ln(12);



//--//------------------------------------------------------------------////------------------------------------------------------------
//--//------------------------------------------------------------------////------------------------------------------------------------
//--//------------------------------------------------------------------////------------------------------------------------------------
//--//------------------------------------------------------------------////------------------------------------------------------------
//--//------------------------------------------------------------------////------------------------------------------------------------
//--//------------------------------------------------------------------////------------------------------------------------------------


//Firmas

//Espacio Supervisor
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(0, 10, '________________________________');

// solo aplica si se le definio un segundo supervisor
if (isset($rowSupervisor2) and $rowSupervisor2['id'] != 0) {
    //Espacio Supervisor2
    $pdf->SetX(70);
    $pdf->Cell(0, 10,'______________________________');
}


//Espacio Contratista
$pdf->SetX(140);
$pdf->Cell(0, 10, '__________________________________');
$pdf->Ln(5);


//-----------------------------------------DATOS--------------------------------------------------------
//Valor Supervisor
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(0, 10, mb_convert_encoding($NombreSupervisor, 'ISO-8859-1', 'UTF-8'));

// solo aplica si se le definio un segundo supervisor
if (isset($rowSupervisor2) and $rowSupervisor2['id'] != 0) {
    //Valor Supervisor2
    $pdf->SetX(70);
    $pdf->Cell(0, 10, mb_convert_encoding($rowSupervisor2['nombre'] . "" . $rowSupervisor2['apellidos'], 'ISO-8859-1', 'UTF-8'));
}

//Valor Contratista
$pdf->SetX(140);
$pdf->Cell(0, 10, mb_convert_encoding($NombreContratistas, 'ISO-8859-1', 'UTF-8'));
$pdf->Ln(5);

//-----------------------------------------DATOS2--------------------------------------------------------
////Supervisor Nombre
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(0, 10, mb_convert_encoding('SUPERVISOR', 'ISO-8859-1', 'UTF-8'));

// solo aplica si se le definio un segundo supervisor
if (isset($rowSupervisor2) and $rowSupervisor2['id'] != 0) {
    //Supervisor2 Nombre
    $pdf->SetX(70);
    $pdf->Cell(0, 10, mb_convert_encoding('SUPERVISOR 2', 'ISO-8859-1', 'UTF-8'));
}

//Contratista Nombre
$pdf->SetX(140);
$pdf->Cell(0, 10, mb_convert_encoding('CONTRATISTA', 'ISO-8859-1', 'UTF-8'));
$pdf->Ln(15);

//Firmas gerente-----------------------------------------------------------
//Firmas gerente-----------------------------------------------------------
//Firmas gerente-----------------------------------------------------------

//Espacio gerente
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(0, 10, '______________________________');
$pdf->Ln(5);

//-----------------------------------------DATOS1--------------------------------------------------------
//Valor Gerente
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(0, 10, mb_convert_encoding($rowOrdenador['nombre']." ". $rowOrdenador['apellidos'], 'ISO-8859-1', 'UTF-8'));
$pdf->Ln(5);
//-----------------------------------------DATOS2--------------------------------------------------------
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(0, 10, mb_convert_encoding('GERENTE', 'ISO-8859-1', 'UTF-8'));
$pdf->Ln(15);

$pdf->Output();
/*$pdf->Cell(40,10,"El ".$fechaActa." en el municipio de Dosquebradas Risaralda se reunieron en las oficionas del Instituto" );

$pdf->Ln();
$pdf->Cell(40,10,"de Desarrollo Municipal de Dosquebradas IDM el supervisor Esteban Velazquez Agudelo Y el/ella contratista Johnson Betancur Betancur con el fin de iniciar las obras y/o actividades correspondientes al contrato de la referencia, lo anterior se motiva en las siguientes consideraciones:");
$pdf->Output();*/