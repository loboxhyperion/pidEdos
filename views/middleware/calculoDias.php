<?php 
//Calculo de dias y de actas del contrato
//la diferencia entre fechas en segundos
function actas_dias($fecha_ini,$fecha_fin,$valorDia){
    global $actasNum;
    //resta las fechas
    $secs = strtotime($fecha_fin) - strtotime($fecha_ini);
    //saca las actas temporales
    $actasTemp =  (round($secs /86400)+1)/30;
    //saca los dias temporales de la resta de fechas

    $diasTemp = intval(date('t', strtotime($fecha_ini))) == 31 ? round($secs /86400): round($secs /86400);
    echo "<br> <strong>dias temporales:</strong>".   $diasTemp ;
    //echo "<br>".   $actasTemp ;
    $actaFraccion =  $actasTemp - intval($actasTemp);
    $actasNum = $actaFraccion < 0.2 ? intval($actasTemp) : ceil($actasTemp);//operador ternario solo valido hasta 4 dias
    echo "<br> <strong> N° actas:</strong> ".   $actasNum ;
    $diasDesc = dias_descontar($fecha_ini,$fecha_fin,$actasNum,$valorDia);
 
    echo "<br> descontados:". $diasDesc; 
    return $diasTemp - $diasDesc ;
}

function dias_descontar($fecha_ini,$fecha_fin,$actas,$valorDia){
    $mesIni = 0;
    for($i = 1; $i<=$actas; $i++){
        if($i < $actas  ||  intval(date('t', strtotime($fecha_fin))) != 31 || intval(date('t', strtotime($fecha_ini))) == 31 ){
            echo "<br>". intval(date('t', strtotime($fecha_ini))) ;
            echo "<br> mes:".  $fecha_ini; 
            $mesIni += intval(date('t', strtotime($fecha_ini))) == 31 ? 1 : 0;
            $fecha_ini = date('Y-m-d', strtotime($fecha_ini."+ 1 months"));
            if(intval(date('m', strtotime($fecha_ini))) == 3){
                $fecha_ini = date('Y-m-d', strtotime($fecha_ini."- 2 days"));
            }
           // if($i == $actas && intval(date('m', strtotime($fecha_fin))) == 1){
            //    $mesIni -= 1;
            //}
        }
        else{       
            echo "<br> perdiodo de inicio :".  $fecha_ini . " // ". "perdiodo de fin:". $fecha_fin;
        }
    }
    return $mesIni ;
}
?>