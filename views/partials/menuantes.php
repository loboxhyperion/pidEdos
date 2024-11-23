<?php
//seguridad de sessiones paginacion
session_start();
error_reporting(0);
//si no hay algun usuario registradose devuelve al login
if(!isset($_SESSION['rol'])){
    header("location:../../index.php");
}

//include("../../partials/layout.php");
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
                    <a class="nav-link active" aria-current="page" href="<?php echo $_SESSION['rutaHome'] ?>">Home</a>
                    <a class="nav-link" href="<?php echo $_SESSION['rutaUsuario'] ?>">Usuarios</a>
                    <a class="nav-link" href="<?php echo $_SESSION['rutaCategoria'] ?>">Categoria</a>
                    <?php
                        //Solo admin y creadores
                        if($_SESSION['rol'] == 1 or $_SESSION['rol'] == 2  ){
                    ?>
                    <a class="nav-link" href="<?php echo $_SESSION['rutaRetencion'] ?>">Retencion</a>
                    <?php
                        }
                    ?>
                     <?php
                        //Solo admin y supervisores
                        if($_SESSION['rol'] == 1 or $_SESSION['rol'] == 3 or $_SESSION['rol'] == 5 ){
                    ?>
                    <a class="nav-link" href="<?php echo $_SESSION['rutaActasPendientes'] ?>">Actas Pendientes</a>
                    <a class="nav-link" href="<?php echo $_SESSION['rutaAlcancesGlobales'] ?>">Alcances Globales</a>
                    <?php
                        }
                    ?>
                    <?php
                        //Solo admin y creadores
                        if($_SESSION['rol'] == 1 or $_SESSION['rol'] == 3 or $_SESSION['rol'] == 5 ){
                    ?>
                    <a class="nav-link" href="<?php echo $_SESSION['rutaInformeSupervisor'] ?>">Informe Supervisor</a>
                    <?php
                        }
                    ?>
                    <a class="nav-link" href="<?php echo $_SESSION['rutaContratos'] ?>">Contratos</a>
                    <a class="nav-link" href="<?php echo $_SESSION['rutaCerrarSesion'] ?>">Cerrar Sesion</a>
                </div>
            </div>
        </div>
</nav>