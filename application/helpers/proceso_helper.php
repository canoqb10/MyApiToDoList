<?php

if (!function_exists('getMes')) {

    function getMes($numMes) {
        switch ($numMes) {
            case '01': {
                    $mes_esp = 'Enero';
                    break;
                }
            case '02': {
                    $mes_esp = 'Febrero';
                    break;
                }
            case '03': {
                    $mes_esp = 'Marzo';
                    break;
                }
            case '04': {
                    $mes_esp = 'Abril';
                    break;
                }
            case '05': {
                    $mes_esp = 'Mayo';
                    break;
                }
            case '06': {
                    $mes_esp = 'Junio';
                    break;
                }
            case '07': {
                    $mes_esp = 'Julio';
                    break;
                }
            case '08': {
                    $mes_esp = 'Agosto';
                    break;
                }
            case '09': {
                    $mes_esp = 'Septiembre';
                    break;
                }
            case '10': {
                    $mes_esp = 'Octubre';
                    break;
                }
            case '11': {
                    $mes_esp = 'Noviembre';
                    break;
                }
            case '12': {
                    $mes_esp = 'Diciembre';
                    break;
                }
        }
        return $mes_esp;
    }

}