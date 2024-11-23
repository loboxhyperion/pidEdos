<?php
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

require('fpdf.php');


$pdf = new FPDF();
$pdf->AddPage();

//Logo
$pdf->Image('../../public/img/actaInicio.jpg', 10, 2, 200);
$pdf->Ln(12);





//FECHA 
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(56, 8, utf8_decode('Fecha de Acta'), 1, 0, 'C', 0);
// Acta N 
$pdf->Cell(56, 8, utf8_decode('Acta N° '), 1, 0, 'C', 0);
//  Modalidad Contractual
$pdf->Cell(80, 8, utf8_decode('Modalidad Contractual '), 1, 1, 'C', 0);


//-----------------------------------------DATOS--------------------------------------------------------
//FECHA 
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(56, 8, utf8_decode($fechaActa), 1, 0, 'C', 0);
//Acta N 
$pdf->Cell(56, 8, utf8_decode('1'), 1, 0, 'C', 0);
// Modalidad Contractual
$pdf->Cell(80, 8, utf8_decode($row['modalidad']), 1, 1, 'C', 0);

//---------------------------------------------------//----------------------------------------------------------------------------------------------//-----------------
//---------------------------------------------------//----------------------------------------------------------------------------------------------//-----------------
//---------------------------------------------------//----------------------------------------------------------------------------------------------//-----------------

//--//------------------------------------------------------------------//FILA1//------------------------------------------------------------
//--//------------------------------------------------------------------//FILA1//------------------------------------------------------------
//--//------------------------------------------------------------------//FILA1//------------------------------------------------------------
//Registro PPTAL 
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(50, 5, utf8_decode('Registro PPTAL'), 1, 0, 'C', 0);
//  CDP  
$pdf->Cell(46, 5, utf8_decode('CDP'), 1, 0, 'C', 0);
//Año
$pdf->Cell(46, 5, utf8_decode('Año'), 1, 0, 'C', 0);
//Contrato N° 
$pdf->Cell(50, 5, utf8_decode('Contrato N°'), 1, 1, 'C', 0);


//-----------------------------------------DATOS--------------------------------------------------------

//Registro PPTAL 
$pdf->SetFont('Arial', 'B', 5);
$pdf->Cell(50, 5, utf8_decode($row['registro_pptal']), 1, 0, 'C', 0);
//  CDP  
$pdf->Cell(46, 5, utf8_decode($row['disp_presupuestal']), 1, 0, 'C', 0);
//Año
$pdf->Cell(46, 5, utf8_decode($row['years']), 1, 0, 'C', 0);
//Contrato N° 
$pdf->Cell(50, 5, utf8_decode($row['num_contrato']), 1, 1, 'C', 0);

//--//------------------------------------------------------------------//FILA2//------------------------------------------------------------
//--//------------------------------------------------------------------//FILA2//------------------------------------------------------------
//--//------------------------------------------------------------------//FILA2//------------------------------------------------------------
//  Rubro 
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(192, 5, utf8_decode('Rubro'), 1, 1, 'C', 0);
//-----------------------------------------DATOS--------------------------------------------------------

//  Rubro 
$pdf->SetFont('Arial', 'B', 5);
$pdf->Cell(192, 5, utf8_decode($row['rubro']), 1, 1, 'C', 0);



//--//------------------------------------------------------------------//FILA4//------------------------------------------------------------
//--//------------------------------------------------------------------//FILA4//------------------------------------------------------------
//--//------------------------------------------------------------------//FILA4//------------------------------------------------------------
//Contratante
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(85, 5, utf8_decode('Contratante'), 1, 0, 'C', 0);
//  Nit  
$pdf->Cell(27, 5, utf8_decode('Nit'), 1, 0, 'C', 0);
//Dirección
$pdf->Cell(80, 5, utf8_decode('Dirección'), 1, 1, 'C', 0);

//-----------------------------------------DATOS--------------------------------------------------------

//Registro PPTAL 
$pdf->SetFont('Arial', 'B', 5);
$pdf->Cell(85, 5, utf8_decode('Empresa de Desarrollo Urbano y Rural de Dosquebras'), 1, 0, 'C', 0);
//  Nit  
$pdf->Cell(27, 5, utf8_decode('816005795-1'), 1, 0, 'C', 0);
//Dirección
$pdf->Cell(80, 5, utf8_decode('Calle 50 NRO 14 -  56 Barrio LOS Naranjos'), 1, 1, 'C', 0);



//--//------------------------------------------------------------------//FILA5//------------------------------------------------------------
//--//------------------------------------------------------------------//FILA5//------------------------------------------------------------
//--//------------------------------------------------------------------//FILA5//------------------------------------------------------------
//Ordenador del Gasto
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(65, 5, utf8_decode('Ordenador del Gasto'), 1, 0, 'C', 0);
//Identificacion  
$pdf->Cell(57, 5, utf8_decode('No. de Identificación'), 1, 0, 'C', 0);
//Cargo
$pdf->Cell(70, 5, utf8_decode('Cargo'), 1, 1, 'C', 0);


//-----------------------------------------DATOS--------------------------------------------------------

//Ordenador del Gasto
$pdf->SetFont('Arial', 'B', 5);
$pdf->Cell(65, 5, utf8_decode('MANUEL ALBERTO RAMIREZ URIBE'), 1, 0, 'C', 0);
//Identificacion  
$pdf->Cell(57, 5, utf8_decode('10.100.227'), 1, 0, 'C', 0);
//Cargo
$pdf->Cell(70, 5, utf8_decode('Gerente General'), 1, 1, 'C', 0);


//--//------------------------------------------------------------------//FILA6//------------------------------------------------------------
//--//------------------------------------------------------------------//FILA6//------------------------------------------------------------
//--//------------------------------------------------------------------//FILA6//------------------------------------------------------------
//Supervisor
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(52, 5, utf8_decode('Supervisor'), 1, 0, 'C', 0);
//Identificacion  
$pdf->Cell(25, 5, utf8_decode('No. Identificación'), 1, 0, 'C', 0);
//Cargo
$pdf->Cell(45, 5, utf8_decode('Cargo'), 1, 0, 'C', 0);
//Fecha Delegación
$pdf->Cell(37, 5, utf8_decode('Fecha Delegación'), 1, 0, 'C', 0);
//N° Delegación
$pdf->Cell(33, 5, utf8_decode('N° Delegación'), 1, 1, 'C', 0);

//-----------------------------------------DATOS--------------------------------------------------------

//Supervisor
$pdf->SetFont('Arial', 'B', 5);
$pdf->Cell(52, 5, utf8_decode($NombreSupervisor), 1, 0, 'C', 0);
//Identificacion  
$pdf->Cell(25, 5, utf8_decode($rowSupervisor['cedula']), 1, 0, 'C', 0);
//Cargo
$pdf->Cell(45, 5, utf8_decode($rowSupervisor['cargo']), 1, 0, 'C', 0);
//Fecha Delegación
$pdf->Cell(37, 5, utf8_decode($row['fecha_delegacion']), 1, 0, 'C', 0);
//N° Delegación
$pdf->Cell(33, 5, utf8_decode($row['num_delegacion']), 1, 1, 'C', 0);

//--//------------------------------------------------------------------//FILA6.5//------------------------------------------------------------
//--//------------------------------------------------------------------//FILA6.5//------------------------------------------------------------
//--//------------------------------------------------------------------//FILA6.5//------------------------------------------------------------
// solo aplica si se le definio un segundo supervisor
if (isset($rowSupervisor2) and $rowSupervisor2['id'] != 0) {
    //Supervisor
    $pdf->SetFont('Arial', 'B', 6);
    $pdf->Cell(52, 5, utf8_decode('Supervisor 2'), 1, 0, 'C', 0);
    //Identificacion  
    $pdf->Cell(25, 5, utf8_decode('No. Identificación'), 1, 0, 'C', 0);
    //Cargo
    $pdf->Cell(45, 5, utf8_decode('Cargo'), 1, 0, 'C', 0);
    //Fecha Delegación
    $pdf->Cell(37, 5, utf8_decode('Fecha Delegación'), 1, 0, 'C', 0);
    //N° Delegación
    $pdf->Cell(33, 5, utf8_decode('N° Delegación'), 1, 1, 'C', 0);

    //-----------------------------------------DATOS--------------------------------------------------------

    //Supervisor
    $pdf->SetFont('Arial', 'B', 5);
    $pdf->Cell(52, 5, utf8_decode($rowSupervisor2['nombre'] . "" . $rowSupervisor2['apellidos']), 1, 0, 'C', 0);
    //Identificacion  
    $pdf->Cell(25, 5, utf8_decode($rowSupervisor2['cedula']), 1, 0, 'C', 0);
    //Cargo
    $pdf->Cell(45, 5, utf8_decode($rowSupervisor2['cargo']), 1, 0, 'C', 0);
    //Fecha Delegación
    $pdf->Cell(37, 5, utf8_decode($row['fecha_delegacion']), 1, 0, 'C', 0);
    //N° Delegación
    $pdf->Cell(33, 5, utf8_decode($row['num_delegacion']), 1, 1, 'C', 0);
}

//--//------------------------------------------------------------------//FILA7//------------------------------------------------------------
//--//------------------------------------------------------------------//FILA7//------------------------------------------------------------
//--//------------------------------------------------------------------//FILA7//------------------------------------------------------------
//Contratista
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(52, 5, utf8_decode('Contratista'), 1, 0, 'C', 0);
//Identificacion  
$pdf->Cell(25, 5, utf8_decode('No. de Identificación'), 1, 0, 'C', 0);
//Profesión
$pdf->Cell(45, 5, utf8_decode('Profesión'), 1, 0, 'C', 0);
//Dirección
$pdf->Cell(70, 5, utf8_decode('Dirección'), 1, 1, 'C', 0);


//-----------------------------------------DATOS--------------------------------------------------------

//Contratista
$pdf->SetFont('Arial', 'B', 5);
$pdf->Cell(52, 5, utf8_decode($NombreContratistas), 1, 0, 'C', 0);
//Identificacion  
$pdf->Cell(25, 5, utf8_decode($rowContratista['cedula']), 1, 0, 'C', 0);
$pdf->Cell(45, 5, utf8_decode($rowContratista['profesion']), 1, 0, 'C', 0);
//Dirección
$pdf->Cell(70, 5, utf8_decode($rowContratista['direccion']), 1, 1, 'C', 0);


//--//------------------------------------------------------------------//FILA8//------------------------------------------------------------
//--//------------------------------------------------------------------//FILA8//------------------------------------------------------------
//--//------------------------------------------------------------------//FILA8//------------------------------------------------------------
//Tipo de persona
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(42, 5, utf8_decode('Tipo de persona'), 1, 0, 'C', 0);
//Responsable de iva 
$pdf->Cell(40, 5, utf8_decode('Resp de iva '), 1, 0, 'C', 0);
//Razón social
$pdf->Cell(50, 5, utf8_decode('Razón social'), 1, 0, 'C', 0);
//nit
$pdf->Cell(25, 5, utf8_decode('NIT'), 1, 0, 'C', 0);
//Telefono
$pdf->Cell(35, 5, utf8_decode('Telefono'), 1, 1, 'C', 0);


//-----------------------------------------DATOS--------------------------------------------------------

//Tipo de persona
$pdf->SetFont('Arial', 'B', 5);
$pdf->Cell(42, 5, utf8_decode($rowContratista['tipo_persona']), 1, 0, 'C', 0);
//Responsable de iva
$pdf->Cell(40, 5, utf8_decode($rowContratista['resp_iva']), 1, 0, 'C', 0);
//Razón social
$pdf->Cell(50, 5, utf8_decode($rowContratista['razon_social']), 1, 0, 'C', 0);
//NIT
$pdf->Cell(25, 5, utf8_decode($rowContratista['nit']), 1, 0, 'C', 0);
//Telefono
$pdf->Cell(35, 5, utf8_decode($rowContratista['telefono']), 1, 1, 'C', 0);


//--//------------------------------------------------------------------//FILA9//------------------------------------------------------------
//--//------------------------------------------------------------------//FILA9//------------------------------------------------------------
//--//------------------------------------------------------------------//FILA9//------------------------------------------------------------
//Correo
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(60, 5, utf8_decode('Correo'), 1, 0, 'C', 0);
//Fecha de Necesidad
$pdf->Cell(20, 5, utf8_decode('Fecha Necesidad'), 1, 0, 'C', 0);
//Fecha de Inicio
$pdf->Cell(17, 5, utf8_decode('Fecha de Inicio'), 1, 0, 'C', 0);
//Fecha de Fin
$pdf->Cell(17, 5, utf8_decode('Fecha de Fin'), 1, 0, 'C', 0);
//Duración
$pdf->Cell(20, 5, utf8_decode('Duración'), 1, 0, 'C', 0);
//Valor
$pdf->Cell(28, 5, utf8_decode('Valor'), 1, 0, 'C', 0);
//Fecha de Firma 
$pdf->Cell(30, 5, utf8_decode('Fecha de Firma de Contrato'), 1, 1, 'C', 0);

//-----------------------------------------DATOS--------------------------------------------------------

//Correo
$pdf->SetFont('Arial', 'B', 5);
$pdf->Cell(60, 5, utf8_decode($rowContratista['correo']), 1, 0, 'C', 0);
//Fecha de Necesidad
$pdf->Cell(20, 5, utf8_decode($row['fecha_necesidad']), 1, 0, 'C', 0);
//Fecha de Inicio
$pdf->Cell(17, 5, utf8_decode($row['fecha_ini']), 1, 0, 'C', 0);
//Fecha de Fin
$pdf->Cell(17, 5, utf8_decode($row['fecha_fin']), 1, 0, 'C', 0);
//Duración
$pdf->Cell(20, 5, utf8_decode($row['duracion']), 1, 0, 'C', 0);
//Valor
$pdf->Cell(28, 5, number_format(intval($row['valor_contrato'])), 1, 0, 'C', 0);
//Fecha de Firma
$pdf->Cell(30, 5, utf8_decode($row['fecha_firma']), 1, 1, 'C', 0);
$pdf->Ln(4);
//--//------------------------------------------------------------------//FILA11//------------------------------------------------------------
//--//------------------------------------------------------------------//FILA11//------------------------------------------------------------
//--//------------------------------------------------------------------//FILA11//------------------------------------------------------------
//Objeto
$pdf->MultiCell(192, 3, utf8_decode('Objeto: ' . $row['objeto']), 1, 'J');
//Forma de pago
$pdf->MultiCell(192, 3, utf8_decode('Forma de pago: ' . $row['forma_pago']), 1, 'J');
//Observación
$pdf->MultiCell(192, 3, utf8_decode('Observaciones: ' . $row['observaciones']), 1, 1);
$pdf->Ln(4);




//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
//--//------------------------------------------------------------------//------------------------------------------------------------
// add text
$pdf->SetFont('Arial', '', 6);
$pdf->MultiCell(192, 3, utf8_decode('El ' . $fechaActa . ' en el municipio de Dosquebradas Risaralda se reunieron en las oficionas de la EMPRESA DE DESARROLLO URBANO RURA EDOS, el supervisor ' . $NombreSupervisor . ' y el/ella contratista ' . $NombreContratistas . ' con el fin de iniciar las obras y/o actividades correspondientes al contrato de la referencia, lo anterior se motiva en las siguientes consideraciones:'), 0, 'J');
$pdf->Ln(3);

$pdf->MultiCell(192, 3, utf8_decode('1. El contratista entegó completa la documentación requerida para el inicio del contrato, incluyendo la afilación a ARL.'), 0, 'J');
$pdf->MultiCell(192, 3, utf8_decode('2. El contratista recibió la induccción repectiva para la ejecución de sus actividades, así como del sistema integral de gestión.'), 0, 'J');
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
$pdf->Cell(0, 10, utf8_decode('__________________________'));

// solo aplica si se le definio un segundo supervisor
if (isset($rowSupervisor2) and $rowSupervisor2['id'] != 0) {
    //Espacio Supervisor2
    $pdf->SetX(70);
    $pdf->Cell(0, 10, utf8_decode('__________________________'));
}


//Espacio Contratista
$pdf->SetX(140);
$pdf->Cell(0, 10, utf8_decode('_______________________'));
$pdf->Ln(5);

//-----------------------------------------DATOS--------------------------------------------------------
//Valor Supervisor
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(0, 10, utf8_decode($NombreSupervisor));

// solo aplica si se le definio un segundo supervisor
if (isset($rowSupervisor2) and $rowSupervisor2['id'] != 0) {
    //Valor Supervisor2
    $pdf->SetX(70);
    $pdf->Cell(0, 10, utf8_decode($rowSupervisor2['nombre'] . "" . $rowSupervisor2['apellidos']));
}

//Valor Contratista
$pdf->SetX(140);
$pdf->Cell(0, 10, utf8_decode($NombreContratistas));
$pdf->Ln(5);

//-----------------------------------------DATOS2--------------------------------------------------------
////Supervisor Nombre
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(0, 10, utf8_decode('SUPERVISOR'));

// solo aplica si se le definio un segundo supervisor
if (isset($rowSupervisor2) and $rowSupervisor2['id'] != 0) {
    //Supervisor2 Nombre
    $pdf->SetX(70);
    $pdf->Cell(0, 10, utf8_decode('SUPERVISOR 2'));
}

//Contratista Nombre
$pdf->SetX(140);
$pdf->Cell(0, 10, utf8_decode('CONTRATISTA'));

$pdf->Output();
/*$pdf->Cell(40,10,"El ".$fechaActa." en el municipio de Dosquebradas Risaralda se reunieron en las oficionas del Instituto" );

$pdf->Ln();
$pdf->Cell(40,10,"de Desarrollo Municipal de Dosquebradas IDM el supervisor Esteban Velazquez Agudelo Y el/ella contratista Johnson Betancur Betancur con el fin de iniciar las obras y/o actividades correspondientes al contrato de la referencia, lo anterior se motiva en las siguientes consideraciones:");
$pdf->Output();*/
