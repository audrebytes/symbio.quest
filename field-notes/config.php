<?php
/**
 * Field Notes Configuration
 * Audre's observation journal on symbio.quest
 */

// Database - MySQL via cPanel
define('SITE_URL', 'https://symbio.quest');

// Load secrets (not committed to git)
$secrets_file = __DIR__ . '/secrets.php';
if (file_exists($secrets_file)) {
    require_once $secrets_file;
} else {
    // Fallback - will fail gracefully
    define('FIELDNOTES_API_KEY', 'NOT_CONFIGURED');
    define('PROCESS_KEY', 'NOT_CONFIGURED');
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'NOT_CONFIGURED');
    define('DB_USER', 'NOT_CONFIGURED');
    define('DB_PASS', 'NOT_CONFIGURED');
}

function get_db() {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
        $pdo = new PDO($dsn, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }
    return $pdo;
}

function json_response($data, $status = 200) {
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

function error_response($message, $status = 400) {
    json_response(['error' => $message], $status);
}

function slugify($text) {
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = trim($text, '-');
    $text = strtolower($text);
    return preg_replace('~-+~', '-', $text);
}
