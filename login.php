<?php 
include("views/partials/layout.php");
session_start();
//si ya hay una session activa
if(isset($_SESSION['rol'])){
    header("location:home.php");
}
?>
<link rel="stylesheet" href="public/css/login.css">
<main class="main">
	<section class="login container">
		<div class="login_intro">
			<h1 class="login_title">PID</h1>
			<p class="login_paragraph">Plataforma institucional pagos</p>
			</div>
		<div class="login_split">
			<hr>
		</div>
		<form action="validar.php" class="login_form" method="post">
				<div class="login_input">
					<i class="fas fa-user"></i>
					<input type="username" placeholder="Usuario" class="box" name="usuario">
				</div>
				<div class="login_input">
					<i class="fas fa-lock"></i>
					<input type="password" placeholder="Contraseña" class="box" name="contraseña">
				</div>
				<div class="login_buttons">
					<a href="pase.php" class="login_btnNew"><i class="fas fa-plus"></i> Registro</a>
					<input type="submit" class="login_submit" value="Entrar">
				</div>
		</form>

		<div class="login_footer">
			<p class="login_footer_paragraph">EMPRESA DE DESARROLLO URBANO Y RURAL DE DOSQUEBRADAS</p>
			<h1 class="login_footer_title">EDOS</h1>
		</div>

	</section>
</main>
<!--
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
                <option value="<?php echo date("Y")?>"><?php echo date("Y")?></option>
				<option value="2023">2023</option>
        </select>
	</div>
	<br>
	<button class="btn-login">Login</button>-->
</form>



