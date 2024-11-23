<?php 
include("../partials/layout.php"); 
include('../partials/menusub.php');
//seguridad de sessiones paginacion
session_start();
error_reporting(0);

 
//si no hay algun usuario registradose devuelve al login
//solo tiene permiso el admin y creador
if((!isset($_SESSION['rol']) or ($_SESSION['rol'] == 3 or $_SESSION['rol'] == 4 )) && !isset($_GET['tipo'])){
    header("location:../../index.php");
}

?>

<div class="container">
    <div class="" style="text-align:right;padding:5px;">
        <a href="listar.php" class="btn btn-primary">Volver</a>
        <hr>
        <h1 class="text-center blanco">Nuevo Perfil</h1>
        <hr>
    </diV>
    <?php
        if(isset($_GET['mensaje'])){
        ?>
    <div class="p-3 mb-2 bg-danger text-white">
        <?php  echo $_GET['mensaje']; ?>
    </div>
    <?php } ?>

    <form class="row g-3" action="./insertar.php" method="post">
        <div class="form-group col-md-2">
            <label for="usuario" class="form-label">Usuario</label>
            <input type="text" class="form-control" placeholder="Nombre de usuario" aria-label="usuario" name="usuario"
                required>
        </div>
        <br>
        
        <div class="form-group col-md-2">
            <label for="contraseña" class="form-label">Contraseña</label>
            <input type="password" class="form-control" placeholder="Digite la contraseña" aria-label="contraseña"
                name="contraseña" required>
        </div>
        <input type="hidden" class="form-control" aria-label="contraseña" name="idRol"
            value="<?php echo $_GET['tipo'] ?>">
        <br>
        <div class="form-group col-md-2">
            <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
            <input class="form-control" type="date" name="fecha_nacimiento" required>
        </div>
        <div class="form-group col-md-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" placeholder="Nombre" aria-label="nombre" name="nombre" required>
        </div>
        <br>
        <div class="form-group col-md-3">
            <label for="apellidos" class="form-label">Apellidos</label>
            <input type="text" class="form-control" placeholder="Apellidos" aria-label="apellidos" name="apellidos"
                required>
        </div>
        <br>
        <div class="form-group col-md-2">
            <label for="cedula" class="form-label">Cedula</label>
            <input type="number" class="form-control" placeholder="Número de cedula" aria-label="cedula" name="cedula"
                required>
        </div>
        <br>
        <div class="form-group col-md-2">
            <label for="resp_iva" class="form-label">Genero</label>
            <select class="form-select" aria-label="Default select example" name="genero" required>
                <option value="">Seleccionar</option>
                <option value="Hombre">Hombre</option>
                <option value="Mujer">Mujer</option>
            </select>
        </div>
        <br>
        <div class="form-group col-md-2">
            <label for="telefono" class="form-label">Telefono</label>
            <input type="text" class="form-control" placeholder="Número de telefono" aria-label="telefono"
                name="telefono" required>
        </div>
        <br>
        <div class="form-group col-md-3">
            <label for="correo" class="form-label">Correo</label>
            <input type="text" class="form-control" placeholder="Correo electronico" aria-label="correo" name="correo"
                required>
        </div>
        <br>
        <div class="form-group col-md-3">
            <label for="direccion" class="form-label">Direccion</label>
            <input type="text" class="form-control" placeholder="Direccion" aria-label="direccion" name="direccion"
                required>
        </div>
        <br>
        <div class="form-group col-md-6">
            <label for="profesion" class="form-label">Profesión</label>
            <input type="text" class="form-control" placeholder="Profesión" aria-label="profesion" name="profesion"
                required>
        </div>
        <br>
        <div class="form-group col-md-6">
            <label for="cargo" class="form-label">Cargo</label>
            <input type="text" class="form-control" placeholder="cargo" aria-label="cargo" name="cargo" required>
        </div>
        <br>
        <div class="form-group col-md-2">
            <label for="rut" class="form-label">RUT</label>
            <input type="text" class="form-control" placeholder="Número de rut" aria-label="rut" name="rut" required>
        </div>
        <br>
        <div class="form-group col-md-2">
            <label for="actividad_e" class="form-label">Actividad Económica</label>
            <input type="text" class="form-control" placeholder="Escriba aquí" aria-label="actividad_e"
                name="actividad_e" required>
        </div>
        <br>
        <div class="form-group col-md-2">
            <label for="banco" class="form-label">Banco</label>
            <input type="text" class="form-control" placeholder="Nombre" aria-label="banco" name="banco" required>
        </div>
        <br>
        <div class="form-group col-md-3">
            <label for="nro_cuenta" class="form-label">Número de Cuenta</label>
            <input type="text" class="form-control" placeholder="Número" aria-label="nro_cuenta" name="nro_cuenta"
                required>
        </div>
        <br>
        <div class="form-group col-md-3">
            <label for="tipo_cuenta" class="form-label">Tipo de Cuenta</label>
            <select class="form-select" aria-label="Default select example" name="tipo_cuenta" required>
                <option value="">Seleccionar</option>
                <option value="Ahorros">Ahorros</option>
                <option value="Corriente">Corriente</option>
            </select>
        </div>
        <br>
        <div class="form-group col-md-3">
            <label for="nit" class="form-label">NIT</label>
            <input type="text" class="form-control" placeholder="Escriba aquí" aria-label="cargo" name="nit" required>
        </div>
        <br>
        <div class="form-group col-md-3">
            <label for="razon_social" class="form-label">Razón social</label>
            <input type="text" class="form-control" placeholder="Escriba aquí" aria-label="razon_social"
                name="razon_social" required>
        </div>
        <br>
        <div class="form-group col-md-3">
            <label for="tipo_persona" class="form-label">Tipo de persona</label>
            <select class="form-select" aria-label="Default select example" name="tipo_persona" required>
                <option value="">Seleccionar</option>
                <option value="Natural">Natural</option>
                <option value="Juridica">Jurídica</option>
            </select>
        </div>
        <br>
        <div class="form-group col-md-3">
            <label for="resp_iva" class="form-label">Responsable de iva</label>
            <select class="form-select" aria-label="Default select example" name="resp_iva" required>
                <option value="">Seleccionar</option>
                <option value="Si">Si</option>
                <option value="No">No</option>
            </select>
        </div>
        <input type="hidden" name="tipo" value="<?php echo $_GET['tipo']?>">
        <br>
        <div class="form-group col-md-12" style="text-align:right;">
            <input type="submit" class="btn btn-primary" value="Guardar" />
        </div>
        <br>
        <br>
    </form>

</div>
</div>