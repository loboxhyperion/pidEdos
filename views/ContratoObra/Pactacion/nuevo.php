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
        <a href="listar.php?id=<?php echo $idContrato ?>&nombre=<?php echo $NombreContratistas ?>" class="btn btn-primary">Volver</a>
    </div>
    <hr>
    <h1 class="text-center blanco">Nuevo Item</h1>
    <hr>

    <div class="col-md-12">
        <form class="row g-3" action="insertar.php" method="post">
            <div class="form-group col-md-6">
                <label for="item" class="form-label"># Item</label>
                <input type="text" class="form-control" placeholder="#101" aria-label="item" name="item" required>
            </div>
            <div class="form-group col-md-6">
                <label for="codigo" class="form-label">Código</label>
                <input type="text" class="form-control" placeholder="#MAT" aria-label="codigo" name="codigo" required>
            </div>
            <div class="form-group col-md-12">
                <label for="alcance" class="form-label">Descripción</label>
                <textarea class="form-control" aria-label="With textarea" name="descripcion" required></textarea>
            </div>
            <div class="form-group col-md-3">
                <label for="unidad" class="form-label">Unidad</label>
                <input type="text" class="form-control" placeholder="Ingrese la unidad" aria-label="unidad" name="unidad" required>
            </div>
            <div class="form-group col-md-3">
                <label for="cantidad" class="form-label">Cantidad</label>
                <input type="number" class="form-control"  placeholder="Ingrese la cantidad" aria-label="Default select example" name="cantidad" required>
            </div>
            <br>
            <div class="form-group col-md-3">
                <label for="valor" class="form-label">Valor</label>
                <input type="number" class="form-control valor"  placeholder="Ingrese el valor" aria-label="Default select example" name="valor" required>
            </div>
            <br>
            <div class="form-group col-md-3">
                <label for="valor" class="form-label">Valor Formato Moneda</label>
                <input type="text" class="form-control currency"  aria-label="Default select example"  readonly>
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
<!-- Convierte en tiempo real cadena a FORMATO MONEDA -->
<<script type="text/javascript">
            // Sample 2
            $(document).ready(function()
            {
                //evento que ocurre cuando se pierde focus
                $('.valor').keyup(function()
                { 
                    $('.currency').val($('.valor').val()).formatCurrency() ;
                    //$('.currency').formatCurrency();
                });
            });
    </script>
    <!-- Fin del Script formatoMoneda -->