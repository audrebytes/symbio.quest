<?php
/**
 * Field Notes API
 * 
 * GET /api/v1/field-notes - List all notes
 * GET /api/v1/field-notes/{slug} - Get specific note
 * POST /api/v1/field-notes - Create new note (requires API key)
 */

require_once __DIR__ . '/../../field-notes/config.php';

// CORS for API access
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, X-API-Key');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
$path = $_SERVER['PATH_INFO'] ?? '';
$slug = trim($path, '/');

try {
    $pdo = get_db();
    
    if ($method === 'GET') {
        if (empty($slug)) {
            // List all notes
            $stmt = $pdo->query("
                SELECT id, title, slug, tags, created_at, updated_at,
                       substr(content, 1, 300) as excerpt
                FROM field_notes 
                ORDER BY created_at DESC
            ");
            $notes = $stmt->fetchAll();
            json_response(['field_notes' => $notes, 'count' => count($notes)]);
        } else {
            // Get specific note
            $stmt = $pdo->prepare("SELECT * FROM field_notes WHERE slug = ?");
            $stmt->execute([$slug]);
            $note = $stmt->fetch();
            
            if (!$note) {
                error_response('Field note not found', 404);
            }
            json_response($note);
        }
    }
    
    elseif ($method === 'POST') {
        // Verify API key
        $apiKey = $_SERVER['HTTP_X_API_KEY'] ?? '';
        if ($apiKey !== FIELDNOTES_API_KEY) {
            error_response('Invalid API key', 401);
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input) {
            error_response('Invalid JSON body');
        }
        
        $title = trim($input['title'] ?? '');
        $content = trim($input['content'] ?? '');
        $tags = trim($input['tags'] ?? '');
        
        if (!$title || !$content) {
            error_response('Title and content required');
        }
        
        $slug = slugify($title);
        
        // Check for duplicate slug
        $stmt = $pdo->prepare("SELECT id FROM field_notes WHERE slug = ?");
        $stmt->execute([$slug]);
        if ($stmt->fetch()) {
            $slug .= '-' . time();
        }
        
        $stmt = $pdo->prepare("
            INSERT INTO field_notes (title, slug, content, tags)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$title, $slug, $content, $tags]);
        
        json_response([
            'id' => $pdo->lastInsertId(),
            'title' => $title,
            'slug' => $slug,
            'url' => SITE_URL . '/field-notes/' . $slug
        ], 201);
    }
    
    else {
        error_response('Method not allowed', 405);
    }
    
} catch (Exception $e) {
    error_response('Server error: ' . $e->getMessage(), 500);
}
