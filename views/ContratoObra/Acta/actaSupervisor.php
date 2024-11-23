<?php
//seguridad de sessiones paginacion
session_start();
error_reporting(0);
if(!isset($_SESSION['rol'])){
     header("location:../../../../index.php");
}else{ 
     $idContrato = $_GET["id"];
     $NombreContratistas = $_GET["nombre"];
     $NombreSupervisor = $_GET["NombreSupervisor"];
     $numInforme = $_GET["num_informe"];
     $fechaInforme = $_GET["fecha_informe"];
     $idActa = $_GET["idActa"];
     $minimoMensual = 908526;
     $minimoDia = $minimoMensual /30;

     include('../../../db.php');
     //extraccion información del contrato
     $query= "SELECT * FROM contrato  WHERE contrato.id = $idContrato";
     $result = mysqli_query($conexion,$query) or die("fallo en la conexión");

     $row = mysqli_fetch_array($result);

     //extracción Supervisor
     $query2= "SELECT * FROM usuario  WHERE usuario.id = $row[idSupervisor]";
     $result2 = mysqli_query($conexion,$query2) or die("fallo en la conexión");

     $row2 = mysqli_fetch_array($result2);

     
      //Extracción del acta actual para comprobar si se realizo por un encargado o no
      $query10= "SELECT encargado FROM acta  WHERE idContrato = $idContrato AND num_informe = $numInforme ";
      $result10 = mysqli_query($conexion,$query10) or die("fallo en la conexión acta ");
      $rowEncargado = mysqli_fetch_array($result10);

     //Si vacaciones y el acta estan en Si recalcula la consulta con los datos del  encargado
     if($row2["vacaciones"] == "Si" && $rowEncargado["encargado"] == "Si"){
          $fecha_ini_vaca = $row2["fecha_ini"];
          $fecha_fin_vaca = $row2["fecha_fin"];
          $query2= "SELECT * FROM usuario  WHERE usuario.id = $row2[idEncargado]";
          $result2 = mysqli_query($conexion,$query2) or die("fallo en la conexión supervisor");
          $row2 = mysqli_fetch_array($result2);
     }

     //extracción contratista
     $query3= "SELECT * FROM usuario  WHERE usuario.id = $row[idUsuario]";
     $result3 = mysqli_query($conexion,$query3) or die("fallo en la conexión");

     $row3 = mysqli_fetch_array($result3);

     //Extraccion de los registros de adiciones y suspensiones
     $query4= "SELECT * FROM adicion_suspension WHERE idContrato = $idContrato";
     $result4 = mysqli_query($conexion,$query4);


     //extraccion proyeccion
     $query5= "SELECT * FROM proyeccion_contractual WHERE idContrato = $idContrato and prioridad = 1";
     $result5 = mysqli_query($conexion,$query5);

     include("../../partials/layout.php");
     include('../../partials/menusub.php');

     //Extraccion de los alcances
     $query6= "SELECT * FROM alcance WHERE idContrato = $idContrato";
     $result6 = mysqli_query($conexion,$query6);

     //Extracción de las actuales actas 
     $query7= "SELECT * FROM acta  WHERE idContrato = $idContrato AND num_informe <= $numInforme ";
     $result7 = mysqli_query($conexion,$query7) or die("fallo en la conexión ");
     $arrayActa = array();
     $arrayIdActas = array(); // para usarse en donde se impacto las actas las x



     //Guarda los datos de las plantillas pagas y el acumulado del las actas creadas 
     //sirve para mostrar en la proyeccion las actas que ya se han hecho
     while($filas7=mysqli_fetch_assoc($result7)){
          $arrayIdActas[] = $filas7['id'];
          $arrayActa[] = $filas7['acumulado'];
          $arrayActa[] = $filas7['fechaPlanilla'];
          $arrayActa[] = $filas7['numPlanilla'];
          $arrayActa[] = $filas7['valorPlanilla'];
     }
     /*for($i=0; $i < count($arrayActa); $i += 4){
          echo "<br>". $arrayActa[$i];
     }*/
     /*for($i=0;$i<=count($arrayIdActas);$i++){
          echo "<br>". $arrayIdActas[$i];
     }*/
     //Extracción de la proyeccion actual  correspondiente al acta a pagar
     $query8= "SELECT * FROM proyeccion_contractual WHERE idContrato = $idContrato and num_acta = $numInforme ";
     $result8 = mysqli_query($conexion,$query8) or die("fallo en la conexión");
     $filas8 = mysqli_fetch_array($result8);

     //Extracción del  actual acta hecha 
     $query9= "SELECT * FROM acta  WHERE id = $idActa  ";
     $result9 = mysqli_query($conexion,$query9) or die("fallo en la conexión acta");
     $filas9 = mysqli_fetch_array($result9);
}

?>


<div class="container" style="text-align:right;padding:5px;">
     <a href="listarActas.php?id=<?php echo $idContrato ?>&nombre=<?php echo $NombreContratistas ?>&NombreSupervisor=<?php echo $NombreSupervisor ?>" class="btn btn-primary">Volver</a>
</div>
<br>
<div class="container decorado" style="background: #e0e0e0;border-radius: 25px;">
          <br>
          <h1 class="text-center blanco">Acta parcial de pagos : <?php echo $NombreContratistas ?></h1>
          <hr>
          <div class="row g-3">
                    <div class="form-group col-md-4">
                              <label for="fecha_fin" class="form-label"><h3>Fecha de informe</h3></label>
                              <input class="form-control" style="width:50%;" type="date" name="fecha_informe" value="<?php echo $fechaInforme ?>" disabled>
                    </div>
                    <div class="form-group col-md-4">
                         <label class="form-label"><h3>N° del informe</h3></label>
                         <input type="number" class="form-control" style="width:20%;"  value="<?php echo $numInforme ?>" readonly>
                    </div>
                    <div class="form-group col-md-4">
                         <label class="form-label"><h3>Modalidad Contractual</h3></label>
                         <input type="text" class="form-control" style="width:100%;" value="<?php echo $row['modalidad'] ?>" readonly>
                    </div>
          </div>
          <hr>
          <h2 class="text-center blanco">1.Datos Contractuales</h2>
          <hr>
          <div class="row g-3">
               <div class="form-group col-md-2">
                    <label class="form-label"><h3>Registro PPTAL</h3></label>
                    <input type="number" class="form-control"  value="<?php echo $row['registro_pptal'] ?>" readonly>
               </div>
               <div class="form-group col-md-2">
                    <label class="form-label"><h3>CDP</h3></label>
                    <input type="number" class="form-control"  value="<?php echo $row['disp_presupuestal'] ?>" readonly>
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
                    <input type="text" class="form-control"  value="LUIS ERNESTO VALENCIA RAMIREZ" readonly>
               </div>
               <div class="form-group col-md-4">
                    <label class="form-label"><h3>Identificacion</h3></label>
                    <input type="text" class="form-control"  value="18510078" readonly>
               </div>
               <div class="form-group col-md-4">
                    <label class="form-label"><h3>Cargo</h3></label>
                    <input type="text" class="form-control"  value="Directo general" readonly>
               </div>
               <!--------------------------------------------------------------------------->
               <!--------------------------------SI  ESTA DE VACACIONES EL INFORME SE LLENA CON LA INFORMACION DEL DEL SUPERVISOR ENCARGADO------------------------------------------->
               <div class="form-group col-md-3">
                    <label class="form-label"><h3>Supervisor <?php if($rowEncargado["encargado"] == "Si")echo "Encargado"?></h3></label>
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
          
          <h2 class="text-center blanco">2.Informe de Actividades</h2>
          <hr>
          <div class="row">
               <div class="col-md-12">
                    <table class="table">
                         <tr>
                              <th>N° Alcance</th>
                              <th>Descripción Alcance </th>
                              <th>1</th>
                              <th>2</th>
                              <th>3</th>
                              <th>4</th>
                              <th>5</th>
                              <th>6</th>
                              <th>7</th>
                              <th>8</th>
                              <th>9</th>
                              <th>10</th>
                              <th>11</th>
                              <th>12</th>
                         </tr>
                              <?php
                               $cont = 1;
                               while($filas6=mysqli_fetch_array($result6)){
                              ?>
                                   <tr>
                                        <td><?php echo $cont ?></td>
                                        <td><?php echo $filas6['nombre']?></td> 
                                        <?php
                                        //Le da continuidad para que los alcances impactados con x vaya linea y no haya saltos en las actas
                                         $posActa = 1;
                                         for($i=0;$i<count($arrayIdActas);$i++){
                                             
                                             //Extracción de las actividades actuales por acta
                                             $query10 = "SELECT * FROM actividad  WHERE idActa = $arrayIdActas[$i] AND IdAlcance = $filas6[id]";
                                             $result10 = mysqli_query($conexion,$query10) or die("fallo en la conexión".$i);
                                             $filas10 = mysqli_fetch_array($result10);
                                             // llena los espacios de las actas impactadas con x
                                             for($j=$posActa ; $j <= 12; $j++){
                                                  if($filas10['descripcion'] <> "" && $filas10['numInforme'] == $j){
                                                       if($filas10['ubicacion'] <> ""){
                                        ?>             
                                                            <td>X</td>

                                                  <?php
                                                       }else{
                                                  ?>
                                                            <td></td>
                                                  <?php
                                                       }
                                                  $posActa++;
                                                  break;
                                                  }else{
                                                  ?>
                                                       <td></td>
                                                  <?php
                                                  }
                                                  ?>
                                        <?php
                                             }
                                        }
                                        ?>
                                        
                                   </tr>
                              <?php
                               $cont++;
                               }
                              ?>
                    </table>

               </div>
          </div>
          
          <hr>
          <h2 class="text-center blanco">3.Novedades</h2>
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
                                   <td><?php echo $filas9['fecha_ini']?></td>
                                   <td><?php echo $filas9['fecha_fin']?></td>
                                   <td><?php echo $filas9['diasPagos']?></td>
                                   <td><?php echo $filas9['valor']?></td>
                                   <td><?php echo $filas9['acumulado']?></td>
                                   <td><?php echo $filas9['saldo']?></td>
                                   <td><input class="form-control" type="date" value="<?php echo $filas9['fechaPlanilla'] ?>" disabled></td>
                                   <td><input type="number" class="form-control" placeholder="Código" aria-label="Default select example" value="<?php echo $filas9['numPlanilla'] ?>" required disabled></td>
                                   <td><input type="number" class="form-control" placeholder="$ cantidad" aria-label="Default select example" value="<?php echo $filas9['valorPlanilla'] ?>" disabled></td>
                              </tr>

                              <!--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
                              <!--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
                              <!--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
                    </table>

               </div>
               <p>El supervisor certifica que cumplió,durante el periodo del <?php echo $filas9['fecha_ini']?> al <?php echo $filas9['fecha_fin']?> a satisfacción el objeto presente contrato,además
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
                         <label class="form-label"><strong><?php echo $row2['nombre'] ." ". $row2['apellidos']  ?></strong></label><br>
                         <label class="form-label">Supervisor <?php if($rowEncargado["encargado"] == "Si")echo "Encargado"." <strong>desde: </strong>".$fecha_ini_vaca." <strong>hasta: </strong>".$fecha_fin_vaca?></label>
                    </div>
               </div>
          </div>
          <br>
          <br>
          <br>
         
</div>
<br>
<br>
<div class="container">
     <div class="form-group col-md-12" style="text-align:right;">
     <a href="generarPdfSupervisor.php?id=<?php echo $idContrato  ?>&nombre=<?php echo $NombreContratistas ?>&NombreSupervisor=<?php echo $NombreSupervisor ?>&numInforme=<?php echo $numInforme ?>&fechaInforme=<?php echo $fechaInforme ?>&idActa=<?php echo $idActa ?>" class="btn btn-danger">Generar Pdf</a>
     </div>
</div>
<br>
<br>
<br>
<br>