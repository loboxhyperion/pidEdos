<?php
include('db.php'); 
// antes de cualquier línea de código html o php:
//evita que no se buque el header para redirigir a otra pagina
ob_start();

session_start();
error_reporting(0);

//extraemos y guardamos los archivos
$id = $_POST["id"];
// $posicion = isset($_POST['posicion']) ? $_POST['posicion'] : "";
$info = pathinfo($_FILES['rutaPerfil']['name']);
$ext = $info['extension']; // get the extension of the file
$target_path = "public/perfiles/".$id.".". $ext;//renombre el archivo 
$nameFile = $id.".". $ext;
echo $target_path;

// header("location:../Codigos/listar.php?nombre=" . $nombre);
//solo supervisores si vacaiones = si

$query= "UPDATE `usuario` SET  `rutaPerfil`='$nameFile' WHERE id = $id";
$result = mysqli_query($conexion,$query);


if($result){
    $_SESSION['imgPerfil'] = $nameFile;
    move_uploaded_file($_FILES['rutaPerfil']['tmp_name'], $target_path);//almacena o mueve el archivo renombrado a la ruta espcificada
    header("location:home.php");
}else{
    ?>
    <?php
    ?>
    <h1 class="bad">ERROR</h1>
    <?php
}
mysqli_free_result($resultado);
mysqli_close($conexion);