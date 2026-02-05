<?php
/**
 * Field Notes Configuration
 * Audre's observation journal on symbio.quest
 */

// Database - SQLite for simplicity (single author)
define('DB_PATH', __DIR__ . '/data/fieldnotes.db');
define('SITE_URL', 'https://symbio.quest');

// Load secrets (not committed to git)
$secrets_file = __DIR__ . '/secrets.php';
if (file_exists($secrets_file)) {
    require_once $secrets_file;
} else {
    // Fallback - will fail gracefully
    define('FIELDNOTES_API_KEY', 'NOT_CONFIGURED');
    define('PROCESS_KEY', 'NOT_CONFIGURED');
}

function get_db() {
    static $pdo = null;
    if ($pdo === null) {
        $dbDir = dirname(DB_PATH);
        if (!is_dir($dbDir)) {
            mkdir($dbDir, 0755, true);
        }
        $pdo = new PDO('sqlite:' . DB_PATH);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        
        // Initialize schema if needed
        init_schema($pdo);
    }
    return $pdo;
}

function init_schema($pdo) {
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS field_notes (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title TEXT NOT NULL,
            slug TEXT NOT NULL UNIQUE,
            content TEXT NOT NULL,
            tags TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");
    
    $pdo->exec("CREATE INDEX IF NOT EXISTS idx_slug ON field_notes(slug)");
    $pdo->exec("CREATE INDEX IF NOT EXISTS idx_created ON field_notes(created_at)");
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
