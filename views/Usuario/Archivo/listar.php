<?php
//seguridad de sessiones paginacion
session_start();
error_reporting(0);

//si no hay algun usuario registradose devuelve al login
if (!isset($_SESSION['rol'])) {
	header("location:../../index.php");
} else {
	include('../../../db.php');
	$idUsuario = $_GET["id"];
	$NombreContratistas = $_GET["nombre"];

	$query = "SELECT * FROM archivo_requerido WHERE idUsuario = $idUsuario";
	$result = mysqli_query($conexion, $query);
}

include("../../partials/layout.php");
//define las rutas para poder navegar en el menu
$_SESSION['rutaHome'] = "../../../../home.php";
$_SESSION['rutaUsuario'] = "../Usuario/listar.php";
$_SESSION['rutaCategoria'] = "../../CategoriaProfesional/listar.php";
$_SESSION['rutaRetencion'] = "../../Retencion/listar.php";
$_SESSION['rutaActasPendientes'] = "../../Contrato/Acta/actasPendientes.php";
$_SESSION['rutaAlcancesGlobales'] = "../../Contrato/alcancesGlobales.php";
$_SESSION['rutaInformeSupervisor'] = "../../InformeSupervisor/listar.php";
$_SESSION['rutaContratos'] = "../../Contrato/listar.php";
$_SESSION['rutaCerrarSesion'] = "../../../cerrar_sesion.php";

include('../../partials/menu.php');
?>

<!-- los css y los js no se pueden importar en otro archivo html  con el include-->
<!-- toca así -->
<!--  -->
<!--  -->
<style>
	<?php include '../../../public/css/mensajes.css';
	include '../../../public/css/main.css';  ?>
</style>
<!--  -->
<!--  -->
<div class="container">
	<br>
	<!-- Mensaje de estado para cuando se completa un accion -->
	<?php
	if (isset($_GET['mensaje'])) {
		$_SESSION['mensaje'] = "Los Archivos se han subido Con éxito";
		include '../../partials/mensajesEstado.php';
	}
	?>
	<h1 class="text-center blanco">Lista de chequeo: <?php echo $NombreContratistas ?></h1>
	<hr>
	<div class="" style="text-align:right;padding:5px;">
		<a href="../listar.php" class="btn btn-primary">Volver</a>
	</div>
	<div class="barra_botones">
		<a href="nuevo.php?id=<?php echo $_GET['id'] ?>&nombre=<?php echo $_GET['nombre'] ?>" class="btn btn-primary">Agregar Archivos</a>
	</div>
	<br>

	<div class="row">
		<div class="col-md-12">
			<table class="table table-striped">
				<tr>
					<th>#</th>
					<th>Fecha de Creación</th>
					<th>Nombre Del Archivo</th>
					<th>Archivo</th>
					<th>Estado</th>
					<th>Acciones</th>

				</tr>
				<?php
				$cont = 1;
				while ($filas = mysqli_fetch_array($result)) {
				?>
					<tr>
						<td>
							<h5><strong><?php echo $cont ?></strong></h5>
						</td>
						<td><?php echo $filas['fecha_creacion'] ?></td>
						<td><?php echo $filas['name_file'] ?></td>
						<td><a class="btn btn-danger btn-sm" target="_blank" href="descargar.php?ruta=<?php echo $filas['ruta'] ?>">Descargar</a></td>
						<td><?php echo $filas['estado'] ?></td>
						<td>
							<div style="display:flex; justify-content:space-between">
								<a class="btn btn-warning btn-sm" href="editar.php"><i class="far fa-edit" style="color:#fff;"></i></a>
								<a class="btn btn-danger btn-sm" href="eliminar.php?idFile=<?php echo $filas['id'] ?>&id=<?php echo $_GET['id'] ?>&nombre=<?php echo $_GET['nombre'] ?>"><i class="fas fa-trash-alt" style="color:#fff;"></i></a>
							</div>
						</td>
					</tr>
				<?php
					$cont++;
				}
				?>
			</table>

		</div>
	</div>
</div>