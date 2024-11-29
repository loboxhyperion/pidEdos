<?php
//seguridad de sessiones paginacion
session_start();
error_reporting(0);

include("../partials/layout.php");
require_once('../partials/menusub.php');

//si no hay algun usuario registradose devuelve al login
if(!isset($_SESSION['rol'])){
    header("location:../../index.php");
}else{   
    //solo tiene permiso el admin y creador
    if($_SESSION['rol'] == 3 or $_SESSION['rol'] == 4  ){
        header("location:../../index.php");
    }
}
?>
<div class="container"
    style="width:50%; padding:1rem 5rem; margin:2rem auto; box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;">
    <div class="" style="text-align:right;padding:5px;">
        <a href="listar.php" class="btn btn-primary">Volver</a>
        <hr>
        <h1 class="text-center blanco">Crear Nueva Configuración</h1>
        <hr>
    </diV>

    <div class="col-md-12">
        <form class="row g-3" action="insertar.php" method="post">
            <div class="form-group col-md-4">
                <label for="nombre" class="form-label">Nombre:</label>
                <input type="text" class="form-control" id="nombre" placeholder="Nombre de la configuracion"
                    aria-label="nombre" name="nombre" required>
            </div>
            <br>
            <div class="form-group col-md-5">
                <label for="valor" class="form-label">Valor:</label>
                <input type="text" class="form-control" id="valor" placeholder="Valor númerico" aria-label="valor"
                    name="valor" required>
            </div>
            <br>
            <div class="form-group col-md-3">
                <label for="activo" class="form-label">Activo:</label>
                <select class="form-select" id="activo" aria-label="Default select example" name="activo" required>
                    <option value="">Seleccionar</option>
                    <option value="1">Sí</option>
                    <option value="0">No</option>
                </select>
            </div>
            <br>
            <div class="form-group col-md-12" style="text-align:right;">
                <input type="submit" class="btn btn-primary" value="Guardar" />
            </div>
            <br>
            <br>
        </form>

    </div>

</div>