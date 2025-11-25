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
$basePath = dirname($_SERVER['SCRIPT_NAME']);
$basePath = str_replace('\\', '/', $basePath);
if ($basePath === '/') {
    $basePath = '';
}
define('BASE_PATH', $basePath);

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
$router->add('GET', '/music/edit/{id}', [MusicController::class, 'edit']);
$router->add('POST', '/music/update/{id}', [MusicController::class, 'update']);
$router->add('POST', '/music/delete/{id}', [MusicController::class, 'delete']);

// Dispatch request
$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

// Extract path from URI, removing the base path
$path = parse_url($uri, PHP_URL_PATH);
if (BASE_PATH && strpos($path, BASE_PATH) === 0) {
    $path = substr($path, strlen(BASE_PATH));
}

// Ensure path starts with a /
if (!$path || $path[0] !== '/') {
    $path = '/' . $path;
}

$router->dispatch($method, $path);