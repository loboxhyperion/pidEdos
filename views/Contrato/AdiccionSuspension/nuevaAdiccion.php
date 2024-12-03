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
}
include("../../partials/layout.php");
include('../../partials/menusub.php');
include('../../../db.php');

// Obtenemos los el ultimo registro de proyeccion del contrato para obtener la ultima fecha de fin
$query= "SELECT periodo_fin,valor_dia FROM proyeccion_contractual  WHERE idContrato = $idContrato ORDER BY id DESC LIMIT 1";
$result = mysqli_query($conexion,$query) or die("fallo en la conexión 1");
$lastActa = mysqli_fetch_array($result); 


?>
<div class="container">
    <div class="" style="text-align:right;padding:5px;">
        <a href="listar.php?id=<?php echo $idContrato ?>&fecha_ini=<?php echo $fecha_ini?>"
            class="btn btn-primary">Volver</a>
        <hr>
        <h1 class="text-center blanco">Nueva Adicción</h1>
        <hr>
    </diV>
    <div class="col-md-12">
        <form class="row g-3" id="formulario" action="insertarAdiccion.php" method="post">


            <div class="form-group col-md-2">
                <label for="fecha_ini" class="form-label">Fecha de inicio</label>
                <input type="date" class="form-control" name="fecha_ini" value="<?php echo $fecha_ini ?>" disabled>
            </div>

            <div class="form-group col-md-2">
                <label for="fecha_fin" class="form-label">Fecha de Finalización</label>
                <input type="date" class="form-control" id="fecha_fin" name="fecha_fin"
                    value="<?php echo $lastActa['periodo_fin']?>" disabled>
            </div>

            <div class="form-group col-md-2">
                <label for="fecha_fin_new" class="form-label"> Nueva Fecha de Finalización</label>
                <input type="date" class="form-control" id="fecha_fin_new" name="fecha_fin_new" required>
                <span id="errorFecha" class="error"></span> <!-- Mensaje de error -->

            </div>
            <div class="form-group col-md-3">
                <label for="disp_presupuestal" class="form-label">Nuevo CDP</label>
                <input type="number" class="form-control" name="disp_presupuestal" required>
            </div>
            <div class="form-group col-md-3">
                <label for="registro_pptal" class="form-label">Nuevo RP</label>
                <input type="number" class="form-control" name="registro_pptal" required>
            </div>
            <input type="hidden" class="form-control" name="idContrato" value="<?php echo $idContrato ?>" />
            <input type="hidden" class="form-control" name="fecha_ini" value="<?php echo $fecha_ini ?>" />
            <input type="hidden" class="form-control" name="fecha_fin" value="<?php echo $lastActa['periodo_fin'] ?>" />
            <input type="hidden" class="form-control" name="valorDia" value="<?php echo $lastActa['valor_dia'] ?>" />
            <br>
            <div class="form-group col-md-12" style="text-align:right;">
                <input type="submit" class="btn btn-primary" value="Guardar" />
            </div>
            <br>
            <br>
        </form>

    </div>
</div>
<style>
.error {
    color: red;
    font-size: 0.9em;
    margin-left: 10px;
    display: none;
    /* Ocultar por defecto */
}

input:invalid {
    border-color: red;
}
</style>
<script>
// evita que la la nueva fecha de fin no sea menor que la anterior
const fechaFinInput = document.getElementById('fecha_fin');
const nuevaFechaFinInput = document.getElementById('fecha_fin_new');
const errorFecha = document.getElementById('errorFecha');
const formulario = document.getElementById('formulario');

// Actualiza el atributo 'min' de nuevaFechaFin cuando cambie fechaFin
// fechaFinInput.addEventListener('change', () => {
//     nuevaFechaFinInput.min = fechaFinInput.value;
//     errorFecha.style.display = 'none'; // Ocultar error si se corrige
// });

// // Validación adicional antes de enviar
// formulario.addEventListener('submit', (e) => {
//     if (nuevaFechaFinInput.value < fechaFinInput.value) {
//         e.preventDefault();
//         errorFecha.textContent = 'La fecha debe se mayor a la de finalización';
//         errorFecha.style.display = 'inline';
//         nuevaFechaFinInput = .focus(); // Enfocar el campo con error
//     }
// });

// Validar al intentar cambiar la nueva fecha fin
nuevaFechaFinInput.addEventListener('input', () => {
    validarNuevaFecha();
});

// Validación al enviar el formulario
formulario.addEventListener('submit', (e) => {
    if (!validarNuevaFecha()) {
        e.preventDefault(); // Evita que el formulario se envíe si la fecha no es válida
        nuevaFechaFinInput.focus(); // Enfocar el campo inválido
    }
});

// Función para validar la nueva fecha
function validarNuevaFecha() {
    if (nuevaFechaFinInput.value < fechaFinInput.value) {
        errorFecha.textContent = 'La fecha debe se mayor a la de finalización';
        errorFecha.style.display = 'block';
        // nuevaFechaFinInput.classList.add('is-invalid'); // Agregar clase de error al campo
        return false; // Indica que la validación falló
    } else {
        errorFecha.textContent = '';
        eerrorFechaa.style.display = 'none';
        // nuevaFechaFinInput.classList.remove('is-invalid'); // Remover clase de error si es válido
        return true; // Indica que la validación fue exitosa
    }
}
</script>