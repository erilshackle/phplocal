<?php

// Get the requested URI and clean it
$uri = trim($_SERVER['REQUEST_URI'], '/');

// Define the fallback route (base route)
$fallbackRoute = CONFIG_PATH['routes'] . '/base.route.php';

// Function to load the correct route file
function loadRouteFile($routeFile, $fallbackFile)
{
    if (file_exists($routeFile)) {
        $res = require_once $routeFile;
    } elseif (file_exists($fallbackFile)) {
        $res = require_once $fallbackFile;
    } else {
        $res = [];
    }
    return $res;
}

// Extract the first part of the URI (prefix) to match the route file
$uriParts = explode('/', $uri);
$routePrefix = $uriParts[0];  // Get the first part of the URI

// Build the path to the route file based on the prefix
$routeFile = CONFIG_PATH['middlewares'] . '/' . $routePrefix . '.route.php';

// Load the specific route file, or fall back to base.route.php
$route_data = loadRouteFile($routeFile, $fallbackRoute);
page_set_data($route_data);

// use these data;
// if(is_array($route_data)){
//     extract($route_data);
// }
