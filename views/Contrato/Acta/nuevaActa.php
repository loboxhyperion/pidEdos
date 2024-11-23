<?php
//seguridad de sessiones paginacion
session_start();
error_reporting(0);

if(!isset($_SESSION['rol'])){
     header("location:../../index.php");
}else{ 
     $idContrato = $_GET["id"];
     $NombreContratistas = $_GET["nombre"];
     $NombreSupervisor = $_GET["NombreSupervisor"];
     $numInforme = $_GET["num_informe"];
     $minimoMensual = 1160000;
     $minimoDia = $minimoMensual /30;
     include('../../../db.php');

     echo "<br>". $arrayActa[$i];

     //extraccion información del contrato
     $query= "SELECT * FROM contrato  WHERE contrato.id = $idContrato";
     $result = mysqli_query($conexion,$query) or die("fallo en la conexión 1");

     $row = mysqli_fetch_array($result);

     //extracción Supervisor
     $query2= "SELECT * FROM usuario  WHERE usuario.id = $row[idSupervisor]";
     $result2 = mysqli_query($conexion,$query2) or die("fallo en la conexión 2");

     $row2 = mysqli_fetch_array($result2);

     //Si vacaciones estan en Si recalcula la consulta con los datos del  encargado
     if($row2["vacaciones"] == "Si"){
          $Encargado = "Si";
          $query2= "SELECT * FROM usuario  WHERE usuario.id = $row2[idEncargado]";
          $result2 = mysqli_query($conexion,$query2) or die("fallo en la conexión supervisor");
          $row2 = mysqli_fetch_array($result2);
     }

     //extracción contratista
     $query3= "SELECT * FROM usuario  WHERE usuario.id = $row[idUsuario]";
     $result3 = mysqli_query($conexion,$query3) or die("fallo en la conexión 3");

     $row3 = mysqli_fetch_array($result3);

     //Extraccion de los registros de adiciones y suspensiones
     $query4= "SELECT * FROM adicion_suspension WHERE idContrato = $idContrato";
     $result4 = mysqli_query($conexion,$query4);


     //extraccion proyeccion
     $query5= "SELECT * FROM proyeccion_contractual WHERE idContrato = $idContrato and prioridad = 1";
     $result5 = mysqli_query($conexion,$query5);
}

include("../../partials/layout.php");
include('../../partials/menusub.php');

//Extraccion de los alcances
$query6= "SELECT * FROM alcance WHERE idContrato = $idContrato";
$result6 = mysqli_query($conexion,$query6);

//Extracción de las actuales actas 
$query7= "SELECT * FROM acta  WHERE idContrato = $idContrato ";
$result7 = mysqli_query($conexion,$query7) or die("fallo en la conexión 4");
$arrayActa = array();

//Guarda los datos de las plantillas pagas y el acumulado del las actas creadas 
//sirve para mostrar en la proyeccion las actas que ya se han hecho
while($filas7=mysqli_fetch_assoc($result7)){
     $arrayActa[] = $filas7['acumulado'];
     $arrayActa[] = $filas7['fechaPlanilla'];
     $arrayActa[] = $filas7['numPlanilla'];
     $arrayActa[] = $filas7['valorPlanilla'];
     $arrayActa[] = $filas7['valorPlanillaReal'];
}
/*for($i=0; $i < count($arrayActa); $i += 4){
     echo "<br>". $arrayActa[$i];
}*/
//Extracción de la proyeccion actual  correspondiente al acta a pagar
$query8= "SELECT * FROM proyeccion_contractual WHERE idContrato = $idContrato and num_acta = $numInforme ";
$result8 = mysqli_query($conexion,$query8) or die("fallo en la conexión 5");
$filas8 = mysqli_fetch_array($result8);
?>

<?php
if(isset($_GET['mensaje'])){
?>
<div class="p-3 mb-2 bg-danger text-white">
     <?php  echo $_GET['mensaje']; ?>
</div>
<?php } ?>

<div class="container" style="text-align:right;padding:5px;">
     <a href="listarActas.php?id=<?php echo $idContrato ?>&nombre=<?php echo $NombreContratistas ?>&NombreSupervisor=<?php echo $NombreSupervisor ?>" class="btn btn-primary">Volver</a>
     <hr>
     <h1 class="text-center blanco">Nueva Acta</h1>
     <hr>
</div>
<br>
<div class="container decorado" style="background: #e0e0e0;border-radius: 25px;">
    <form class="row g-3" action="insertarActa.php" method="post">
          <br>
          <h1 class="text-center blanco">Proyeccion contractual : <?php echo $NombreContratistas ?></h1>
          <hr>
          <hr>
          <h2 class="text-center blanco">Informe de actividades</h2>
          <hr>
          <div class="row g-3">
                    <div class="form-group col-md-4">
                              <label for="fecha_fin" class="form-label"><h3>Fecha de informe</h3></label>
                              <input class="form-control" style="width:50%;" type="date" name="fecha_informe" required>
                    </div>
                    <div class="form-group col-md-4">
                         <label class="form-label"><h3>N° del informe</h3></label>
                         <input type="number" class="form-control" style="width:20%;"  value="<?php echo $numInforme ?>" readonly>
                    </div>
                    <div class="form-group col-md-4">
                         <label class="form-label"><h3>Modalidad Contractual</h3></label>
                         <input type="text" class="form-control" style="width:80%;" value="<?php echo $row['modalidad'] ?>" readonly>
                    </div>
          </div>
          <hr>
          <h2 class="text-center blanco">1.Datos Contractuales</h2>
          <hr>
          <div class="row g-3">
               <div class="form-group col-md-2">
                    <label class="form-label"><h3>Registro PPTAL</h3></label>
                    <input type="text" class="form-control"  value="<?php echo $row['registro_pptal'] ?>" readonly>
               </div>
               <div class="form-group col-md-2">
                    <label class="form-label"><h3>CDP</h3></label>
                    <input type="text" class="form-control"  value="<?php echo $row['disp_presupuestal'] ?>" readonly>
               </div>
               <div class="form-group col-md-4">
                    <label class="form-label"><h3>Rubro</h3></label>
                    <textarea  class="form-control" readonly> <?php echo $row['rubro'] ?></textarea>
               </div>
               <div class="form-group col-md-2">
                    <label class="form-label"><h3>Año</h3></label>
                    <input type="number" class="form-control"  value="<?php echo $row['years'] ?>" readonly>
               </div>
               <div class="form-group col-md-2">
                    <label class="form-label"><h3>Contrato N°</h3></label>
                    <input type="number" class="form-control"  value="<?php echo $row['num_contrato'] ?>" readonly>
               </div>

               <!--------------------------------------------------------------------------->
               <div class="form-group col-md-5">
                    <label class="form-label"><h3>Contratante</h3></label>
                    <input type="text" class="form-control"  value="<?php echo $row['contratante'] ?>" readonly>
               </div>
               <div class="form-group col-md-2">
                    <label class="form-label"><h3>Nit</h3></label>
                    <input type="text" class="form-control"  value="816005795-1" readonly>
               </div>
               <div class="form-group col-md-5">
                    <label class="form-label"><h3>Dirección</h3></label>
                    <input type="text" class="form-control"  value="CALLE 50 NRO 14 -  56 BARRIO LOS NARANJOS" readonly>
               </div>
               <!--------------------------------------------------------------------------->
               <div class="form-group col-md-4">
                    <label class="form-label"><h3>Ordenador del Gasto</h3></label>
                    <input type="text" class="form-control"  value="MANUEL ALBERTO RAMIREZ URIBE" readonly>
               </div>
               <div class="form-group col-md-4">
                    <label class="form-label"><h3>Identificacion</h3></label>
                    <input type="text" class="form-control"  value="10100227" readonly>
               </div>
               <div class="form-group col-md-4">
                    <label class="form-label"><h3>Cargo</h3></label>
                    <input type="text" class="form-control"  value="Directo general" readonly>
               </div>
               <!--------------------------------------------------------------------------->
               <!--------------------------------SI  ESTA DE VACACIONES EL INFORME SE LLENA CON LA INFORMACION DEL DEL SUPERVISOR ENCARGADO------------------------------------------->
               <div class="form-group col-md-3">
                    <label class="form-label"><h3>Supervisor <?php if($Encargado == "Si") echo "Encargado"?></h3></label>
                    <input type="text" class="form-control"  value="<?php echo $row2['nombre'] ." ". $row2['apellidos']  ?>" readonly>
                    <!--valor usado para crear la acta-->
                    <input type="hidden" class="form-control" name="idSupervisor" value="<?php echo $row2['id'] ?>" />  
               </div>
               <div class="form-group col-md-2">
                    <label class="form-label"><h3>Identificacion</h3></label>
                    <input type="number" class="form-control"  value="<?php echo $row2['cedula'] ?>" readonly>
               </div>
               <div class="form-group col-md-2">
                    <label class="form-label"><h3>Cargo</h3></label>
                    <input type="text" class="form-control"  value="<?php echo $row2['cargo'] ?>" readonly>
               </div>
               <div class="form-group col-md-3">
                    <label class="form-label"><h3>Fecha Delegación</h3></label>
                    <input type="text" class="form-control"  value="<?php echo $row['fecha_delegacion'] ?>" readonly>
               </div>
               <div class="form-group col-md-2">
                    <label class="form-label"><h3>N° Delegación</h3></label>
                    <input type="text" class="form-control"  value="<?php echo $row['num_delegacion'] ?>" readonly>
               </div>
               <!--------------------------------------------------------------------------->
               <div class="form-group col-md-3">
                    <label class="form-label"><h3>Contratista</h3></label>
                    <input type="text" class="form-control"  value="<?php echo $NombreContratistas ?>" readonly>
               </div>
               <div class="form-group col-md-2">
                    <label class="form-label"><h3>Identificacion</h3></label>
                    <input type="number" class="form-control"  value="<?php echo $row3['cedula'] ?>" readonly>
               </div>
               <div class="form-group col-md-3">
                    <label class="form-label"><h3>Profesion</h3></label>
                    <input type="text" class="form-control"  value="<?php echo $row3['profesion'] ?>" readonly>
               </div>
               <div class="form-group col-md-4">
                    <label class="form-label"><h3>Dirección</h3></label>
                    <input type="text" class="form-control"  value="<?php echo $row3['direccion'] ?>" readonly>
               </div>
               <!--------------------------------------------------------------------------->
               <div class="form-group col-md-3">
                    <label class="form-label"><h3>Tipo de persona </h3></label>
                    <input type="text" class="form-control"  value="<?php echo $row3['tipo_persona'] ?>" readonly>
               </div>
               <div class="form-group col-md-3">
                    <label class="form-label"><h3>Responsable de iva</h3></label>
                    <input type="text" class="form-control"  value="<?php echo $row3['resp_iva'] ?>" readonly>
               </div>
               <div class="form-group col-md-2">
                    <label class="form-label"><h3>Telefono</h3></label>
                    <input type="text" class="form-control"  value="<?php echo $row3['telefono'] ?>" readonly>
               </div>
               <div class="form-group col-md-4">
                    <label class="form-label"><h3>Correo</h3></label>
                    <input type="text" class="form-control"  value="<?php echo $row3['correo'] ?>" readonly>
               </div>
               <!--------------------------------------------------------------------------->
               <div class="form-group col-md-3">
                    <label class="form-label"><h3>Duración</h3></label>
                    <input type="number" class="form-control"  value="<?php echo $row['duracion'] ?>" readonly>
               </div>
               <div class="form-group col-md-4">
                    <label class="form-label"><h3>Valor</h3></label>
                    <input type="text" class="form-control"  value="<?php echo $row['valor_contrato'] ?>" readonly>
               </div>
               <div class="form-group col-md-3">
                    <label class="form-label"><h3>Fecha de Inicio</h3></label>
                    <input type="text" class="form-control"  value="<?php echo $row['fecha_ini'] ?>" readonly>
               </div>
               <div class="form-group col-md-2">
                    <label class="form-label"><h3>Fecha de Fin</h3></label>
                    <input type="text" class="form-control"  value="<?php echo $row['fecha_fin'] ?>" readonly>
               </div>
               <hr>
               <!----------------------------------------------------------------------------->
               <!--Suspensiones y adicciones--------------------------------------------------------------------------->
               <!----------------------------------------------------------------------------->
               <div class="row">
                         <div class="col-md-12">
                              <table class="table">
                                   <tr>
                                        <th>N° Modificación</th>
                                        <th>Tipo</th>
                                        <th>Fecha de modifiación</th>
                                        <th>Fecha de suspension</th>
                                        <th>Fecha de reinicio</th>
                                        <th>Dias</th>
                                        <th>Valor</th>
                                        <th>Fecha de terminación Anterior</th>
                                        <th>Nueva fecha de terminación</th>

                                   </tr>
                                        <?php
                                             $cont = 1;
                                             while($filas4=mysqli_fetch_array($result4)){
                                        ?>
                                        <tr>
                                             <td><?php echo $cont?></td>
                                             <td><?php echo $filas4['tipo']?></td>
                                             <td><?php echo $filas4['fecha_modificacion']?></td>
                                             <td><?php echo $filas4['fecha_suspension']?></td>
                                             <td><?php echo $filas4['fecha_reinicio']?></td>
                                             <td><?php echo $filas4['dias']?></td>
                                             <td><?php echo round($filas4['valor'])?></td>
                                             <td><?php echo $filas4['fecha_terminacion_pre']?></td>
                                             <td><?php echo $filas4['fecha_terminacion_new']?></td>
                                        </tr>
                                        <?php
                                             $cont++;
                                             }
                                        ?>
                              </table>
                    </div>
                    </div>
                    <hr>
                         <div class="form-group col-md-12">
                              <ul class="list-group">
                                   <li class="list-group-item "><Strong>Objeto:  </Strong><?php echo $row['objeto']?></li>
                                   <li class="list-group-item "><Strong>Forma de pago: </Strong><?php echo $row['forma_pago']?></li>
                                   <li class="list-group-item "><Strong>Entregables: </Strong><?php echo $row['entregables']?></li>
                                   <li class="list-group-item "><Strong>Observación: </Strong><?php echo $row['observaciones']?></li>
                              </ul>
                         </div>


               </div>
          <hr>
          <h2 class="text-center blanco">2.Seguridad Social E Impuestos</h2>
          <hr>
                    <div class="row">
                         <div class="col-md-12">
                              <table class="table">
                                   <tr>
                                        <th>Item</th>
                                        <th>%</th>
                                        <th>Tipo</th>
                                        <th>Valor Total</th>
                                        <th>Valor Mes</th>
                                        <th>Valor Dia</th>
                                        <th>Dias a Pagar</th>
                                        <th>Correspondiente al Acta</th>
                                        <th>Entidad Afiliación</th>
                                        <th>Fecha afiliación</th>
                                        <th>Día Habil de pago</th>       
                                   </tr>
                                   <?php
                                        $consulta2= "SELECT * FROM `contrato_retencion` WHERE idContrato = $idContrato";
                                        $resultado2 = mysqli_query($conexion,$consulta2);
                                        $rowsImpuestos = mysqli_num_rows($resultado2);
                                   // $idRetencion =mysqli_fetch_array($resultado2);
                                   
                                        /*if($idRetencion =mysqli_fetch_array($resultado2)){
                                             echo "verdad";
                                        }else{
                                             echo "falso";
                                        }*/

                                        $cont = 1;
                                        $sumBaseTotal = 0;
                                        $sumBaseMes = 0;
                                        $sumBaseDia = 0;
                                        $sumActa = 0;
                                        while($idRetencion =mysqli_fetch_array($resultado2)){ 
                                             $consulta3= "SELECT * FROM `retencion` WHERE id = $idRetencion[idRetencion] ";
                                             $resultado3 = mysqli_query($conexion,$consulta3); 
                                             $itemRetencion =mysqli_fetch_array($resultado3);                  
                                   ?>
                                        <tr>
                                             <td><?php echo $itemRetencion['nombre']; ?></td>
                                             <td><?php echo $itemRetencion['porcentaje'];?>%</td>
                                             <td><?php echo $itemRetencion['tipo']; ?></td>
                                             <td>
                                                  <?php 
                                                  ///para sacar el valor total
                                                       if($cont == 1){
           
                                                            $baseCotizacionTotal = round(($itemRetencion['porcentaje'] / 100) * ($row['valor_contrato']));
                                                            if($baseCotizacionTotal < ($minimoDia * $row['duracion'])){
                                                                 $baseCotizacionTotal = $minimoDia * $row['duracion'];
          
                                                            }
                                                            echo $baseCotizacionTotal ;
                                                       }else{
                                                            echo round(($itemRetencion['porcentaje'] / 100) * $baseCotizacionTotal);
                                                            $sumBaseTotal = $sumBaseTotal + round(($itemRetencion['porcentaje'] / 100) * $baseCotizacionTotal);
                                                            
                                                       }
                                                       
                                                  ?>
                                             </td>
                                             <td><?php 
                                                  ///para sacar el valor mes
                                                       if($cont == 1){
                                                            $baseCotizacion = round(($itemRetencion['porcentaje'] / 100) * ($row['valorMes']));
                                                           //$baseCotizacion = $baseCotizacion / $row['num_actas'];
                                                            if($baseCotizacion < $minimoMensual){
                                                                 $baseCotizacion = $minimoMensual;
                                                            }
                                                            echo $baseCotizacion ;
                                                       }else{
                                                            echo round(($itemRetencion['porcentaje'] / 100) * $baseCotizacion );
                                                            $sumBaseMes = $sumBaseMes + round(($itemRetencion['porcentaje'] / 100) * $baseCotizacion) ;
                                                       }
                                                       
                                                  ?>
                                             </td>
                                             <td><?php 
                                             ///para sacar el valor dia
                                                       if($cont == 1){
                                                            $baseCotizacionDia = $baseCotizacion / 30 ;//round($baseCotizacion / 30);
                                                            echo round($baseCotizacionDia) ;
                                                       }else{
                                                            echo round(($itemRetencion['porcentaje'] / 100) * $baseCotizacionDia) ;
                                                            $sumBaseDia = $sumBaseDia + round(($itemRetencion['porcentaje'] / 100) * $baseCotizacionDia) ;
                                                       }
                                                       
                                                  ?>
                                             </td>
          
                                             <td  style="vertical-align:middle;"><?php echo $filas8['dias']; ?></td>
                                             <!-- <td><?php  //if($cont == 1){ echo $baseCotizacion; }else{ echo ($itemRetencion['porcentaje'] / 100) * $baseCotizacionDia * $filas8['dias']; $sumActa  = $sumActa + ($itemRetencion['porcentaje'] / 100) * $baseCotizacionDia * $filas8['dias'];} ?></td>-->
                                             <td><?php  if($cont == 1){ echo $baseCotizacionDia * $filas8['dias'] ; }else{ echo round((($itemRetencion['porcentaje'] / 100) * $baseCotizacionDia) * $filas8['dias']) ; $sumActa  = $sumActa + (($itemRetencion['porcentaje'] / 100) * $baseCotizacionDia) * $filas8['dias'];} ?></td>
                                             <td><?php echo $row['salud'] ?></td>
                                             <td><?php echo $row['fecha_activacion'] ?></td>
                                             <td rowspan="8" style="vertical-align:middle;"><h1><?php  if($cont == 1){ echo $row['dia_habil_pago']; } ?></h1></td>
                              
                                        </tr>
                                        <?php if($cont == 4 || $cont == $rowsImpuestos ){ $baseCotizacionTotal =  $row['valor_contrato'] ;  $baseCotizacion = $row['valorDia'] * 30;   $baseCotizacionDia = $row['valorDia'] ; ?>
                                             <tr>
                                                  <td colspan="3" style="text-align:center;"><strong><h3>Total</h3></strong></td>
                                                  <td><strong><?php echo $sumBaseTotal ?></strong></td>
                                                  <td><strong><?php echo $sumBaseMes ?></strong></td>
                                                  <td><strong><?php echo $sumBaseDia ?></strong></td>
                                                  <td></td>
                                                  <td><strong><?php echo $sumActa ?></strong></td>
                                                  <?php if($cont == 4){ ?>
                                                       <input type="hidden" class="form-control" name="valorPlanillaReal" value="<?php echo $sumActa ?>" /> 
                                                  <?php } ?>
               
                                             </tr>
                                        <?php $sumBaseTotal =0; $sumBaseMes =0; $sumBaseDia =0; $sumActa = 0;} ?>  
                                        
                                   <?php $cont++; } ?>  
                              </table>
                         </div>
                    </div>
          <hr>
          <h2 class="text-center blanco ">3.Proyeccion contractual</h2>
          <hr>
          <div class="row">
               <div class="col-md-12">
                    <table class="table">
                         <tr>
                              <th>#</th>
                              <th>Periodo Inicio</th>
                              <th>Periodo Fin</th>
                              <th>Dias</th>
                              <th>Valor Dia</th>
                              <th>Valor</th>
                              <th>Estado</th>
                              <th>Acumulado</th>
                              <th>Saldo</th>
                              <th>Fecha de pago de Planilla</th>
                              <th>Planilla</th>
                              <th>Valor Pagado</th>
                         </tr>
                              <!------------MUESTRA LAS PROYECCIONES ACTUALES--------------------------------------------------------------------------------------------------------------------------------------------------------->
                              <!--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
                              <!--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
                              <?php
                              $cont = 1;
                              while($filas5=mysqli_fetch_array($result5)){
                                   //SOLO HABILITA UNA PROYECCION A LLENAR SEGUN EL CONSECUTIVO DEL INFORME
                                   if($numInforme == $cont){
                              ?>
                                        <tr>
                                             <td><?php echo $cont ?></td>
                                             <td><?php echo $filas5['periodo_ini']?></td>
                                             <!--valor usado para crear la acta-->
                                             <input type="hidden" class="form-control" name="fecha_ini" value="<?php echo $filas5['periodo_ini']?>" /> 
                                             <td><?php echo $filas5['periodo_fin']?></td>
                                             <!--valor usado para crear la acta-->
                                             <input type="hidden" class="form-control" name="fecha_fin" value="<?php echo $filas5['periodo_fin'] ?>" /> 
                                             <td><?php echo $filas5['dias']?></td>
                                             <!--valor usado para crear la acta-->
                                             <input type="hidden" class="form-control" name="diasPagos" value="<?php echo $filas5['dias'] ?>" /> 
                                             <td><?php echo round($filas5['valor_dia'])?></td>
                                             <td><?php echo round($filas5['valor_mes'])?></td>
                                             <!--valor usado para crear la acta-->
                                             <input type="hidden" class="form-control" name="valor" value="<?php echo round($filas5['valor_mes']) ?>" /> 
                                             <td>Pendiente</td>
                                             <td><?php echo round($filas5['acomulado'])?></td>
                                             <!--valor usado para crear la acta-->
                                             <input type="hidden" class="form-control" name="acumulado" value="<?php echo round($filas5['acomulado']) ?>" /> 
                                             <td><?php echo round($filas5['saldo'])?></td>
                                             <!--valor usado para crear la acta-->
                                             <input type="hidden" class="form-control" name="saldo" value="<?php echo round($filas5['saldo']) ?>" /> 
                                             <td><input class="form-control" type="date" name="fechaPlanilla" required></td>
                                             <td><input type="text" class="form-control" placeholder="Código" aria-label="Default select example" name="numPlanilla" required></td>
                                             <td><input type="number" class="form-control" placeholder="$ cantidad" aria-label="Default select example" name="valorPlanilla" required></td>
                                        </tr>
                                   <?php
                                   }else{
                                   ?>
                                        <tr>
                                             <td><?php echo $cont ?></td>
                                             <td><?php echo $filas5['periodo_ini']?></td>
                                             <td><?php echo $filas5['periodo_fin']?></td>
                                             <td><?php echo $filas5['dias']?></td>
                                             <td><?php echo round($filas5['valor_dia'])?></td>
                                             <td><?php echo round($filas5['valor_mes'])?></td>
                                             <td>Pendiente</td>
                                             <td><?php echo round($filas5['acomulado'])?></td>
                                             <td><?php echo round($filas5['saldo'])?></td>
                                             <!--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
                                             <!-------------MUESTRA LA INFORMACION DE LAs PLANILLA YA REALIZADAS-------------------------------------------------------------------------------------------------------------------------------------------------------->
                                             <!--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
                                             <?php
                                             $sinData = true;
                                             for($i=0; $i < count($arrayActa); $i += 4){
                                             //while($filas7=mysqli_fetch_assoc($result7)){
                                                  //if($filas5['acomulado'] == $filas7['acumulado']){
                                                  if($arrayActa[$i] == $filas5['acomulado']){
                                                       $sinData = false;
                                             ?>
                                                       <td><input class="form-control" type="date" value="<?php echo $arrayActa[$i+1] ?>" disabled></td>
                                                       <td><input type="text" class="form-control" placeholder="Código" aria-label="Default select example" value="<?php echo $arrayActa[$i+2] ?>" required disabled></td>
                                                       <td><input type="number" class="form-control" placeholder="$ cantidad" aria-label="Default select example" value="<?php echo $arrayActa[$i+3] ?>" disabled></td>
                                                  <?php
                                                  }
                                                  ?>
                                                       
                                             <?php
                                             }
                                             ?>
                                             <!--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
                                             <?php
                                             //si es verdaderi muestra el apartado de planillas vacias 
                                             if($sinData){
                                             ?>
                                                  <td><input class="form-control" type="date"  disabled></td>
                                                  <td><input type="text" class="form-control" placeholder="Código" aria-label="Default select example"  required disabled></td>
                                                  <td><input type="number" class="form-control" placeholder="$ cantidad" aria-label="Default select example"  disabled></td>
                                             <?php
                                             }
                                             ?>
                                             <!--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
                                             <!--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
                                             <!--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
                                        </tr>
                                   <?php
                                   }
                                   ?>
                              <?php
                              $cont++;
                              }
                              ?>
                              <!--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
                              <!--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
                              <!--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
                    </table>

               </div>
          </div>
          <hr>
          <h2 class="text-center blanco">4.Informe de Actividades</h2>
          <hr>
          <div class="row">
               <h6 class="text-center blanco text-Danger"> <strong>NOTA:</strong> SI NO IMPACTÓ LA ACTIVIDAD NO DILIGENCIAR LA CASILLA EN REGISTRO</h6>
               <div class="col-md-12">
                    <table class="table">
                         <tr>
                              <th>N° Alcance</th>
                              <th>Descripción Alcance </th>
                              <th>Actividades Realizadas</th>
                              <th>Registro</th>
                         </tr>
                              <?php
                              $cont = 1;
                              while($filas6=mysqli_fetch_array($result6)){
                              ?>
                              <tr>
                                   <td><?php echo $cont ?></td>
                                   <td><?php echo $filas6['nombre']?></td>
                                   <input type="hidden" class="form-control" name="idAlcance[]" value="<?php echo $filas6['id'] ?>" />      
                                   <td><textarea  class="form-control" aria-label="With textarea" name="actividad[]" rows="9" cols="200" required></textarea></td>
                                   <td><textarea type="text" class="form-control"  aria-label="Default select example" name="ubicacion[]" rows="12" cols="200"></textarea></td>
                                   </tr>
                              <?php
                              $cont++;
                              }
                              ?>
                    </table>

               </div>
               <div class="form-group col-md-12">
                    <label class="form-label"><h6>Observaciones:</h6></label>
                    <textarea  class="form-control" aria-label="With textarea" name="observaciones" rows="5" cols="70"></textarea>
               </div>
               
          </div>
          
          <hr>
          <h2 class="text-center blanco">5.Novedades</h2>
          <hr>
          <div class="row">
               <div class="col-md-12">
                    <table class="table">
                         <tr>
                              <th>Periodo Inicio</th>
                              <th>Periodo Fin</th>
                              <th>Dias</th>
                              <th>Valor</th>
                              <th>Acumulado</th>
                              <th>Saldo</th>
                              <th>Fecha de pago de Planilla</th>
                              <th>Planilla</th>
                              <th>Valor Pagado</th>
                         </tr>
                              <!------------MUESTRA LAS PROYECCIONES ACTUALES--------------------------------------------------------------------------------------------------------------------------------------------------------->
                              <!--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
                              <!--------------------------------------------------------------------------------------------------------------------------------------------------------------------->

                              <tr>
                                   <td><?php echo $filas8['periodo_ini']?></td>
                                   <td><?php echo $filas8['periodo_fin']?></td>
                                   <td><?php echo $filas8['dias']?></td>
                                   <td><?php echo $filas8['valor_mes']?></td>
                                   <td><?php echo $filas8['acomulado']?></td>
                                   <td><?php echo $filas8['saldo']?></td>
                                   <td><input class="form-control" type="date" value="<?php echo $arrayActa[$i+1] ?>" disabled></td>
                                   <td><input type="number" class="form-control" placeholder="Código" aria-label="Default select example" value="<?php echo $arrayActa[$i+2] ?>" required disabled></td>
                                   <td><input type="number" class="form-control" placeholder="$ cantidad" aria-label="Default select example" value="<?php echo $arrayActa[$i+3] ?>" disabled></td>
                              </tr>

                              <!--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
                              <!--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
                              <!--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
                    </table>


               </div>
               <p>El supervisor certifica que cumplió,durante el periodo del <?php echo $filas8['periodo_ini']?> a <?php echo $filas8['periodo_fin']?> a satisfacción el objeto presente contrato,además
                que ha revisado lo correspondiente a su afiliación al sistema Seguridad Social y ARL y se encuentra al dia durante el mes de  con dichas obligaciones legales.
               De igual forma certifico que el contratista realizo el respaldo magnetico del informe. </p>
               <br>
               <br>
               <br>
               <br>
               <br>
               <br>
               <div class="row g-3">
                    <div class="form-group col-md-6">
                         <hr style="width:50%;">
                         <label class="form-label"><strong><?php echo $NombreContratistas ?></strong></label><br>
                         <label class="form-label">Contratista</label>
                    </div>
               </div>
          </div>
          <!--valor usado para crear la acta-->
          <input type="hidden" class="form-control" name="encargado" value="<?php if($Encargado == "Si"){echo "Si";}else{echo "No";} ?>" /> 
          <input type="hidden" class="form-control" name="nombre" value="<?php echo $NombreContratistas ?>" /> 
          <input type="hidden" class="form-control" name="nombreSupervisor" value="<?php echo $NombreSupervisor ?>" /> 
          <input type="hidden" class="form-control" name="num_informe" value="<?php echo $numInforme ?>" /> 
          <input type="hidden" class="form-control" name="idContrato" value="<?php echo $idContrato ?>" /> 
          <input type="hidden" class="form-control" name="idUsuario" value="<?php echo $row['idUsuario'] ?>" /> 

          <br>
          <br>
          <br>
          <div class="form-group col-md-12" style="text-align:right;">
                <input type="submit" class="btn btn-primary" value="Guardar" />
          </div>
    </form> 
</div>
<br>
<br>
<br>
<br>
<br>
<br>
