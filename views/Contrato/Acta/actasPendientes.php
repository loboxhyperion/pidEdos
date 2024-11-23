<?php
//seguridad de sessiones paginacion
session_start();
error_reporting(0);
//si no hay algun usuario registradose devuelve al login
if(!isset($_SESSION['rol'])){
    header("location:../../index.php");
}else{  
    include('../../../db.php');
    $varsesion = $_SESSION['usuario'];
    //para visualizar todas las actas registradas
    switch($_SESSION['rol']){
        case 1:
            $query= "SELECT u.nombre, u.apellidos, s.nombre AS nombre1, s.apellidos AS apellidos1, c.id AS idContrato, c.num_contrato, 
                     a.id, a.num_informe, a.fecha_informe, a.fecha_ini, a.fecha_fin, a.diasPagos, a.valor, a.acumulado, a.saldo, a.estado FROM usuario AS u
                     JOIN contrato AS c ON u.id = c.idUsuario
                     JOIN acta AS a ON a.idContrato = c.id
                     JOIN usuario AS s ON s.id = c.idSupervisor
                     WHERE a.estado = 'Pendiente' 
                     ORDER BY a.fecha_informe ASC ";
        break;
        case 3:
            $query= "SELECT u.nombre, u.apellidos, s.nombre AS nombre1, s.apellidos AS apellidos1, c.id AS idContrato, c.num_contrato, 
                     a.id, a.num_informe, a.fecha_informe, a.fecha_ini, a.fecha_fin, a.diasPagos, a.valor, a.acumulado, a.saldo, a.estado FROM usuario AS u
                     JOIN contrato AS c ON u.id = c.idUsuario
                     JOIN acta AS a ON a.idContrato = c.id
                     JOIN usuario AS s ON s.id = c.idSupervisor
                     WHERE (a.idSupervisor = $varsesion[id] OR c.idSupervisor2 = $varsesion[id]) AND a.estado = 'Pendiente' 
                     ORDER BY a.fecha_informe ASC ";
        break;
        case 5:
            $query= "SELECT u.nombre, u.apellidos, s.nombre AS nombre1, s.apellidos AS apellidos1, c.id AS idContrato, c.num_contrato, 
                     a.id, a.num_informe, a.fecha_informe, a.fecha_ini, a.fecha_fin, a.diasPagos, a.valor, a.acumulado, a.saldo, a.estado FROM usuario AS u
                     JOIN contrato AS c ON u.id = c.idUsuario
                     JOIN acta AS a ON a.idContrato = c.id
                     JOIN usuario AS s ON s.id = c.idSupervisor
                     WHERE a.estado = 'Pendiente' 
                     ORDER BY a.fecha_informe ASC ";
        break;

    } 
    $result = mysqli_query($conexion,$query) or die("fallo en la conexión");

}
//$filas = mysqli_fetch_array($resultado);
include("../../partials/layout.php");
//define las rutas para poder navegar en el menu
$_SESSION['rutaHome'] = "../../../home.php";
$_SESSION['rutaUsuario'] = "../../Usuario/listar.php";
$_SESSION['rutaCategoria'] = "../../CategoriaProfesional/listar.php";
$_SESSION['rutaRetencion'] = "../../Retencion/listar.php";
$_SESSION['rutaActasPendientes'] = "actasPendientes.php";
$_SESSION['rutaAlcancesGlobales'] = "../alcancesGlobales.php";
$_SESSION['rutaInformeSupervisor'] = "../../InformeSupervisor/listar.php";
$_SESSION['rutaContratos'] = "../listar.php";
$_SESSION['rutaCerrarSesion'] = "../../../cerrar_sesion.php";

include('../../partials/menuantes.php');
?>

<div class="container">
    <h1 class="text-center blanco">Lista de  Actas Pendientes </h1>
    <hr>
    <div class="" style="text-align:right;padding:5px;">
        <a href="../listar.php" class="btn btn-primary">Volver</a>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped">
                <tr>
                    <th>#</th>
                    <th>Fecha del acta</th>
                    <th>N° de contrato</th>
                    <th>Contratista</th>
                    <th>Supervisor</th>
                    <th>Periodo inicio</th>
                    <th>Periodo fin</th>
                    <th>Dias pagados</th>
                    <th>Total pagado</th>
                    <th>Acumulado</th>
                    <th>Saldo</th>
                    <th>Acta Contratista</th>
                    <?php
                        //Solo admin y creadores
                        if($_SESSION['rol'] == 1 or $_SESSION['rol'] == 2  ){
                    ?>
                    <th>Estado del Acta</th>
                    <?php
                         }
                    ?>
                    
                </tr>
                    <?php
                        $cont = 1;
                        while($filas= mysqli_fetch_array($result)){
                    ?>
                        <tr>
                            <td><?php echo $cont?></td>
                            <td><?php echo $filas['fecha_informe']?></td>
                            <td><?php echo $filas['num_contrato']?></td>
                            <td><?php 
                                    //Almacenamos al contratista 
                                    $NombreContratistas = $filas['nombre']." ".$filas['apellidos'];
                                    echo $filas['nombre']." ".$filas['apellidos']?>
                            </td>
                            <td><?php 
                                    //Almacenamos al supervisor 
                                    $NombreSupervisor = $filas['nombre1']." ".$filas['apellidos1'];
                                    echo $filas['nombre1']." ".$filas['apellidos1']?>
                            </td>
                            <td><?php echo $filas['fecha_ini']?></td>
                            <td><?php echo $filas['fecha_fin']?></td>
                            <td><?php echo $filas['diasPagos']?></td>
                            <!--Formato moneda php-->
                            <td><?php echo number_format($filas['valor'],2,".",",")?></td>
                            <td><?php echo number_format($filas['acumulado'],2,".",",")?></td>
                            <td><?php echo number_format(round($filas['saldo']),2,".",",")?></td>
                            <td><a class="btn btn-success btn-sm" href="verActa.php?id=<?php echo $filas['idContrato'] ?>&nombre=<?php echo $NombreContratistas ?>&num_informe=<?php echo $filas['num_informe'] ?>&fecha_informe=<?php echo $filas['fecha_informe'] ?>&idActa=<?php echo $filas['id'] ?>&NombreSupervisor=<?php echo $NombreSupervisor ?>"><i class="far fa-eye"></i></a></td>
                            <td>
                            <?php
                                //Solo admin y creadores
                                if($_SESSION['rol'] == 1 or $_SESSION['rol'] == 3){
                            ?>
                                    <div style="display:flex; justify-content:space-between;">
                                        <?php echo $filas['estado']?>
                                        <a class="btn btn-success btn-sm" href="validarActas.php?id=<?php echo $filas['idContrato']   ?>&nombre=<?php echo $NombreContratistas ?>&NombreSupervisor=<?php echo $NombreSupervisor ?>&idActa=<?php echo $filas['id'] ?>&estado=Aprobado&actaPendiente=Si"><i class="fas fa-check"></i></a>
                                        <a class="btn btn-danger btn-sm" href="validarActas.php?id=<?php echo $filas['idContrato']   ?>&nombre=<?php echo $NombreContratistas ?>&NombreSupervisor=<?php echo $NombreSupervisor ?>&idActa=<?php echo $filas['id'] ?>&estado=Denegado&actaPendiente=Si"><i class="fas fa-times"></i></a>
                                    </div>
                            <?php
                                }else{
                                    
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
