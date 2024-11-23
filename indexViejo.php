<?php 
include("views/partials/layout.php");
session_start();
//si ya hay una session activa
if(isset($_SESSION['rol'])){
    header("location:home.php");
}
?>
<link rel="stylesheet" href="public/css/style.css">
<div class="" style="text-align:right;padding:5px;">
        <a href="pase.php" class="btn btn-primary" name="">Nuevo Usuario</a>
</div>
<form action="validar.php"  class="login-container" method="post">
	<img src="public/img/logo.png" alt="">
	<p>Code Web</p>
	<div class="fields">
		<div class="data">
			<i class="fas fa-user"></i>
			<input type="username" placeholder="Usuario" name="usuario">
		</div>
	</div>
	<div class="fields">
		<div class="data">
			<i class="fas fa-lock"></i>
			<input type="password" placeholder="Contraseña" name="contraseña">
		</div>
	</div>
	<div class="fields">
		<select class="form-select" aria-label="Default select example" name="año">
		        <option value="2022">2022</option>
                <option value="<?php echo date("Y")?>" selected><?php echo date("Y")?></option>
        </select>
	</div>
	<br>
	<button class="btn-login">Login</button>
</form>

