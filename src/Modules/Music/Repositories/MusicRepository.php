<?php

namespace App\Modules\Music\Repositories;

use App\Core\Database;
use PDO;

class MusicRepository
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getPDO();
    }

    /**
     * Get all music for a user
     * 
     * @param int $userId
     * @param string|null $search
     * @return array
     */
    public function getAll($userId, $search = null)
    {
        $sql = "SELECT * FROM music WHERE user_id = :user_id";
        $params = [':user_id' => $userId];

        if ($search) {
            $sql .= " AND (title LIKE :search OR artist LIKE :search OR album LIKE :search)";
            $params[':search'] = "%$search%";
        }

        $sql .= " ORDER BY created_at DESC, id DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Get music by ID
     * 
     * @param int $id
     * @param int $userId
     * @return array|false
     */
    public function getById($id, $userId)
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM music WHERE id = :id AND user_id = :user_id"
        );
        $stmt->execute([':id' => $id, ':user_id' => $userId]);
        return $stmt->fetch();
    }

    /**
     * Create new music entry
     * 
     * @param int $userId
     * @param array $data
     * @return bool
     */
    public function create($userId, $data)
    {
        $sql = "INSERT INTO music (user_id, title, artist, album, genre, year, rating, notes) 
                VALUES (:user_id, :title, :artist, :album, :genre, :year, :rating, :notes)";
        
        $stmt = $this->pdo->prepare($sql);
        
        return $stmt->execute([
            ':user_id' => $userId,
            ':title' => $data['title'],
            ':artist' => $data['artist'],
            ':album' => $data['album'] ?: null,
            ':genre' => $data['genre'] ?: null,
            ':year' => $data['year'] ?: null,
            ':rating' => $data['rating'] ?: null,
            ':notes' => $data['notes'] ?: null
        ]);
    }

    /**
     * Update music entry
     * 
     * @param int $id
     * @param int $userId
     * @param array $data
     * @return bool
     */
    public function update($id, $userId, $data)
    {
        $sql = "UPDATE music SET 
                title = :title, 
                artist = :artist, 
                album = :album, 
                genre = :genre, 
                year = :year, 
                rating = :rating, 
                notes = :notes,
                updated_at = CURRENT_TIMESTAMP
                WHERE id = :id AND user_id = :user_id";
        
        $stmt = $this->pdo->prepare($sql);
        
        return $stmt->execute([
            ':id' => $id,
            ':user_id' => $userId,
            ':title' => $data['title'],
            ':artist' => $data['artist'],
            ':album' => $data['album'] ?: null,
            ':genre' => $data['genre'] ?: null,
            ':year' => $data['year'] ?: null,
            ':rating' => $data['rating'] ?: null,
            ':notes' => $data['notes'] ?: null
        ]);
    }

    /**
     * Delete music entry
     * 
     * @param int $id
     * @param int $userId
     * @return bool
     */
    public function delete($id, $userId)
    {
        $stmt = $this->pdo->prepare(
            "DELETE FROM music WHERE id = :id AND user_id = :user_id"
        );
        return $stmt->execute([':id' => $id, ':user_id' => $userId]);
    }

    /**
     * Count total music entries for user
     * 
     * @param int $userId
     * @return int
     */
    public function count($userId)
    {
        $stmt = $this->pdo->prepare(
            "SELECT COUNT(*) as total FROM music WHERE user_id = :user_id"
        );
        $stmt->execute([':user_id' => $userId]);
        $result = $stmt->fetch();
        return (int) $result['total'];
    }
}
