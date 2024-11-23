<?php
include('db.php');

session_start();
error_reporting(0);

if(isset($_GET['año'])){
    //para cargar todo lo que se hizó ese año
    $_SESSION['año'] = $_GET['año'];
    header("location:views/Contrato/listar.php");
}else{
    eader("location:home.php");
}



 // Consulta segura para evitar inyecciones SQL.
 //$sql = sprintf("SELECT * FROM usuario WHERE email='%s' AND password = %s", mysql_real_escape_string($email), mysql_real_escape_string($password));
 //$resultado = $conn->query($sql);



mysqli_free_result($resultado);
mysqli_close($conexion);
