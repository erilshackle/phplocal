<?php

// Load core (backend)
require_once __DIR__ . '/core/mainscript.php';

// load vendor
$vendor_var =  __DIR__ . '/vendor/autoload.php';
file_exists($vendor_var) && require_once $vendor_var;
unset($vendor_var);

// start session if not
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// config site var
define('CONFIG_SITE', [
    'name' => '',
    'shortname' => '',
    'url' => base_url,
    'email' => '',
    'descripton' => '',
    'link_facebook' => 'https://facebook.com/',
    'link_instagram' => 'https://instagram.com/',
    'link_linkedin' => 'https://linkedin.com/',
    'link_twitter' => 'https://twitter.com/',
    'link_x' => 'https://x.com/',
]);