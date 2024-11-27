<?php
include("../../partials/layout.php");
include('../../partials/menusub.php');
?>

<div class="container">
    <div class="" style="text-align:right;padding:5px;">
        <a href="listar.php" class="btn btn-primary">Volver</a>
        <hr>
        <h1 class="text-center blanco">Nuevo Contrato</h1>
        <hr>
    </diV>

    <?php
    if (isset($_GET['mensaje'])) {
    ?>
    <div class="p-3 mb-2 bg-danger text-white">
        <?php echo $_GET['mensaje']; ?>
    </div>
    <?php } ?>
    <div class="col-md-12">
        <form class="row g-3" action="calculos.php" method="post">


            <div class="form-group col-md-2">
                <label for="fecha_ini" class="form-label">Fecha de Inicio</label>
                <input class="form-control" type="date" name="fecha_ini" required>
            </div>
            <br>
            <div class="form-group col-md-2">
                <label for="fecha_fin" class="form-label">Fecha de Finalización</label>
                <input class="form-control" type="date" name="fecha_fin" required>
            </div>
            <div class="form-group col-md-4">
                <label for="idSupervisor" class="form-label">Valor Mensual</label>
                <input class="form-control" type="number" name="valorMes">
            </div>

            <div class="form-group col-md-12" style="text-align:right;">
                <input type="submit" class="btn btn-primary" value="Guardar" />
            </div>
            <br>
            <br>
        </form>

    </div>
</div>
<script>
//cada que se seleciona algo en el select el valor del input con nombre de impuesto cambia
//permite así traer tambien el nombre del riesgo
function actualizarValor(option) {
    //obtiene el texto del option en el select
    var textoValor = option.options[option.selectedIndex].text;
    textoValor = textoValor.replace(/,/g, "");
    //document.contrato.nombreImpuesto.value = textoRiesgo;
    $('.currency').val(textoValor);
}
</script>