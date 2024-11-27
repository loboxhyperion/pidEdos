<?php

$fechaInicio = $_POST['fecha_ini'];
$fechaFin = $_POST['fecha_fin'];
$valorMes= $_POST['valorMes'];
$valorDia= $valorMes / 30;

function esBisiesto($año){
    return ($año % 4 == 0 && ($año % 100 != 0 || $año % 400 == 0));
}

function diasExtraFebrero($año) {
    return esBisiesto($año) ? 1 : 2;
}

function calcularDiasRango($inicio, $fin) {
    $fechaInicio = new DateTime($inicio);
    $fechaFin = new DateTime($fin);
    $fechaFin->modify('+1 day'); // Incluimos el día final en el rango

    $diasTotales = 0;
    $diasExtraFebrero = 0; // Variable para días adicionales según año bisiesto o no
    $febreroSumado = false; // Control para evitar sumar varias veces en febrero

    while ($fechaInicio < $fechaFin) {
        $año = (int)$fechaInicio->format('Y');
        $mes = (int)$fechaInicio->format('m');
        $dia = (int)$fechaInicio->format('d');

        // Excluir días 31
        if ($dia != 31) {
            $diasTotales++;
        }

        // Determinar días adicionales de febrero (1 si es bisiesto, 2 si no lo es)
        if ($mes == 2 && !$febreroSumado) {
            $diasExtraFebrero = diasExtraFebrero($año);
            $diasTotales += $diasExtraFebrero;
            $febreroSumado = true;
        }

        $fechaInicio->modify('+1 day'); // Avanzar al siguiente día
    }

    return $diasTotales;
}

function generarActas($inicio, $fin,$diasTotales,$valorDia,$presupuesto) {

    $proyeccionActas = [];
    $fechaInicioActa = new DateTime($inicio);
    $diasRestantes = $diasTotales;
    $actaNumero = 1;

    $saldo = $presupuesto;
    $acumulado = 0;
    
    $diaInicioEnero = (int)$fechaInicioActa->format('m') == 1 ? (int)$fechaInicioActa->format('d') : 0; // manejo especial dias 29 y 30 enero

    while ($diasRestantes > 0) {
        $diasActa = min($diasRestantes, 30); // Cada acta tiene un máximo de 30 días
        $fechaFinActa = clone $fechaInicioActa;
        $diasValidos = 1;
        $repetirDia = diasExtraFebrero((int)$fechaInicioActa->format('Y'));//para los días febreros
        $esBisiesto = $repetirDia ;

        


        while ($diasValidos <= $diasActa) {
            $dia = (int)$fechaFinActa->format('d');
            $mes = (int)$fechaFinActa->format('m');
            // echo $diasValidos."validos ".$fechaFinActa->format('d-m-Y')."<br>";
            $diasASumar = 1;


            // Manejo especial para febrero año no bisiesto
            //Febrero acta # 2 con  inicio dias 29 0 30 enero
            // para el 29 se repite el dia sin sumar dias
            // para el 30 simplemente se suma 1 dia
            if($actaNumero == 2 && ($diaInicioEnero == 29 || $diaInicioEnero == 30) && $validarDiaEnero == 1 && $repetirDia > 0 ){ 
                $diasValidos++;
                $diasASumar = $diaInicioEnero == 30 ? 1 : 0;
                $repetirDia= 0;
            }elseif($mes == 2 && $repetirDia == 1 && $dia == 29){
                $diasValidos++;
                $diasASumar = 0;
                $repetirDia--;
            } elseif($mes == 2 && $repetirDia > 0 && $dia == 28 && $esBisiesto != 1){
                $diasValidos++;
                $diasASumar = 0;
                $repetirDia--;
            }elseif ($dia != 31) { //evita los 31
                $diasValidos++;
            }
            
            if ($diasValidos <= $diasActa) {
                $fechaFinActa->modify("+$diasASumar day");
            }    
            
        }
        // TO-DO calcula el presupuesto del acta
        $valorActa = calcularPresupuesto($diasActa, $valorDia);
        $acumulado += $valorActa; 
        $saldo -= $valorActa;
        // fecha de inicio en enero con dias 29 y 30 
        //  solo escluye en el año bisiesto el 29
        $validarDiaEnero =    ($esBisiesto == 1 && $fechaInicioActa->format('d') == 30) || ($esBisiesto != 1 && $fechaInicioActa->format('m') == 1 && 
        $fechaInicioActa->format('d') == 29   ||  $fechaInicioActa->format('d') == 30) ? 1 : 0;
        // $validarDiaEnero = $fechaInicioActa->format('m') == 1 && ($fechaInicioActa->format('d') == 29   ||  $fechaInicioActa->format('d') == 30) 
        // ? 1 : 0;
        
        // Guardar la acta actual
        $proyeccionActas[] = [
            'acta' => $actaNumero,
            'inicio' => $fechaInicioActa->format('Y-m-d'),
            'fin' => $fechaFinActa->format('Y-m-d'),
            'dias' => $diasActa,
            'valorDia' => number_format($valorDia,2),
            'valorMes' => number_format($valorActa,2),
            'acumulado' => number_format($acumulado, 2),
            'saldo' => number_format($saldo, 2),
            ''
        ];


        // Preparar para la siguiente acta
        $diasRestantes -= $diasActa;
        $actaNumero++;
        $fechaInicioActa = clone $fechaFinActa;
        
        // La siguiente acta inicia al día siguiente
        //  siempre y cuando no se comienze el 29 o 30 de enero
        // para ese caso la fecha queda inicial sin aumentar al dia siguiente
        $fechaInicioActa = $validarDiaEnero ? $fechaInicioActa : $fechaInicioActa->modify('+1 day'); 
        $fechaInicioActa = (int)$fechaInicioActa->format('d') == 31 ? $fechaInicioActa->modify('+1 day'): $fechaInicioActa;
    }

    return $proyeccionActas;
}

function calcularPresupuesto($diasActa,$valorDia){
    return $valorDia * $diasActa;;
}
$dias = calcularDiasRango($fechaInicio, $fechaFin);
$totalActas = ceil($dias/30);

//calculos presupuesto
$presupuesto = $valorDia * $dias;
$saldo = $dias  * $valorDia;

// echo "<br>". $dias;
// echo "<br>". $actasNum;

$proyeccion = generarActas($fechaInicio, $fechaFin,$dias,$valorDia,$presupuesto);


echo "El número de días en el rango, excluyendo los días 31 y sumando 2 días a febrero, es<strong>: $dias</strong>días.";

// Mostrar resultados
echo "<br><strong>Número total de actas:</strong> $totalActas";

// Mostrar resultados
echo "<br><strong>Prsupuesto:</strong>".number_format($presupuesto,2)."<br>";

// Mostrar resultados
echo "<strong>Proyección de actas:</strong>\n<br>";
foreach ($proyeccion as $acta) {
    echo "Acta {$acta['acta']}: desde {$acta['inicio']} hasta {$acta['fin']} dias: {$acta['dias']} valordia: {$acta['valorDia']} valorMes: {$acta['valorMes']} Acumulado: {$acta['acumulado']} Saldo: {$acta['saldo']}<br>";
}





?>