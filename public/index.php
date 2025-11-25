<?php
/**
 * Application Entry Point
 * Monolithic Modular Architecture
 */

session_start();

// Autoloader
spl_autoload_register(function ($class) {
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

// Define base path for assets and URLs
$scriptName = dirname($_SERVER['SCRIPT_NAME']);
$scriptName = str_replace('\\', '/', $scriptName);
if ($scriptName === '/') {
    $scriptName = '';
}
define('BASE_PATH', $scriptName);

// Initialize Router
$router = new Router();

// Auth Routes
$router->add('GET', '/', [AuthController::class, 'login']);
$router->add('GET', '/login', [AuthController::class, 'login']);
$router->add('POST', '/login', [AuthController::class, 'authenticate']);
$router->add('GET', '/register', [AuthController::class, 'register']);
$router->add('POST', '/register', [AuthController::class, 'store']);
$router->add('GET', '/logout', [AuthController::class, 'logout']);

// Music Routes (Protected)
$router->add('GET', '/dashboard', [MusicController::class, 'index']);
$router->add('GET', '/music/add', [MusicController::class, 'create']);
$router->add('POST', '/music/store', [MusicController::class, 'store']);
$router->add('GET', '/music/edit', [MusicController::class, 'edit']);
$router->add('POST', '/music/update', [MusicController::class, 'update']);
$router->add('POST', '/music/delete', [MusicController::class, 'delete']);

// Dispatch request
$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

// Extract path from URI
$path = str_replace(BASE_PATH, '', $uri);
if (strpos($path, '/') !== 0) {
    $path = '/' . $path;
}
$path = parse_url($path, PHP_URL_PATH);

$router->dispatch($method, $path);