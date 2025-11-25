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
        // Add CSRF token to all views that might contain forms
        if (!isset($data['csrf_token'])) {
            $data['csrf_token'] = $this->generateCsrfToken();
        }

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
     * Generate and store a CSRF token
     *
     * @return string
     */
    protected function generateCsrfToken()
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Validate the CSRF token from POST data
     *
     * @return void
     */
    protected function validateCsrfToken()
    {
        if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
            // Token mismatch, handle error (e.g., show error page)
            http_response_code(403);
            die('CSRF token validation failed.');
        }
        // Invalidate token after use
        unset($_SESSION['csrf_token']);
    }

    /**
     * Get POST data safely and sanitize it.
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    protected function post($key, $default = null)
    {
        $value = $_POST[$key] ?? $default;
        if (is_string($value)) {
            return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
        }
        return $value;
    }

    /**
     * Get GET data safely and sanitize it.
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    protected function get($key, $default = null)
    {
        $value = $_GET[$key] ?? $default;
        if (is_string($value)) {
            return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
        }
        return $value;
    }
}