<?php

$page_data = [];

function page_set_data($data)
{
    global $page_data;
    $page_data = $data;
}

function page_get_data()
{
    global $page_data;
    return $page_data;
}

$use_page_data = fn(): callable => function () {
    global $page_data;
    extract($page_data);
};

$route_response = fn() => function () use ($page_data) {
    global $page_data;
    return $page_data;
};

// Function to handle layout extending
function page_extends(string $layoutFile, array $layoutData = [])
{
    // Buffer output so the layout can inject content later
    ob_start();
    // Include the layout file at the end of the page
    register_shutdown_function(function () use ($layoutFile, $layoutData) {
        $content = ob_get_clean(); // Get all the content of the page
        $file = CONFIG_PATH['layouts'] . "/$layoutFile";
        // Ensure layout file exists
        if (file_exists($file)) {
            extract($layoutData);
            ob_start();
            include $file;
            $layoutContent = ob_get_clean();
            // Replace the placeholder with the actual page content @{{ content }}
            echo preg_replace("/@\{\{\s*content\s*\}\}/", $content, $layoutContent);
        } else {
            echo "Layout file not found!";
        }
    });
}

function page_header(string $header , $vars = [])
{
    global $layout_data;
    $vars = array_merge($vars, $layout_data);
    $header = ltrim($header, '/');
    $header = file_exists($header) ? $header : CONFIG_PATH['layouts'] . "/$header";
    extract($vars);
    include_once $header;
}

function page_footer(string $footer, array $vars = [])
{
    global $layout_data;
    $vars = array_merge($vars, $layout_data);
    $footer = ltrim($footer, '/');
    $footer = file_exists(ltrim($footer, '/')) ? $footer : CONFIG_PATH['layouts'] . "/$footer";
    
    register_shutdown_function(function () use ($footer, $vars) {
        if (file_exists($footer)) {
            extract($vars);
            include $footer;
        } else {
            echo "{{ footer }}";
        }
    });
}

function page_layout(string $header, string|null $footer, array $vars = [])
{
    global $layout_data;
    $vars = array_merge($vars, $layout_data);

    $header = file_exists($header) ? $header : CONFIG_PATH['layouts'] . "/$header";
    extract($vars);
    include_once $header;

    ($footer !== null) && register_shutdown_function(function () use ($footer, $vars) {
        $footer = dirname(current_script_file()) . "/$footer";
        $footer = file_exists($footer) ? $footer : CONFIG_PATH['layouts'] . "/$footer";
        if (file_exists($footer)) {
            extract($vars);
            include $footer;
        }
    });
}

function page_include($include_filename, $data = [])
{
    extract($data);
    $file = CONFIG_PATH['includes'] . "/$include_filename";
    file_exists($file) && include $file;
}

function page_post_script(callable $action)
{
    if (!request_method('POST')) {
        return false;
    }
    return $action();
}