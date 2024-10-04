<?php

spl_autoload_register(function ($class) {
    $root = __DIR__ . '/../..';
    $paths = ['', 'core/classes'];
    $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    foreach ($paths as $path) {
        $file = "$root/$path/$class.php";
        if (file_exists($file)) {
            return require_once $file;
        }
    }
});

$directories = [
    __DIR__ . '/../funcs',
    __DIR__ . '/../configs'
];

foreach ($directories as $dir) {
    if (is_dir($dir)) {
        foreach (glob($dir . '/*.php') as $filename) {
            require_once $filename;
        }
    }
}

// Todos os arquivos PHP das pastas foram carregados.