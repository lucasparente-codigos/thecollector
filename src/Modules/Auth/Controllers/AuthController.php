<?php

namespace App\Modules\Auth\Controllers;

use App\Core\Controller;
use App\Modules\Auth\Repositories\UserRepository;

class AuthController extends Controller
{
    private $userRepo;

    public function __construct()
    {
        $this->userRepo = new UserRepository();
    }

    /**
     * Show login form
     */
    public function login()
    {
        // Redirect to dashboard if already logged in
        if (isset($_SESSION['user_id'])) {
            $this->redirect('/dashboard');
        }
        
        $this->view('Auth/Views/login');
    }

    /**
     * Process login
     */
    public function authenticate()
    {
        $this->validateCsrfToken();

        $username = trim($this->post('username', ''));
        $password = $this->post('password', '');

        // Validate input
        if (empty($username) || empty($password)) {
            $this->view('Auth/Views/login', [
                'error' => 'Please fill in all fields.',
                'username' => $username
            ]);
            return;
        }

        // Find user
        $user = $this->userRepo->findByUsername($username);

        // Verify password
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $this->redirect('/dashboard');
        } else {
            $this->view('Auth/Views/login', [
                'error' => 'Invalid username or password.',
                'username' => $username
            ]);
        }
    }

    /**
     * Show register form
     */
    public function register()
    {
        // Redirect to dashboard if already logged in
        if (isset($_SESSION['user_id'])) {
            $this->redirect('/dashboard');
        }
        
        $this->view('Auth/Views/register');
    }

    /**
     * Process registration
     */
    public function store()
    {
        $this->validateCsrfToken();

        $username = trim($this->post('username', ''));
        $password = $this->post('password', '');

        // Validate input
        if (empty($username) || empty($password)) {
            $this->view('Auth/Views/register', [
                'error' => 'Please fill in all fields.',
                'username' => $username
            ]);
            return;
        }

        // Validate username length
        if (strlen($username) < 3) {
            $this->view('Auth/Views/register', [
                'error' => 'Username must be at least 3 characters.',
                'username' => $username
            ]);
            return;
        }

        // Validate password length
        if (strlen($password) < 6) {
            $this->view('Auth/Views/register', [
                'error' => 'Password must be at least 6 characters.',
                'username' => $username
            ]);
            return;
        }

        // Create user
        if ($this->userRepo->create($username, $password)) {
            $this->view('Auth/Views/register', [
                'success' => 'Registration successful! You can now <a href="' . BASE_PATH . '/login">login</a>.'
            ]);
        } else {
            $this->view('Auth/Views/register', [
                'error' => 'Username already taken.',
                'username' => $username
            ]);
        }
    }

    /**
     * Logout user
     */
    public function logout()
    {
        session_destroy();
        $this->redirect('/login');
    }
}
