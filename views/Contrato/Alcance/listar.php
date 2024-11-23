<?php
//seguridad de sessiones paginacion
session_start();
error_reporting(0);

//si no hay algun usuario registradose devuelve al login
if(!isset($_SESSION['rol'])){
    header("location:../../../index.php");
}else{  
    include('../../../db.php');
    $idContrato = $_GET["id"];
    $NombreContratistas = $_GET["nombre"];

    switch($_SESSION['rol']){
        case 1:
            $consulta= "SELECT * FROM alcance  WHERE idContrato = $idContrato";
        break;
        case 2:
            $consulta= "SELECT * FROM alcance  WHERE idContrato = $idContrato";
        break;
        case 3:
            $consulta= "SELECT * FROM alcance  WHERE idContrato = $idContrato";
        break;
        case 4:
            $consulta= "SELECT * FROM alcance  WHERE idContrato = $idContrato ";
        break;
        case 5:
            $consulta= "SELECT * FROM alcance  WHERE idContrato = $idContrato ";
        break;
    } 
    $resultado = mysqli_query($conexion,$consulta);
}

include("../../partials/layout.php");

//define las rutas para poder navegar en el menu
$_SESSION['rutaHome'] = "../../../home.php";
$_SESSION['rutaUsuario'] = "../../Usuario/listar.php";
$_SESSION['rutaCategoria'] = "../../CategoriaProfesional/listar.php";
$_SESSION['rutaRetencion'] = "../../Retencion/listar.php";
$_SESSION['rutaActasPendientes'] = "../Acta/actasPendientes.php";
$_SESSION['rutaAlcancesGlobales'] = "../alcancesGlobales.php";
$_SESSION['rutaInformeSupervisor'] = "../../InformeSupervisor/listar.php";
$_SESSION['rutaContratos'] = "../listar.php";
$_SESSION['rutaCerrarSesion'] = "../../../cerrar_sesion.php";

include('../../partials/menusub.php');
?>


<div class="container">
    <h1 class="text-center blanco">Lista de Alcances de: <?php echo $NombreContratistas ?></h1>
    <hr>
    <div class="" style="text-align:right;padding:5px;">
        <?php
            //Solo admin y creadores
            if($_SESSION['rol'] == 1 or $_SESSION['rol'] == 2  ){
        ?>
                <a class="btn btn-primary " href="nuevo.php?id=<?php echo $idContrato ?>&nombre=<?php echo $NombreContratistas?>">Agregar Alcance</a>                          
        <?php
            }
        ?>
        <a href="../listar.php" class="btn btn-primary">Volver</a>
    </div>
    <div class="row">
        <div class="col-md-12">
            <table class="table">
                <tr>
                    <th>#</th>
                    <th>Alcances</th>
                    <th style="width:5%;">Acciones</th>
                    
                </tr>
                    <?php
                        $cont = 1;
                        while($filas=mysqli_fetch_array($resultado)){
                    ?>
                        <tr>
                            <td><?php echo $cont ?></td>
                            <td><?php echo $filas['nombre']?></td>
                            <td>
                                <?php
                                    //Solo admin y creadores
                                    if($_SESSION['rol'] == 1 or $_SESSION['rol'] == 2  ){
                                ?>
                                        <div style="display:flex; justify-content:space-between">
                                            <a class="btn btn-warning btn-sm" href="editar.php?idAlcance=<?php echo $filas['id'] ?>&id=<?php echo $idContrato ?>&nombre=<?php echo $NombreContratistas?>"><i class="far fa-edit" style="color:#fff;"></i></a>
                                            <a class="btn btn-danger btn-sm" href="eliminar.php?idAlcance=<?php echo $filas['id'] ?>&id=<?php echo $idContrato ?>&nombre=<?php echo $NombreContratistas?>"><i class="fas fa-trash-alt" style="color:#fff;"></i></a>
                                        </div>
                                <?php
                                    }else{
                                        echo "Sin acceso";
                                    }
                                ?>
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
