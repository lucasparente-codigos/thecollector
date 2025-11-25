<?php

namespace App\Core;

class Controller
{
    protected function view($viewPath, $data = [])
    {
        extract($data);
        require __DIR__ . '/../Modules/' . $viewPath . '.php';
    }

    protected function redirect($path)
    {
        header("Location: " . BASE_PATH . $path);
        exit;
    }
}
