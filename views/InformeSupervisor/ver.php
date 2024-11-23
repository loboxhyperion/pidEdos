<?php
//seguridad de sessiones paginacion
session_start();
error_reporting(0);

//si no hay algun usuario registradose devuelve al login
if(!isset($_SESSION['rol'])){
		header("location:../../index.php");
}else{
		//solo tiene permiso el admin y el supervisor
		if($_SESSION['rol'] == 2 or $_SESSION['rol'] == 4  ){
				header("location:../../index.php");
		}
}
include('../../db.php');
$idInforme = $_GET["id"];
$trimestre = $_GET["trimestre"];
$meses = $_GET["meses"];
$query= "SELECT * FROM informe_trimestral WHERE idInformeSupervisor = $idInforme ";

$result = mysqli_query($conexion,$query);

include('../partials/menusub.php');
include("../partials/layout.php");

?>

<div class="container">
		<div class="" style="text-align:right;padding:5px;">
				<a href="listar.php" class="btn btn-primary">Volver</a>
				<hr>
				<h1 class="text-center blanco">Trimestre: <?php echo "#".$trimestre."(".$meses.")"?> </h1>
				<hr>
		</diV>
      <br>
		<div class="col-md-12">
						<div class="row">
								<div class="col-md-12">
												<table class="table table-striped">
														<tr>
																<th>#</th>
                                                				<th>Contratista</th>
																<th>N° Contrato</th>
																<th>Valor del Contrato</th>
																<th>Acumulado</th>
																<th>Saldo</th>
																<th>N° Alcances</th>
																<th>Alcances Impactados</th>
																<th>Alcances Avances</th>
																<th>Ejecución Financiera</th>
																<th>Seguridad Social Pagada</th>
																<th>Seguridad Social Correspondiente</th>
																<th>Diferencia</th>
																<th>Observaciones</th>

														</tr>
																<!------------MUESTRA LA INFORMACIÓN ACTUALES--------------------------------------------------------------------------------------------------------------------------------------------------------->
																<!--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
																<!--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
																<?php
																$cont = 1;
																while($filas=mysqli_fetch_array($result)){
																?>
																		<tr>
																						<td><h5><?php echo $cont ?></h5></td>
                                                                  <td><?php echo $filas['contratista']?></td>
																						<!---->
																						<td><strong><?php echo $filas['num_contrato']?></strong></td>
																						<!---->
																						<td><strong><?php echo number_format($filas['valorContrato'],2,".",",")?></strong></td>
																						<!---->
																						<td><?php echo number_format($filas['acomulado'],2,".",",")?></td>
																						<!---->
																						<td><?php echo number_format(round($filas['saldo']),2,".",",")?></td>
																						<!---->
																						<td><?php echo $filas['alcances_num']?></td>
																						<!---->
																						<td><?php echo $filas['alcances_impactados']?></td>
																						<!---->
																						<td><?php echo round($filas['alcances_avances'],2)."%"?></td>
																						<!---->
																						<td><?php echo round($filas['ejecucion_financiera'],2)."%"?></td>
																						<!---->
																						<td><?php echo number_format($filas['seguridad_pagada_avg'],2,".",",")?></td>
																						<!---->
																						<td><?php echo number_format($filas['seguridad_real_avg'],2,".",",")?></td>
																						<!---->
																						<td><?php echo number_format($filas['diferencia'],2,".",",")?></td>
																						<!---->
																						<td><textarea  class="form-control" aria-label="With textarea" rows="3" cols="100" readonly><?php echo $filas['observaciones']?></textarea></td>
																						<!--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
																						<!--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
																						<!--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
																		</tr>
																<?php
																$cont++;
																}
																?>
																<!--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
																<!--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
																<!--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
												</table>
												<br>
												<input type="hidden" class="form-control" name="idSupervisor" value="<?php echo $varsesion["id"] ?>" />
												<div class="form-group col-md-12" style="text-align:right;padding:5px;">
														<input type="submit" class="btn btn-primary" value="Guardar" />
												</div>
												<br>
												<br>

		</div>
</div>
