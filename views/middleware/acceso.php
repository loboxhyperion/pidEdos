<?php
function menuRol($rol){
    switch($rol){
        case 1:
             include('views/partials/menu_admin.php');
        break;
        case 2:
            include('views/partials/menu_admin.php');
        break;
        case 3:
            include('views/partials/menu_visitante.php');
        break;
        case 4:
            include('views/partials/menu_visitante.php');
        break;
    }
}

?>