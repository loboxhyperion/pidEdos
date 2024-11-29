<?php
    include('../../db.php');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nombre = $_POST['nombre'];
        $valor = $_POST['valor'];
        $activo = $_POST['activo'];

        $consulta= "INSERT INTO configuracion (nombre_configuracion, valor, activo) VALUES ('$nombre', '$valor', '$activo')";
        $resultado = mysqli_query($conexion,$consulta);

        if($resultado){
            header("location:listar.php");
        }else{
?>
<h1 class="bad">ERROR EN LA AUTENTIFICACION</h1>
<?php
        }
        //mysqli_free_result($resultado);
        mysqli_close($conexion);

    }
?>