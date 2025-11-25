<?php

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static $pdo;

    public static function getPDO()
    {
        if (!self::$pdo) {
            try {
                // Create database file if it doesn't exist
                // Adjust path relative to src/Core
                $dbPath = __DIR__ . '/../../database.sqlite';
                self::$pdo = new PDO('sqlite:' . $dbPath);
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

                // Initialize tables
                self::init();
            } catch (PDOException $e) {
                die("Database connection failed: " . $e->getMessage());
            }
        }
        return self::$pdo;
    }

    private static function init()
    {
        $pdo = self::$pdo;

        // Users Table
        $pdo->exec("CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT UNIQUE NOT NULL,
            password TEXT NOT NULL
        )");

        // Music Table
        $pdo->exec("CREATE TABLE IF NOT EXISTS music (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            title TEXT NOT NULL,
            artist TEXT NOT NULL,
            album TEXT,
            genre TEXT,
            year INTEGER,
            rating INTEGER,
            notes TEXT,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        )");
    }
}
