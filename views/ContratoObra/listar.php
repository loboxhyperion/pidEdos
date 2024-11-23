<?php
//seguridad de sessiones paginacion
session_start();
error_reporting(0);
/** Consulta para sacar el en la matriz la base de datos de los contratos unida a los usuarios
 * SELECT usuario.nombre, usuario.apellidos, usuario.cedula, usuario.telefono, usuario.correo, usuario.direccion, usuario.profesion,usuario.cargo, usuario.tipo_persona,usuario.resp_iva, 
 * usuario.genero ,usuario.fecha_nacimiento,contrato.registro_pptal, contrato.rubro, contrato.disp_presupuestal, contrato.years, contrato.num_contrato, contrato.contratante,
 *  contrato.fecha_delegacion, contrato.num_delegacion, contrato.area, contrato.fecha_ini, contrato.fecha_fin, contrato.valor_contrato, contrato.valorDia, contrato.valorMes, 
 * contrato.duracion, contrato.objeto, contrato.forma_pago, contrato.entregables, contrato.salud, contrato.pension, contrato.arl, contrato.dia_habil_pago, contrato.fecha_activacion, 
 * contrato.observaciones, contrato.num_actas FROM usuario JOIN contrato ON usuario.id = contrato.idUsuario 
 * 
 * * consulta para filtrar usuarios juridicos
 * 
 * SELECT usuario.nombre, contrato.id, contrato.num_contrato, contrato.years, contrato.area, contrato.fecha_ini, contrato.fecha_fin, contrato.valor_contrato, contrato.valorDia, 
 * contrato.duracion, contrato.num_actas, contrato.idUsuario, contrato.idSupervisor FROM usuario JOIN contrato ON usuario.id = contrato.idUsuario WHERE usuario.tipo_persona = "JurÃ­dica"
 */
//si no hay algun usuario registradose devuelve al login
if(!isset($_SESSION['rol'])){
    header("location:../../index.php");
}else{  
    include('../../db.php');
    $varsesion = $_SESSION['usuario'];
    $sesionAño = $_SESSION['año'];

    if(isset($_POST['keyword'])){
        $keyword = $_POST['keyword'];
        if($_SESSION['rol'] != 4){
            if(isset($_POST['filtroContratista']) and ($_SESSION['rol'] == 1 or $_SESSION['rol'] == 2) ){
                //Obtiene el contrato(s) el cual coincida con la busqueda de la palabra clave
                $query1= "SELECT usuario.nombre, contrato.id, contrato.num_contrato, contrato.years, contrato.area, contrato.fecha_ini, contrato.fecha_fin, 
                contrato.valor_contrato, contrato.valorDia, contrato.duracion, contrato.num_actas, contrato.idUsuario, contrato.idSupervisor FROM usuario JOIN contrato ON usuario.id = contrato.idUsuario 
                WHERE usuario.idRol = 4 and usuario.tipo_persona = 'Juridica' and (usuario.nombre LIKE '%$keyword%' OR contrato.num_contrato LIKE '$keyword')";
                
            }else{
                //Obtiene el contrato(s) el cual coincida con la busqueda de la palabra clave
                $query1= "SELECT usuario.nombre, contrato.id, contrato.num_contrato, contrato.years, contrato.area, contrato.fecha_ini, contrato.fecha_fin, 
                contrato.valor_contrato, contrato.valorDia, contrato.duracion, contrato.num_actas, contrato.idUsuario, contrato.idSupervisor FROM usuario JOIN contrato ON usuario.id = contrato.idUsuario 
                WHERE idSupervisor = $varsesion[id] and usuario.idRol = 4 and usuario.tipo_persona = 'Juridica' and (usuario.nombre LIKE '%$keyword%' OR contrato.num_contrato LIKE '$keyword')";
            }
            $usuarios = mysqli_query($conexion,$query1);
            $count_usuarios = mysqli_num_rows($usuarios);
            $resultado = $usuarios;
        }
        if(isset($_POST['filtroSupervisor'])){
            //Obtiene el contrato(s) el cual coincida con la busqueda de la palabra clave
            $query1= "SELECT usuario.nombre, contrato.id, contrato.num_contrato, contrato.years, contrato.area, contrato.fecha_ini, contrato.fecha_fin, 
            contrato.valor_contrato, contrato.valorDia, contrato.duracion, contrato.num_actas, contrato.idUsuario, contrato.idSupervisor FROM usuario JOIN contrato ON usuario.id = contrato.idSupervisor 
            WHERE usuario.idRol = 3 and (usuario.nombre LIKE '%$keyword%' OR apellidos LIKE '%$keyword%')";
            $usuarios = mysqli_query($conexion,$query1);
            $count_usuarios = mysqli_num_rows($usuarios);
            $resultado = $usuarios;
        }
      
    }else{
        $arrayUsuario =  $_SESSION['usuario'];
        //administradores
        if($_SESSION['rol'] == 1 or $_SESSION['rol'] == 2 or  $_SESSION['rol'] == 5){
            $consulta= "SELECT usuario.nombre,contrato.id,contrato.num_contrato,contrato.years,contrato.area,contrato.fecha_ini,contrato.fecha_fin,
            contrato.valor_contrato,contrato.valorDia,contrato.duracion,contrato.num_actas,contrato.idUsuario,contrato.idSupervisor FROM contrato JOIN usuario ON contrato.idUsuario = usuario.id
            WHERE contrato.years = $sesionAño AND usuario.tipo_persona = 'Juridica' Order by contrato.num_contrato ASC";
        }//supervisor
        elseif($_SESSION['rol'] == 3){
             //$consulta= "SELECT * FROM contrato WHERE idSupervisor = $arrayUsuario[id]";
             $consulta= "SELECT usuario.nombre,contrato.id,contrato.num_contrato,contrato.years,contrato.area,contrato.fecha_ini,contrato.fecha_fin,
             contrato.valor_contrato,contrato.valorDia,contrato.duracion,contrato.num_actas,contrato.idUsuario,contrato.idSupervisor FROM usuario JOIN contrato ON usuario.id = contrato.idUsuario 
             WHERE contrato.idSupervisor = $arrayUsuario[id] AND contrato.years = $sesionAño AND usuario.tipo_persona = 'Juridica' Order by contrato.num_contrato ASC"; 
        }//contratista
        else{
            //$consulta= "SELECT * FROM contrato WHERE idUsuario = $arrayUsuario[id]";
            $consulta= "SELECT usuario.nombre,contrato.id,contrato.num_contrato,contrato.years,contrato.area,contrato.fecha_ini,contrato.fecha_fin,
            contrato.valor_contrato,contrato.valorDia,contrato.duracion,contrato.num_actas,contrato.idUsuario,contrato.idSupervisor FROM usuario JOIN contrato ON usuario.id = contrato.idUsuario 
            WHERE contrato.idUsuario= $arrayUsuario[id] AND contrato.years = $sesionAño AND usuario.tipo_persona = 'Juridica'"; 
        }
        $resultado = mysqli_query($conexion,$consulta);
    }
    

    
}

include("../partials/layout.php");
//define las rutas para poder navegar en el menu
$_SESSION['rutaHome'] = "../../home.php";
$_SESSION['rutaUsuario'] = "../Usuario/listar.php";
$_SESSION['rutaCategoria'] = "../CategoriaProfesional/listar.php";
$_SESSION['rutaRetencion'] = "../Retencion/listar.php";
$_SESSION['rutaActasPendientes'] = "Acta/actasPendientes.php";
$_SESSION['rutaAlcancesGlobales'] = "alcancesGlobales.php";
$_SESSION['rutaInformeSupervisor'] = "../InformeSupervisor/listar.php";
$_SESSION['rutaContratos'] = "../Contrato/listar.php";
$_SESSION['rutaContratosObra'] = "listar.php";
$_SESSION['rutaCerrarSesion'] = "../../cerrar_sesion.php";

include('../partials/menu.php');
?>
<!-- los css y los js no se pueden importar en otro archivo html  con el include-->
<!-- toca así -->
<!--  -->
<!--  -->
<style><?php include '../../public/css/style2.css';?></style>
<!--  -->
<!--  -->
<div class="container">
    <h1 class="text-center blanco">Contratos Por Obra</h1>
    <p class="text-center blanco">(En fase de Prueba)</p>
    <br>
    <?php
       //Solo admin y creadores
        if($_SESSION['rol'] == 1 or $_SESSION['rol'] == 2 ){
    ?>
            <div class="barra_botones">
                <a href="nuevo.php" class="btn btn-primary">Contrato</a>
            </div>
            <br>
            <form action="listar.php" method="POST" class="form-group col-md-4">
                <div class="col-md-12 input-group mb-3">
                    <input type="text" class="form-control" placeholder="Nombre del Supervisor" aria-label="Recipient's username" aria-describedby="button-addon2" name="keyword">
                    <input type="hidden" name="filtroSupervisor" value="si">
                    <input type="submit" class="btn btn-primary" value="Buscar">
                </div>
            </form>
            <br>
    <?php
        }
    ?>
     <?php
       //Solo admin y creadores
        if($_SESSION['rol'] == 1 or $_SESSION['rol'] == 2 or $_SESSION['rol'] == 3 ){
    ?>
            <form action="listar.php" method="POST" class="form-group col-md-4">
                <div class="col-md-12 input-group mb-3">
                    <input type="text" class="form-control" placeholder="Nombre del contratista o N° de contrato" aria-label="Recipient's username" aria-describedby="button-addon2" name="keyword">
                    <input type="hidden" name="filtroContratista" value="si">
                    <input type="submit" class="btn btn-primary" value="Buscar">
                </div>
            </form>
    <?php
        }
    ?>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped">
                <tr>
                    <th>Detalles del contrato</th>
                    <th>N° Contrato</th>
                    <th>Representante</th>
                    <th>Razón Social</th>
                    <th>Año</th>
                    <th>Supervisor</th>
                    <th>Area</th>
                    <th>Fecha De Inicio</th>
                    <th>Fecha De Finalización</th>
                    <th>Valor Contrato</th>
                    <th>Duración Días</th> 
                    <th>Estampillas</th>
                    <th>Items</th>
                    <th>Acta de Inicio</th> 
                    <th>Acta Pactación de Precios</th> 
                    <th>Crear Actas</th>
                    <?php
                        //Solo admin y creadores
                        if($_SESSION['rol'] == 1 or $_SESSION['rol'] == 2  ){
                    ?>
                            <th>Modificaciones Contractuales</th>
                    <?php
                         }
                    ?>
                    <?php
                        //Solo admin y creadores
                        if($_SESSION['rol'] == 1 or $_SESSION['rol'] == 2  ){
                    ?>
                            <th>Acciones</th>
                    <?php
                         }
                    ?>

                    
                </tr>
                    <?php
                        $cont = 1;
                        while($filas=mysqli_fetch_array($resultado)){
                
                    ?>
                        <tr>
                            <?php
                                //Obtiene el nombre del contratista
                                $consulta1= "SELECT * FROM usuario WHERE id = $filas[idUsuario]";
                                $resultado1 = mysqli_query($conexion,$consulta1);
                                $singleRow = mysqli_fetch_array($resultado1);
                            ?>
                            <td><a class="btn btn-success btn-sm" href="../Contrato/ver.php?id=<?php echo $filas['id'] ?>&nombre=<?php echo $singleRow['nombre']," ",$singleRow['apellidos'] ?>"><i class="far fa-eye"></i></a></td>
                            <!--<td><?php /*echo $cont */?></strong></td>-->
                            <td><strong><?php echo $filas['num_contrato']?></strong></td>
                            <td><?php
                                    echo $singleRow['nombre']," ",$singleRow['apellidos'] ?>
                            </td>
                            <td><?php echo $singleRow['razon_social']?></td>
                            <td><?php echo $filas['years']?></td>
                            <td><?php
                                    //Obtiene el nombre del supervisor
                                    $consulta2= "SELECT * FROM usuario WHERE id = $filas[idSupervisor]";
                                    $resultado2 = mysqli_query($conexion,$consulta2);
                                    $singleRow2 = mysqli_fetch_array($resultado2);
                                    echo $singleRow2['nombre']," ",$singleRow2['apellidos']?>
                            </td>
                            <td><?php echo $filas['area']?></td>
                            <td><?php echo $filas['fecha_ini']?></td>
                            <td><?php echo $filas['fecha_fin']?></td>
                            <!--la clase currency se creo por mi, sirve para usar la libreria de divisa todo esta en la carpeta js-->
                            <!--el script esta en la carpeta public/js/main.js-->
                            <td class ="currency"><?php 
                                   echo number_format(intval($filas['valor_contrato']))
                                ?>
                            </td>
                            <td><?php echo $filas['duracion']?></td>
                            <td><a class="btn btn-success btn-sm" href="Estampilla/listar.php?id=<?php echo $filas['id'] ?>&nombre=<?php echo $singleRow['nombre']," ",$singleRow['apellidos'] ?>"><i class="far fa-eye"></i></a></td>
                            <td><a class="btn btn-success btn-sm" href="Pactacion/listar.php?id=<?php echo $filas['id'] ?>&nombre=<?php echo $singleRow['nombre']," ",$singleRow['apellidos'] ?>&NombreSupervisor=<?php echo $singleRow2['nombre']," ",$singleRow2['apellidos'] ?>&fechaActa=<?php echo $filas['fecha_ini'] ?>"><i class="far fa-eye"></i></a></td>
                            <td><a class="btn btn-danger btn-sm" target="_blank" href="generarPdf.php?id=<?php echo $filas['id'] ?>&nombre=<?php echo $singleRow['nombre'], " ", $singleRow['apellidos'] ?>&NombreSupervisor=<?php echo $singleRow2['nombre'], " ", $singleRow2['apellidos'] ?>&fechaActa=<?php echo $filas['fecha_ini'] ?>">Pdf</a></td>
                            <td><a class="btn btn-danger btn-sm" href="generarPdfPactacion.php?id=<?php echo $filas['id'] ?>&nombre=<?php echo $singleRow['nombre']," ",$singleRow['apellidos']  ?>&NombreSupervisor=<?php echo $singleRow2['nombre']," ",$singleRow2['apellidos'] ?>&fechaActa=<?php echo $filas['fecha_ini'] ?>">PDF</a></td>
                            <td>
                                <a class="btn btn-primary btn-sm" href="Acta/listarActas.php?id=<?php echo $filas['id'] ?>&nombre=<?php echo $singleRow['nombre']," ",$singleRow['apellidos'] ?>&NombreSupervisor=<?php echo $singleRow2['nombre']," ",$singleRow2['apellidos'] ?>"><i class="far fa-plus-square"></i></a>
                            </td>
                            <?php
                                //Solo admin y creadores
                                if($_SESSION['rol'] == 1 or $_SESSION['rol'] == 2  ){
                            ?>
                                    <td><a class="btn btn-primary btn-sm" href="AdiccionSuspension/listar.php?id=<?php echo $filas['id'] ?>&fecha_ini=<?php echo $filas['fecha_ini']?>&valorDia=<?php echo $filas['valorDia']?>"><i class="far fa-plus-square"></i></a></td>
                            <?php
                                }
                            ?>
                            
                            <td>
                                <?php
                                    $query2= "SELECT * FROM acta  WHERE idContrato = $filas[id]";
                                    $result2 = mysqli_query($conexion,$query2) or die("fallo en la conexión 1");
                                    $Actas = mysqli_fetch_array($result2); 
                                    //Solo admin y creadores
                                    if($_SESSION['rol'] == 1 or $_SESSION['rol'] == 2  ){
                                        if(!$Actas){
                                ?>
                                            <div style="display:flex; justify-content:space-between">
                                                <a class="btn btn-warning btn-sm" href="editar.php?id=<?php echo $filas['id'] ?>&modificar=No"><i class="far fa-edit" style="color:#fff;"></i></a>
                                                <a class="btn btn-danger btn-sm" href="eliminar.php?id=<?php echo $filas['id'] ?>"><i class="fas fa-trash-alt" style="color:#fff;"></i></a>
                                            </div>
                                <?php
                                        }else if ($Actas && $_SESSION['rol'] ==1 or $_SESSION['rol'] == 2 ){
                                ?>
                                            <div style="display:flex; justify-content:space-between">
                                                <a class="btn btn-warning btn-sm" href="editar.php?id=<?php echo $filas['id'] ?>&modificar=Si"><i class="far fa-edit" style="color:#fff;"></i></a>
                                                <a class="btn btn-danger btn-sm" href="eliminar.php?id=<?php echo $filas['id'] ?>"><i class="fas fa-trash-alt" style="color:#fff;"></i></a>
                                            </div>
                                <?php
                                        }else{
                                ?>
                                            <div style="display:flex; justify-content:space-between">
                                                <a class="btn btn-warning btn-sm" href="" disabled><i class="far fa-edit" style="color:#fff;"></i></a>
                                                <a class="btn btn-danger btn-sm" href="" disabled><i class="fas fa-trash-alt" style="color:#fff;"></i></a>
                                            </div>
                                <?php
                                        }
                                    }
                                ?>
                            </td>
            
                        </tr>
                    <?php  
                        $cont++;    
                        }
                    ?>
            </table>
        </div>
    </div>
</div>
</div>
<script type="text/javascript" src="../../public/js/main.js"></script>