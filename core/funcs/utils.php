<?php


function current_script_file()
{
    return CONFIG_PATH['public'] . str_replace('public', '', $_SERVER['PHP_SELF']);
}