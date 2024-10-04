<?php


function response_json($json){
    header('Content-Type: application/json');
    echo $json;
}

function response_getJson(){
    $json = file_get_contents('php://input');
    $json = json_decode($json, true);
    return $json;
}

function response_error401(){
    header('HTTP/1.1 401 Unauthorized', true, 401);
    echo '{"error": "Unauthorized"}';
}