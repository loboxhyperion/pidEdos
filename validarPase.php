<?php
include('db.php');

session_start();



if(isset($_POST['secret_key'])){
    $secretKey = $_POST['secret_key'];


    $consulta= "SELECT * FROM codigo where secret_key='$secretKey'";
    $resultado = mysqli_query($conexion,$consulta);
    $filas = mysqli_fetch_array($resultado);

    if($filas){
        header("location:views/Usuario/nuevo.php?tipo=4");
    }else{
        ?>
        <?php
        header("location:pase.php");
        ?>
        <h1 class="bad">Clave incorrecta</h1>
        <?php
    }
}else{
    header("location:pase.php");
}



 // Consulta segura para evitar inyecciones SQL.
 //$sql = sprintf("SELECT * FROM usuario WHERE email='%s' AND password = %s", mysql_real_escape_string($email), mysql_real_escape_string($password));
 //$resultado = $conn->query($sql);



mysqli_free_result($resultado);
mysqli_close($conexion);