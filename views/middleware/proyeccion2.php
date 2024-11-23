<?php
function proyeccionCalculo($idModificaciones,$puntoPartida,$fecha_ini,$fecha_fin,$fecha_suspension,$fecha_fin_new,$valorDia,$valorMes,$actasNum,$acomulado,$saldo, $idContrato,$tipo){
    //por la posicion de las carpetas toca
    if($tipo == "Inicial"){
        include('../../db.php');
    }else{
        include('../../../db.php');
    }
    ///-------------------------------------------------------------------------------------
       ///-------------------------------------------------------------------------------------
    /*echo "<br>".$puntoPartida;
    echo "<br>".$fecha_ini;
    echo "<br>".$fecha_fin;
    echo "<br>".$actasNum;
    echo "<br>".$idContrato;*/
    //$filas=mysqli_fetch_array($result2);
    ///-------------------------------------------------------------------------------------
       ///-------------------------------------------------------------------------------------
           ///-------------------------------------------------------------------------------------
    $periodoInicial = $fecha_ini;
    for($i = $puntoPartida; $i<= $actasNum; $i++){ 
        $flagAtemporal = false;
        echo"<br>iteracion:".$i;
        //echo "<br>". $acomulado;
        //echo "<br>". $saldo;
        //Proyeccion en fechas

      
        if($i == 1 or $i == $puntoPartida){
            $periodoInicial= date("d-m-Y",strtotime($fecha_ini)); 
            $nextMonth = date("d-m-Y",strtotime($fecha_ini."+ 1 month"));
        }else{
            $periodoInicial = $nextMonth;
            $nextMonth= date("d-m-Y",strtotime($nextMonth."+ 1 month"));
            
        }

        //consulta y almacena todas las suspensiones hechas del contrato
        $query2= "SELECT * FROM `adicion_suspension` WHERE idContrato = $idContrato";
        $result2 = mysqli_query($conexion,$query2) or die ("No se puede establecer conexion con la DB.");
        if($numRow = mysqli_num_rows($result2)){
            //para ajustar los dias de suspension 
            for($j=1; $j <= $numRow; $j++){
                $filas=mysqli_fetch_array($result2);
                echo"<br>iteracion atemporal:".$j;
                echo"<br>fecha ini atemporal:".date('d-m-Y', strtotime($periodoInicial));
                echo"<br>fecha fin atemporal:".$filas['fecha_suspension'];
                echo"<br>fecha de post acta:".date('d-m-Y', strtotime($filas['fecha_reinicio']));
                //$casoEspecial= intval(date('d', strtotime($filas['fecha_suspension']))) == intval(date('d', strtotime($periodoFinal."+ 1 days"))) ? True : False;
                //if(intval(date('m', strtotime($periodoInicial ))) == intval(date('m', strtotime($filas['fecha_suspension']))) && intval(date('d', strtotime($periodoInicial))) != intval(date('d', strtotime($filas['fecha_suspension']."+ 1 days"))) && $tipo =="Corte Atemporal"){
                if(date('d-m-Y', strtotime($filas['fecha_reinicio'])) ==  date('d-m-Y', strtotime($periodoInicial)) && $tipo =="Corte Atemporal"){
                    $flagAtemporal = true;
                    $fecha_suspension = $filas['fecha_suspension'];
                    $nextMonth = date('d-m-Y', strtotime($periodoInicial."+ ". $filas['diasTotal']." days"));
                    echo "<br> Atemporal:". $nextMonth;
                    /*if( $filas['dias'] == $filas['diasTotal'] && intval(date('t', strtotime($nextMonth ))) == 31 ){
                        $nextMonth = date('d-m-Y', strtotime($nextMonth ."+ ". (31) ." days"));
                    }else{
                            $nextMonth = date('d-m-Y', strtotime($nextMonth ."+ ". (30) ." days"));
                    }*/
                        
                    echo "<br>". $periodoInicial;
                    //$nextMonth = date('d-m-Y', strtotime($periodoInicial."+ ". ($diasModi) ." days"));
                    echo "<br>". $nextMonth;
                    //$nextMonth= date("d-m-Y",strtotime($nextMonth."+ 1 month"));
                    //echo "<br>". $nextMonth;*/
                }
            }
        }

       

        //Proyección en  presupuesto
        if($i == $actasNum ) {
            $periodoFinal = date("d-m-Y",strtotime($fecha_fin_new)); 
            if($tipo =="Suspension" || $tipo =="Corte Atemporal"){
                $mesIni = 0;
                echo "<br> hola". $mesIni ;
                $diasTemp = actas_dias1($periodoInicial,$periodoFinal,$valorDia) -  $mesIni ;
            }else if($tipo =="Adicion" or $tipo =="Finalizar" or $tipo =="Cesion"){
                $diasTemp = actas_dias1($periodoInicial,$periodoFinal,$valorDia) ;
            }else{
                $diasTemp = actas_dias($periodoInicial,$periodoFinal,$valorDia);
            }
            
            echo "<br> holasa". $diasTemp;
            $valorMes = date("d",strtotime($periodoInicial)) == 1  && date("d",strtotime($periodoFinal)) == 30 ? $valorMes : $valorDia * $diasTemp;
            $acomulado = intval($acomulado + $valorMes);
        }else if($flagAtemporal){
            $flagAtemporal = false;
            $diasTemp = actas_dias1($periodoInicial,$fecha_suspension,$valorDia);
            $valorMes =  $valorDia * $diasTemp;
            $acomulado = intval($acomulado + $valorMes);
            $periodoFinal = date("d-m-Y",strtotime($nextMonth."- 1 days")); 
        }else{
            $diasTemp = 30;
            $valorMes =  $valorDia * $diasTemp;
            $acomulado = $acomulado + $valorMes;
            $periodoFinal = date("d-m-Y",strtotime($nextMonth."- 1 days")); 
        }
        $saldo = intval($saldo - $valorMes) ;
        $query3= "INSERT INTO `proyeccion_contractual`(`num_acta`, `periodo_ini`, `periodo_fin`, `dias`, `valor_dia`, `valor_mes`, `acomulado`, `saldo`, `tipo`, `prioridad`,`idContrato`,`idModificaciones`)
                  VALUES ('$i','$periodoInicial','$periodoFinal','$diasTemp','$valorDia','$valorMes','$acomulado','$saldo','$tipo',1,'$idContrato','$idModificaciones')";
        $result3 = mysqli_query($conexion,$query3);
        //$result3 = false;
        echo "<br>". $periodoInicial . " // ". $periodoFinal ."------------------------" . $acomulado. " // ". $saldo ."<br>";
    }
    if($result3){
        return true;
    }else{
        return false;
    }
    mysqli_close($conexion);
}
?>