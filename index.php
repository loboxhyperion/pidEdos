<?php 
include("views/partials/layout.php");
session_start();
//si ya hay una session activa
if(isset($_SESSION['rol'])){
    header("location:home.php");
}
?>
<link rel="stylesheet" href="public/css/login.css">
<div class="contenedor">
    <div class="logo">
        <img src="public/img/logo.png" alt="logo">
    </div>

    <div class="formulario">
        <form action="validar.php" class="formulario" method="post">
            <div class="login_input">
                <i class="fas fa-user"></i>
                <input type="username" placeholder="Usuario" class="box" name="usuario">
            </div>
            <div class="login_input">
                <i class="fas fa-lock"></i>
                <input type="password" placeholder="Contraseña" class="box" name="contraseña">
            </div>
            <input type="hidden" value="<?php echo date("Y")?>" name="año">
            <input type="submit" class="login_submit" value="Entrar">
        </form>
        <div class="login_footer">
            <!-- <a href="pase.php" class="login_btnNew">CREAR USUARIO</a> -->
            <!-- Button to Open the Modal -->
            <a href="#" class="login_btnNew" data-bs-toggle="modal" data-bs-target="#myModal1">CREAR USUARIO</a>
            <!--Apartado para recuperar clave -->
            <a href="#" class="login_btnNew">RECUPERAR CLAVE</a>
            <!-- The Modal -->
            <div class="modal" id="myModal1">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Validar Clave</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="validarPase.php" method="POST">
                            <!-- Modal body -->
                            <div class="modal-body">
                                Ingrese la clave para poder tener acceso:
                                <div class="form-group col-md-12">
                                    <input type="password" class="form-control" placeholder="Digite la contraseña" aria-label="contraseña" name="secret_key">
                                </div>
                            </div>
                            <!-- Modal footer -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                                <input type="submit" class="btn btn-primary" value="Continuar" />
                            </div>
                                                                    
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>


