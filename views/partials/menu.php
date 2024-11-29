<?php
//seguridad de sessiones paginacion
session_start();
error_reporting(0);
//si no hay algun usuario registradose devuelve al login
if(!isset($_SESSION['rol'])){
    header("location:../../index.php");
}

//include("../../partials/layout.php");
$varsesion = $_SESSION['usuario'];
?>

<!-- los css y los js no se pueden importar en otro archivo html  con el include-->
<!-- toca así -->
<!--  -->
<!--  -->
<style>
<?php include '../../public/css/styles.css';
//include '../../public/css/sidebar.css'; 

?>
</style>
<!--  -->
<!--  -->


<div class="sidebar minimized">
    <button class="toggle-btn" id="toggleBtn">
        <i class="fas fa-bars"></i>
    </button>
    <div class="profile minimized">
        <img src="../../public/perfiles/<?php echo $_SESSION['imgPerfil']?>" alt="User Profile" id="profileImg">
        <h5><?php echo $varsesion["nombre"] . ' ' . $varsesion["apellidos"] ?></h5>
        <!-- Agregar el nombre de usuario aquí -->
        <p><?php echo $varsesion["cargo"]?></p> <!-- Agregar el tipo de usuario aquí -->

        <button class="imgPerfil" type="button" class="btn btn-primary" data-bs-toggle="modal"
            data-bs-target="#myModal1" data-bs-backdrop="static">Editar Foto</button>
        <!-- <a class="imgPerfil" href="../../editarPerfil.php" class="btn btn-primary">Editar Foto</a> -->

        <div class="divider"></div> <!-- Línea divisora -->
    </div>
    <ul class="menu">
        <li><a href="<?php echo $_SESSION['rutaHome'] ?>" title="Inicio"><i class="fas fa-home"></i> Inicio</a></li>
        <li class="submenu config">
            <a href="#" title="Configuración"><i class="fas fa-thin fa-gear"></i> Configuración</a>
            <ul>
                <li><a href="<?php echo $_SESSION['rutaUsuario'] ?>" title="Perfil"><i
                            class="fas fa-solid fa-user-large"></i>Perfil</a></li>
                <?php
                //Solo admin y creadores
                if ($_SESSION['rol'] == 1 or $_SESSION['rol'] == 2) {
                ?>
                <li><a href="<?php echo $_SESSION['rutaConfiguracion'] ?>" title="Configuraciones"><i
                            class="fas fa-solid fa-coins"></i>Configuraciones</a></li>
                <li><a href="<?php echo $_SESSION['rutaRetencion'] ?>" title="Retenciones"><i
                            class="fas fa-solid fa-coins"></i>Retenciones</a></li>
                <li><a href="<?php echo $_SESSION['rutaCategoria'] ?>" title="Sueldos"><i
                            class="fas fa-solid fa-dollar-sign"></i>Sueldos</a></li>

                <?php
                }
                ?>
            </ul>
        </li>
        <?php
        //Solo admin y supervisores
        if ($_SESSION['rol'] != 4) {
        ?>
        <li class="submenu revision">
            <a href="#"><i class="fas fa-chart-bar"></i> Revisión</a>
            <ul class="qr">
                <li><a href="<?php echo $_SESSION['rutaInformeSupervisor'] ?>" title="Informe Supervisor"><i
                            class="fas fa-thin fa-clipboard"></i>Informe Supervisor</a></li>
                <li><a href="<?php echo $_SESSION['rutaActasPendientes'] ?>" title="Actas Pendientes"><i
                            class="fas fa-thin fa-list-check"></i>Actas Pendientes</a></li>
                <li><a href="<?php echo $_SESSION['rutaAlcancesGlobales'] ?>" title="Alcances Globales"><i
                            class="fas fa-globe-americas"></i></i>Alcances Globales</a></li>
            </ul>
        </li>
        <?php
        }
        ?>
        <li class="submenu contrato">
            <a href="#" title="Contratos"><i class="fas fa-thin fa-file-signature"></i>Contratos</a>
            <ul>
                <li><a href="<?php echo $_SESSION['rutaContratos'] ?>" title="Contratista"><i
                            class="fas fa-solid fa-user-large"></i>Contratista</a></li>
                <?php
                //Solo admin y creadores
                if ($_SESSION['rol'] == 1 or $_SESSION['rol'] == 3) {
                ?>
                <li><a href="<?php echo $_SESSION['rutaContratosObra'] ?>" title="Obra"><i
                            class="fas fa-solid fa-helmet-safety"></i>Obra</a></li>

                <?php
                }
                ?>
            </ul>
        </li>
    </ul>
    <div class="divider"></div> <!-- Línea divisora -->
    <a class="logout" href="<?php echo $_SESSION['rutaCerrarSesion'] ?>" title="Salir"><i
            class="fas fa-sign-out-alt"></i>Salir</a>
</div>
<!-- este apartado hace parte de la ventana modal del boton con clase .imgPerfil se tuvo que sacar -->
<!-- porque no dejaba interactuar con el modal pero de esta manera -->
<!-- The Modal -->
<div class="modal" id="myModal1">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Cambiar foto de perfil</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo $_SESSION['rutaPerfil'] ?>" method="post" enctype='multipart/form-data'>
                <!-- Modal body -->
                <div class="modal-body">
                    <div class="form-group col-md-12">
                        <br>
                        <label class="form-label">
                            <h6>Nueva foto de perfil:</h6>
                        </label>
                        <input type="file" name="rutaPerfil" id="rutaPerfil" class="form-control" required>
                    </div>
                </div>
                <!---->
                <input type="hidden" class="form-control" name="id" value="<?php echo $varsesion["id"] ?>" />
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                    <input type="submit" class="btn btn-primary" value="Actualizar" />
                </div>

            </form>
        </div>
    </div>
</div>
<script>
const toggleBtn = document.getElementById("toggleBtn");
const profileImg = document.getElementById("profileImg");
const profile = document.querySelector(".profile");
const sidebar = document.querySelector(".sidebar");
// Obtener todos los elementos principales del menú que tienen submenús
const menuItemsWithSubmenu = document.querySelectorAll('.submenu li');

toggleBtn.addEventListener("click", () => {
    profile.classList.toggle("minimized");
    sidebar.classList.toggle("minimized");
});


// Agrega un evento de clic a la opción "Configuración"
// const configMenuItem2 = document.querySelector('.sidebar .menu li:nth-child(2)'); // Cambia el selector según la posición de "Configuración"
const configMenuItem2 = document.querySelector('.config'); // Cambia el selector según la posición de "Configuración"
// Agrega un evento de clic a la opción "Configuración"
const configMenuItem3 = document.querySelector('.revision'); // Cambia el selector según la posición de "Configuración"
// Agrega un evento de clic a la opción "Configuración"
const configMenuItem4 = document.querySelector('.contrato'); // Cambia el selector según la posición de "Contrato"

configMenuItem2.addEventListener('click', () => {
    // Encuentra el submenú asociado a la opción "Configuración"
    const submenu = configMenuItem2.querySelector('.submenu ul');
    //// Encuentra el submenú asociado a la opción "Revision"
    const submenuOpciones = configMenuItem3.querySelector('.submenu ul');
    //// Encuentra el submenú asociado a la opción "Contrato"
    const submenuOpciones2 = configMenuItem4.querySelector('.submenu ul');

    // Verifica si la clase está activa en el submenú "Revision"
    if (submenuOpciones.classList.contains("active")) {
        submenuOpciones.classList.toggle('active');
    }
    // Verifica si la clase está activa en el submenú "Contrato"
    if (submenuOpciones2.classList.contains("active")) {
        submenuOpciones2.classList.toggle('active');
    }
    // Alterna la clase "active" en el submenú para mostrarlo u ocultarlo
    submenu.classList.toggle('active');
});



configMenuItem3.addEventListener('click', () => {
    // Encuentra el submenú asociado a la opción "Revision"
    const submenu = configMenuItem3.querySelector('.submenu ul');
    //// Encuentra el submenú asociado a la opción "Configuraciónn"
    const submenuOpciones = configMenuItem2.querySelector('.submenu ul');
    //// Encuentra el submenú asociado a la opción "Contrato"
    const submenuOpciones2 = configMenuItem4.querySelector('.submenu ul');

    // Verifica si la clase está activa en el submenú "Configuración"
    if (submenuOpciones.classList.contains("active")) {
        submenuOpciones.classList.toggle('active');
    }
    // Verifica si la clase está activa en el submenú "Contrato"
    if (submenuOpciones2.classList.contains("active")) {
        submenuOpciones2.classList.toggle('active');
    }
    // Alterna la clase "active" en el submenú para mostrarlo u ocultarlo
    submenu.classList.toggle('active');
});

configMenuItem4.addEventListener('click', () => {
    // Encuentra el submenú asociado a la opción "Contrato"
    const submenu = configMenuItem4.querySelector('.submenu ul');
    // Encuentra el submenú asociado a la opción "Configuraciónn"
    const submenuOpciones = configMenuItem2.querySelector('.submenu ul');
    //// Encuentra el submenú asociado a la opción "Revision"
    const submenuOpciones2 = configMenuItem3.querySelector('.submenu ul');

    // Verifica si la clase está activa en el submenú "Configuración"
    if (submenuOpciones.classList.contains("active")) {
        submenuOpciones.classList.toggle('active');
    }

    // Verifica si la clase está activa en el submenú "Revision"
    if (submenuOpciones2.classList.contains("active")) {
        submenuOpciones2.classList.toggle('active');
    }
    // Alterna la clase "active" en el submenú para mostrarlo u ocultarlo
    submenu.classList.toggle('active');
});

// Cuando se abre la modal
// $('#myModal1').on('show.bs.modal', function () {
//     // Oculta el menú
//     $('.sidebar').hide();
// });

// // Cuando se cierra la modal
// $('#myModal1').on('hidden.bs.modal', function () {
//     // Muestra nuevamente el menú
//     $('.sidebar').show();
// });
</script>