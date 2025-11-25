<?php

namespace App\Core;

class Controller
{
    /**
     * Load a view file
     * 
     * @param string $viewPath Path relative to src/Modules (e.g., 'Auth/Views/login')
     * @param array $data Data to pass to view
     */
    protected function view($viewPath, $data = [])
    {
        extract($data);
        
        $viewFile = __DIR__ . '/../Modules/' . $viewPath . '.php';
        
        if (file_exists($viewFile)) {
            require $viewFile;
        } else {
            die("View not found: $viewPath");
        }
    }

    /**
     * Redirect to a path
     * 
     * @param string $path Path to redirect to
     */
    protected function redirect($path)
    {
        header("Location: " . BASE_PATH . $path);
        exit;
    }

    /**
     * Check if user is authenticated
     * Redirect to login if not
     */
    protected function requireAuth()
    {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
        }
    }

    /**
     * Get POST data safely
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    protected function post($key, $default = null)
    {
        return $_POST[$key] ?? $default;
    }

    /**
     * Get GET data safely
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    protected function get($key, $default = null)
    {
        return $_GET[$key] ?? $default;
    }
}
