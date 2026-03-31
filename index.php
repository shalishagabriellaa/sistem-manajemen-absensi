<?php

/**
 * Laravel - A PHP Framework For Web Artisans
 * 
 * This file redirects all requests to the public directory
 * where the main index.php file is located.
 */

// Get the current request URI
$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? ''
);

// Check if the request is for an existing file in the public directory
if ($uri !== '/' && file_exists(__DIR__.'/public'.$uri)) {
    return false;
}

// Otherwise, redirect to the public directory
header('Location: public/');
exit;