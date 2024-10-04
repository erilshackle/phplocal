<?php

// define('ROOT', dirname(__FILE__, 3));
define('ROOT', __DIR__ . '/../..');

/**
 * 
 */
define('CONFIG_PATH', [
    'public' => ROOT . '/public',
    'includes' => ROOT . '/includes',
    'layouts' => ROOT . '/layouts',
    'uploads' => ROOT . '/uploads',
    'templates' => ROOT . '/templates',

    'src' => ROOT . '/src',
    'routes' => ROOT . '/src/routes',
    'middlewares' => ROOT . '/src/middlewares',
    'srvices' => ROOT . '/src/srvices',
    'models' => ROOT . '/src/models',
]);