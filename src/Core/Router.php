<?php

namespace App\Core;

class Router
{
    private $routes = [];

    public function add($method, $path, $handler)
    {
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $path,
            'handler' => $handler
        ];
    }

    public function dispatch($method, $uri)
    {
        $method = strtoupper($method);
        $uri = parse_url($uri, PHP_URL_PATH);

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            $routePath = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $route['path']);
            $pattern = "@^" . $routePath . "$@D";

            if (preg_match($pattern, $uri, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                $handler = $route['handler'];
                
                if (is_array($handler)) {
                    [$controllerName, $action] = $handler;
                    $controller = new $controllerName();
                    
                    return call_user_func_array([$controller, $action], $params);
                }
                
                return;
            }
        }

        $this->notFound();
    }

    private function notFound()
    {
        http_response_code(404);
        echo "<!DOCTYPE html>
<html>
<head>
    <title>404 - Not Found</title>
    <link rel=\"stylesheet\" href=\"" . BASE_PATH . "/assets/style.css\">
</head>
<body>
    <div class=\"auth-container\">
        <h1>404 - Page Not Found</h1>
        <p>The page you're looking for doesn't exist.</p>
        <p><a href=\"" . BASE_PATH . "/dashboard\">Go to Dashboard</a></p>
    </div>
</body>
</html>";
    }
}
