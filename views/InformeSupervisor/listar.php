<?php
//seguridad de sessiones paginacion
session_start();
error_reporting(0);

//si no hay algun usuario registradose devuelve al login
if (!isset($_SESSION['rol'])) {
    header("location:../../index.php");
} else {
    //solo tiene permiso el admin y creador
    if ($_SESSION['rol'] == 2 or $_SESSION['rol'] == 4) {
        header("location:../../index.php");
    }
}
include('../../db.php');

$varsesion = $_SESSION['usuario'];

$arrayUsuario =  $_SESSION['usuario'];
switch ($_SESSION['rol']) {
    case 1:
        $query = "SELECT u.nombre,u.apellidos,i.id As idInforme,i.trimestre,i.meses,i.fecha_create,i.estado 
        FROM usuario AS u
        JOIN informe_supervisor AS i ON  i.idSupervisor = u.id
        Where 1";
        break;
    case 3:
        $query = "SELECT u.nombre,u.apellidos,i.id As idInforme,i.trimestre,i.meses,i.fecha_create,i.estado 
            FROM usuario AS u
            JOIN informe_supervisor AS i ON  i.idSupervisor = u.id
            Where i.idSupervisor = $varsesion[id] ";
        break;
    case 5:
        $query = "SELECT u.nombre,u.apellidos,i.id As idInforme,i.trimestre,i.meses,i.fecha_create,i.estado 
            FROM usuario AS u
            JOIN informe_supervisor AS i ON  i.idSupervisor = u.id
            Where 1";
        break;
}

$result = mysqli_query($conexion, $query);


include("../partials/layout.php");
//define las rutas para poder navegar en el menu
$_SESSION['rutaHome'] = "../../home.php";
$_SESSION['rutaUsuario'] = "../Usuario/listar.php";
$_SESSION['rutaCategoria'] = "../CategoriaProfesional/listar.php";
$_SESSION['rutaRetencion'] = "../Retencion/listar.php";
$_SESSION['rutaActasPendientes'] = "../Contrato/Acta/actasPendientes.php";
$_SESSION['rutaAlcancesGlobales'] = "../Contrato/alcancesGlobales.php";
$_SESSION['rutaInformeSupervisor'] = "listar.php";
$_SESSION['rutaContratos'] = "../Contrato/listar.php";
$_SESSION['rutaCerrarSesion'] = "../../cerrar_sesion.php";


include('../partials/menu.php');
?>

<div class="container">
    <h1 class="text-center blanco">Lista de Informes</h1>

    <div class="" style="text-align:right;padding:5px;">
        <a href="nuevo.php" class="btn btn-primary">Nuevo Informe Semestral</a>
    </div>
    <br>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped">
                <tr>
                    <th>N° Cuatrimestre</th>
                    <th>Meses</th>
                    <th>Fecha De Creación</th>
                    <th>Supervisor</th>
                    <th>Acciones</th>

                </tr>
                <?php
                $cont = 1;
                while ($filas = mysqli_fetch_array($result)) {
                ?>
                    <tr>
                        <td><?php echo $filas['trimestre'] ?></td>
                        <td><?php echo $filas['meses'] ?></td>
                        <td><?php echo $filas['fecha_create'] ?></td>
                        <td><?php echo $filas['nombre'] . ' ' . $filas['apellidos'] ?></td>
                        <td>
                            <div style="display:flex; justify-content:space-between">
                                <a class="btn btn-success btn-sm" href="ver.php?id=<?php echo $filas['idInforme'] ?>&trimestre=<?php echo $filas['trimestre'] ?>&meses=<?php echo $filas['meses'] ?>"><i class="far fa-eye"></i></a>
                                <?php
                                //Solo admin y creadores
                                if ($_SESSION['rol'] == 1 || $_SESSION['rol'] == 3) {
                                ?>
                                    <a class="btn btn-warning btn-sm" href="editar.php?id=<?php echo $filas['idInforme'] ?>&semestre=<?php echo $filas['trimestre'] ?>&meses=<?php echo $filas['meses'] ?>"><i class="far fa-edit" style="color:#fff;"></i></a>
                                    <a class="btn btn-danger btn-sm" href="eliminar.php?id=<?php echo $filas['idInforme'] ?>"><i class="fas fa-trash-alt" style="color:#fff;"></i></a>
                                <?php
                                }
                                ?>
                                <a class="btn btn-primary btn-sm" href="generarExcel.php?id=<?php echo $filas['idInforme'] ?>&cuatrimestre=<?php echo $cuatrimestre - 1 ?>"><i class="fas fa-solid fa-file-excel" style="color:#fff;"></i></a>
                            </div>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </table>
        </div>
    </div>
</div>