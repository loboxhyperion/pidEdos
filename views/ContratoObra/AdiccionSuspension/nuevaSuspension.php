<?php 
//seguridad de sessiones paginacion
session_start();
error_reporting(0);

//si no hay algun usuario registradose devuelve al login
if(!isset($_SESSION['rol'])){
    header("location:../../../index.php");
}else{   
    $idContrato = $_GET["id"];
    $fecha_ini = $_GET["fecha_ini"];
    $fecha_fin = $_GET['fecha_fin'];
    $valor_contrato = $_GET['valor_contrato'];
    $duracion = $_GET['duracion'];
    $valorDia = $_GET["valorDia"];
    $num_actas = $_GET["num_actas"];
}
include("../../partials/layout.php");
include('../../partials/menusub.php');

?>
<div class="container">
    <div class="" style="text-align:right;padding:5px;">
    <a href="listar.php?id=<?php echo $idContrato ?>&fecha_ini=<?php echo $fecha_ini?>&valorDia=<?php echo $valorDia ?>" class="btn btn-primary">Volver</a>
        <hr>
        <h1 class="text-center blanco">Nueva Suspensión</h1>
        <hr>
    </diV>
    <div class="col-md-12">
        <form class="row g-3" action="insertarSuspension.php" method="post">

            <div class="form-group col-md-6">
                <label for="fecha_suspension" class="form-label">Fecha de Suspension</label>
                <input class="form-control" type="date" name="fecha_suspension" required>
            </div>
            <div class="form-group col-md-6">
                <label for="fecha_reinicio" class="form-label">Fecha de Reinicio</label>
                <input class="form-control" type="date" name="fecha_reinicio" required>
            </div>
            <input type="hidden" class="form-control" name="idContrato" value="<?php echo $idContrato ?>" />
            <input type="hidden" class="form-control" name="fecha_ini" value="<?php echo $fecha_ini ?>" />
            <input type="hidden" class="form-control" name="valorDia" value="<?php echo $valorDia ?>" />
            <br>            
            <div class="form-group col-md-12" style="text-align:right;">
                <input type="submit" class="btn btn-primary" value="Guardar" />
            </div>
            <br>
            <br>
        </form>
        
    </div>
</div>