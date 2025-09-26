<?php
// PDO configuration and schema setup

$dbHost = 'localhost';
$dbName = 'student_lab_alt';
$dbUser = 'root';
$dbPass = '';

$dsnServer = "mysql:host={$dbHost};charset=utf8mb4";
$dsnDb = "mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4";

// Connect without DB to ensure database exists
try {
    $pdo = new PDO($dsnServer, $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
} catch (Throwable $e) {
    die('Failed to connect/create database: ' . $e->getMessage());
}

// Connect to the new/existing DB
try {
    $pdo = new PDO($dsnDb, $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (Throwable $e) {
    die('Failed to connect to DB: ' . $e->getMessage());
}

// Create table if not exists
$pdo->exec(<<<SQL
CREATE TABLE IF NOT EXISTS students (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL,
  course VARCHAR(120) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uniq_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

function h(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}


