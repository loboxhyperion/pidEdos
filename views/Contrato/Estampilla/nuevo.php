<?php 
//seguridad de sessiones paginacion
session_start();
error_reporting(0);

//si no hay algun usuario registradose devuelve al login
if(!isset($_SESSION['rol'])){
    header("location:../../index.php");
}else{   
    $idContrato = $_GET["id"];
    $NombreContratistas = $_GET["nombre"];
}
include("../../partials/layout.php");
include('../../partials/menusub.php');

?>
<div class="container">
    <div class="" style="text-align:right;padding:5px;">
        <a href="listar.php?id=<?php echo $idContrato ?>&nombre=<?php echo $NombreContratistas ?>"
            class="btn btn-primary">Volver</a>
    </div>
    <hr>
    <h1 class="text-center blanco">Nueva Estampilla</h1>
    <hr>
    <div class="col-md-12">
        <form class="row g-3" action="insertar.php" method="post">

            <div class="form-group col-md-12">
                <label for="idRetencion" class="form-label">Estampilla</label>
                <select class="form-select" aria-label="Default select example" name="idRetencion" required>
                    <option value="">Seleccionar</option>
                    <?php
                        include('../../../db.php');
                        $consulta= "SELECT * FROM retencion WHERE orden != 1";
                        $resultado = mysqli_query($conexion,$consulta);  
                        while($filas=mysqli_fetch_array($resultado)){
                    ?>
                    <option value="<?php echo $filas['id'] ?>"><?php echo $filas['nombre']?></option>
                    <?php } ?>
                </select>
            </div>
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