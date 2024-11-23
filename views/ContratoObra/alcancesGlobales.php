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
    $NombreContratistas = $_GET["nombre"];

    if(isset($_POST['keyword'])){
        
        $keyword = $_POST['keyword'];
         //Obtiene el usuario(s) el cual coincida con la busqueda de la palabra clave
         $consulta= "SELECT u.nombre, u.apellidos, s.nombre As nombre1, s.apellidos AS apellidos1, c.num_contrato, a.nombre AS item, a.impacto
         FROM usuario AS u
         JOIN contrato AS c ON u.id = c.idUsuario 
         JOIN alcance AS a ON a.idContrato = c.id
         JOIN usuario AS s ON s.id = c.idSupervisor
         WHERE a.nombre LIKE '%$keyword%'
         ORDER BY c.num_contrato";
    }else{
        $consulta= "SELECT u.nombre, u.apellidos, s.nombre As nombre1, s.apellidos AS apellidos1, c.num_contrato, a.nombre AS item , a.impacto
        FROM usuario AS u
        JOIN contrato AS c ON u.id = c.idUsuario 
        JOIN alcance AS a ON a.idContrato = c.id
        JOIN usuario AS s ON s.id = c.idSupervisor
        ORDER BY c.num_contrato";
    }
    $resultado = mysqli_query($conexion,$consulta);
   
}

include("../partials/layout.php");
//define las rutas para poder navegar en el menu
$_SESSION['rutaHome'] = "../../home.php";
$_SESSION['rutaUsuario'] = "../Usuario/listar.php";
$_SESSION['rutaCategoria'] = "../CategoriaProfesional/listar.php";
$_SESSION['rutaRetencion'] = "../Retencion/listar.php";
$_SESSION['rutaActasPendientes'] = "Acta/actasPendientes.php";
$_SESSION['rutaAlcancesGlobales'] = "alcancesGlobales.php";
$_SESSION['rutaContratos'] = "listar.php";
$_SESSION['rutaCerrarSesion'] = "../../cerrar_sesion.php";

include('../partials/menu.php');
?>

<div class="container">
    <h1 class="text-center blanco">Lista De Alcances Globales</h1>
    <hr>
    <div class="" style="text-align:right;padding:5px;">
        <a href="listar.php" class="btn btn-primary">Volver</a>
    </div>
   
			
    <form action="alcancesGlobales.php" method="POST" class="col-md-4">
        <div class="col-md-12 input-group mb-3">
            <input type="text" class="form-control" placeholder="Palabra clave" aria-label="Recipient's username" aria-describedby="button-addon2" name="keyword">
            <input type="submit" class="btn btn-primary" value="Buscar">
        </div>
    </form>
    <br>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped">
                <tr>
                    <th>#</th>
                    <th>N° Contrato</th>
                    <th>Contratista</th>
                    <th>Supervisor</th>
                    <th>Alcance</th>
                    <th>Impactado</th>
                    
                </tr>
                    <?php
                        $cont = 0;
                        while($filas=mysqli_fetch_array($resultado)){
                            $NombreContratistas = $filas['nombre']." ".$filas['apellidos'];
                            if($cont == 1){
                                //Almacenamos al contratista 
                                $Nombretemp = $NombreContratistas;
                            }else if($NombreContratistas <> $Nombretemp){
                                $cont = 0; 
                            } 
                            $cont++;
                    ?>
                        <tr>
                            <td><?php echo $cont ?></td>
                            <td><?php echo $filas['num_contrato']?></td>
                            <td><?php echo $filas['nombre']." ".$filas['apellidos']?></td>
                            <td><?php echo $filas['nombre1']." ".$filas['apellidos1']?></td>
                            <td><?php echo $filas['item']?></td>
                            <td><?php echo $filas['impacto']?></td>
                        </tr>
                    <?php 
                            
                        }
                    ?>
            </table>
        </div>
    </div>
</div>
