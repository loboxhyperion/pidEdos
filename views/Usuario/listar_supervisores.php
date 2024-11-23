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
include('../../db.php');
$consulta= "SELECT * FROM usuario WHERE idRol = 3";
$resultado = mysqli_query($conexion,$consulta);

//$filas = mysqli_fetch_array($resultado);
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
                    <a class="nav-link" href="../Contrato/listar.php">Contratos</a>
                    <a class="nav-link" href="../../cerrar_sesion.php">Cerrar Sesion</a>
                </div>
            </div>
        </div>
</nav>
<div class="container">
    <h1 class="text-center blanco">Lista de Usuarios</h1>

    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link" aria-current="page" href="listar.php">Todos</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="listar_contratistas.php">Contratistas</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="listar_supervisores.php">Supervisores</a>
        </li>
    </ul>

    <div class="" style="text-align:right;padding:5px;">
        <a href="nuevo.php?tipo=3" class="btn btn-primary">Nuevo Supervisor</a>
    </div>
    <div class="row">
        <div class="col-md-12">
            <table class="table">
                <tr>
                    <th>#</th>
                    <th>Usuario</th>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Cedula</th>
                    <th>Telefono</th>
                    <th>Correo</th>
                    <th>Direccion</th>
                    <th>Profesión</th>
                    <th>Cargo</th>
                    <th>Tipo de persona</th>
                    <th>Responsable de iva</th>
                    <th>Acciones</th>
                    
                </tr>
                    <?php
                        $cont = 1;
                        while($filas=mysqli_fetch_array($resultado)){
                    ?>
                        <tr>
                            <td><?php echo $cont?></td>
                            <td><?php echo $filas['usuario']?></td>
                            <td><?php echo $filas['nombre']?></td>
                            <td><?php echo $filas['apellidos']?></td>
                            <td><?php echo $filas['cedula']?></td>
                            <td><?php echo $filas['telefono']?></td>
                            <td><?php echo $filas['correo']?></td>
                            <td><?php echo $filas['direccion']?></td>
                            <td><?php echo $filas['profesion']?></td>
                            <td><?php echo $filas['cargo']?></td>
                            <td><?php echo $filas['tipo_persona']?></td>
                            <td><?php echo $filas['resp_iva']?></td>
                            <td>
                                <div style="display:flex; justify-content:space-between">
                                    <a class="btn btn-warning btn-sm" href="../Usuario/editar.php?id=<?php echo $filas['id'] ?>"><i class="far fa-edit" style="color:#fff;"></i></a>
                                    <a class="btn btn-danger btn-sm" href="../Usuario/eliminar.php?id=<?php echo $filas['id'] ?>"><i class="fas fa-trash-alt" style="color:#fff;"></i></a>
                                </div>
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
