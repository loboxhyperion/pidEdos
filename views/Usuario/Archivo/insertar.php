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
// antes de cualquier línea de código html o php:
//evita que no se buque el header para redirigir a otra pagina
ob_start();


// Verificar si se ha enviado un archivo
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["fecha_creacion"])) {
    echo "<br> pase";
    // Configuración de la base de datos
    include('../../../db.php');

    //get from form
    $fecha_creacion = $_POST["fecha_creacion"];
    $idUsuario = $_POST['idUsuario'];
    $nombreContratista = $_POST['nombreContratista'];

    echo "<br> id:" . $idUsuario;
    echo "<br> Contratista:" . $nombreContratista;
    //get all  form files  in a list
    $rutas = $_FILES['ruta']['name'];
    $rutasTemp = $_FILES['ruta']['tmp_name'];

    //Se define la lista de nombres de la lista chequeo a guardar 
    $checkList = array("18 Cedula", "26 Acta de grado");

    // Ruta donde se guardarán los archivos subidos
    $uploadDir = '../../../public/uploadedFiles/';

    //procedure to save file dir
    for ($i = 0; $i < count($rutas); $i++) {
        $infoFile = pathinfo($rutas[$i]); // get name of the file
        $ext = $infoFile['extension']; // get the extension of the file
        $uploadFilePath = $uploadDir . $checkList[$i] . "." . $ext; //create final path 
        $nameFile = $checkList[$i] . "." . $ext;
        echo "<br> Archivos:" . $uploadFilePath;
        // Mover el archivo subido a la ubicación deseada
        if (move_uploaded_file($rutasTemp[$i], $uploadFilePath)) {
            $query = "INSERT INTO `archivo_requerido`(`ruta`,`name_file`, `estado`, `fecha_creacion`, `idUsuario`) VALUES ('$uploadFilePath','$nameFile','Pendiente','$fecha_creacion','$idUsuario')";
            $result = mysqli_query($conexion, $query) or die("No se puede establecer conexion con la DB.");
            // $result = true;
        }
    }
    //check if the query is done
    if ($result) {
        $message = "Los Archivos se han subido Con éxito";
        echo "<br>" . $message;
        header("location:listar.php?id=" . $idUsuario . "&nombre=" . $nombreContratista . "&mensaje=" . $message);
    } else {
?>
        <?php
        if (mysqli_error($conexion)) {
            $message = "Los Archivos  no se han subido Con éxito";
            echo "<br>" . $message;
            header("location:nuevo.php?id=" . $idUsuario . "&nombre=" . $nombreContratista . "&mensaje=" . $message);
        }
        ?>
        <h1 class="bad">ERROR EN LA AUTENTIFICACION DEL CONTRATO</h1>
<?php
    }
}
//mysqli_free_result($resultado);
ob_end_flush();
mysqli_close($conexion);
?>