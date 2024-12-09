<?php
//seguridad de sessiones paginacion
session_start();
error_reporting(0);

//si no hay algun usuario registradose devuelve al login
if (!isset($_SESSION['rol'])) {
    header("location:../../index.php");
} else {
    //solo tiene permiso el admin y creador
    if ($_SESSION['rol'] == 3 or $_SESSION['rol'] == 4) {
        header("location:../../index.php");
    }
}
$idContrato = $_GET["id"];
$modificar = $_GET["modificar"];

include('../../db.php');
include("../partials/layout.php");
include('../partials/menusub.php');

//extraccion información del contrato
$query = "SELECT * FROM contrato  WHERE contrato.id = $idContrato";
$result = mysqli_query($conexion, $query) or die("fallo en la conexión");
$row = mysqli_fetch_array($result);
?>


<div class="container">
    <div class="" style="text-align:right;padding:5px;">
        <a href="listar.php" class="btn btn-primary">Volver</a>
        <hr>
        <h1 class="text-center blanco">Editar Contrato</h1>
        <hr>
    </diV>
    <div class="col-md-12">
        <form class="row g-3" name="contrato" id="contrato" action="actualizar.php" method="post">

            <div class="form-group col-md-4">
                <label for="idUsuario" class="form-label">Contratista</label>
                <select class="form-select" aria-label="Default select example" name="idUsuario" required>
                    <option value="">Seleccionar</option>
                    <?php
                    include('../../db.php');
                    $consulta = "SELECT * FROM usuario WHERE idRol = 4";
                    $resultado = mysqli_query($conexion, $consulta);

                    while ($filas = mysqli_fetch_array($resultado)) {
                        if ($row['idUsuario'] == $filas['id']) {
                    ?>
                    <option value="<?php echo $filas['id'] ?>" selected>
                        <?php echo $filas['nombre'] . ' ' . $filas['apellidos'] ?></option>
                    <?php   } else {
                        ?>
                    <option value="<?php echo $filas['id'] ?>">
                        <?php echo $filas['nombre'] . ' ' . $filas['apellidos'] ?></option>
                    <?php
                        }
                    }
                    ?>
                </select>
            </div>
            <br>
            <div class="form-group col-md-4">
                <label for="idSupervisor" class="form-label">Supervisor</label>
                <select class="form-select" aria-label="Default select example" name="idSupervisor" required>
                    <option value="">Seleccionar</option>
                    <?php
                    include('../../db.php');
                    $consulta = "SELECT * FROM usuario WHERE idRol = 3";
                    $resultado = mysqli_query($conexion, $consulta);

                    while ($filas = mysqli_fetch_array($resultado)) {
                        if ($row['idSupervisor'] == $filas['id']) {
                    ?>
                    <option value="<?php echo $filas['id'] ?>" selected>
                        <?php echo $filas['nombre'] . ' ' . $filas['apellidos'] ?></option>
                    <?php   } else {
                        ?>
                    <option value="<?php echo $filas['id'] ?>">
                        <?php echo $filas['nombre'] . ' ' . $filas['apellidos'] ?></option>
                    <?php
                        }
                    }
                    ?>
                </select>
            </div>
            <br>
            <div class="form-group col-md-4">
                <label for="idOrdenador" class="form-label">Ordenador del Gasto</label>
                <select class="form-select" aria-label="Default select example" name="idOrdenador" required>
                    <option value="">Seleccionar</option>
                    <?php
                    include('../../db.php');
                    $consulta = "SELECT * FROM usuario WHERE idRol = 3";
                    $resultado = mysqli_query($conexion, $consulta);

                    while ($filas = mysqli_fetch_array($resultado)) {
                        if ($row['idOrdenador'] == $filas['id']) {
                    ?>
                    <option value="<?php echo $filas['id'] ?>" selected>
                        <?php echo $filas['nombre'] . ' ' . $filas['apellidos'] ?></option>
                    <?php   } else {
                        ?>
                    <option value="<?php echo $filas['id'] ?>">
                        <?php echo $filas['nombre'] . ' ' . $filas['apellidos'] ?></option>
                    <?php
                        }
                    }
                    ?>
                </select>
            </div>
            <br>
            <div class="form-group col-md-3">
                <label for="idSupervisor" class="form-label">Segundo Supervisor<strong>(dejar vacio si no
                        aplica)</strong></label>
                <select class="form-select" aria-label="Default select example" name="idSupervisor2">
                    <option value="0">Seleccionar</option>
                    <?php
                    include('../../db.php');
                    $consulta = "SELECT * FROM usuario WHERE idRol = 3";
                    $resultado = mysqli_query($conexion, $consulta);

                    while ($filas = mysqli_fetch_array($resultado)) {
                        if ($row['idSupervisor2'] == $filas['id']) {
                    ?>
                    <option value="<?php echo $filas['id'] ?>" selected>
                        <?php echo $filas['nombre'] . ' ' . $filas['apellidos'] ?></option>
                    <?php   } else {
                        ?>
                    <option value="<?php echo $filas['id'] ?>">
                        <?php echo $filas['nombre'] . ' ' . $filas['apellidos'] ?></option>
                    <?php
                        }
                    }
                    ?>
                </select>
            </div>
            <br>
            <div class="form-group col-md-5">
                <label for="rubro" class="form-label">Rubro</label>
                <textarea class="form-control" aria-label="With textarea" name="rubro"
                    required><?php echo $row['rubro'] ?></textarea>
            </div>
            <br>
            <div class="form-group col-md-4">
                <label for="modalidad" class="form-label">Modalidad Contractual</label>
                <textarea class="form-control" aria-label="With textarea" name="modalidad"
                    required><?php echo $row['modalidad'] ?></textarea>
            </div>
            <br>
            <div class="form-group col-md-2">
                <label for="registro_pptañ" class="form-label">Registro PPTAL</label>
                <input type="text" class="form-control" placeholder="Ingrese el registro PPTAL"
                    aria-label="Default select example" name="registro_pptal"
                    value="<?php echo $row['registro_pptal'] ?>" required>
            </div>
            <br>
            <div class="form-group col-md-2">
                <label for="disp_presupuestal" class="form-label">Disponibilidad Presupuestal</label>
                <input type="text" class="form-control" placeholder="" aria-label="Default select example"
                    name="disp_presupuestal" value="<?php echo $row['disp_presupuestal'] ?>" required>
            </div>
            <br>
            <div class="form-group col-md-4">
                <label for="año" class="form-label">Año</label>
                <select class="form-select" aria-label="Default select example" name="año" required>
                    <option value="">Seleccionar</option>
                    <option value="<?php echo $row['years'] ?>" selected><?php echo $row['years'] ?></option>
                    <option value="2023">2023</option>
                    <option value="2024">2024</option>
                    <option value="2025">2025</option>
                    <option value="2026">2026</option>
                    <option value="2027">2027</option>
                    <option value="2028">2028</option>
                    <option value="2029">2029</option>
                    <option value="2030">2030</option>
                </select>
            </div>
            <br>
            <div class="form-group col-md-4">
                <label for="num_contrato" class="form-label">Número de contrato</label>
                <input type="number" class="form-control" placeholder="Número de contrato"
                    aria-label="Default select example" name="num_contrato" value="<?php echo $row['num_contrato'] ?>"
                    required>
            </div>
            <br>
            <div class="form-group col-md-4">
                <label for="fecha_delegacion" class="form-label">Fecha de delegación</label>
                <input class="form-control" type="date" name="fecha_delegacion"
                    value="<?php echo $row['fecha_delegacion'] ?>" required>
            </div>
            <br>
            <div class="form-group col-md-4">
                <label for="num_delegacion" class="form-label">Número de delegación</label>
                <input type="number" class="form-control" placeholder="Ingrese el número de delegación"
                    aria-label="Default select example" name="num_delegacion"
                    value="<?php echo $row['num_delegacion'] ?>" required>
            </div>
            <br>
            <div class="form-group col-md-4">
                <label for="area" class="form-label">Area</label>
                <select class="form-select" aria-label="Default select example" name="area" required>
                    <option value="">Seleccionar</option>
                    <option value="<?php echo $row['area'] ?>" selected><?php echo $row['area'] ?></option>
                    <option value="Subdirección técnica">Subgerencia técnica</option>
                    <option value="Subdirección Administrativa y Financiera">Subgerencia Administrativa y Financiera
                    </option>
                </select>
            </div>
            <br>
            <div class="form-group col-md-2">
                <label for="fecha_ini" class="form-label">Fecha de Inicio</label>
                <input class="form-control" type="date" name="fecha_ini" value="<?php echo $row['fecha_ini'] ?>"
                    required>
            </div>
            <br>
            <div class="form-group col-md-2">
                <label for="fecha_fin" class="form-label">Fecha de Finalización</label>
                <input class="form-control" type="date" name="fecha_fin" value="<?php echo $row['fecha_fin'] ?>"
                    required>
            </div>
            <br>
            <div class="form-group col-md-4">
                <label for="idSupervisor" class="form-label">Valor Mensual</label>
                <select class="form-select valorMes" aria-label="Default select example" name="idCategoria"
                    onchange="actualizarValor(this)" required>
                    <option value="">Seleccionar</option>
                    <?php
                    include('../../db.php');
                    //$consulta = "SELECT * FROM categoria WHERE tipo = 'Contratista' ORDER BY valor ASC";
                    $consulta = "SELECT * FROM categoria ORDER BY valor ASC";
                    $resultado = mysqli_query($conexion, $consulta);

                    while ($filas = mysqli_fetch_array($resultado)) {
                        if ($row['valorMes'] == $filas['valor']) {
                    ?>
                    <option value="<?php echo $filas['id'] ?>" selected>
                        <?php echo number_format($filas['valor'], 0, ".", ",") ?></option>
                    <?php
                        } else {
                        ?>
                    <option value="<?php echo $filas['id'] ?>"><?php echo number_format($filas['valor'], 0, ".", ",") ?>
                    </option>
                    <?php
                        }
                    }
                    ?>

                </select>
            </div>
            <div class="form-group col-md-2">
                <label for="fecha_necesidad" class="form-label">Fecha Necesidad del servicio</label>
                <input class="form-control" type="date" name="fecha_necesidad"
                    value="<?php echo $row['fecha_necesidad'] ?>" required>
            </div>
            <br>
            <div class="form-group col-md-2">
                <label for="fecha_firma" class="form-label">Fecha De La Firma de Contrato</label>
                <input class="form-control" type="date" name="fecha_firma" value="<?php echo $row['fecha_firma'] ?>"
                    required>
            </div>
            <!--VALOR PARA  GUARDAR Y ENVIAR EL SUELDO DE LA PERSONA-->
            <input type="hidden" class="form-control currency" aria-label="Default select example" name="valorMes"
                value="<?php echo $row['valorMes'] ?>">
            <div class="form-group col-md-12">
                <label for="objeto" class="form-label">Objeto</label>
                <textarea class="form-control" aria-label="With textarea" name="objeto" rows="4"
                    required><?php echo $row['objeto'] ?></textarea>
            </div>
            <br>
            <div class="form-group col-md-12">
                <label for="forma_pago" class="form-label">Forma de pago</label>
                <textarea class="form-control" aria-label="With textarea" name="forma_pago" rows="10"
                    required><?php echo $row['forma_pago'] ?></textarea>
            </div>
            <br>
            <div class="form-group col-md-12">
                <label for="entregables" class="form-label">Entregables</label>
                <textarea class="form-control" aria-label="With textarea" name="entregables" rows="4"
                    required><?php echo $row['entregables'] ?></textarea>
            </div>
            <br>
            <div class="form-group col-md-3">
                <label for="salud" class="form-label">Salud</label>
                <input type="text" class="form-control" placeholder="Ingrese la salud a la que pertenece"
                    aria-label="Default select example" name="salud" value="<?php echo $row['salud'] ?>" required>
            </div>
            <br>
            <div class="form-group col-md-3">
                <label for="pension" class="form-label">Pensión</label>
                <input type="text" class="form-control" placeholder="Ingrese la pension a la que pertenece"
                    aria-label="Default select example" name="pension" value="<?php echo $row['pension'] ?>" required>
            </div>
            <br>

            <div class="form-group col-md-3">
                <label for="arl" class="form-label">ARL</label>
                <input type="text" class="form-control" placeholder="Ingrese la arl a la que pertenece"
                    aria-label="Default select example" name="arl" value="<?php echo $row['arl'] ?>" required>
            </div>
            <br>
            <div class="form-group col-md-3">
                <label for="fecha_activacion" class="form-label">Fecha de Activación</label>
                <input class="form-control" type="date" name="fecha_activacion"
                    value="<?php echo $row['fecha_activacion'] ?>" required>
            </div>
            <br>
            <div class="form-group col-md-12">
                <label for="observaciones" class="form-label">Observaciones</label>
                <textarea class="form-control" aria-label="With textarea" name="observaciones"
                    required><?php echo $row['observaciones'] ?></textarea>
            </div>
            <br>

            <div class="form-group col-md-12">
                <label for="riesgo" class="form-label">Riesgo</label>
                <select class="form-select" aria-label="Default select example" name="idRiesgo"
                    onchange="actualizarNombreImpuestos(this)" required>
                    <option value="">Seleccionar</option>
                    <?php
                    include('../../db.php');
                    $consulta2 = "SELECT c.id,cr.idContrato,cr.idRetencion,r.id,r.nombre,r.orden FROM contrato AS c 
                                 INNER JOIN  contrato_retencion AS cr ON c.id = cr.idContrato 
                                 INNER JOIN retencion AS r ON cr.idRetencion = r.id  
                                 WHERE c.id = $row[id]  AND r.orden = 3";
                                 
                    $riesgo = mysqli_query($conexion, $consulta2);

                    $consulta = "SELECT * FROM retencion WHERE orden = 3";
                    
                    $resultado = mysqli_query($conexion, $consulta);

                    while ($filas2 = mysqli_fetch_array($resultado)) {
                        // if($filas2['nombre'] == $riesgo['nombre']){
                        
                    ?>
                    <!-- <option value="<?php //echo $filas2['id'] ?>" selected><?php //echo $filas2['nombre'] ?></option> -->
                    <?php
                        // }else{
                    ?>
                    <option value="<?php echo $filas2['id'] ?>"><?php echo $filas2['nombre'] ?></option>
                    <?php
                        // }
                    }
                    ?>
                </select>
            </div>
            <input type="hidden" class="form-control" name="nombreImpuesto" id="riesgo" />
            <input type="hidden" class="form-control" name="idContrato" value="<?php echo $idContrato ?>" />
            <input type="hidden" class="form-control" name="modificar" value="<?php echo $modificar ?>" />

            <div class="form-group col-md-12" style="text-align:right;">
                <input type="submit" class="btn btn-primary" value="Guardar" />
            </div>
            <br>
            <br>
        </form>

    </div>
</div>
<!-- Convierte en tiempo real cadena a FORMATO MONEDA -->
<script type="text/javascript">
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