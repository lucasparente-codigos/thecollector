<?php

namespace App\Core;

class Router
{
    private $routes = [];

    public function add($method, $path, $handler)
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler
        ];
    }

    public function dispatch($method, $uri)
    {
        // Remove query string
        $uri = parse_url($uri, PHP_URL_PATH);
        // Remove base path if needed (assuming hosted at /thecollector/public or similar, but let's handle relative to root for now)
        // For simplicity in this environment, we'll assume relative paths or handle prefix removal in index.php

        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $route['path'] === $uri) {
                $handler = $route['handler'];
                if (is_array($handler)) {
                    $controllerName = $handler[0];
                    $action = $handler[1];
                    $controller = new $controllerName();
                    return $controller->$action();
                }
            }
        }

        // 404
        http_response_code(404);
        echo "404 Not Found";
    }
}
