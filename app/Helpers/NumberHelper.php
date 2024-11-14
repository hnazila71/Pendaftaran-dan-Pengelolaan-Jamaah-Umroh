<?php

if (!function_exists('format_number')) {
    function format_number($number)
    {
        return (intval($number) == $number) ? number_format($number, 0, '.', ',') : number_format($number, 2, '.', ',');
    }
}
