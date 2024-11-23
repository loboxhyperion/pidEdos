<?php 
//seguridad de sessiones paginacion
session_start();
error_reporting(0);

//si no hay algun usuario registradose devuelve al login
if(!isset($_SESSION['rol'])){
    header("location:../../../index.php");
}else{   
    $idProyeccion = $_GET["idProyeccion"];
    $idContrato = $_GET["id"];
    $NombreContratistas = $_GET["nombre"];
}
include("../../partials/layout.php");
include('../../partials/menusub.php');
include('../../../db.php'); 
$consulta= "SELECT * FROM proyeccion_contractual  WHERE id=$idProyeccion";
$resultado = mysqli_query($conexion,$consulta);
$row = mysqli_fetch_array($resultado);
?>
<div class="container">
    <div class="" style="text-align:right;padding:5px;">
        <a href="listar.php?id=<?php echo $idContrato ?>&nombre=<?php echo $NombreContratistas ?>" class="btn btn-primary">Volver</a>
    </div>
    <hr>
    <h1 class="text-center blanco">Editar Alcance</h1>
    <hr>
    <div class="col-md-12">
        <form class="row g-3" action="actualizar.php" method="post">

            <div class="form-group col-md-6">
                <label for="periodo_ini" class="form-label">periodo de Inicio</label>
                <input class="form-control" type="date" name="periodo_ini" value="<?php echo date("Y-m-d", strtotime($row['periodo_ini']))?>" required>
            </div>
            <br>
            <div class="form-group col-md-6">
                <label for="periodo_fin" class="form-label">periodo de Finalización</label>
                <input class="form-control" type="date" name="periodo_fin" value="<?php echo date("Y-m-d", strtotime($row['periodo_fin']))?>" required>
            </div>
            <br>
            <div class="form-group col-md-2">
                <label for="dias" class="form-label">Dias</label>
                <input type="text" class="form-control" placeholder="Ingrese un valor" aria-label="Default select example" name="dias" value="<?php echo $row['dias']?>" required>
            </div>
            <br>
            <div class="form-group col-md-2">
                <label for="valor_dia" class="form-label">Valor Dia</label>
                <input type="text" class="form-control" placeholder="Ingrese un valor" aria-label="Default select example" name="valor_dia" value="<?php echo $row['valor_dia']?>" required>
            </div>
            <br>
            <div class="form-group col-md-2">
                <label for="valor_mes" class="form-label">Valor Mes</label>
                <input type="text" class="form-control" placeholder="Ingrese un valor" aria-label="Default select example" name="valor_mes" value="<?php echo $row['valor_mes']?>" required>
            </div>
            <br>
            <div class="form-group col-md-3">
                <label for="acomulado" class="form-label">Acumulado</label>
                <input type="text" class="form-control" placeholder="Ingrese un valor" aria-label="Default select example" name="acomulado" value="<?php echo $row['acomulado']?>" required>
            </div>
            <br>
            <div class="form-group col-md-3">
                <label for="saldo" class="form-label">Saldo</label>
                <input type="text" class="form-control" placeholder="Ingrese un valor" aria-label="Default select example" name="saldo" value="<?php echo $row['saldo']?>" required>
            </div>
            <br>
            <input type="hidden" class="form-control" name="idProyeccion" value="<?php echo $idProyeccion ?>" />
            <input type="hidden" class="form-control" name="idContrato" value="<?php echo $idContrato ?>" />
            <input type="hidden" class="form-control" name="nombre" value="<?php echo $NombreContratistas ?>" />
            <br>            
            <div class="form-group col-md-12" style="text-align:right;">
                <input type="submit" class="btn btn-primary" value="Guardar" />
            </div>
            <br>
            <br>
        </form>
        
    </div>
</div>