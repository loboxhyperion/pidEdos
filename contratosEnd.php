<?php
//seguridad de sessiones paginacion
session_start();
error_reporting(0);
include("views/middleware/acceso.php");

$varsesion = $_SESSION['usuario'];

//si no hay algun usuario registradose devuelve al login
if (!isset($_SESSION['rol'])) {
	header("location:index.php");
}
include('db.php');
//AND CONVERT(contrato.fecha_fin,DATE) > CONVERT(date('Y/m/d'),DATE) 
$arrayUsuario =  $_SESSION['usuario'];
$sesionAño = $_SESSION['año'];
//echo date('Y/m/d');
	switch ($_SESSION['rol']) {
		case 1: //admin
			$consulta = "SELECT usuario.nombre,usuario.apellidos,contrato.id,contrato.num_contrato,contrato.years,contrato.area,contrato.fecha_ini,contrato.fecha_fin,
			contrato.valor_contrato,contrato.valorDia,contrato.duracion,contrato.num_actas,contrato.idUsuario,contrato.idSupervisor FROM contrato JOIN usuario ON contrato.idUsuario = usuario.id
			WHERE contrato.years = $sesionAño AND usuario.tipo_persona = 'Natural' Order by contrato.fecha_fin ASC";
			break;
		case 2: //creador
			$consulta = "SELECT usuario.nombre,contrato.id,contrato.num_contrato,contrato.years,contrato.area,contrato.fecha_ini,contrato.fecha_fin,
			contrato.valor_contrato,contrato.valorDia,contrato.duracion,contrato.num_actas,contrato.idUsuario,contrato.idSupervisor FROM usuario JOIN contrato ON usuario.id = contrato.idUsuario 
			WHERE contrato.years = $sesionAño AND usuario.tipo_persona = 'Natural' Order by contrato.num_contrato ASC";
			break;
		case 3:
			//$consulta= "SELECT * FROM contrato WHERE idSupervisor = $arrayUsuario[id]";
			$consulta = "SELECT usuario.nombre,contrato.id,contrato.num_contrato,contrato.years,contrato.area,contrato.fecha_ini,contrato.fecha_fin,
			contrato.valor_contrato,contrato.valorDia,contrato.duracion,contrato.num_actas,contrato.idUsuario,contrato.idSupervisor FROM usuario JOIN contrato ON usuario.id = contrato.idUsuario 
			WHERE (contrato.idSupervisor = $arrayUsuario[id] OR contrato.idSupervisor2 = $arrayUsuario[id]) AND contrato.years = $sesionAño AND usuario.tipo_persona = 'Natural' Order by contrato.num_contrato ASC";
			break;
		case 4:
			//$consulta= "SELECT * FROM contrato WHERE idUsuario = $arrayUsuario[id]";
			$consulta = "SELECT usuario.nombre,contrato.id,contrato.num_contrato,contrato.years,contrato.area,contrato.fecha_ini,contrato.fecha_fin,
			contrato.valor_contrato,contrato.valorDia,contrato.duracion,contrato.num_actas,contrato.idUsuario,contrato.idSupervisor FROM usuario JOIN contrato ON usuario.id = contrato.idUsuario 
			WHERE contrato.idUsuario= $arrayUsuario[id] AND contrato.years = $sesionAño AND usuario.tipo_persona = 'Natural'";
			break;
		case 5: //seguimiento
			$consulta = "SELECT usuario.nombre,contrato.id,contrato.num_contrato,contrato.years,contrato.area,contrato.fecha_ini,contrato.fecha_fin,
			contrato.valor_contrato,contrato.valorDia,contrato.duracion,contrato.num_actas,contrato.idUsuario,contrato.idSupervisor FROM usuario JOIN contrato ON usuario.id = contrato.idUsuario 
			WHERE contrato.years = $sesionAño AND usuario.tipo_persona = 'Natural' Order by contrato.num_contrato ASC";
			break;
	}
	$result = mysqli_query($conexion, $consulta);


include("views/partials/layout.php");
//define las rutas para poder navegar en el menu
$_SESSION['rutaHome'] = "home.php";
$_SESSION['rutaUsuario'] = "views/Usuario/listar.php";
$_SESSION['rutaCategoria'] = "views/CategoriaProfesional/listar.php";
$_SESSION['rutaRetencion'] = "views/Retencion/listar.php";
$_SESSION['rutaActasPendientes'] = "views/Contrato/Acta/actasPendientes.php";
$_SESSION['rutaAlcancesGlobales'] = "views/Contrato/alcancesGlobales.php";
$_SESSION['rutaInformeSupervisor'] = "views/InformeSupervisor/listar.php";
$_SESSION['rutaContratos'] = "views/Contrato/listar.php";
$_SESSION['rutaContratosObra'] = "views/ContratoObra/listar.php";
$_SESSION['rutaCerrarSesion'] = "cerrar_sesion.php";

include('views/partials/menu.php');

?>
<!-- los css y los js no se pueden importar en otro archivo html  con el include-->
<!-- toca así -->
<!--  -->
<!--  -->
<style>
	<?php include 'public/css/style2.css';
	include 'public/css/main.css';
	//include 'public/css/sidebar.css'; 
	?>
</style>
<!--  -->
<!--  -->
<div class="container">
	<hr>
	<h1 class="text-center">Listado de contratos por finalizar</h1>
	<hr>
   <?php
   //Solo admin y creadores
	if ($_SESSION['rol'] == 1 or $_SESSION['rol'] == 2) {
	?>
		<ul class="nav nav-tabs">
			<li class="nav-item">
				<a class="nav-link" aria-current="page" href="home.php">Cumpleaños</a>
			</li>
			<li class="nav-item">
				<a class="nav-link active" href="contratosEnd.php">Contratos Finalizando</a>
			</li>
		</ul>
	<?php
	}
	?>
	<br>
	<div class="row">
		<div class="col-md-12">
			<table class="table table-striped">
				<tr>
					<th>
						<h2><strong>N° Contrato</strong></h2>
					</th>
					<th>
						<h2><strong>Contratista</strong></h2>
					</th>
					<th>
						<h2><strong>Finaliza en :</strong></h2>
					</th>


				</tr>
				<?php
				$cont = 1;
				while ($filas = mysqli_fetch_array($result)) {
               //filtramos  solo contratos aún  no vencidos
               if(strtotime(date('Y/m/d')) < strtotime($filas["fecha_fin"])){
                  //restamos fechas 
                  $fecha_actual = date('Y/m/d');
                  $secs = strtotime($filas["fecha_fin"]) - strtotime($fecha_actual);
                  $diasEnd =  intval($secs / (86400) +1);//días faltantes para finalizar el contrato
                  // echo "<br>".$diasEnd;

                  //solo aparecen contratos que les falten 31 o menos días para acabar
                  if($diasEnd <= 31 ){
				?>
                     <tr>
                        <td><h3><strong><?php echo $filas['num_contrato']?></strong></h3></td>
                        <td><?php echo $filas['nombre'] . ' ' . $filas['apellidos']?></td>
                        <td><h3><strong><?php echo $diasEnd." Días" ?></strong></h3></td>
                     </tr>
				<?php
                  }
					}
					$cont++;
				}
				?>
			</table>
		</div>
	</div>
</div>


<script type="text/javascript" src="public/js/main.js"></script>