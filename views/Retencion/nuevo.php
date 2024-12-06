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
include("../partials/layout.php"); 
include('../partials/menusub.php');
?>

<div class="container">
    <div class="" style="text-align:right;padding:5px;">
        <a href="listar.php" class="btn btn-primary">Volver</a>
        <hr>
        <h1 class="text-center blanco">Nueva Retención</h1>
        <hr>
    </diV>
    <div class="col-md-12">
        <form action="insertar.php" method="post">
            <div class="form-group col-md-8">
                <label for="Nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" placeholder="Nombre del impuesto" aria-label="nombre"
                    name="nombre" required>
            </div>
            <br>
            <div class="form-group col-md-8">
                <label for="Porcentaje" class="form-label">%</label>
                <input type="number" class="form-control" placeholder="Valor númerico" aria-label="Porcentaje"
                    name="porcentaje" step="0.0001" required>
            </div>
            <br>
            <div class="form-group col-md-8">
                <label for="tipo" class="form-label">Tipo</label>
                <select class="form-select" aria-label="Default select example" name="tipo" required>
                    <option value="">Seleccionar</option>
                    <option value="Base">Base</option>
                    <option value="Estampilla">Estampilla</option>
                    <option value="Impuesto">Impuesto</option>
                    <option value="ARL">ARL</option>

                </select>
            </div>
            <br>
            <div class="form-group col-md-8" style="text-align:right;">
                <input type="submit" class="btn btn-primary" value="Guardar" />
            </div>
            <br>
            <br>
        </form>

    </div>
</div>