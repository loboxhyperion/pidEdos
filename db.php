<?php
//conexion local 
$db = "pidedos";
$host = "localhost:3307";
$pw = "";
$user = "root";
// remota
// $db = "edosgov2_pidDB";
// $host = "localhost";
// $pw = "MGUW9o].LYrNMGUW9o].LYrN";
// $user = "edosgov2_pidDBUS";
/*$db = "epiz_29134950_seguicontratistas";
$host = "sql310.epizy.com";
$pw = "U67KpyS3UF";
$user = "epiz_29134950";*/
//conexion remota
$conexion = mysqli_connect($host,$user,$pw,$db); //or die ("No se puede establecer conexion con la DB.")
if(!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}
?>