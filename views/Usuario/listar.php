<?php
include('../../db.php');
//seguridad de sessiones paginacion
session_start();
// error_reporting(0);

$varsesion = $_SESSION['usuario'];
//si no hay algun usuario registradose devuelve al login
if (!isset($_SESSION['rol'])) {
	header("location:../../index.php");
} else {

	//solo tiene permiso el admin y creador
	//if($_SESSION['rol'] == 3 or $_SESSION['rol'] == 4  ){
	//header("location:../../index.php");
	//
	if (isset($_POST['keyword'])) {

		$keyword = $_POST['keyword'];
		//Obtiene el usuario(s) el cual coincida con la busqueda de la palabra clave
		$query1 = "SELECT id,usuario,nombre,apellidos,cedula,telefono,correo,direccion,profesion,cargo,tipo_persona,resp_iva,genero,fecha_nacimiento FROM usuario 
		 		   WHERE idRol != 1 and (nombre LIKE '%$keyword%' OR apellidos LIKE '%$keyword%'  OR cedula LIKE '$keyword')";
		$resultado2 = mysqli_query($conexion, $query1);
	} else {
		$arrayUsuario =  $_SESSION['usuario'];
		switch ($_SESSION['rol']) {
			case 1:
				$consulta = "SELECT id FROM usuario WHERE idRol != 1";
				$resultado = mysqli_query($conexion, $consulta);
				break;
			case 2:
				$consulta = "SELECT id FROM usuario WHERE idRol != 1";
				$resultado = mysqli_query($conexion, $consulta);
				break;
			case 3:
				$consulta = "SELECT id,usuario,nombre,apellidos,cedula,telefono,correo,direccion,profesion,cargo,tipo_persona,resp_iva,genero,fecha_nacimiento FROM usuario WHERE idRol != 1 AND id = $arrayUsuario[id]";
				$resultado2 = mysqli_query($conexion, $consulta);
				break;
			case 4:
				$consulta = "SELECT id,usuario,nombre,apellidos,cedula,telefono,correo,direccion,profesion,cargo,tipo_persona,resp_iva,genero,fecha_nacimiento FROM usuario WHERE idRol != 1 AND id = $arrayUsuario[id]";
				$resultado2 = mysqli_query($conexion, $consulta);
				break;
			case 5:
				$consulta = "SELECT id,usuario,nombre,apellidos,cedula,telefono,correo,direccion,profesion,cargo,tipo_persona,resp_iva,genero,fecha_nacimiento FROM usuario WHERE idRol != 1 AND id = $arrayUsuario[id]";
				$resultado2 = mysqli_query($conexion, $consulta);
				break;
		}
	}
}
if (!isset($_GET['pagina']) and !isset($_POST['keyword'])) {
	header('location:listar.php?pagina=1');
}
//$consulta= "SELECT * FROM usuario WHERE idRol != 1";
//$resultado = mysqli_query($conexion,$consulta);

//Solo admin y creadores
if (($_SESSION['rol'] == 1 or $_SESSION['rol'] == 2)  and !isset($_POST['keyword'])) {
	//saber el numero de registros en la db
	$row = mysqli_num_rows($resultado);
	$resultXPagina = 10;
	$paginas = ceil($row / $resultXPagina);
	//Para evitar una paginacion erronea
	if ($_GET['pagina'] > $paginas || $_GET['pagina'] <= 0) {
		header('location:listar.php?pagina=1');
	}

	$iniciar = ($_GET['pagina'] - 1) * $resultXPagina;
	//ahora mostramos segun la paginacion 
	$consulta2 = "SELECT id,usuario,nombre,apellidos,cedula,telefono,correo,direccion,profesion,cargo,tipo_persona,resp_iva,genero,fecha_nacimiento FROM usuario 
				 WHERE idRol != 1 LIMIT $iniciar,$resultXPagina ";
	$resultado2 = mysqli_query($conexion, $consulta2);
	//$filas = mysqli_fetch_array($resultado);
}



include("../partials/layout.php");
//define las rutas para poder navegar en el menu
$_SESSION['rutaHome'] = "../../home.php";
$_SESSION['rutaUsuario'] = "listar.php";
$_SESSION['rutaCategoria'] = "../CategoriaProfesional/listar.php";
$_SESSION['rutaRetencion'] = "../Retencion/listar.php";
$_SESSION['rutaActasPendientes'] = "../Contrato/Acta/actasPendientes.php";
$_SESSION['rutaAlcancesGlobales'] = "../Contrato/alcancesGlobales.php";
$_SESSION['rutaInformeSupervisor'] = "../InformeSupervisor/listar.php";
$_SESSION['rutaContratos'] = "../Contrato/listar.php";
$_SESSION['rutaCerrarSesion'] = "../../cerrar_sesion.php";

include('../partials/menu.php');
?>
<!-- los css y los js no se pueden importar en otro archivo html  con el include-->
<!-- toca así -->
<!--  -->
<!--  -->
<style>
	<?php include '../../public/css/mensajes.css'; ?>
</style>
<!--  -->
<!--  -->
<div class="container">
	<br>
	<!-- Mensaje de estado para cuando se completa un accion -->
	<?php
	if (isset($_GET['mensaje']) || 1 == 1) {
		$_SESSION['mensaje'] = "El usuario ha sido creado con éxito";
		include '../partials/mensajesEstado.php';
	}
	?>

	<h1 class="text-center blanco">Lista de Usuarios</h1>
	<?php
	//Solo admin y creadores
	if ($_SESSION['rol'] == 1 or $_SESSION['rol'] == 2) {
	?>
		<ul class="nav nav-tabs">
			<li class="nav-item">
				<a class="nav-link active" aria-current="page" href="listar.php">Todos</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="listar_contratistas.php">Contratistas</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="listar_supervisores.php">Supervisores</a>
			</li>
		</ul>
	<?php
	}
	?>
	<?php
	//Solo admin y creadores
	if ($_SESSION['rol'] == 1 or $_SESSION['rol'] == 2) {
	?>
		<div class="" style="text-align:right;padding:5px;">
			<a href="nuevo.php?tipo=4" class="btn btn-primary">Nuevo Contratista</a>
			<a href="nuevo.php?tipo=3" class="btn btn-primary">Nuevo Supervisor</a>
		</div>
		<form action="listar.php" method="POST" class="col-md-4">
			<div class="col-md-12 input-group mb-3">
				<input type="text" class="form-control" placeholder="Nombre de la persona  o N° de cedula" aria-label="Recipient's username" aria-describedby="button-addon2" name="keyword">
				<input type="submit" class="btn btn-primary" value="Buscar">
			</div>
		</form>
		<br>
	<?php
	}
	?>
	<div class="row">
		<div class="col-md-12">
			<table class="table table-striped">
				<tr>
					<th>#</th>
					<th>Usuario</th>
					<th>Nombre</th>
					<th>Apellidos</th>
					<th>Cedula</th>
					<th>Genero</th>
					<th>Telefono</th>
					<th>Correo</th>
					<th>Direccion</th>
					<th>Profesión</th>
					<th>Cargo</th>
					<th>Tipo de persona</th>
					<th>Responsable de iva</th>
					<th>Lista de chequeo</th>
					<th>Acciones</th>

				</tr>
				<?php
				$cont = 1;
				while ($filas = mysqli_fetch_array($resultado2)) {
				?>
					<tr>
						<td><?php echo $cont ?></td>
						<td><?php echo $filas['usuario'] ?></td>
						<td><?php echo $filas['nombre'] ?></td>
						<td><?php echo $filas['apellidos'] ?></td>
						<td><?php echo $filas['cedula'] ?></td>
						<td><?php echo $filas['genero'] ?></td>
						<td><?php echo $filas['telefono'] ?></td>
						<td><?php echo $filas['correo'] ?></td>
						<td><?php echo $filas['direccion'] ?></td>
						<td><?php echo $filas['profesion'] ?></td>
						<td><?php echo $filas['cargo'] ?></td>
						<td><?php echo $filas['tipo_persona'] ?></td>
						<td><?php echo $filas['resp_iva'] ?></td>
						<!-- VER Y CREAR LISTA DE CHEQUEO -->
						<td><a class="btn btn-success btn-sm" href="Archivo/listar.php?id=<?php echo $filas['id'] ?>&nombre=<?php echo $filas['nombre'], " ", $filas['apellidos'] ?>"><i class="far fa-eye"></i></a></td>
						<td>
							<div style="display:flex; justify-content:space-between">
								<a class="btn btn-warning btn-sm" href="../Usuario/editar.php?id=<?php echo $filas['id'] ?>"><i class="far fa-edit" style="color:#fff;"></i></a>
								<?php
								//Solo admin y creadores
								if ($_SESSION['rol'] == 1 or $_SESSION['rol'] == 2) {
								?>
									<a class="btn btn-danger btn-sm" href="../Usuario/eliminar.php?id=<?php echo $filas['id'] ?>"><i class="fas fa-trash-alt" style="color:#fff;"></i></a>
								<?php
								}
								?>
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
	<?php
	//Solo admin y creadores
	if (($_SESSION['rol'] == 1 or $_SESSION['rol'] == 2)  and !isset($_POST['keyword'])) {
	?>
		<nav aria-label="...">
			<ul class="pagination">
				<li class="page-item <?php echo $_GET['pagina'] < $paginas ? 'disabled' : '' ?>">
					<a class="page-link" href="listar.php?pagina=<?php echo ($_GET['pagina'] - 1) ?>" tabindex="-1" aria-disabled="true">Previous</a>
				</li>

				<?php for ($i = 1; $i <= $paginas; $i++) { ?>
					<li class="page-item <?php echo $_GET['pagina'] == $i ? 'active' : '' ?>"><a class="page-link" href="listar.php?pagina=<?php echo $i ?>"><?php echo $i ?></a></li>
				<?php } ?>

				<li class="page-item <?php echo $_GET['pagina'] >= $paginas ? 'disabled' : '' ?>">
					<a class="page-link" href="listar.php?pagina=<?php echo ($_GET['pagina'] + 1) ?>">Next</a>
				</li>
			</ul>
		</nav>
	<?php
	}
	?>
</div>