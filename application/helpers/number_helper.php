<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

// Forme 30 000, 200
function format_number($number)
{
    return number_format($number, 2, ',', ' ') . " Ar";
}
function format_number_simple($number)
{
    return number_format($number, 0, ',', ' ');
}

// Forme 200 000 000
function number_three($nombre)
{
    if ( $nombre == ''){
        return '--' ; 
    }
    return number_format($nombre, 0, ',', ' ') . ' Ar';
}

function my_trim($string) {
    return preg_replace('/\s+/', '', $string);
}

function telephone($number) {
    return sprintf("%02d %02d %03d %02d", $number % 100, ($number / 100) % 100, ($number / 10000) % 1000, $number / 1000000);
}
