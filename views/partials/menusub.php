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
<nav class="navbar navbar-expand-lg  navbar-dark" style="background-color:#088ee8;">
    <!-- Navbar content -->
        <div class="container-fluid">
            <a class="navbar-brand" href="#">PID</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            </div>
        </div>
</nav>