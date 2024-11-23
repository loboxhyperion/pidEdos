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
    $fecha_ini = $_GET["fecha_ini"];
    $fecha_fin = $_GET['fecha_fin'];;
    $valorDia = $_GET["valorDia"];

    $arrayUsuario =  $_SESSION['usuario'];
    switch($_SESSION['rol']){
        case 1:
            $consulta= "SELECT * FROM adicion_suspension WHERE idContrato= $idContrato";
        break;
        case 2:
            $consulta= "SELECT * FROM adicion_suspension WHERE idContrato= $idContrato";
        break;
        case 3:
            $consulta= "SELECT * FROM adicion_suspension WHERE idContrato= $idContrato";
        break;
        case 4:
            $consulta= "SELECT * FROM adicion_suspension WHERE idContrato= $idContrato";
        break;
    } 

    $resultado = mysqli_query($conexion,$consulta) or die("fallo en la conexión");
    
    //$filas = mysqli_fetch_array($resultado); 
    //si la consulta falla y tratan de meterse por otro lado 
    //if(!$filas){
        //header("location:../../index.php");
    //}  
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
    <h1 class="text-center blanco">Lista de adicciones y suspensiones</h1>



    <div class="" style="text-align:right;padding:5px;">
        <a class="btn btn-primary " href="nuevaAdiccion.php?id=<?php echo $idContrato ?>&fecha_ini=<?php echo $fecha_ini?>&valorDia=<?php echo $valorDia?>">Adicción</a>
        <a class="btn btn-primary " href="nuevaSuspension.php?id=<?php echo $idContrato ?>&fecha_ini=<?php echo $fecha_ini?>&valorDia=<?php echo $valorDia?>">Suspensión</a>
        <a class="btn btn-primary " href="nuevaCancelar.php?id=<?php echo $idContrato ?>&fecha_ini=<?php echo $fecha_ini?>&valorDia=<?php echo $valorDia?>">Finalizar Contrato</a>
        <a class="btn btn-primary " href="nuevoAtemporal.php?id=<?php echo $idContrato ?>&fecha_ini=<?php echo $fecha_ini?>&valorDia=<?php echo $valorDia?>">Corte atemporal</a>
        <a class="btn btn-primary " href="nuevaCesion.php?id=<?php echo $idContrato ?>&fecha_ini=<?php echo $fecha_ini?>&valorDia=<?php echo $valorDia?>">Cesión</a>
        <a href="../listar.php" class="btn btn-primary">Volver</a>
    </div>
    <div class="row">
        <div class="col-md-12">
            <table class="table">
                <tr>
                    <th>#</th>
                    <th>Tipo</th>
                    <th>Fecha de modifiación</th>
                    <th>Fecha de suspension</th>
                    <th>Fecha de fin suspensión</th>
                    <th>Dias</th>
                    <th>Valor</th>
                    <th>Fecha de terminación pre</th>
                    <th>Nueva fecha de terminación</th>
                    <th style="width:5%">Acciones</th>
                    
                </tr>
                    <?php
                        $cont = 1;
                        while($filas=mysqli_fetch_array($resultado)){
                            
                    ?>
                        <tr>
                            <td><?php echo $cont?></td>
                            <td><?php echo $filas['tipo']?></td>
                            <td><?php echo $filas['fecha_modificacion']?></td>
                            <?php
                                if($filas['tipo'] == "Suspension"){
                            ?>
                                    <td><?php echo $filas['fecha_suspension']?></td>
                                    <td><?php echo $filas['fecha_reinicio']?></td>
                            <?php
                                }else{
                            ?>
                                    <td><?php echo "NA" ?></td>
                                    <td><?php echo "NA" ?></td>
                            <?php
                                }
                            ?>
                            <td><?php echo $filas['dias']?></td>
                            <td><?php echo $filas['valor']?></td>
                            <?php
                                if($filas['tipo'] != "Cesion"){
                            ?>
                                    <td><?php echo $filas['fecha_terminacion_pre']?></td>
                                    <td><?php echo $filas['fecha_terminacion_new']?></td>
                            <?php
                                }else{
                            ?>
                                    <td><?php echo "NA" ?></td>
                                    <td><?php echo "NA" ?></td>
                            <?php
                                }
                            ?>
                            <td>
                                <?php
                                    $query2= "SELECT idModificaciones FROM proyeccion_contractual  WHERE idContrato = $idContrato ORDER BY id DESC LIMIT 1";
                                    $result2 = mysqli_query($conexion,$query2) or die("fallo en la conexión 1");
                                    $lastRow = mysqli_fetch_array($result2); 
                                    //Solo admin y creadores
                                    if($_SESSION['rol'] == 1 or $_SESSION['rol'] == 2  ){
                                        if($lastRow["idModificaciones"] == $filas["id"] ){
                                ?>
                                            <div style="display:flex; justify-content:space-between">
                                                <a class="btn btn-danger btn-sm" href="eliminar.php?id=<?php echo $filas['id'] ?>&valorDia=<?php echo $valorDia?>&idContrato=<?php echo $idContrato?>&fecha_ini=<?php echo $fecha_ini?>"><i class="fas fa-trash-alt" style="color:#fff;"></i></a>
                                            </div>
                                <?php
                                        }else{
                                ?>
                                            <div style="display:flex; justify-content:space-between">
                                                <a class="btn btn-danger btn-sm" href="" disabled><i class="fas fa-trash-alt" style="color:#fff;"></i></a>
                                            </div>
                                <?php
                                        }
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
