<?php
include("../../partials/layout.php");
include('../../partials/menusub.php');
//seguridad de sessiones paginacion
session_start();
error_reporting(0);


//si no hay algun usuario registradose devuelve al login
//solo tiene permiso el admin y creador
if ((!isset($_SESSION['rol']) or ($_SESSION['rol'] == 3 or $_SESSION['rol'] == 4)) && !isset($_GET['tipo'])) {
    header("location:../../../index.php");
}
?>

<div class="container">
    <div class="" style="text-align:right;padding:5px;">
        <a href="listar.php" class="btn btn-primary">Volver</a>
        <hr>
        <h1 class="text-center blanco">Lista de Chequeo</h1>
        <hr>
    </diV>
    <?php
    if (isset($_GET['mensaje'])) {
    ?>
        <div class="p-3 mb-2 bg-danger text-white">
            <?php echo $_GET['mensaje']; ?>
        </div>
    <?php } ?>

    <form class="row g-3" action="insertar.php" method="post" enctype='multipart/form-data'>
        <div class="form-group col-md-4">
            <label for="fecha_creacion" class="form-label">Fecha de Registro</label>
            <input class="form-control" type="date" name="fecha_creacion" required>
        </div>

        <div class="form-group col-md-4">
            <label for="ruta" class="form-label">Cédula:</label>
            <input type="file" class="form-control" name="ruta[]" id="ruta" required>
        </div>
        <br>
        <div class="form-group col-md-4">
            <label for="usuario" class="form-label">Acta de grado:</label>
            <input type="file" class="form-control" name="ruta[]" id="file" required>
        </div>
        <br>
        <!--idUsuario traído de la página anterior -->
        <input type="hidden" name="idUsuario" value="<?php echo $_GET['id'] ?>">
        <input type="hidden" name="nombreContratista" value="<?php echo $_GET['nombre'] ?>">
        <br>
        <div class="form-group col-md-12" style="text-align:right;">
            <input type="submit" class="btn btn-primary" value="Subir Archivos" />
        </div>
        <br>
        <br>
    </form>

</div>
</div>