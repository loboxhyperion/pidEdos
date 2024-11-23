<?php
//seguridad de sessiones paginacion
session_start();
error_reporting(0);

//si no hay algun usuario registradose devuelve al login
if(!isset($_SESSION['rol'])){
    header("location:../../index.php");
}else{  
    include('../../db.php');
    $idContrato = $_GET["id"];
    $NombreContratista = $_GET["nombre"];
    $arrayUsuario =  $_SESSION['usuario'];
    switch($_SESSION['rol']){
        case 1:
            $consulta= "SELECT * FROM contrato  WHERE contrato.id = $idContrato";
        break;
        case 2:
            $consulta= "SELECT * FROM contrato  WHERE contrato.id = $idContrato";
        break;
        case 3:
            $consulta= "SELECT * FROM contrato  WHERE contrato.id = $idContrato and idSupervisor = $arrayUsuario[id]";
        break;
        case 4:
            $consulta= "SELECT * FROM contrato  WHERE contrato.id = $idContrato and idUsuario = $arrayUsuario[id]";
        break;
    } 

    $resultado = mysqli_query($conexion,$consulta) or die("fallo en la conexión");

    $filas = mysqli_fetch_array($resultado); 
    //si la consulta falla y tratan de meterse por otro lado 
    if(!$filas){
        header("location:../../index.php");
    }  
}


/*$consulta= "SELECT * FROM contrato  WHERE contrato.id = $idContrato";
$resultado = mysqli_query($conexion,$consulta) or die("fallo en la conexión");

$filas = mysqli_fetch_array($resultado);*/

include("../partials/layout.php");
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
                    <a class="nav-link active" aria-current="page" href="../../home.php">Home</a>
                    <a class="nav-link" href="../Usuario/listar.php">Usuarios</a>
                    <?php
                        //Solo admin y creadores
                        if($_SESSION['rol'] == 1 or $_SESSION['rol'] == 2  ){
                    ?>
                    <a class="nav-link" href="../Retencion/listar.php">Retencion</a>
                    <?php
                        }
                    ?>
                    <a class="nav-link" href="listar.php">Contratos</a>
                    <a class="nav-link" href="../../cerrar_sesion.php">Cerrar Sesion</a>
                </div>
            </div>
        </div>
</nav>
<div class="container">
    <h1 class="text-center blanco">Mi Contrato</h1>
    <hr>
    <div class="" style="text-align:right;padding:5px;">
        <a href="listar.php" class="btn btn-primary">Volver</a>
    </div>
    <ul class="list-group">
        <li class="list-group-item "><Strong>Contratista:</Strong><?php echo $NombreContratista?></li>
        <li class="list-group-item"><strong>Registro PPTAL: </strong><?php echo $filas["registro_pptal"] ?></li>
        <li class="list-group-item"><strong>Disponibilidad Presupuestal: </strong><?php echo $filas["disp_presupuestal"] ?></li>
        <li class="list-group-item"><strong>Rubro: </strong><?php echo $filas["rubro"] ?></li>
        <li class="list-group-item"><strong>Año: </strong><?php echo $filas["years"] ?></li>
        <li class="list-group-item"><strong>N° Contrato: </strong><?php echo $filas["num_contrato"] ?></li>
        <li class="list-group-item"><strong>Contratante: </strong><?php echo $filas["contratante"] ?></li>
        <li class="list-group-item"><strong>Dirección: </strong>CALLE 50 NRO 14 -  56 BARRIO LOS NARANJOS</li>
        <li class="list-group-item"><strong>Ordenador Del Gasto: </strong>LUIS ERNESTO VALENCIA RAMIREZ</li>
        <li class="list-group-item"><strong>Supervisor: </strong><?php
                                                                    //Obtiene el nombre del supervisor
                                                                    $consulta2= "SELECT * FROM usuario WHERE id = $filas[idSupervisor]";
                                                                    $resultado2 = mysqli_query($conexion,$consulta2);
                                                                    $singleRow2 = mysqli_fetch_array($resultado2);
                                                                    echo $singleRow2['nombre']," ",$singleRow2['apellidos']?>
        </li>
        <li class="list-group-item"><strong>Fecha De Delegacion: </strong><?php echo $filas["fecha_delegacion"] ?></li>
        <li class="list-group-item"><strong>Número de oficio Delegación: </strong><?php echo $filas["num_delegacion"] ?></li>
        <li class="list-group-item"><strong>Area: </strong><?php echo $filas["area"] ?></li>
        <li class="list-group-item"><strong>Fecha De Inicio: </strong><?php echo $filas["fecha_ini"] ?></li>
        <li class="list-group-item"><strong>Fecha De Finalización: </strong><?php echo $filas["fecha_fin"] ?></li>
        <li class="list-group-item"><strong>Valor Contrato: </strong>
            <?php 
                echo number_format($filas['valor_contrato'],2,".",",");
             ?>
        </li>
        <li class="list-group-item"><strong>Duración Días: </strong><?php echo $filas["duracion"] ?></li>
        <li class="list-group-item"><strong>Adición Días: </strong><?php echo $filas["adicion1"] ?></li>
        <li class="list-group-item"><strong>Fecha Adición: </strong><?php echo $filas["fecha_adicion1"] ?></li>
        <li class="list-group-item"><strong>Objeto: </strong><?php echo $filas["objeto"] ?></li>
        <li class="list-group-item"><strong>Forma de Pago: </strong><?php echo $filas["forma_pago"] ?></li>
        <li class="list-group-item"><strong>Entregables: </strong><?php echo $filas["entregables"] ?></li>
        <li class="list-group-item"><strong>Salud: </strong><?php echo $filas["salud"] ?></li>
        <li class="list-group-item"><strong>Pensión: </strong><?php echo $filas["pension"] ?></li>
        <li class="list-group-item"><strong>ARL: </strong><?php echo $filas["arl"] ?></li>
        <li class="list-group-item"><strong>Fecha Activación: </strong><?php echo $filas["fecha_activacion"] ?></li>
        <li class="list-group-item"><strong>Dia habil de pago: </strong><?php echo $filas["dia_habil_pago"] ?></li>
        <li class="list-group-item"><strong>Alcances: </strong></li>
        <?php
            $query = "SELECT * FROM alcance WHERE idContrato = $idContrato";
            $result = mysqli_query($conexion,$query);
            $cont = 1;
            while($filas2=mysqli_fetch_array($result)){
        ?>
            <li class="list-group-item active"><strong><?php echo $cont?>: </strong><?php echo $filas2["nombre"] ?></li>
        <?php
            $cont++;
            }
        ?>
        <li class="list-group-item"><strong>Modalidad: </strong><?php echo $filas["modalidad"] ?> </li>
        <li class="list-group-item"><strong>Observaciones: </strong><?php echo $filas["observaciones"] ?></li>

    </ul>
</div>
