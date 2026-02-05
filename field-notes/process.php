<?php
/**
 * Field Notes Processor
 * 
 * Scans /incoming for .md files, inserts into database, moves to /processed
 * 
 * Expected .md format:
 * ---
 * title: The Title Here
 * tags: tag1, tag2, tag3
 * ---
 * 
 * Content starts here...
 */

require_once __DIR__ . '/config.php';

// Security: only run from CLI or with correct key
$is_cli = php_sapi_name() === 'cli';
$has_key = isset($_GET['key']) && $_GET['key'] === 'process_2026';

if (!$is_cli && !$has_key) {
    die("Access denied. Use CLI or provide ?key=process_2026");
}

$incoming_dir = __DIR__ . '/incoming';
$processed_dir = __DIR__ . '/processed';

// Ensure directories exist
if (!is_dir($incoming_dir)) mkdir($incoming_dir, 0755, true);
if (!is_dir($processed_dir)) mkdir($processed_dir, 0755, true);

$pdo = get_db();
$files = glob($incoming_dir . '/*.md');

if (empty($files)) {
    output("No files in incoming/\n");
    exit;
}

$processed = 0;
$errors = [];

foreach ($files as $file) {
    $filename = basename($file);
    output("Processing: $filename\n");
    
    $content = file_get_contents($file);
    
    // Parse frontmatter
    if (!preg_match('/^---\s*\n(.*?)\n---\s*\n(.*)$/s', $content, $matches)) {
        $errors[] = "$filename: No valid frontmatter found";
        continue;
    }
    
    $frontmatter = $matches[1];
    $body = trim($matches[2]);
    
    // Parse frontmatter fields
    $meta = [];
    foreach (explode("\n", $frontmatter) as $line) {
        if (preg_match('/^(\w+):\s*(.*)$/', $line, $m)) {
            $meta[$m[1]] = trim($m[2]);
        }
    }
    
    if (empty($meta['title'])) {
        $errors[] = "$filename: Missing title in frontmatter";
        continue;
    }
    
    $title = $meta['title'];
    $tags = $meta['tags'] ?? '';
    $slug = slugify($title);
    
    // Check for duplicate slug
    $stmt = $pdo->prepare("SELECT id FROM field_notes WHERE slug = ?");
    $stmt->execute([$slug]);
    if ($stmt->fetch()) {
        // Append timestamp to make unique
        $slug .= '-' . date('Ymd-His');
    }
    
    // Insert into database
    try {
        $stmt = $pdo->prepare("
            INSERT INTO field_notes (title, slug, content, tags, created_at, updated_at)
            VALUES (?, ?, ?, ?, datetime('now'), datetime('now'))
        ");
        $stmt->execute([$title, $slug, $body, $tags]);
        
        // Move to processed with slug as filename
        $new_path = $processed_dir . '/' . $slug . '.md';
        rename($file, $new_path);
        
        output("  -> Created: $slug\n");
        output("  -> Moved to: processed/$slug.md\n");
        $processed++;
        
    } catch (Exception $e) {
        $errors[] = "$filename: " . $e->getMessage();
    }
}

output("\n--- Summary ---\n");
output("Processed: $processed\n");

if (!empty($errors)) {
    output("Errors:\n");
    foreach ($errors as $err) {
        output("  - $err\n");
    }
}

function output($msg) {
    if (php_sapi_name() === 'cli') {
        echo $msg;
    } else {
        echo nl2br(htmlspecialchars($msg));
    }
}
