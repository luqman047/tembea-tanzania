<?php
declare(strict_types=1);

/*
 * Tembea Tanzania configuration.
 * Update these values for your local MySQL installation.
 */
session_start();

const DB_HOST = '127.0.0.1';
const DB_NAME = 'tembea_tanzania';
const DB_USER = 'root';
const DB_PASS = ''; // XAMPP default is commonly blank. Set your real password in production.
const SITE_NAME = 'Tembea Tanzania';

function db(): PDO {
    static $pdo = null;
    if ($pdo instanceof PDO) return $pdo;
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    return $pdo;
}

function e(?string $value): string {
    return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf(): void {
    $sent = $_POST['csrf_token'] ?? '';
    $saved = $_SESSION['csrf_token'] ?? '';
    if (!is_string($sent) || !is_string($saved) || !hash_equals($saved, $sent)) {
        http_response_code(403);
        exit('Ombi la usalama halijathibitishwa. Rudi nyuma na ujaribu tena.');
    }
}

function money_tzs(int|float|string $amount): string {
    return 'TZS ' . number_format((float)$amount, 0);
}
