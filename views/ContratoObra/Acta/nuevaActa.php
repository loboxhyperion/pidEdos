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
     $numInforme = $_GET['num_informe'];;
     $minimoMensual = 1000000;
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

}

include("../../partials/layout.php");
include('../../partials/menusub.php');



//Extracción de las actuales actas 
$query7= "SELECT * FROM acta_obra  WHERE idContrato = $idContrato ";
$result7 = mysqli_query($conexion,$query7) or die("fallo en la conexión 4");

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
                    <label class="form-label"><h3>Fecha de Inicio</h3></label>
                    <input type="text" class="form-control"  value="<?php echo $row['fecha_ini'] ?>" readonly>
               </div>
               <div class="form-group col-md-2">
                    <label class="form-label"><h3>Fecha de Fin</h3></label>
                    <input type="text" class="form-control"  value="<?php echo $row['fecha_fin'] ?>" readonly>
               </div>
               <div class="form-group col-md-3">
                    <label class="form-label"><h3>Duración</h3></label>
                    <input type="number" class="form-control"  value="<?php echo $row['duracion'] ?>" readonly>
               </div>
               <div class="form-group col-md-2">
                    <label class="form-label"><h3>Valor</h3></label>
                    <input type="text" class="form-control"  value="<?php echo number_format($row['valor_contrato'] ,0,".",",")?>" readonly>
               </div>
               <div class="form-group col-md-2">
                    <label class="form-label"><h3>Anticipo</h3></label>
                    <input type="text" class="form-control"  value="<?php echo $row['anticipo']."%" ?>" readonly>
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
          <h2 class="text-center blanco ">2.Proyeccion contractual</h2>
          <hr>
          <div class="row">
               <div class="col-md-12">
                    <table class="table">
                         <tr>
                              <th>Acta</th>
                              <th>Periodo Inicio</th>
                              <th>Periodo De Corte</th>
                              <th>Valor Del Acta</th>
                              <th>Anticipo</th>
                              <th>Amortización</th>
                              <th>Valor Pagado</th>
                              <th>Saldo</th>
                         </tr>
                         
                         <?php
                              if($numInforme == 1){
                         ?>
                                   <tr>
                                        <td><h3><?php echo "1" ?></h3></td>
                                        <td><input class="form-control" type="date" name="fecha_corte" required></td>
                                        <td><input class="form-control" type="date"></td>
                                        <td><input class="form-control valor" type="number" placeholder="$ cantidad" name="valorBruto" required></td>
                                        <!--Anticipo-->
                                        <input type="hidden" class="form-control anticipo" name="anticipo" value="<?php echo $row['anticipo']?>" /> 
                                        <td><h3><?php echo $row['anticipo']."%" ?></h3></td>
                                        <!--Amortizacion-->
                                        <td><input class="form-control abono" type="number" name="amortizacion" readonly></td>
                                        <!--Valor Pagado-->
                                        <td><input class="form-control pagado" type="number" name="valorPagado" readonly></td>             
                                        <!--Saldo-->
                                        <input type="hidden" class="form-control total" value="<?php echo $row['valor_contrato']?>" /> 
                                        <td><input class="form-control saldo" type="number" name="saldo" readonly></td> 
                                         
                                   </tr>
                         <?php
                              }else{
                         ?>
                                    <!------------MUESTRA LAS PROYECCIONES ACTUALES--------------------------------------------------------------------------------------------------------------------------------------------------------->
                                   <!--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
                                   <!--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
                                   <?php
                                   $cont = 1;
                                   while($filas7=mysqli_fetch_array($result7)){
                                        //SOLO HABILITA UNA PROYECCION A LLENAR SEGUN EL CONSECUTIVO DEL INFORME
                                        if($numInforme == $cont){
                                   ?>
                                              <tr>
                                                  <td><h3><?php echo "1" ?></h3></td>
                                                  <td><input class="form-control" type="date" name="fecha_corte" required></td>
                                                  <td><input class="form-control" type="date"></td>
                                                  <td><input class="form-control valor" type="number" placeholder="$ cantidad" name="valorBruto" required></td>
                                                  <!--Anticipo-->
                                                  <input type="hidden" class="form-control anticipo" name="anticipo" value="<?php echo $row['anticipo']?>" /> 
                                                  <td><h3><?php echo $row['anticipo']."%" ?></h3></td>
                                                  <!--Amortizacion-->
                                                  <td><input class="form-control abono" type="number" name="amortizacion" readonly></td>
                                                  <!--Valor Pagado-->
                                                  <td><input class="form-control pagado" type="number" name="valorPagado" readonly></td>             
                                                  <!--Saldo-->
                                                  <input type="hidden" class="form-control total" value="<?php echo $saldoPre ?>" /> 
                                                  <td><input class="form-control saldo" type="number" name="saldo" readonly></td>   
                                             </tr>
                                        <?php
                                        }else{
                                        ?>
                                             <tr>                                           
                                                  <!--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
                                                  <!-------------MUESTRA LA INFORMACION DE LAs ACTAS YA REALIZADAS-------------------------------------------------------------------------------------------------------------------------------------------------------->
                                                  <!--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
                                                  
                                                  
                                                  <td><?php echo $cont ?></td>
                                                  <td><?php echo $filas7['fecha_corte'] ?></td>
                                                  <td><?php echo $filas7['fecha_informe'] ?></td>
                                                  <td><?php echo round($filas7['valorBruto']) ?></td>
                                                  <td><?php echo $filas7['anticipo']."%" ?></td>
                                                  <td><?php echo round($filas7['amortizacion']) ?></td>
                                                  <td><?php echo round($filas7['valorPagado']) ?></td>
                                                  <td><?php echo round($filas7['saldo']); $saldoPre = $filas7['saldo']?></td>
                                   
                                                  
                                                  <!--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
                                                  <!--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
                                                  <!--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
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
                         <?php
                              }
                         ?>
        
                             
                    </table>

               </div>
          </div>
          <hr>

          <div class="form-group col-md-12">
               <label class="form-label"><h6>Observaciones:</h6></label>
               <textarea  class="form-control" aria-label="With textarea" name="observaciones" rows="5" cols="70"></textarea>
          </div>
               
          
          <hr>
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
                <br>
                <br>
          </div>
    </form> 
</div>
<br>
<br>
<br>
<br>
<br>
<br>
<script>
     // Sample 2
     $(document).ready(function()
     {
          //evento que ocurre cuando se pierde focus
          $('.valor').keyup(function()
          { 
               var amortizacion = $('.valor').val() * ($('.anticipo').val()/100);
               var valorPagado = $('.valor').val() - amortizacion;
               $('.abono').val(amortizacion);
               $('.pagado').val(valorPagado );
               $('.saldo').val($('.total').val() - $('.valor').val() );

               // $('.resultado').val($('.valor').val())
          //$('.currency').formatCurrency();
          });
     });
//cada que se seleciona algo en el select el valor del input con nombre de impuesto cambia
//permite así traer tambien el nombre del riesgo
function actualizarValor(option){
    //obtiene el texto del option en el select
   var textoValor=option.options[option.selectedIndex].text;
   textoValor = textoValor.replace(/,/g, "");
   //document.contrato.nombreImpuesto.value = textoRiesgo;
   $('.currency').val(textoValor);
}
</script>