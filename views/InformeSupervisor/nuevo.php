<?php
//seguridad de sessiones paginacion
session_start();
error_reporting(0);

//si no hay algun usuario registradose devuelve al login
if (!isset($_SESSION['rol'])) {
    header("location:../../index.php");
} else {
    //solo tiene permiso el admin y el supervisor
    if ($_SESSION['rol'] == 2 or $_SESSION['rol'] == 4) {
        header("location:../../index.php");
    }
}
include('../../db.php');
$varsesion = $_SESSION['usuario'];
$mes_ini =  date("Y") . "-01-01";
($mes_cut =  array("", date("Y") . "-06-30", date("Y") . "-12-31"));
//echo $mes_ini;
//echo date('Y');
$añoFiltro = date('Y');
// -----------------------------------------------------------------------------------------------------------------------------------------------
// -----------------------------------------------------------------------------------------------------------------------------------------------
// -----------------------------------------------------------------------------------------------------------------------------------------------

//define cual cuatrimestre se esta haciendo

//echo "<br>".$mes_cut[$cuatrimestre];
$mesActual = date('m'); //verificador del cuatrimestre
//  echo "<br>".$mesActual;

// -----------------------------------------------------------------------------------------------------------------------------------------------
// -----------------------------------------------------------------------------------------------------------------------------------------------
// -----------------------------------------------------------------------------------------------------------------------------------------------
//define cual cuatrimestre se esta haciendo
if ($mesActual == 6 or $mesActual == 7) {
    $semestre = 1;
}
if ($mesActual == 12  or $mesActual == 1) {
    $semestre = 2;
}

// echo "<br>".$cuatrimestre ;
// -----------------------------------------------------------------------------------------------------------------------------------------------
// -----------------------------------------------------------------------------------------------------------------------------------------------
// -----------------------------------------------------------------------------------------------------------------------------------------------

$query = "SELECT u.nombre, u.apellidos,c.id AS idContrato,c.valor_contrato,
        c.num_contrato FROM usuario AS u
        JOIN contrato AS c ON u.id = c.idUsuario
        WHERE c.idSupervisor = $varsesion[id] And c.years = $añoFiltro And u.tipo_persona = 'Natural'";

$result = mysqli_query($conexion, $query);


include('../partials/menusub.php');
include("../partials/layout.php");

?>

<div class="container">
    <div class="" style="text-align:right;padding:5px;">
        <a href="listar.php" class="btn btn-primary">Volver</a>
        <hr>
        <h1 class="text-center blanco">Informe Semestral # <?php echo $semestre ?></h1>
        <hr>
    </diV>

    <div class="col-md-12">
        <form action="insertar.php" method="post">
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-striped">
                        <tr>
                            <th>#</th>
                            <th>N° Contrato</th>
                            <th>Contratista</th>
                            <th>Valor del Contrato</th>
                            <th>Acumulado</th>
                            <th>Saldo</th>
                            <th>N° Alcances</th>
                            <th>Alcances Impactados</th>
                            <th>Ejecución Alcances</th>
                            <th>Ejecución Financiera</th>
                            <th>Seguridad Social Pagada</th>
                            <th>Seguridad Social Correspondiente</th>
                            <th>Diferencia</th>
                            <th>Observaciones</th>

                        </tr>
                        <!------------MUESTRA LA INFORMACIÓN ACTUALES--------------------------------------------------------------------------------------------------------------------------------------------------------->
                        <!--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
                        <!--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
                        <?php
                        $cont = 1;
                        while ($filas = mysqli_fetch_array($result)) {
                            //para poder sacar el numero del informe
                            //coge la última acta registrada 
                            $query2 = "SELECT SUM(valor) acumulado,SUM(valorPlanilla) seguridad_pagada_avg ,SUM(valorPlanillaReal) seguridad_real_avg FROM acta  WHERE idContrato = $filas[idContrato]  AND fecha_informe BETWEEN $mes_ini  AND '$mes_cut[$semestre]' ";
                            $result2 = mysqli_query($conexion, $query2) or die("fallo en la conexión");
                            $filas2 = mysqli_fetch_array($result2);

                            $query3 = "SELECT COUNT(idContrato) alcances_num,SUM(CASE WHEN impacto='Si' THEN 1 ELSE 0 END) impacto FROM alcance  WHERE idContrato = $filas[idContrato]";
                            $result3 = mysqli_query($conexion, $query3) or die("fallo en la conexión");
                            $filas3 = mysqli_fetch_array($result3);

                        ?>
                            <tr>
                                <td><?php echo $cont ?></td>
                                <td><strong><?php echo $filas['num_contrato'] ?></strong></td>
                                <input type="hidden" class="form-control" name="num_contrato[]" value="<?php echo $filas['num_contrato'] ?>" />
                                <!---->
                                <td><?php echo $filas['nombre'] . " " . $filas['apellidos'] ?></td>
                                <input type="hidden" class="form-control" name="contratista[]" value="<?php echo $filas['nombre'] . " " . $filas['apellidos'] ?>" />
                                <!---->
                                <td><?php echo number_format($filas['valor_contrato'], 2, ".", ",") ?></td>
                                <input type="hidden" class="form-control" name="valor_contrato[]" value="<?php echo $filas['valor_contrato'] ?>" />
                                <!---->
                              <td><?php echo number_format($filas2['acumulado'], 2, ".", ",") ?></td>
                                <input type="hidden" class="form-control" name="acumulado[]" value="<?php echo isset($filas2['acumulado']) ? $filas2['acumulado'] : 0 ?>" />
                                <!---->
                                <td><?php echo number_format(($filas['valor_contrato'] - $filas2['acumulado']), 2, ".", ",") ?></td>
                                <input type="hidden" class="form-control" name="saldo[]" value="<?php echo $filas['valor_contrato'] - $filas2['acumulado'] ?>" />
                                <!---->
                                <!---->
                                <td><?php echo $filas3['alcances_num'] ?></td>
                                <input type="hidden" class="form-control" name="alcances_num[]" value="<?php echo $filas3['alcances_num'] ?>" />
                                <!---->
                                <td><?php echo $filas3['impacto'] ?></td>
                                <input type="hidden" class="form-control" name="alcances_impactados[]" value="<?php echo $filas3['impacto'] ?>" />
                                <!--porcentaje -->
                                  <td><?php echo  (($filas3['impacto'] * 100) / $filas3['alcances_num']) . "%" ?></td>
                                <input type="hidden" class="form-control" name="alcances_avances[]" value="<?php echo ($filas3['impacto'] * 100)/ $filas3['alcances_num']?>" />
                                <!---->
                                 <td><?php echo  round(($filas2['acumulado'] * 100) / $filas['valor_contrato'], 2) . "%" ?></td>
                                <input type="hidden" class="form-control" name="ejecucion_financiera[]" value="<?php echo ($filas2['acumulado'] * 100) / $filas['valor_contrato'] ?>" />
                                <!---->
                                <td><?php echo number_format($filas2['seguridad_pagada_avg'], 2, ".", ",") ?></td>
                                <input type="hidden" class="form-control" name="seguridad_pagada_avg[]" value="<?php echo isset($filas2['seguridad_pagada_avg']) ? $filas2['seguridad_pagada_avg'] : 0?>" />
                                <!---->
                                <td><?php echo number_format($filas2['seguridad_real_avg'], 2, ".", ",") ?></td>
                                <input type="hidden" class="form-control" name="seguridad_real_avg[]" value="<?php echo isset($filas2['seguridad_real_avg']) ? $filas2['seguridad_real_avg'] : 0?>" />
                                <!---->
                                <td><?php echo number_format(($filas2['seguridad_pagada_avg'] - $filas2['seguridad_real_avg']), 2, ".", ",") ?></td>
                                <input type="hidden" class="form-control" name="diferencia[]" value="<?php echo $filas2['seguridad_pagada_avg'] - $filas2['seguridad_real_avg'] ?>" />
                                <!---->
                                <td><textarea class="form-control" aria-label="With textarea" name="observaciones[]" rows="5" cols="70"></textarea></td>
                                <!--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
                                <!--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
                                <!--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
                            </tr>
                        <?php
                            $cont++;
                        }
                        ?>
                        <!--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
                        <!--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
                        <!--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
                    </table>
                    <br>
                    <input type="hidden" class="form-control" name="idSupervisor" value="<?php echo $varsesion["id"] ?>" />
                    <input type="hidden" class="form-control" name="trimestre" value="<?php echo $semestre  ?>" />
                    <div class="form-group col-md-12" style="text-align:right;padding:5px;">
                        <input type="submit" class="btn btn-primary" value="Guardar" />
                    </div>
                    <br>
                    <br>
        </form>

    </div>
</div>