<?php 
//seguridad de sessiones paginacion
session_start();
error_reporting(0);

//si no hay algun usuario registradose devuelve al login
if(!isset($_SESSION['rol'])){
    header("location:../../../index.php");
}else{   
    $idAlcance= $_GET['idAlcance'];
    $idContrato = $_GET["id"];
    $NombreContratistas = $_GET["nombre"];
}
include("../../partials/layout.php");
include('../../partials/menusub.php');
include('../../../db.php'); 
$consulta= "SELECT * FROM alcance  WHERE id=$idAlcance";
$resultado = mysqli_query($conexion,$consulta);
$fila = mysqli_fetch_array($resultado);
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

            <div class="form-group col-md-12">
                <label for="alcance" class="form-label">Alcance</label>
                <textarea class="form-control" aria-label="With textarea" name="alcance" required><?php echo $fila['nombre'] ?></textarea>
            </div>
            <input type="hidden" class="form-control" name="idAlcance" value="<?php echo $idAlcance ?>" />
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