<?php

namespace App\Modules\Auth\Repositories;

use App\Core\Database;
use PDO;
use PDOException;

class UserRepository
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getPDO();
    }

    /**
     * Create a new user
     * 
     * @param string $username
     * @param string $password
     * @return bool
     */
    public function create($username, $password)
    {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare(
            "INSERT INTO users (username, password) VALUES (:username, :password)"
        );
        
        try {
            return $stmt->execute([
                ':username' => $username,
                ':password' => $hash
            ]);
        } catch (PDOException $e) {
            // Unique constraint violation
            if ($e->getCode() == 23000) {
                return false;
            }
            throw $e;
        }
    }

    /**
     * Find user by username
     * 
     * @param string $username
     * @return array|false
     */
    public function findByUsername($username)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute([':username' => $username]);
        return $stmt->fetch();
    }

    /**
     * Find user by ID
     * 
     * @param int $id
     * @return array|false
     */
    public function findById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
}
