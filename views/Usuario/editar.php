<?php 

//seguridad de sessiones paginacion
session_start();
error_reporting(0);

//si no hay algun usuario registradose devuelve al login
if(!isset($_SESSION['rol'])){
    header("location:../../index.php");
}else{   
    //solo tiene permiso el admin y creador
    /*if($_SESSION['rol'] == 3 or $_SESSION['rol'] == 4  ){
        header("location:../../index.php");
    }*/
}

$idUsuario = $_GET['id'];

include('../../db.php');

$query= "SELECT * FROM usuario  WHERE id = $idUsuario";
$result = mysqli_query($conexion,$query) or die("fallo en la conexión");

$filas = mysqli_fetch_array($result);

include("../partials/layout.php"); 
include('../partials/menusub.php');
?>

<div class="container">
    <div class="" style="text-align:right;padding:5px;">
        <a href="listar.php" class="btn btn-primary">Volver</a>
        <hr>
        <h1 class="text-center blanco">Editar Perfil</h1>
        <hr>
    </diV>
    <div class="col-md-12">
        <form class="row g-3" action="actualizar.php" method="post">
            <div class="form-group col-md-2">
                <label for="usuario" class="form-label">Usuario</label>
                <input type="text" class="form-control" placeholder="Nombre de usuario" aria-label="usuario" name="usuario" value="<?php echo $filas['usuario'] ?>" required>
            </div>
            <br>
            <div class="form-group col-md-2">
                <label for="contraseña" class="form-label">Contraseña</label>
                <input type="text" class="form-control" placeholder="Digite la contraseña" aria-label="contraseña" name="contraseña" value="<?php echo $filas['clave'] ?>" required>
            </div>
            <div class="form-group col-md-2">
                <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                <input class="form-control" type="date" name="fecha_nacimiento" value="<?php echo $filas['fecha_nacimiento']?>" required>
            </div>
            <input type="hidden" class="form-control" aria-label="contraseña" name="idRol" value="<?php echo $filas['idRol'] ?>">
            <br>
            <div class="form-group col-md-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" placeholder="Nombre" aria-label="nombre" name="nombre" value="<?php echo $filas['nombre'] ?>" required>
            </div>
            <br>
            <div class="form-group col-md-3">
                <label for="apellidos" class="form-label">Apellidos</label>
                <input type="text" class="form-control" placeholder="Apellidos" aria-label="apellidos" name="apellidos" value="<?php echo $filas['apellidos'] ?>" required >
            </div>
            <br>
            <div class="form-group col-md-2">
                <label for="resp_iva" class="form-label">Genero</label>
                <select class="form-select" aria-label="Default select example" name="genero" required>
                    <option value="">Seleccionar</option>
                    <?php
                        if($filas['genero'] == "Hombre" ){
                    ?>
                            <option value="<?php echo $filas['genero']?>" selected><?php echo $filas['genero'] ?></option>
                            <option value="Mujer">Mujer</option>
                    <?php
                        }elseif($filas['genero'] == "Mujer" ){
                    ?>
                            <option value="Hombre">Hombre</option>
                            <option value="<?php echo $filas['genero']?>" selected><?php echo $filas['genero'] ?></option>
                    <?php
                        }
                    ?>
                </select>
            </div>
            <br>
            <div class="form-group col-md-2">
                <label for="cedula" class="form-label">Cedula</label>
                <input type="number" class="form-control" placeholder="Número de cedula" aria-label="cedula" name="cedula" value="<?php echo $filas['cedula'] ?>" required>
            </div>
            <br>
            <div class="form-group col-md-2">
                <label for="telefono" class="form-label">Telefono</label>
                <input type="text" class="form-control" placeholder="Número de telefono" aria-label="telefono" name="telefono" value="<?php echo $filas['telefono'] ?>" required>
            </div>
            <br>
            <div class="form-group col-md-3">
                <label for="correo" class="form-label">Correo</label>
                <input type="text" class="form-control" placeholder="Correo electronico" aria-label="correo" name="correo" value="<?php echo $filas['correo'] ?>" required> 
            </div>
            <br>
            <div class="form-group col-md-3">
                <label for="direccion" class="form-label">Direccion</label>
                <input type="text" class="form-control" placeholder="Direccion" aria-label="direccion" name="direccion" value="<?php echo $filas['direccion'] ?>" required>
            </div>
            <br>
            <div class="form-group col-md-6">
                <label for="profesion" class="form-label">Profesión</label>
                <input type="text" class="form-control" placeholder="Profesión" aria-label="profesion" name="profesion" value="<?php echo $filas['profesion'] ?>" required>
            </div>
            <br>
            <div class="form-group col-md-6">
                <label for="cargo" class="form-label">Cargo</label>
                <input type="text" class="form-control" placeholder="cargo" aria-label="cargo" name="cargo" value="<?php echo $filas['cargo'] ?>" required>
            </div>
            <br>
            <div class="form-group col-md-2">
                <label for="rut" class="form-label">RUT</label>
                <input type="text" class="form-control" placeholder="Número de rut" aria-label="rut" name="rut" value="<?php echo $filas['rut'] ?>" required>
            </div>
            <br>
            <div class="form-group col-md-2">
                <label for="actividad_e" class="form-label">Actividad Económica</label>
                <input type="text" class="form-control" placeholder="Escriba aquí" aria-label="actividad_e" name="actividad_e" value="<?php echo $filas['actividad_e'] ?>" required>
            </div>
            <br>
            <div class="form-group col-md-2">
                <label for="banco" class="form-label">Banco</label>
                <input type="text" class="form-control" placeholder="Nombre" aria-label="banco" name="banco" value="<?php echo $filas['banco'] ?>" required>
            </div>
            <br>
            <div class="form-group col-md-3">
                <label for="nro_cuenta" class="form-label">Número de Cuenta</label>
                <input type="text" class="form-control" placeholder="Número" aria-label="nro_cuenta" name="nro_cuenta" value="<?php echo $filas['nro_cuenta'] ?>" required>
            </div>
            <br>
            <div class="form-group col-md-3">
                <label for="tipo_cuenta" class="form-label">Tipo de Cuenta</label>
                <select class="form-select" aria-label="Default select example" name="tipo_cuenta" required>
                    <option value="">Seleccionar</option>
                    <?php
                    if ($filas['tipo_cuenta'] == "Ahorros") {
                    ?>
                        <option value="<?php echo $filas['tipo_cuenta'] ?>" selected><?php echo $filas['tipo_cuenta'] ?></option>
                        <option value="Corriente">Corriente</option>
                    <?php
                    } elseif ($filas['tipo_cuenta'] == "Corriente") {
                    ?>
                        <option value="Ahorros">Ahorros</option>
                        <option value="<?php echo $filas['tipo_cuenta'] ?>" selected><?php echo $filas['tipo_cuenta'] ?></option>
                    <?php
                        }else{
                    ?>
                        <option value="Ahorros">Ahorros</option>
                        <option value="Corriente">Corriente</option>
                    <?php  
                        }
                    ?>
                </select>
            </div>
            <br>
            <div class="form-group col-md-6">
                <label for="tipo_persona" class="form-label">Tipo de persona</label>
                <select class="form-select" aria-label="Default select example"name="tipo_persona" required>
                    <option value="">Seleccionar</option>
                    <?php
                        if($filas['tipo_persona'] == "Natural" ){
                    ?>
                            <option value="<?php echo $filas['tipo_persona']?>" selected><?php echo $filas['tipo_persona'] ?></option>
                            <option value="Jurídica">Jurídica</option>
                    <?php
                        }elseif($filas['tipo_persona'] == "Juridica" ){
                    ?>
                            <option value="Natural">Natural</option>
                            <option value="<?php echo $filas['tipo_persona']?>" selected><?php echo $filas['tipo_persona'] ?></option>
                    <?php
                        }
                    ?>
                </select>
            </div>
            <br>
            <div class="form-group col-md-6">
                <label for="resp_iva" class="form-label">Responsable de iva</label>
                <select class="form-select" aria-label="Default select example" name="resp_iva" required>
                    <option value="">Seleccionar</option>
                    <?php
                        if($filas['resp_iva'] == "Si" ){
                    ?>
                            <option value="<?php echo $filas['resp_iva']?>" selected><?php echo $filas['resp_iva'] ?></option>
                            <option value="No">No</option>
                    <?php
                        }elseif($filas['resp_iva'] == "No" ){
                    ?>
                            <option value="Si">Si</option>
                            <option value="<?php echo $filas['resp_iva']?>" selected><?php echo $filas['resp_iva'] ?></option>
                    <?php
                        }
                    ?>
                </select>
            </div>
            <br>
            <?php
                //Solo admin y creadores
                if($_SESSION['rol'] == 1 or $_SESSION['rol'] == 3){
            ?>
                <div class="form-group col-md-3">
                    <label for="resp_iva" class="form-label">Vacaciones</label>
                    <select class="form-select" id="vacaciones" aria-label="Default select example" name="vacaciones"  onchange="ActivarVacaciones(this)">
                        <option value="">Seleccionar</option>
                        <?php
                            if($filas['vacaciones'] == "Si" ){
                        ?>
                                <option value="<?php echo $filas['vacaciones']?>" selected><?php echo $filas['vacaciones'] ?></option>
                                <option value="No">No</option>
                        <?php
                            }elseif($filas['vacaciones'] == "No" ){
                        ?>
                                <option value="Si">Si</option>
                                <option value="<?php echo $filas['vacaciones']?>" selected><?php echo $filas['vacaciones'] ?></option>
                        <?php
                            }
                        ?>
                    </select>
                </div>
                <br>
                <div class="form-group col-md-3 encargado" style="visibility:hidden">
                    <label for="fecha_ini" class="form-label">Inicio de vacaciones</label>
                    <input class="form-control" type="date" name="fecha_ini" value="<?php echo $filas['fecha_ini']?>">
                </div>
                <br>
                <div class="form-group col-md-3 encargado" style="visibility:hidden">
                    <label for="fecha_fin" class="form-label">Fin de vacaciones</label>
                    <input class="form-control" type="date" name="fecha_fin" value="<?php echo $filas['fecha_fin']?>" >
                </div>
                <div class="form-group col-md-3 encargado" style="visibility:hidden">
                    <label for="idSupervisor" class="form-label">Supervisor Encargado</label>
                    <select class="form-select" aria-label="Default select example" name="idEncargado">
                        <option value="">Seleccionar</option>
                        <?php
                            $consulta= "SELECT * FROM usuario WHERE idRol = 3 AND id != $idUsuario";
                            $resultado = mysqli_query($conexion,$consulta);

                            while($filas2=mysqli_fetch_array($resultado)){
                                if($filas['idEncargado'] == $filas2['id'] ){
                        ?>
                                    <option value="<?php echo $filas2['id']?>" selected><?php echo $filas2['nombre'].' '.$filas2['apellidos']?></option>
                        <?php   
                                }else{
                        ?>
                                    <option value="<?php echo $filas2['id']?>"><?php echo $filas2['nombre'].' '.$filas2['apellidos']?></option>
                        <?php
                                }
                            }
                        ?>
                    </select>
                </div>
            <?php     
                }
            ?>
            <input type="hidden" name="id" value="<?php echo $filas['id'] ?>">
            <div class="form-group col-md-12" style="text-align:right;">
                <input type="submit" class="btn btn-primary" value="Guardar" />
            </div>
            <br>
            <br>
        </form>
        
    </div>
</div>
<script>
//Obtiene el valor de las vaciones 
var estadoVacaciones = document.getElementById("vacaciones").value;
//para verificar si muestra los campos o no
MostrarCampos(estadoVacaciones);

//cada que se seleciona algo en el select el valor del input con nombre de impuesto cambia
//permite así traer tambien el nombre del riesgo
function ActivarVacaciones(option){
    //obtiene el texto del option en el select
   var textoRiesgo=option.options[option.selectedIndex].text; 
   MostrarCampos(textoRiesgo);
}

function MostrarCampos(textoRiesgo){
     //Muestra los campos del encargado
   if(textoRiesgo == "Si"){
    document.getElementsByClassName("encargado")[0].style.visibility = "visible";
    document.getElementsByClassName("encargado")[1].style.visibility = "visible";
    document.getElementsByClassName("encargado")[2].style.visibility = "visible";
   }else{//Oculta los campos del encargado
    document.getElementsByClassName("encargado")[0].style.visibility = "hidden";
    document.getElementsByClassName("encargado")[1].style.visibility = "hidden";
    document.getElementsByClassName("encargado")[2].style.visibility = "hidden";
   }
   //document.contrato.nombreImpuesto.value = textoRiesgo ;
}
</script>