<?php
// router.php

// Inclui o core.php antes de qualquer requisição ser servida
require_once __DIR__ . '/core.php';

// Verifica se o arquivo solicitado existe dentro da pasta public
// Se existe, serve o arquivo normalmente
$file = __DIR__ . '/public' . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if (file_exists($file)) {
    // Serve o arquivo se ele existe
    return false;
} else {
    // Se o arquivo não existir, redireciona para o index.php ou exibe 404
    include __DIR__ . '/public/index.php';
}
