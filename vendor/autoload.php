<?php
// Manual Guzzle autoloader for Pokedex project
// This file is a simplified version for inclusion without Composer

// Define Guzzle path
$guzzlePath = __DIR__ . '/guzzlehttp';

// Include the main Guzzle files
if (file_exists($guzzlePath . '/Client.php')) {
    require_once $guzzlePath . '/Client.php';
}
if (file_exists($guzzlePath . '/functions_include.php')) {
    require_once $guzzlePath . '/functions_include.php';
}

// Simple autoloader for the Pokedex project
spl_autoload_register(function ($class) {
    // Convert namespace to file path
    $prefix = 'GuzzleHttp\\';
    $base_dir = __DIR__ . '/guzzlehttp/';
    
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // Check if it's our own namespace
        $ourPrefix = 'Pokedex\\';
        $ourLen = strlen($ourPrefix);
        if (strncmp($ourPrefix, $class, $ourLen) === 0) {
            $relative_class = substr($class, $ourLen);
            $file = __DIR__ . '/../classes/' . str_replace('\\', '/', $relative_class) . '.php';
            if (file_exists($file)) {
                require $file;
            }
        }
        return;
    }
    
    $relative_class = substr($class, $len);
    
    // Replace namespace separators with directory separators
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    
    // If the file exists, require it
    if (file_exists($file)) {
        require $file;
    }
});