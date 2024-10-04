<?php

function request_method(string $check = null)
{
    if ($check === null) {
        return $_SERVER['REQUEST_METHOD'];
    }
    return strtoupper($check) == $_SERVER['REQUEST_METHOD'];
}

function request_uri(){
    return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
}

function request_queries(){
    return $_SERVER['QUERY_STRING'];
}

