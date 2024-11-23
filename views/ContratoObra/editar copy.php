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
$idContrato = $_GET["id"];

include('../../db.php');
include("../partials/layout.php");

//extraccion información del contrato
$query= "SELECT * FROM contrato  WHERE contrato.id = $idContrato";
$result = mysqli_query($conexion,$query) or die("fallo en la conexión");
$row = mysqli_fetch_array($result);
?>

<nav class="navbar navbar-expand-lg  navbar-dark bg-dark">
    <!-- Navbar content -->
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Navbar</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav">
                    <a class="nav-link active" aria-current="page" href="../home.php">Home</a>
                    <?php
                        //Solo admin y creadores
                        if($_SESSION['rol'] == 1 or $_SESSION['rol'] == 2  ){
                    ?>
                    <a class="nav-link" href="../Usuario/listar.php">Usuarios</a>
                    <a class="nav-link" href="../Retencion/listar.php">Retencion</a>
                    <?php
                        }
                    ?>
                    <a class="nav-link" href="listar.php">Contratos</a>
                    <a class="nav-link" href="../cerrar_sesion.php">Cerrar Sesion</a>
                </div>
            </div>
        </div>
</nav>
<br />
<div class="container">
    <div class="col-md-12">
        <h1>Nuevo Contrato</h1>
        <form class="row g-3" action="insertar.php" method="post">

            <div class="form-group col-md-4">
                <label for="idUsuario" class="form-label">Contratista</label>
                <select class="form-select" aria-label="Default select example" name="idUsuario" required>
                    <option value="">Seleccionar</option>
                    <?php
                        include('../../db.php');
                        $consulta= "SELECT * FROM usuario WHERE idRol = 4";
                        $resultado = mysqli_query($conexion,$consulta);

                        while($filas=mysqli_fetch_array($resultado)){
                            if($row['idUsuario'] == $filas['id'] ){
                    ?>
                            <option value="<?php echo $filas['id']?>" selected><?php echo $filas['nombre']?></option>
                    <?php   }else{
                    ?>
                            <option value="<?php echo $filas['id']?>"><?php echo $filas['nombre']?></option>
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
                        $consulta= "SELECT * FROM usuario WHERE idRol = 3";
                        $resultado = mysqli_query($conexion,$consulta);

                        while($filas=mysqli_fetch_array($resultado)){
                            if($row['idSupervisor'] == $filas['id'] ){
                    ?>
                           <option value="<?php echo $filas['id']?>" selected><?php echo $filas['nombre']?></option>
                    <?php   }else{
                    ?>
                            <option value="<?php echo $filas['id']?>"><?php echo $filas['nombre']?></option>
                    <?php
                            }
                        }
                    ?>
                </select>
            </div>
            <br>
            <div class="form-group col-md-4">
                <label for="registro_pptañ" class="form-label">Registro PPTAL</label>
                <input type="number" class="form-control" placeholder="Ingrese el registro PPTAL" aria-label="Default select example" name="registro_pptal" value="<?php echo $row['registro_pptal']?>" required>
            </div>
            <br>
            <div class="form-group col-md-12">
                <label for="rubro" class="form-label">Rubro</label>
                <textarea  class="form-control" aria-label="With textarea" name="rubro" required><?php echo $row['rubro']?></textarea>
            </div>
            <br>
            <div class="form-group col-md-4">
                <label for="disp_presupuestal" class="form-label">Disponibilidad Presupuestal</label>
                <input type="number" class="form-control" placeholder="" aria-label="Default select example" name="disp_presupuestal" value="<?php echo $row['disp_presupuestal']?>" required>
            </div>
            <br>
            <div class="form-group col-md-4">
                <label for="año" class="form-label">Año</label>
                <select class="form-select" aria-label="Default select example" name="año" required>
                    <option value="">Seleccionar</option>
                    <option value="<?php echo $row['years']?>" selected><?php echo $row['years']?></option>
                    <option value="2022">2022</option>
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
                <input type="number" class="form-control" placeholder="Número de contrato" aria-label="Default select example" name="num_contrato" value="<?php echo $row['num_contrato']?>" required>
            </div>
            <br>
            <div class="form-group col-md-4">
                <label for="fecha_delegacion" class="form-label">Fecha de delegación</label>
                <input class="form-control" type="date" name="fecha_delegacion" value="<?php echo $row['fecha_delegacion']?>" required>
            </div>
            <br>
            <div class="form-group col-md-4">
                <label for="num_delegacion" class="form-label">Número de delegación</label>
                <input type="number" class="form-control" placeholder="Ingrese el número de delegación" aria-label="Default select example" name="num_delegacion" value="<?php echo $row['num_delegacion']?>" required>
            </div>
            <br>
            <div class="form-group col-md-4">
                <label for="area" class="form-label">Area</label>
                <select class="form-select" aria-label="Default select example" name="area" required>
                    <option value="">Seleccionar</option>
                    <option value="<?php echo $row['area']?>" selected><?php echo $row['area']?></option>
                    <option value="Subdirección técnica">Subdirección técnica</option>
                    <option value="Subdirección Administrativa y Financiera">Subdirección Administrativa y Financiera</option>
                </select>
            </div>
            <br>
            <div class="form-group col-md-4">
                <label for="fecha_ini" class="form-label">Fecha de Inicio</label>
                <input class="form-control" type="date" name="fecha_ini" value="<?php echo $row['fecha_ini']?>" required>
            </div>
            <br>
            <div class="form-group col-md-4">
                <label for="fecha_fin" class="form-label">Fecha de Finalización</label>
                <input class="form-control" type="date" name="fecha_fin" value="<?php echo $row['fecha_fin']?>" required>
            </div>
            <br>
            <div class="form-group col-md-4">
                <label for="valor_dia" class="form-label">Valor Mes</label>
                <input type="number" class="form-control" placeholder="Ingrese el valor del mes" aria-label="Default select example" name="valorMes" value="<?php echo $row['valorMes']?>" required>
            </div>
            <br>
            <div class="form-group col-md-12">
                <label for="objeto" class="form-label">Objeto</label>
                <textarea class="form-control"  aria-label="With textarea" name="objeto" required><?php echo $row['objeto']?></textarea>
            </div>
            <br>
            <div class="form-group col-md-12">
                <label for="forma_pago" class="form-label">Forma de pago</label>
                <textarea class="form-control"  aria-label="With textarea" name="forma_pago" required><?php echo $row['forma_pago']?></textarea>
            </div>
            <br>
            <div class="form-group col-md-12">
                <label for="entregables" class="form-label">Entregables</label>
                <textarea class="form-control"  aria-label="With textarea" name="entregables" required><?php echo $row['entregables']?></textarea>
            </div>
            <br>
            <div class="form-group col-md-3">
                <label for="salud" class="form-label">Salud</label>
                <input type="text" class="form-control" placeholder="Ingrese la salud a la que pertenece" aria-label="Default select example" name="salud" value="<?php echo $row['salud']?>" required>
            </div>
            <br>
            <div class="form-group col-md-3">
                <label for="pension" class="form-label">Pensión</label>
                <input type="text" class="form-control" placeholder="Ingrese la pension a la que pertenece" aria-label="Default select example" name="pension" value="<?php echo $row['pension']?>" required>
            </div>
            <br>

            <div class="form-group col-md-3">
                <label for="arl" class="form-label">ARL</label>
                <input type="text" class="form-control" placeholder="Ingrese la arl a la que pertenece" aria-label="Default select example" name="arl" value="<?php echo $row['arl']?>" required>
            </div>
            <br>
            <div class="form-group col-md-3">
                <label for="fecha_activacion" class="form-label">Fecha de Activación</label>
                <input class="form-control" type="date" name="fecha_activacion" value="<?php echo $row['fecha_activacion']?>"required>
            </div>
            <br>
            <div class="form-group col-md-12">
                <label for="observaciones" class="form-label">Observaciones</label>
                <textarea class="form-control" aria-label="With textarea" name="observaciones" required><?php echo $row['observaciones']?></textarea>
            </div>
            <br>
            <?php
                include('../../db.php');
                $consulta= "SELECT * FROM retencion WHERE orden = 1 OR orden = 2 Order by orden Asc";
                $resultado = mysqli_query($conexion,$consulta);

                while($filas2=mysqli_fetch_array($resultado)){
            ?>
                 <input type="hidden" class="form-control" name="idRetencion[]" value="<?php echo $filas2['id'] ?>" />      
            <?php
                }
            ?>
            <div class="form-group col-md-12">
                <label for="riesgo" class="form-label">Riesgo</label>
                <select class="form-select" aria-label="Default select example" name="idRetencion[]" required>
                    <option value="">Seleccionar</option>
                    <?php
                        include('../../db.php');
                        $consulta= "SELECT * FROM retencion WHERE orden = 3";
                        $resultado = mysqli_query($conexion,$consulta);

                        while($filas2=mysqli_fetch_array($resultado)){
                    ?>
                            <option value="<?php echo $filas2['id']?>"><?php echo $filas2['nombre']?></option>
                    <?php
                        }
                    ?>
                </select>
            </div>
            <br>
            <div class="form-group col-md-12">
                <label for="arl" class="form-label">Estampillas</label>
                <div class="input-group col-md-8">
                <input type="number" class="form-control" placeholder="Digite el numero de retenciones" aria-label="Default select example" id="retencionnum" required>
                <li class="btn btn-outline-primary btn-lg col-md-4" id="add-retencion" ><i class="fas fa-plus"></i> Agregar Estampillas</li>
                </div>
            </div>
            <div class="col-md-8" id="retenciones">
            </div>
            <br>
            <div class="form-group col-md-12">
                <label for="arl" class="form-label">Alcances</label>
                <div class="input-group col-md-8">
                <input type="number" class="form-control" placeholder="Digite el numero de alcances" aria-label="Default select example" id="alcancenum" required>
                <li class="btn btn-outline-primary btn-lg col-md-4" id="add-alcance" ><i class="fas fa-plus"></i> Agregar Alcances</li>
                </div>
            </div>
            <div class="col-md-12" id="alcances">
            </div>
            
            <br>
            <br>
            <input type="hidden" class="form-control" name="idContrato" value="<?php echo $idContrato ?>" />
            <input type="hidden" class="form-control" name="editar" value="Si" />

            <div class="form-group col-md-12" style="text-align:right;">
                <input type="submit" class="btn btn-primary" value="Guardar" />
            </div>
            <br>
            <br>
        </form>
        
    </div>
</div>
 
<script>
var cont = 1;
var cont2 = 1;

//document.getElementById("adicion").addEventListener("change", ActivarFechaAdiccion);
document.getElementById("add-retencion").addEventListener("click", AgregarEstampillas);
document.getElementById("add-alcance").addEventListener("click", AgregarAlcances);

/*function ActivarFechaAdiccion(){
	if(document.getElementById("adicion").value > 0){
		document.getElementById("fecha_adicion").disabled = false;
	}else{
		document.getElementById("fecha_adicion").disabled = true;
	}
}*/

function AgregarEstampillas() {
  var retencionNum = document.getElementById("retencionnum").value;
	for(var i = 1; i<= retencionNum; i++){
		document.getElementById("retenciones").innerHTML += '<div class="form-group col-md-8"><label for estampilla>Estampilla'+i+'</label><select class="form-select" aria-label="Default select example" name="idRetencion[]" required><option value="">Seleccionar</option><?php $consulta= "SELECT * FROM retencion WHERE orden = 4"; $resultado = mysqli_query($conexion,$consulta);  while($filas=mysqli_fetch_array($resultado)){?> <option value="<?php echo $filas['id'] ?>"><?php echo $filas['nombre']?></option><?php } ?></select></div><br>';
	}
  window.scrollTo(0, document.body.scrollHeight);
}
function AgregarAlcances() {
  var alcanceNum = document.getElementById("alcancenum").value;
  for(var i = 1; i<= alcanceNum; i++){
    document.getElementById("alcances").innerHTML += '<div class="form-group col-md-8"><label>Alcance'+i+'</label><textarea class="form-control" aria-label="With textarea" name="alcance[]" required></textarea></div><br>';
  }
  //document.getElementById("alcances").innerHTML += '<div class="form-group col-md-8"><label>Alcance'+cont+'</label><textarea class="form-control" aria-label="With textarea" name="alcance[]"></textarea></div><br>';
  //cont++;
  window.scrollTo(0, document.body.scrollHeight);
}
</script>