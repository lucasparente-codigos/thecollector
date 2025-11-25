<?php

require_once __DIR__ . '/Database.php';

class MusicRepo
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getPDO();
    }

    public function getAll($userId, $search = null)
    {
        $sql = "SELECT * FROM music WHERE user_id = :user_id";
        $params = [':user_id' => $userId];

        if ($search) {
            $sql .= " AND (title LIKE :search OR artist LIKE :search OR album LIKE :search)";
            $params[':search'] = "%$search%";
        }

        $sql .= " ORDER BY id DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getById($id, $userId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM music WHERE id = :id AND user_id = :user_id");
        $stmt->execute([':id' => $id, ':user_id' => $userId]);
        return $stmt->fetch();
    }

    public function create($userId, $data)
    {
        $sql = "INSERT INTO music (user_id, title, artist, album, genre, year, rating, notes) 
                VALUES (:user_id, :title, :artist, :album, :genre, :year, :rating, :notes)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':user_id' => $userId,
            ':title' => $data['title'],
            ':artist' => $data['artist'],
            ':album' => $data['album'] ?? null,
            ':genre' => $data['genre'] ?? null,
            ':year' => $data['year'] ?? null,
            ':rating' => $data['rating'] ?? null,
            ':notes' => $data['notes'] ?? null
        ]);
    }

    public function update($id, $userId, $data)
    {
        $sql = "UPDATE music SET 
                title = :title, 
                artist = :artist, 
                album = :album, 
                genre = :genre, 
                year = :year, 
                rating = :rating, 
                notes = :notes 
                WHERE id = :id AND user_id = :user_id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':user_id' => $userId,
            ':title' => $data['title'],
            ':artist' => $data['artist'],
            ':album' => $data['album'] ?? null,
            ':genre' => $data['genre'] ?? null,
            ':year' => $data['year'] ?? null,
            ':rating' => $data['rating'] ?? null,
            ':notes' => $data['notes'] ?? null
        ]);
    }

    public function delete($id, $userId)
    {
        $stmt = $this->pdo->prepare("DELETE FROM music WHERE id = :id AND user_id = :user_id");
        return $stmt->execute([':id' => $id, ':user_id' => $userId]);
    }
}
