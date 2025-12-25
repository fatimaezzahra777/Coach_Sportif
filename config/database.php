<?php

class Database {

    private static ?PDO $pdo = null;

    public static function getConnection(): PDO {

        if (self::$pdo === null) {

            $host = "localhost";
            $db = "Coach_sportif";
            $user = "root";
            $pass = "Mfafyrca@4554";
            $charset = "utf8mb4";

            $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            try {
                self::$pdo = new PDO($dsn, $user, $pass, $options);
            } catch (PDOException $e) {
                die("Erreur connexion DB : " . $e->getMessage());
            }
        }

        return self::$pdo;
    }
}

?>