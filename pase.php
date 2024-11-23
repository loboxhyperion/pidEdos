<?php 
include("views/partials/layout.php");
?>
<br />
<div class="container">
    <div class="col-md-12">
        <form class="row g-3" action="validarPase.php" method="post">
            <div class="form-group col-md-12">
                <label for="contraseña" class="form-label">Contraseña</label>
                <input type="password" class="form-control" placeholder="Digite la contraseña" aria-label="contraseña" name="secret_key">
            </div>

            <div class="form-group col-md-12" style="text-align:right;">
                <input type="submit" class="btn btn-primary" value="Guardar" />
            </div>
            <br>
            <br>
        </form>
        
    </div>
</div>
