<?php
//seguridad de sessiones paginacion
session_start();
error_reporting(0);

//si no hay algun usuario registradose devuelve al login
if(!isset($_SESSION['rol'])){
    header("location:../../index.php");
}else{  
    include('../../db.php');
    $idContrato = $_GET["id"];
    $NombreContratistas = $_GET["nombre"];

    switch($_SESSION['rol']){
        case 1:
            $consulta= "SELECT * FROM proyeccion_contractual WHERE idContrato = $idContrato";
        break;
        case 2:
            $consulta= "SELECT * FROM proyeccion_contractual WHERE idContrato = $idContrato";
        break;
        case 3:
            $consulta= "SELECT * FROM proyeccion_contractual WHERE idContrato = $idContrato";
        break;
        case 4:
            $consulta= "SELECT * FROM proyeccion_contractual WHERE idContrato = $idContrato ";
        break;
        case 5:
            $consulta= "SELECT * FROM proyeccion_contractual WHERE idContrato = $idContrato ";
        break;
    } 
    $resultado = mysqli_query($conexion,$consulta);
}

include("../partials/layout.php");
//define las rutas para poder navegar en el menu
$_SESSION['rutaHome'] = "../../home.php";
$_SESSION['rutaUsuario'] = "../Usuario/listar.php";
$_SESSION['rutaCategoria'] = "../CategoriaProfesional/listar.php";
$_SESSION['rutaRetencion'] = "../Retencion/listar.php";
$_SESSION['rutaActasPendientes'] = "Acta/actasPendientes.php";
$_SESSION['rutaAlcancesGlobales'] = "alcancesGlobales.php";
$_SESSION['rutaInformeSupervisor'] = "../InformeSupervisor/listar.php";
$_SESSION['rutaContratos'] = "listar.php";
$_SESSION['rutaCerrarSesion'] = "../../cerrar_sesion.php";

include('../partials/menu.php');
?>

<div class="container">
    <h1 class="text-center blanco">Proyeccion contractual de: <?php echo $NombreContratistas ?></h1>
    <hr>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped">
                <tr>
                    <th>#</th>
                    <th>Periodo Inicio</th>
                    <th>Periodo Fin</th>
                    <th>Dias</th>
                    <th>Valor Dia</th>
                    <th>Valor</th>
                    <th>Acumulado</th>
                    <th>Saldo</th>
                    <th>Tipo</th>
                    <th>Acciones</th>
                    
                </tr>
                    <?php
                        $cont = 1;
                        while($filas=mysqli_fetch_array($resultado)){
                    ?>
                        <tr>
                            <td><h5><strong><?php echo $filas['num_acta'] ?></strong></h5></td>
                            <td><?php echo $filas['periodo_ini']?></td>
                            <td><?php echo $filas['periodo_fin']?></td>
                            <td><?php echo $filas['dias']?></td>
                            <!--la clase currency se creo por mi, sirve para usar la libreria de divisa todo esta en la carpeta js-->
                            <!--el script esta en la carpeta public/js/main.js-->
                            <td><?php echo number_format(intval($filas['valor_dia']))?></td>
                            <td><?php echo number_format(intval($filas['valor_mes']))?></td>
                            <td><?php echo number_format($filas['acomulado'],0,".",",")?></td>
                            <td><?php echo number_format($filas['saldo'],0,".",",")?></td>
                            <td><h5><strong><?php echo $filas['tipo']?></h5></strong></td>
                            <td>
                                <div style="display:flex; justify-content:space-between">
                                    <a class="btn btn-warning btn-sm" href="../Contrato/editar.php"><i class="far fa-edit" style="color:#fff;"></i></a>
                                    <a class="btn btn-danger btn-sm" href="../Contrato/eliminar.php"><i class="fas fa-trash-alt" style="color:#fff;"></i></a>
                                </div>
                            </td>
                        </tr>
                    <?php 
                        $cont++;    
                        }
                    ?>
            </table>
            <div class="" style="text-align:right;padding:5px;">
                <a href="listar.php" class="btn btn-primary">Volver</a>
             </div>
        </div>
    </div>
</div>