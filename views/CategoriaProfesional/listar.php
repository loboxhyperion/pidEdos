<?php
//seguridad de sessiones paginacion
session_start();
error_reporting(0);

//si no hay algun usuario registradose devuelve al login
if(!isset($_SESSION['rol'])){
    header("location:../../index.php");
}else{   
    //solo tiene permiso el admin y creador
    if($_SESSION['rol'] == 3 or $_SESSION['rol'] == 4  ){
        header("location:../../index.php");
    }
}
include('../../db.php');
$consulta= "SELECT * FROM categoria ORDER BY valor ASC";
$resultado = mysqli_query($conexion,$consulta);

include("../partials/layout.php");
//define las rutas para poder navegar en el menu
$_SESSION['rutaHome'] = "../../home.php";
$_SESSION['rutaUsuario'] = "../Usuario/listar.php";
$_SESSION['rutaCategoria'] = "listar.php";
$_SESSION['rutaRetencion'] = "../Retencion/listar.php";
$_SESSION['rutaActasPendientes'] = "../Contrato/Acta/actasPendientes.php";
$_SESSION['rutaAlcancesGlobales'] = "../Contrato/alcancesGlobales.php";
$_SESSION['rutaInformeSupervisor'] = "../InformeSupervisor/listar.php";
$_SESSION['rutaContratos'] = "../Contrato/listar.php";
$_SESSION['rutaCerrarSesion'] = "../../cerrar_sesion.php";

include('../partials/menu.php');
?>

<div class="container">
    <h1 class="text-center blanco">Lista de categorias profesionales</h1>

    <div class="" style="text-align:right;padding:5px;">
        <a href="nuevo.php" class="btn btn-primary">Nuevo</a>
    </div>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped">
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Valor</th>
                    <th style="width:5%;">Acciones</th>
                    
                </tr>
                    <?php
                        $cont = 1;
                        while($filas=mysqli_fetch_array($resultado)){
                    ?>
                        <tr>
                            <td><?php echo $cont ?></td>
                            <td><?php echo $filas['nombre']?></td>
                            <td><?php echo "$".number_format($filas['valor'],2,".",",")?></td>
                            <td>
                                <div style="display:flex; justify-content:space-between">
                                    <a class="btn btn-warning btn-sm" href="../CategoriaProfesional/editar.php?id=<?php echo $filas['id'] ?>"><i class="far fa-edit" style="color:#fff;"></i></a>
                                    <a class="btn btn-danger btn-sm" href="../CategoriaProfesional/eliminar.php?id=<?php echo $filas['id'] ?>"><i class="fas fa-trash-alt" style="color:#fff;"></i></a>
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
