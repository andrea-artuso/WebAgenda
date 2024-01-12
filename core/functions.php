<?php

function is_valid_date($date){
    $arr = explode("-", $date);

    if (count($arr) != 3)
        return false;
    
    $gg = intval($arr[2]);
    $mm = intval($arr[1]);
    $yy = intval($arr[0]);
    if ($mm == 4 || $mm == 6 || $mm == 9 || $mm == 11){
        if ($gg > 30)
            return false;
    } else if ($mm == 2){
        if ($yy % 400 == 0 || ($yy % 4 == 0 && $yy % 100 != 0)){
            if ($gg > 29)
                return false;
        } else if ($gg > 28)
            return false;
    } else if ($gg > 31)
        return false;

    return true;
}