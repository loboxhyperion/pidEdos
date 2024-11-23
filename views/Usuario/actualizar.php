<?php
//seguridad de sessiones paginacion
session_start();
error_reporting(0);

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
$contraseña=$_POST['contraseña'];
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
$idUsuario = $_POST['id'];
$genero = $_POST['genero'];
$fechaNacimiento = $_POST['fecha_nacimiento'];
$vacaciones = $_POST['vacaciones'];

//nuevos campos agregados 2/07/2023
$rut = $_POST['rut'];
$actividad_e = $_POST['actividad_e'];
$nro_cuenta = $_POST['nro_cuenta'];
$banco = $_POST['banco'];
$tipo_cuenta = $_POST['tipo_cuenta'];


//sacar los ultimos digitos CC
$last_num_cc = substr($cedula,-2);

include('../../db.php');

//solo supervisores si vacaiones = si
if($vacaciones == 'Si'){

    $fecha_ini = $_POST['fecha_ini'];
    $fecha_fin = $_POST['fecha_fin'];
    $idEncargado = $_POST['idEncargado'];

    $query = "UPDATE `usuario` SET `usuario`='$usuario',`clave`='$contraseña',`nombre`='$nombre',`apellidos`='$apellidos',`cedula`='$cedula',`last_num_cc`='$last_num_cc',`telefono`='$telefono',`correo`='$correo',`direccion`='$direccion',`profesion`='$profesion',`cargo`='$cargo',`tipo_persona`='$tipo_persona',`resp_iva`='$resp_iva',`idRol`='$idRol',`genero`='$genero',`fecha_nacimiento`='$fechaNacimiento',`vacaciones`='$vacaciones',`fecha_ini`='$fecha_ini',`fecha_fin`='$fecha_fin',`idEncargado`='$idEncargado',`rut`='$rut',`actividad_e`='$actividad_e',`nro_cuenta`='$nro_cuenta',`banco`='$banco',`tipo_cuenta`='$tipo_cuenta' WHERE id = $idUsuario";
}else{
    $query= "UPDATE `usuario` SET `usuario`='$usuario',`clave`='$contraseña',`nombre`='$nombre',`apellidos`='$apellidos',`cedula`='$cedula',`last_num_cc`='$last_num_cc',`telefono`='$telefono',`correo`='$correo',`direccion`='$direccion',`profesion`='$profesion',`cargo`='$cargo',`tipo_persona`='$tipo_persona',`resp_iva`='$resp_iva',`idRol`='$idRol',`genero`='$genero',`fecha_nacimiento`='$fechaNacimiento', `vacaciones`='$vacaciones',`rut`='$rut',`actividad_e`='$actividad_e',`nro_cuenta`='$nro_cuenta',`banco`='$banco',`tipo_cuenta`='$tipo_cuenta' WHERE id = $idUsuario";
}
//$query= "UPDATE usuario SET usuario=$usuario,clave=$contraseña,nombre=$nombre, apellidos=$apellidos, cedula=$cedula, last_num_cc=$last_num_cc, telefono=$telefono, correo=$correo, direccion=$direccion, profesion=$profesion,cargo=$cargo, tipo_persona=$tipo_persona,resp_iva=$resp_iva,idRol=$idRol WHERE id = $idUsuario";
$result = mysqli_query($conexion,$query);


if($result){
    header("location:listar.php");
}else{
    ?>
    <?php
    ?>
    <h1 class="bad">ERROR EN LA AUTENTIFICACION</h1>
    <?php
}
//mysqli_free_result($resultado);
mysqli_close($conexion);
