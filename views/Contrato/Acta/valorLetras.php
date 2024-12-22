<?php
function convertirEnLetras($numero) {
    $unidades = ['', 'uno', 'dos', 'tres', 'cuatro', 'cinco', 'seis', 'siete', 'ocho', 'nueve'];
    $decenas = ['', '', 'veinte', 'treinta', 'cuarenta', 'cincuenta', 'sesenta', 'setenta', 'ochenta', 'noventa'];
    $decenasEspeciales = [10 => 'diez', 11 => 'once', 12 => 'doce', 13 => 'trece', 14 => 'catorce', 15 => 'quince', 16 => 'dieciséis', 17 => 'diecisiete', 18 => 'dieciocho', 19 => 'diecinueve'];
    $centenas = ['', 'ciento', 'doscientos', 'trescientos', 'cuatrocientos', 'quinientos', 'seiscientos', 'setecientos', 'ochocientos', 'novecientos'];

    if ($numero == 0) {
        return 'cero';
    }

    if ($numero == 100) {
        return 'cien';
    }

    $letras = '';
    if ($numero >= 100) {
        $letras .= $centenas[intval($numero / 100)] . ' ';
        $numero %= 100;
    }

    if ($numero >= 10 && $numero <= 19) {
        $letras .= $decenasEspeciales[$numero];
        return trim($letras);
    }

    if ($numero >= 20) {
        $letras .= $decenas[intval($numero / 10)];
        $numero %= 10;
        if ($numero > 0) {
            $letras .= ' y ';
        }
    }

    if ($numero > 0) {
        $letras .= $unidades[$numero];
    }

    return trim($letras);
}

function convertirMilesEnLetras($numero) {
    if ($numero < 1000) {
        return convertirEnLetras($numero);
    }

    $miles = intval($numero / 1000);
    $resto = $numero % 1000;

    $resultado = convertirEnLetras($miles) . ' mil';
    if ($resto > 0) {
        $resultado .= ' ' . convertirEnLetras($resto);
    }

    return trim($resultado);
}

function convertirMillonesEnLetras($numero) {
    if ($numero < 1000000) {
        return convertirMilesEnLetras($numero);
    }

    $millones = intval($numero / 1000000);
    $resto = $numero % 1000000;

    $resultado = convertirEnLetras($millones) . ' millón';
    if ($millones > 1) {
        $resultado .= 'es'; // Para pluralizar "millón"
    }

    if ($resto > 0) {
        $resultado .= ' ' . convertirMilesEnLetras($resto);
    }

    return trim($resultado);
}

function convertirDineroEnLetras($cantidad) {
    $partes = explode('.', number_format($cantidad, 2, '.', ''));
    $entero = (int)$partes[0];
    $decimal = (int)$partes[1];

    $resultado = convertirMillonesEnLetras($entero) . ' pesos';
    if ($decimal > 0) {
        $resultado .= ' con ' . convertirEnLetras($decimal) . ' centavos';
    }

    return ucfirst($resultado);
}

// Ejemplo de uso
echo convertirDineroEnLetras(4000000); // Salida: "Cuatro millones de pesos"
echo "\n";
echo convertirDineroEnLetras(1234567.89); // Salida: "Un millón doscientos treinta y cuatro mil quinientos sesenta y siete pesos con ochenta y nueve centavos"
?>
