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
        
        echo"<br> hola";
        //echo "<br>". $acomulado;
        //echo "<br>". $saldo;
        //Proyeccion en fechas

      
        if($i == 1 or $i == $puntoPartida){
            $periodoInicial= date("d-m-Y",strtotime($fecha_ini)); 
            //$nextMonth = date("d-m-Y",strtotime($_POST['fecha_ini']."+ 1 month"));
            $nextMonth = date("d-m-Y",strtotime($fecha_ini."+ 1 month"));
        }else{
            $periodoInicial = $nextMonth;
            $nextMonth= date("d-m-Y",strtotime($nextMonth."+ 1 month"));
            
        }

        if($tipo == "Suspension"){
             //consulta y almacena todas las suspensiones hechas del contrato
            $query2= "SELECT * FROM `adicion_suspension` WHERE idContrato = $idContrato";
            $result2 = mysqli_query($conexion,$query2) or die ("No se puede establecer conexion con la DB.");
            if($result2 ){ $numRow = mysqli_num_rows($result2);}
            
            //para ajustar los dias de suspension 
            for($j=1; $j <= $numRow; $j++){
                $filas=mysqli_fetch_array($result2);
            // echo "<br>". intval(date('m', strtotime($filas['fecha_suspension'] )));
                //echo "<br>". $j;
                if(intval(date('m', strtotime($periodoInicial ))) == intval(date('m', strtotime($filas['fecha_suspension']))) && $tipo =="Suspension"){
                    //echo "<br>". $filas['dias'];
                    //$diasModi = intval(date('t', strtotime($filas['fecha_suspension']))) == 31 ? $filas['dias'] : $filas['dias'];
                    /*if(intval(date('d', strtotime($periodoInicial ))) == intval(date('d', strtotime($filas['fecha_suspension'])))){
                        $diasModi = $diasModi -1;
                    }*/
                    $nextMonth = date('d-m-Y', strtotime($periodoInicial."+ ". $filas['diasTotal']." days"));
                    echo "<br>". $nextMonth;
                    if( $filas['dias'] == $filas['diasTotal'] && intval(date('t', strtotime($nextMonth ))) == 31 ){
                        $nextMonth = date('d-m-Y', strtotime($nextMonth ."+ ". (31) ." days"));
                    }else{
                        $nextMonth = date('d-m-Y', strtotime($nextMonth ."+ ". (30) ." days"));
                    }
                    
                    echo "<br>". $periodoInicial;
                    //$nextMonth = date('d-m-Y', strtotime($periodoInicial."+ ". ($diasModi) ." days"));
                    echo "<br>". $nextMonth;
                    //$nextMonth= date("d-m-Y",strtotime($nextMonth."+ 1 month"));
                    //echo "<br>". $nextMonth;
                }
            }
        /* //para ajustar los dias de suspension 
            for($j=1; $j <= $numRow; $j++){
                $filas=mysqli_fetch_array($result2);
            // echo "<br>". intval(date('m', strtotime($filas['fecha_suspension'] )));
                //echo "<br>". $j;
                if(intval(date('m', strtotime($periodoInicial ))) == intval(date('m', strtotime($filas['fecha_suspension']))) && $tipo =="Suspension"){
                    //echo "<br>". $filas['dias'];
                    $diasModi = intval(date('t', strtotime($filas['fecha_suspension']))) == 31 ? $filas['dias'] - 1: $filas['dias'];
                    $nextMonth = date('d-m-Y', strtotime($nextMonth."+ ". ($diasModi) ." days"));
                    echo "<br>". $nextMonth;
                }
            }*/

        }
       

        //Proyección en  presupuesto
        if($i == $actasNum) {
            $periodoFinal = date("d-m-Y",strtotime($fecha_fin_new)); 
            //date("d",strtotime($fecha_fin_new)) == 31 ? 30 : date("d",strtotime($fecha_fin_new));
            //$secs = strtotime($fecha_fin_new) - strtotime($periodoInicial ) ;
            //$diasTemp =  (round($secs /86400)+1) == 31 || (round($secs /86400)+1) ==29 ? 30 : (round($secs /86400)+1);
            if($tipo =="Suspension"){
                $mesIni = 0;
                //$mesIni = intval(date('t', strtotime($periodoInicial))) == 31 && intval(date('d', strtotime($periodoInicial ))) ==31 ? 1 : 0;
                echo "<br> hola". $mesIni ;
                $diasTemp = actas_dias1($periodoInicial,$periodoFinal,$valorDia) -  $mesIni ;
            }else if($tipo =="Adicion" or $tipo =="Finalizar" or $tipo =="Cesion"){
                $diasTemp = actas_dias1($periodoInicial,$periodoFinal,$valorDia) ;
            }else{
                $diasTemp = actas_dias($periodoInicial,$periodoFinal,$valorDia);
            }
            
            //date("d",strtotime($periodoFinal))
            //if()
            echo "<br> holasa". $diasTemp;
            $valorMes = date("d",strtotime($periodoInicial)) == 1  && date("d",strtotime($periodoFinal)) == 30 ? $valorMes : $valorDia * $diasTemp;
            $acomulado = intval($acomulado + $valorMes);
        }else{
            $diasTemp = 30;
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
    //mysqli_free_result($resultado);
    mysqli_close($conexion);
}
?>