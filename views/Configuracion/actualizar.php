<?php
    include('../../db.php');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $valor = $_POST['valor'];
        $activo = $_POST['activo'];


        $consulta= "UPDATE `configuracion` SET `nombre_configuracion` = '$nombre', `valor` = '$valor', `activo` = '$activo' WHERE id_configuracion = $id";
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