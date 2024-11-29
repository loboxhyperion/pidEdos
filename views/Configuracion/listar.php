<?php
//seguridad de sessiones paginacion
session_start();
error_reporting(0);

require '../../db.php';
include("../partials/layout.php");
require('../partials/menu.php');

//si no hay algun usuario registradose devuelve al login
if(!isset($_SESSION['rol'])){
    header("location:../../index.php");
}else{   
    //solo tiene permiso el admin y creador
    if($_SESSION['rol'] == 3 or $_SESSION['rol'] == 4  ){
        header("location:../../index.php");
    }
}
// Consulta para obtener todos los registros

$consulta= "SELECT * FROM configuracion";
$configuraciones = mysqli_query($conexion,$consulta);

$configuracionesPortables = [];

    
foreach($configuraciones as $config){
    $configuracionesPortables [] = [
        'nombre' => $config["nombre_configuracion"],
        'valor' => $config["valor"]
    ];
    $_SESSION['config'] = $config;
}

$_SESSION['config'] = $configuracionesPortables;

//define las rutas para poder navegar en el menu
$_SESSION['rutaHome'] = "../../home.php";
$_SESSION['rutaUsuario'] = "../Usuario/listar.php";
$_SESSION['rutaCategoria'] = "../CategoriaProfesional/listar.php";
$_SESSION['rutaRetencion'] = "../Retencion/listar.php";
$_SESSION['rutaConfiguracion'] = "listar.php";
$_SESSION['rutaActasPendientes'] = "../Contrato/Acta/actasPendientes.php";
$_SESSION['rutaAlcancesGlobales'] = "../Contrato/alcancesGlobales.php";
$_SESSION['rutaInformeSupervisor'] = "../InformeSupervisor/listar.php";
$_SESSION['rutaContratos'] = "../Contrato/listar.php";
$_SESSION['rutaCerrarSesion'] = "../../cerrar_sesion.php";

?>
<div class="container">
    <h1 class="text-center blanco" blanco>Listado de Configuraciones</h1>
    <a href="nuevo.php" class="btn btn-primary">Agregar Nueva Configuración</a>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Valor</th>
                <th>Activo</th>
                <th style="width:5%;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($configuraciones as $config): ?>
            <tr>
                <td><?= htmlspecialchars($config['nombre_configuracion']) ?></td>
                <td><?= htmlspecialchars($config['valor'])?></td>
                <td><?= $config['activo'] ? 'Sí' : 'No' ?></td>
                <td>
                    <div style="display:flex; justify-content:space-between">
                        <a class="btn btn-warning btn-sm" href="editar.php?id=<?= $config['id_configuracion'] ?>"><i
                                class="far fa-edit" style="color:#fff;"></i></a>
                        <a class="btn btn-danger btn-sm" href="eliminar.php?id=<?= $config['id_configuracion'] ?>"><i
                                class="fas fa-trash-alt" style="color:#fff;"></i></a>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>