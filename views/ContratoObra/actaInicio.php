<?php
//seguridad de sessiones paginacion
session_start();
error_reporting(0);

//si no hay algun usuario registradose devuelve al login
if(!isset($_SESSION['rol'])){
     header("location:../../index.php");
 }else{  
     include('../../db.php');
  //control +k t control + c para comentar codigo y para descomentar es control +k t control + u
     $idContrato = $_GET["id"];
     $NombreContratistas = $_GET["nombre"];
     $NombreSupervisor = $_GET["NombreSupervisor"];
     $fechaActa = $_GET["fechaActa"];

     $arrayUsuario =  $_SESSION['usuario'];
 
     switch($_SESSION['rol']){
         case 1:
             //extraccion información del contrato
             $query= "SELECT * FROM contrato  WHERE contrato.id = $idContrato";
         break;
         case 2:
             //extraccion información del contrato
             $query= "SELECT * FROM contrato  WHERE contrato.id = $idContrato";
         break;
         case 3:
             //extraccion información del contrato
             $query= "SELECT * FROM contrato  WHERE contrato.id = $idContrato and idSupervisor = $arrayUsuario[id]";
         break;
         case 4:
             //extraccion información del contrato
             $query= "SELECT * FROM contrato  WHERE contrato.id = $idContrato and idUsuario = $arrayUsuario[id]";
         break;
         case 5:
             //extraccion información del contrato
             $query= "SELECT * FROM contrato  WHERE contrato.id = $idContrato";
         break;
     } 

     
     $result = mysqli_query($conexion,$query) or die("fallo en la conexións");
     //si la consulta falla y tratan de meterse por otro lado 
     if(!$result ){
          header("location:../../index.php");
     } else{
          $row = mysqli_fetch_array($result);

          //extracción Supervisor
          $query2= "SELECT * FROM usuario  WHERE usuario.id = $row[idSupervisor]";
          $result2 = mysqli_query($conexion,$query2) or die("fallo en la conexión");
     
          $row2 = mysqli_fetch_array($result2);
     
          //extracción contratista
          $query3= "SELECT * FROM usuario  WHERE usuario.id = $row[idUsuario]";
          $result3 = mysqli_query($conexion,$query3) or die("fallo en la conexión");
     
          $row3 = mysqli_fetch_array($result3);
     
          //Extraccion de los registros de adiciones y suspensiones
          $query4= "SELECT * FROM adicion_suspension WHERE idContrato = $idContrato";
          $result4 = mysqli_query($conexion,$query4);
     } 
        
 }










include("../partials/layout.php");
include('../partials/menusub.php');

?>



<div class="container" style="text-align:right;padding:5px;">
     <a href="listar.php" class="btn btn-primary">Volver</a>
</div>
<br>
<div class="container decorado" style="background: #e0e0e0;border-radius: 25px;">
    <form class="row g-3" action="insertarActa.php" method="post">
          <br>
          <h1 class="text-center blanco">Acta de inicio : <?php echo $NombreContratistas ?></h1>
          <hr>
          <hr>
          <h2 class="text-center blanco">Informe de actividades</h2>
          <hr>
          <div class="row g-3">
                    <div class="form-group col-md-4">
                              <label for="fecha_fin" class="form-label"><h3>Fecha de Acta</h3></label>
                              <input class="form-control" style="width:50%;" type="date" name="fecha_informe" value="<?php echo $fechaActa?>" disabled>
                    </div>
                    <div class="form-group col-md-4">
                         <label class="form-label"><h3>Acta N°</h3></label>
                         <input type="number" class="form-control" style="width:20%;"  value="1" readonly>
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
               <div class="form-group col-md-3">
                    <label class="form-label"><h3>Supervisor</h3></label>
                    <input type="text" class="form-control"  value="<?php echo $NombreSupervisor?>" readonly>
                    <!--valor usado para crear la acta-->
                    <input type="hidden" class="form-control" name="idSupervisor" value="<?php echo $row2['id'] ?>" />  
               </div>
               <div class="form-group col-md-2">
                    <label class="form-label"><h3>Identificacion</h3></label>
                    <input type="number" class="form-control"  value="<?php echo $row2['cedula'] ?>" readonly>
               </div>
               <div class="form-group col-md-2">
                    <label class="form-label"><h3>Cargo</h3></label>
                    <input type="text" class="form-control"  value="<?php echo $row2['profesion'] ?>" readonly>
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
                    <input type="text" class="form-control"  value="<?php echo $row2['direccion'] ?>" readonly>
               </div>
               <!--------------------------------------------------------------------------->
               <div class="form-group col-md-3">
                    <label class="form-label"><h3>Tipo de persona </h3></label>
                    <input type="text" class="form-control"  value="<?php echo $row2['tipo_persona'] ?>" readonly>
               </div>
               <div class="form-group col-md-3">
                    <label class="form-label"><h3>Responsable de iva</h3></label>
                    <input type="text" class="form-control"  value="<?php echo $row2['resp_iva'] ?>" readonly>
               </div>
               <div class="form-group col-md-2">
                    <label class="form-label"><h3>Telefono</h3></label>
                    <input type="text" class="form-control"  value="<?php echo $row2['telefono'] ?>" readonly>
               </div>
               <div class="form-group col-md-4">
                    <label class="form-label"><h3>Correo</h3></label>
                    <input type="text" class="form-control"  value="<?php echo $row2['correo'] ?>" readonly>
               </div>
               <!--------------------------------------------------------------------------->
               <div class="form-group col-md-3">
                    <label class="form-label"><h3>Duración</h3></label>
                    <input type="number" class="form-control"  value="<?php echo $row['duracion'] ?>" readonly>
               </div>
               <!--la clase currency se creo por mi, sirve para usar la libreria de divisa todo esta en la carpeta js-->
               <!--el script esta en la carpeta public/js/main.js-->
               <div class="form-group col-md-4">
                    <label class="form-label"><h3>Valor</h3></label>
                    <input type="text" class="form-control currency" value="<?php echo number_format(intval($row['valor_contrato'])) ?>" readonly>
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
                                             <td><?php echo $filas4['valor']?></td>
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
                                   <li class="list-group-item "><Strong>Observación: </Strong><?php echo $row['observaciones']?></li>
                              </ul>
                         </div>


               </div>
         
          <hr>
               <p>El <?php echo $fechaActa ?> en el municipio de Dosquebradas Risaralda se reunieron en las oficionas del Instituto de Desarrollo Municipal de Dosquebradas
                IDM el supervisor <?php echo $NombreSupervisor?> Y el/ella contratista <?php echo $NombreContratistas ?> con el fin de iniciar las obras y/o actividades correspondientes al contrato de la referencia, lo anterior se motiva en las siguientes consideraciones:
                </p>
                <p>1. El contatista entegó completa la documentación requerida para el inicio del contrato,incluyendo la afilación a ARL.</p>
                <p>2. El contatista recibió la induccción repectiva para la ejecución de sus actividades, así como del sistema integral de gestión.</p>
               <br>
               <br>
               <br>
               <br>
               <br>
               <br>
               <div class="row g-3">
                    <div class="form-group col-md-6">
                        <hr style="width:50%;">
                         <label class="form-label"><strong><?php echo $NombreSupervisor ?></strong></label><br>
                         <label class="form-label">Supervisor</label>
                    </div>
                    <div class="form-group col-md-6">
                         <hr style="width:50%;">
                         <label class="form-label"><strong><?php echo $NombreContratistas ?></strong></label><br>
                         <label class="form-label">Contratista</label>
                    </div>
               </div>
          </div>
          <!--valor usado para crear la acta-->
          <input type="hidden" class="form-control" name="num_informe" value="<?php echo $numInforme ?>" /> 
          <input type="hidden" class="form-control" name="idContrato" value="<?php echo $idContrato ?>" /> 
          <br>
          <br>
          <br>
    </form> 
</div>
<br>
<br>
<div class="container">
     <div class="form-group col-md-12" style="text-align:right;">
          <a href="generarPdf.php?id=<?php echo $idContrato  ?>&nombre=<?php echo $NombreContratistas ?>&NombreSupervisor=<?php echo $NombreSupervisor ?>&fechaActa=<?php echo $fechaActa ?>" class="btn btn-danger">Generar Pdf</a>
     </div>
</div>
<br>
<br>
<br>
<br>