<?php
include('db.php');

session_start();
error_reporting(0);

if(isset($_POST['usuario']) && isset($_POST['contraseña'])){
    $usuario = $_POST['usuario'];
    $contraseña = $_POST['contraseña'];

    $consulta= "SELECT * FROM usuario where usuario='$usuario' and clave='$contraseña'";
    $resultado = mysqli_query($conexion,$consulta);
    $filas = mysqli_fetch_array($resultado);

    // se cargan las configuraciones 
    $consulta= "SELECT * FROM configuracion WHERE activo = 1";
    $configuraciones= mysqli_query($conexion,$consulta);

    $configuracionesPortables = [];

    
    foreach($configuraciones as $config){
        $configuracionesPortables [] = [
            'nombre' => $config["nombre_configuracion"],
            'valor' => $config["valor"]
        ];
        $_SESSION['config'] = $config;
        // $_SESSION['configValor']  = $config['valor'];
    }
    
    $_SESSION['config'] = $configuracionesPortables;
    
    if($filas){
        $_SESSION['usuario']= $filas;
        $_SESSION['rol'] = $filas['idRol'];
        $_SESSION['imgPerfil']= $filas['rutaPerfil'];
        //para cargar todo lo que se hizó ese año
        $_SESSION['año'] = $_POST['año'];
        header("location:home.php");
    }else{
        ?>
<?php
        include("index.php");
        ?>
<h1 class="bad">ERROR EN LA AUTENTIFICACION</h1>
<?php
    }
}



 // Consulta segura para evitar inyecciones SQL.
 //$sql = sprintf("SELECT * FROM usuario WHERE email='%s' AND password = %s", mysql_real_escape_string($email), mysql_real_escape_string($password));
 //$resultado = $conn->query($sql);



mysqli_free_result($resultado);
mysqli_close($conexion);