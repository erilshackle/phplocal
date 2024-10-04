<?php


// dd
function ddie($var)
{
    echo '<pre>';
    print_r($var);
    echo '</pre>';
    exit;
}

function dd($variable){
    return ddie($variable);
}

