<?php
//seguridad de sessiones paginacion
session_start();
error_reporting(0);

//si no hay algun usuario registradose devuelve al login
if (!isset($_SESSION['rol'])) {
    header("location:../../../index.php");
} else {
    //solo tiene permiso el admin y creador
    if ($_SESSION['rol'] == 3 or $_SESSION['rol'] == 4) {
        header("location:../../../index.php");
    }
}


if (isset($_GET['ruta'])) {
    $rutaArchivo = $_GET['ruta'];
    // Descargar el archivo
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($rutaArchivo) . '"');
    readfile($rutaArchivo);
    exit;
} else {
    echo "ID de archivo no especificado.";
}
