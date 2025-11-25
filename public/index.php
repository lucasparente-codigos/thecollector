<?php
session_start();

// Autoloader
spl_autoload_register(function ($class) {
    // Prefix: App\ -> src/
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/../src/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

use App\Core\Router;
use App\Modules\Auth\Controllers\AuthController;
use App\Modules\Music\Controllers\MusicController;

$router = new Router();

// Auth Routes
$router->add('GET', '/login', [AuthController::class, 'login']);
$router->add('POST', '/login', [AuthController::class, 'authenticate']);
$router->add('GET', '/register', [AuthController::class, 'register']);
$router->add('POST', '/register', [AuthController::class, 'store']);
$router->add('GET', '/logout', [AuthController::class, 'logout']);

// Music Routes
$router->add('GET', '/dashboard', [MusicController::class, 'index']);
$router->add('GET', '/music/add', [MusicController::class, 'create']);
$router->add('POST', '/music/store', [MusicController::class, 'store']);
$router->add('GET', '/music/edit', [MusicController::class, 'edit']);
$router->add('POST', '/music/update', [MusicController::class, 'update']);
$router->add('GET', '/music/delete', [MusicController::class, 'delete']);

// Default Route
$router->add('GET', '/', [AuthController::class, 'login']);

// Dispatch
$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

// Handle subfolder installation if necessary, but assuming root or simple path
// For WAMP localhost/thecollector/public/index.php or similar
// We need to strip the base path to match routes
// Let's assume the user accesses via http://localhost/thecollector/public
// We need to extract the path relative to public/index.php

// Define Base Path for URLs
$scriptName = dirname($_SERVER['SCRIPT_NAME']);
// Normalize slashes and remove trailing slash if root
$scriptName = str_replace('\\', '/', $scriptName);
if ($scriptName === '/') {
    $scriptName = '';
}
define('BASE_PATH', $scriptName);

$path = str_replace($scriptName, '', $uri);
// Ensure path starts with /
if (strpos($path, '/') !== 0) {
    $path = '/' . $path;
}
// Remove query string
$path = parse_url($path, PHP_URL_PATH);

$router->dispatch($method, $path);
