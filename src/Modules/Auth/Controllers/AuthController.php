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

    public function login()
    {
        if (isset($_SESSION['user_id'])) {
            $this->redirect('/dashboard');
        }
        $this->view('Auth/Views/login');
    }

    public function authenticate()
    {
        $username = trim($_POST['username']);
        $password = $_POST['password'];

        if (empty($username) || empty($password)) {
            $this->view('Auth/Views/login', ['error' => 'Please fill in all fields.']);
            return;
        }

        $user = $this->userRepo->findByUsername($username);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $this->redirect('/dashboard');
        } else {
            $this->view('Auth/Views/login', ['error' => 'Invalid username or password.']);
        }
    }

    public function register()
    {
        if (isset($_SESSION['user_id'])) {
            $this->redirect('/dashboard');
        }
        $this->view('Auth/Views/register');
    }

    public function store()
    {
        $username = trim($_POST['username']);
        $password = $_POST['password'];

        if (empty($username) || empty($password)) {
            $this->view('Auth/Views/register', ['error' => 'Please fill in all fields.']);
            return;
        }

        if ($this->userRepo->create($username, $password)) {
            $this->view('Auth/Views/register', ['success' => 'Registration successful! You can now <a href="/login">login</a>.']);
        } else {
            $this->view('Auth/Views/register', ['error' => 'Username already taken.']);
        }
    }

    public function logout()
    {
        session_destroy();
        $this->redirect('/login');
    }
}
