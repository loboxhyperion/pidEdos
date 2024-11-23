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
include('../../db.php');
$id=$_GET['id'];
$query= "SELECT * FROM categoria  WHERE id = $id";
$result = mysqli_query($conexion,$query) or die("fallo en la conexión");

$filas = mysqli_fetch_array($result);
?>

<div class="container">
    <div class="" style="text-align:right;padding:5px;">
        <a href="listar.php" class="btn btn-primary">Volver</a>
        <hr>
        <h1 class="text-center blanco">Editar Retención</h1>
        <hr>
    </diV>
    <div class="col-md-12">
        <form class="row g-3" action="actualizar.php" method="post">
        <div class="form-group col-md-4">
                <label for="Nombre" class="form-label">Nombre de la categoria</label>
                <input type="text" class="form-control" placeholder="Nombre del impuesto" aria-label="nombre" name="nombre" value="<?php echo $filas['nombre'] ?>"required>
            </div>
            <br>
            <div class="form-group col-md-4">
                <label for="valor_dia" class="form-label">Valor Mes</label>
                <input type="number" class="form-control valor"  placeholder="Ingrese el valor del mes" aria-label="Default select example" name="valor" value="<?php echo $filas['valor'] ?>" required>
            </div>
            <br>
            <div class="form-group col-md-4">
                <label for="valor_dia" class="form-label">Valor Formato Moneda</label>
                <input type="text" class="form-control currency"  aria-label="Default select example"  readonly>
            </div>
            <br>
            <input type="hidden" class="form-control"  name="id" step="0.01" value="<?php echo $id ?>" >
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
