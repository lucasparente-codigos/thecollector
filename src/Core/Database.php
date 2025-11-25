<?php

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static $pdo;

    /**
     * Get PDO instance (Singleton pattern)
     * 
     * @return PDO
     */
    public static function getPDO()
    {
        if (!self::$pdo) {
            try {
                $dbPath = __DIR__ . '/../../database.sqlite';
                self::$pdo = new PDO('sqlite:' . $dbPath);
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

                self::initTables();
            } catch (PDOException $e) {
                die("Database connection failed: " . $e->getMessage());
            }
        }

        return self::$pdo;
    }

    /**
     * Initialize database tables
     */
    private static function initTables()
    {
        $pdo = self::$pdo;

        // Users table
        $pdo->exec("CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT UNIQUE NOT NULL,
            password TEXT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )");

        // Music table
        $pdo->exec("CREATE TABLE IF NOT EXISTS music (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            title TEXT NOT NULL,
            artist TEXT NOT NULL,
            album TEXT,
            genre TEXT,
            year INTEGER,
            rating INTEGER CHECK(rating >= 1 AND rating <= 5),
            notes TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        )");

        // Create indexes for performance
        $pdo->exec("CREATE INDEX IF NOT EXISTS idx_music_user_id ON music(user_id)");
        $pdo->exec("CREATE INDEX IF NOT EXISTS idx_music_title ON music(title)");
        $pdo->exec("CREATE INDEX IF NOT EXISTS idx_music_artist ON music(artist)");
    }
}
