<?php
//seguridad de sessiones paginacion
session_start();
error_reporting(0);

//si no hay algun usuario registradose devuelve al login
if (!isset($_SESSION['rol'])) {
	header("location:../../index.php");
} else {
	//solo tiene permiso el admin y el supervisor
	if ($_SESSION['rol'] == 2 or $_SESSION['rol'] == 4) {
		header("location:../../index.php");
	}
}
include('../../db.php');
$idInforme = $_GET["id"];
$semestre = $_GET["trimestre"];
$meses = $_GET["meses"];
$query = "SELECT * FROM informe_trimestral WHERE idInformeSupervisor = $idInforme ";

$result = mysqli_query($conexion, $query);

include('../partials/menusub.php');
include("../partials/layout.php");

?>

<div class="container">
	<div class="" style="text-align:right;padding:5px;">
		<a href="listar.php" class="btn btn-primary">Volver</a>
		<hr>
		<h1 class="text-center blanco">Semestre: <?php echo "#" . $semestre . "(" . $meses . ")" ?> </h1>
		<hr>
	</diV>
	<br>
	<div class="col-md-12">
		<form action="actualizar.php" method="post">
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
						while ($filas = mysqli_fetch_array($result)) {
						?>
							<tr>
								<td>
									<h5><?php echo $cont ?></h5>
								</td>
								<input type="hidden" class="form-control" name="idTrimestral[]" value="<?php echo $filas['id'] ?>" />
								<!---->
								<td><?php echo $filas['contratista'] ?></td>
								<!---->
								<td><strong><?php echo $filas['num_contrato'] ?></strong></td>
								<!---->
								<td><strong><?php echo number_format($filas['valorContrato'], 2, ".", ",") ?></strong></td>
								<!---->
								<td><?php echo number_format($filas['acomulado'], 2, ".", ",") ?></td>
								<!---->
								<td><input type="text" name="saldo[]" value="<?php echo $filas['saldo'] ?>" /></td>
								<!---->
								<td><?php echo $filas['alcances_num'] ?></td>
								<!---->
								<td><input type="text" name="alcances_impactados[]" value="<?php echo $filas['alcances_impactados'] ?>" /></td>
								<!---->
								<td><input type="text" name="alcances_avances[]" value="<?php echo $filas['alcances_avances'] ?>" /></td>
								<!---->
								<td><input type="text" name="ejecucion_financiera[]" value="<?php echo $filas['ejecucion_financiera'] ?>" /></td>
								<!---->
								<td><input type="text" name="seguridad_pagada_avg[]" value="<?php echo $filas['seguridad_pagada_avg'] ?>" /></td>
								<!---->
								<td><input type="text" name="seguridad_real_avg[]" value="<?php echo $filas['seguridad_real_avg'] ?>" /></td>
								<!---->
								<td><input type="text" name="diferencia[]" value="<?php echo $filas['diferencia'] ?>" /></td>
								<!---->
								<td><textarea class="form-control" aria-label="With textarea" name="observaciones[]" rows="3" cols="100"><?php echo $filas['observaciones'] ?></textarea></td>
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
				</div>
			</div>

			<br>
			<input type="hidden" class="form-control" name="idInformeSupervisor" value="<?php echo $idInforme ?>" />
			<div class="form-group col-md-12" style="text-align:right;padding:5px;">
				<input type="submit" class="btn btn-primary" value="Guardar" />
			</div>
			<br>
			<br>
		</form>


	</div>
</div>