<?php

namespace App\Modules\Auth\Repositories;

use App\Core\Database;
use PDOException;

class UserRepository
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getPDO();
    }

    public function create($username, $password)
    {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
        try {
            return $stmt->execute([':username' => $username, ':password' => $hash]);
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) { // Integrity constraint violation
                return false;
            }
            throw $e;
        }
    }

    public function findByUsername($username)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute([':username' => $username]);
        return $stmt->fetch();
    }
}
