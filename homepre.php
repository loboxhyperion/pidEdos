<?php
//seguridad de sessiones paginacion
session_start();
error_reporting(0);
include("views/middleware/acceso.php");

$varsesion = $_SESSION['usuario'];

//si no hay algun usuario registradose devuelve al login
if(!isset($_SESSION['rol'])){
    header("location:index.php");
}
include('db.php');

$query= "SELECT * FROM usuario  WHERE idRol != 1 AND idRol  != 2 AND idRol != 5";
$result = mysqli_query($conexion,$query) or die("fallo en la conexión");

$filas = mysqli_fetch_array($result);


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
$_SESSION['rutaCerrarSesion'] = "cerrar_sesion.php";

include('views/partials/menu.php');

?>
<!-- los css y los js no se pueden importar en otro archivo html  con el include-->
<!-- toca así -->
<!--  -->
<!--  -->
<style><?php include 'public/css/style2.css'; ?></style>
<!--  -->
<!--  -->
<div class="container">
    <hr>
    <h1 class="text-center">Bienvenido al  Sistema : <?php echo $varsesion["nombre"].' '.$varsesion["apellidos"] ?></h1>
    <hr>
	<h5 class="text-center">Seleccione El año que desea visualizar:</h5>
	<div class="barra_años">
        <a href="yearShow.php?año=2022" class="btn btn-primary">2022</a>
		<a href="yearShow.php?año=2023" class="btn btn-primary">2023</a>
		<a href="yearShow.php?año=2024" class="btn btn-primary">2024</a>
    </div>
	<hr>
	<br>
    <div class="row">
		<div class="col-md-12">
			<table class="table table-striped">
				<tr>
					<th><h2>Dia</h2></th>
					<th>
						<h2>Cumpleañeros Del Mes De 
							<?php 
								$meses = array("","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
								echo $meses[date("n")];
							?> 
						</h2>
					</th>

					
				</tr>
					<?php
					 	$cont = 1;
						while($filas=mysqli_fetch_array($result)){
                            if(date("n") == intval(date('m', strtotime($filas['fecha_nacimiento'])))){
					?>
                            <tr>
                                <td><h3><?php echo intval(date('d', strtotime($filas['fecha_nacimiento'])))?></h3></td>
                                <td><h3><?php echo "Feliz Cumpleaños ".$filas['nombre'].' '.$filas['apellidos']?></h3></td>
                            </tr>
					<?php 
                            }
						$cont++;    
						}
					?>
			</table>
		</div>
	</div>
</div>
