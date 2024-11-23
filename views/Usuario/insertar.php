<?php
// antes de cualquier línea de código html o php:
//evita que no se buque el header para redirigir a otra pagina
ob_start();
//seguridad de sessiones paginacion
session_start();

// error_reporting(0); // Activa todos los errores


//si no hay algun usuario registradose devuelve al login
if(!isset($_SESSION['rol'])){
    header("location:../../index.php");
}else{   
    //solo tiene permiso el admin y creador
    if($_SESSION['rol'] == 3 or $_SESSION['rol'] == 4  ){
        header("location:../../index.php");
    }
}

$usuario=$_POST['usuario'];
$contrasena= $_POST['contraseña'];
$nombre=$_POST['nombre'];
$apellidos=$_POST['apellidos'];
$cedula=$_POST['cedula'];
$telefono=$_POST['telefono'];
$correo=$_POST['correo'];
$direccion=$_POST['direccion'];
$profesion=$_POST['profesion'];
$cargo=$_POST['cargo'];
$tipo_persona=$_POST['tipo_persona'];
$resp_iva=$_POST['resp_iva'];
$idRol = $_POST['idRol'];
$tipo = $_POST['tipo'];
$genero = $_POST['genero'];
$fechaNacimiento = $_POST['fecha_nacimiento'];
$nit = $_POST['nit'];
$razon_social = $_POST['razon_social'];

//nuevos campos agregados 2/07/2023
$rut = $_POST['rut'];
$actividad_e = $_POST['actividad_e'];
$nro_cuenta = $_POST['nro_cuenta'];
$banco = $_POST['banco'];
$tipo_cuenta = $_POST['tipo_cuenta'];

include('../../db.php');
//Consulta para saber si ya el usuario existe
$consulta= "SELECT * FROM usuario where usuario='$usuario'";
$resultado = mysqli_query($conexion,$consulta);
$filas = mysqli_fetch_array($resultado);

if($filas){
    header("location:nuevo.php?tipo=".$tipo."&mensaje= EL USUARIO YA EXISTE");
}else{
   //sacar los ultimos digitos CC
    $last_num_cc = substr($cedula,-2);



    $consulta = "INSERT INTO usuario(`usuario`,`clave`,`nombre`, `apellidos`, `cedula`, `last_num_cc`, `telefono`, `correo`, `direccion`, 
                `profesion`,`cargo`, `tipo_persona`,`resp_iva`,`idRol`,`genero`,`fecha_nacimiento`,`vacaciones`,`nit`, `razon_social`,
                `fecha_ini`, `fecha_fin`, `idEncargado`,`rutaPerfil`,`rut`,`actividad_e`,`nro_cuenta`,`banco`,`tipo_cuenta`) VALUES ('$usuario','$contrasena','$nombre','$apellidos','$cedula','$last_num_cc',
                '$telefono','$correo','$direccion','$profesion','$cargo','$tipo_persona','$resp_iva','$idRol','$genero','$fechaNacimiento',
                 'No','$nit','$razon_social','2022-04-23','2022-04-23',0,'perfil.jpg','$rut','$actividad_e','$nro_cuenta','$banco','$tipo_cuenta')";
                 

    $result = mysqli_query($conexion,$consulta);

    if($result){
        header("location:../Usuario/listar.php");
    }else{
        ?>
        <?php
        ?>
        <h1 class="bad">ERROR EN LA AUTENTIFICACION</h1>
        <?php
    }
    
    ob_end_flush();
    //mysqli_free_result($resultado);
    mysqli_close($conexion);

}
