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
    $NombreSupervisor = $_GET["NombreSupervisor"];
    $estado = "Aprobado";

    //para visualizar todas las actas registradas
    $query= "SELECT * FROM acta  WHERE idContrato = $idContrato ";
    $result = mysqli_query($conexion,$query) or die("fallo en la conexión");

    //para poder sacar el numero del informe
    //coge la última acta registrada 
    $query2= "SELECT * FROM acta  WHERE idContrato = $idContrato ORDER BY id DESC LIMIT 1";
    $result2 = mysqli_query($conexion,$query2) or die("fallo en la conexión");

    if($row2 = mysqli_fetch_array($result2)){
        //aumenta para cuandos se haga la proximá acta  sea el numero de informe correspondiente
        $numInforme = $row2['num_informe'] + 1 ;
        //saco también el estado
        $estado = $row2['estado'];
    }else{
        $numInforme = 1;
    }

}
//$filas = mysqli_fetch_array($resultado);
include("../../partials/layout.php");
//define las rutas para poder navegar en el menu
// $_SESSION['rutaHome'] = "../../../home.php";
// $_SESSION['rutaUsuario'] = "../../Usuario/listar.php";
// $_SESSION['rutaCategoria'] = "../../CategoriaProfesional/listar.php";
// $_SESSION['rutaRetencion'] = "../../Retencion/listar.php";
// $_SESSION['rutaActasPendientes'] = "actasPendientes.php";
// $_SESSION['rutaAlcancesGlobales'] = "../alcancesGlobales.php";
// $_SESSION['rutaInformeSupervisor'] = "../../InformeSupervisor/listar.php";
// $_SESSION['rutaContratos'] = "../listar.php";
// $_SESSION['rutaCerrarSesion'] = "../../../cerrar_sesion.php";

include('../../partials/menusub.php');
?>

<div class="containerS">
    <h1 class="text-center blanco">Lista de Actas </h1>
    <hr>
    <h2 class="text-center blanco"><strong>Contratista: </strong><?php echo $NombreContratistas?></h2>



    <div class="" style="text-align:right;padding:5px;">
        <?php 
         //Solo admin y creadores
         if($_SESSION['rol'] == 1 or $_SESSION['rol'] == 2  or $_SESSION['rol'] == 4 ){
            if($estado  == "Aprobado" && $numInforme  <= count($result)){
        ?>
        <a class="btn btn-primary "
            href="nuevaActa.php?id=<?php echo $idContrato ?>&nombre=<?php echo $NombreContratistas ?>&NombreSupervisor=<?php echo $NombreSupervisor ?>&num_informe=<?php echo $numInforme ?>">Nueva
            Acta</a>
        <?php   
            }else{
        ?>
        <a class="btn btn-danger " href="" disabled>Nueva Acta</a>
        <?php   
            }
          }
        ?>
        <a href="../listar.php" class="btn btn-primary">Volver</a>
    </div>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped">
                <tr>
                    <th>#</th>
                    <th>Fecha del acta</th>
                    <th>N° de contrato</th>
                    <th>Supervisor</th>
                    <th>Periodo inicio</th>
                    <th>Periodo fin</th>
                    <th>Dias pagados</th>
                    <th>Total pagado</th>
                    <th>Acomulado</th>
                    <th>Saldo</th>
                    <th>N° de planilla</th>
                    <th>Periodo de la planilla</th>
                    <th>Valor pagado planilla</th>
                    <th>Valor Real planilla </th>
                    <th>Acta Contratista</th>
                    <th>Acta Supervisor</th>
                    <th>Cuenta de Cobro</th>
                    <?php
                        //Solo admin y creadores
                        if($_SESSION['rol'] == 1 or $_SESSION['rol'] == 2  ){
                    ?>
                    <th>Estado del Acta</th>
                    <?php
                         }
                    ?>
                    <?php
                        //Solo admin y creadores
                        if($_SESSION['rol'] == 1 or $_SESSION['rol'] == 2 or $_SESSION['rol'] == 4 ){
                    ?>
                    <th>Acciones</th>
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
                    <td><?php
                                    //Obtiene el N° de contrato
                                    $query2= "SELECT * FROM contrato  WHERE id= $idContrato";
                                    $result2 = mysqli_query($conexion,$query2) or die("fallo en la conexión");
                                    $singleRow2 = mysqli_fetch_array($result2);
                                    echo $singleRow2['num_contrato']?>
                    </td>
                    <td><?php echo $NombreSupervisor ?></td>
                    <td><?php echo $filas['fecha_ini']?></td>
                    <td><?php echo $filas['fecha_fin']?></td>
                    <td><?php echo $filas['diasPagos']?></td>
                    <!--Formato moneda php-->
                    <td><?php echo number_format($filas['valor'],2,".",",")?></td>
                    <td><?php echo number_format($filas['acumulado'],2,".",",")?></td>
                    <td><?php echo number_format(round($filas['saldo']),2,".",",")?></td>
                    <td><?php echo $filas['numPlanilla']?></td>
                    <td><?php echo $filas['fechaPlanilla']?></td>
                    <td><?php echo number_format($filas['valorPlanilla'],2,".",",")?></td>
                    <td><?php echo number_format($filas['valorPlanillaReal'],2,".",",")?></td>
                    <td><a class="btn btn-danger btn-sm"
                            href="generarPdf.php?id=<?php echo $idContrato  ?>&nombre=<?php echo $NombreContratistas ?>&NombreSupervisor=<?php echo $NombreSupervisor ?>&numInforme=<?php echo $filas['num_informe'] ?>&fechaInforme=<?php echo $filas['fecha_informe'] ?>&idActa=<?php echo $filas['id'] ?>">PDF</a>
                    </td>
                    <td>
                        <?php 
                                    if( $filas['estado'] == "Aprobado"){
                                ?>
                        <a class="btn btn-danger btn-sm"
                            href="generarPdfSupervisor.php?id=<?php echo $idContrato  ?>&nombre=<?php echo $NombreContratistas ?>&NombreSupervisor=<?php echo $NombreSupervisor ?>&numInforme=<?php echo $filas['num_informe'] ?>&fechaInforme=<?php echo $filas['fecha_informe'] ?>&idActa=<?php echo $filas['id'] ?>">PDF</a>
                        <?php 
                                    }else{
                                        echo "Sin acceso";
                                    }
                                ?>
                    </td>
                    <td><a class="btn btn-danger btn-sm"
                            href="generarPdfCuenta.php?id=<?php echo $idContrato  ?>&nombre=<?php echo $NombreContratistas ?>&NombreSupervisor=<?php echo $NombreSupervisor ?>&numInforme=<?php echo $filas['num_informe'] ?>&fechaInforme=<?php echo $filas['fecha_informe'] ?>&idActa=<?php echo $filas['id'] ?>">PDF</a>
                    </td>
                    <td>
                        <?php
                                    //Solo admin y creadores
                                    if($_SESSION['rol'] == 1 or $_SESSION['rol'] == 3){
                                ?>
                        <div style="display:flex; justify-content:space-between;">
                            <?php echo $filas['estado']?>
                            <!-- Button to Open the Modal -->
                            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal"
                                data-bs-target="#myModal<?php echo $cont?>"><i class="fas fa-check"></i></button>
                            <a class="btn btn-danger btn-sm"
                                href="validarActas.php?id=<?php echo $idContrato ?>&nombre=<?php echo $NombreContratistas ?>&NombreSupervisor=<?php echo $NombreSupervisor ?>&idActa=<?php echo $filas['id'] ?>&estado=Denegado&actaPendiente=No"><i
                                    class="fas fa-times"></i></a>

                            <!-- The Modal -->
                            <div class="modal" id="myModal<?php echo $cont?>">
                                <div class="modal-dialog">
                                    <div class="modal-content">

                                        <!-- Modal Header -->
                                        <div class="modal-header">
                                            <h4 class="modal-title">Aprobar Acta/Observacion</h4>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="validarActas.php" method="GET">
                                            <!-- Modal body -->
                                            <div class="modal-body">
                                                ¿Desea modificar o conservar el comentario?
                                                <div class="form-group col-md-12">
                                                    <br>
                                                    <label class="form-label">
                                                        <h6>Observaciones:</h6>
                                                    </label>
                                                    <textarea class="form-control" aria-label="With textarea"
                                                        name="observaciones" rows="5"
                                                        cols="70"><?php echo $filas['observaciones'] ?></textarea>
                                                </div>
                                            </div>
                                            <!---->
                                            <input type="hidden" class="form-control" name="id"
                                                value="<?php echo $idContrato ?>" />
                                            <input type="hidden" class="form-control" name="nombre"
                                                value="<?php echo $NombreContratistas ?>" />
                                            <input type="hidden" class="form-control" name="NombreSupervisor"
                                                value="<?php echo $NombreSupervisor ?>" />
                                            <input type="hidden" class="form-control" name="idActa"
                                                value="<?php echo $filas['id'] ?>" />
                                            <input type="hidden" class="form-control" name="estado"
                                                value="<?php echo "Aprobado" ?>" />
                                            <input type="hidden" class="form-control" name="actaPendiente"
                                                value="<?php echo "No" ?>" />
                                            <!-- Modal footer -->
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger"
                                                    data-bs-dismiss="modal">Cerrar</button>
                                                <input type="submit" class="btn btn-primary" value="Continuar" />
                                            </div>

                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                                    }else{
                                       echo "Sin accesso";
                                    }
                                ?>
                    </td>
                    <td>
                        <?php
                                    //Solo admin y creadores
                                    if($_SESSION['rol'] == 1 or $_SESSION['rol'] == 2 or  $_SESSION['rol'] == 4){
                                ?>
                        <div style="display:flex; justify-content:space-between">
                            <?php 
                                                if( $filas['estado'] != "Aprobado" or $_SESSION['rol'] == 1){
                                            ?>
                            <div style="display:flex; justify-content:space-between">
                                <a class="btn btn-warning btn-sm"
                                    href="editarActa.php?id=<?php echo $idContrato ?>&nombre=<?php echo $NombreContratistas ?>&num_informe=<?php echo $filas['num_informe'] ?>&fecha_informe=<?php echo $filas['fecha_informe'] ?>&idActa=<?php echo $filas['id'] ?>&NombreSupervisor=<?php echo $NombreSupervisor ?>"><i
                                        class="far fa-edit" style="color:#fff;"></i></a>
                                <a class="btn btn-danger btn-sm"
                                    href="eliminar.php?id=<?php echo $idContrato ?>&nombre=<?php echo $NombreContratistas ?>&num_informe=<?php echo $filas['num_informe'] ?>&fecha_informe=<?php echo $filas['fecha_informe'] ?>&idActa=<?php echo $filas['id'] ?>&NombreSupervisor=<?php echo $NombreSupervisor ?>"><i
                                        class="fas fa-trash-alt" style="color:#fff;"></i></a>
                            </div>
                            <?php 
                                                }else{
                                            ?>
                            <a class="btn btn-warning btn-sm"
                                href="editarActa.php?id=<?php echo $idContrato ?>&nombre=<?php echo $NombreContratistas ?>&num_informe=<?php echo $filas['num_informe'] ?>&fecha_informe=<?php echo $filas['fecha_informe'] ?>&idActa=<?php echo $filas['id'] ?>&NombreSupervisor=<?php echo $NombreSupervisor ?>"
                                hidden><i class="far fa-edit" style="color:#fff;"></i></a>
                            <?php
                                                }
                                            ?>
                        </div>
                        <?php
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