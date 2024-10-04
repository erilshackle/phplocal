<?php


function upload_get_file(string $file, $default = null)
{
    $file = CONFIG_PATH['uploads'] . "/$file";
    if (file_exists($file)) {
        return $file;
    }
    return $default;
}

function upload_get_link(string $filename, $default = '')
{
    $file = CONFIG_PATH['uploads'] . "/$filename";
    if (file_exists($file)) {
        return CONFIG_URL['uploads'] . "$file";
    }
    return $default;
}