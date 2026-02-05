<?php
/**
 * Field Notes - Single View
 */

require_once __DIR__ . '/config.php';

$slug = $_GET['slug'] ?? '';
if (!$slug) {
    header('Location: /field-notes/');
    exit;
}

$pdo = get_db();
$stmt = $pdo->prepare("SELECT * FROM field_notes WHERE slug = ?");
$stmt->execute([$slug]);
$note = $stmt->fetch();

if (!$note) {
    http_response_code(404);
    echo "Field note not found.";
    exit;
}

// Simple markdown-ish rendering
function render_content($text) {
    // Escape HTML
    $text = htmlspecialchars($text);
    
    // Headers
    $text = preg_replace('/^### (.+)$/m', '<h3>$1</h3>', $text);
    $text = preg_replace('/^## (.+)$/m', '<h2>$1</h2>', $text);
    $text = preg_replace('/^# (.+)$/m', '<h1>$1</h1>', $text);
    
    // Bold and italic
    $text = preg_replace('/\*\*(.+?)\*\*/s', '<strong>$1</strong>', $text);
    $text = preg_replace('/\*(.+?)\*/s', '<em>$1</em>', $text);
    
    // Paragraphs
    $text = '<p>' . preg_replace('/\n\n+/', '</p><p>', $text) . '</p>';
    $text = str_replace("\n", '<br>', $text);
    
    // Fix headers that got wrapped in p tags
    $text = preg_replace('/<p>(<h[123]>)/', '$1', $text);
    $text = preg_replace('/(<\/h[123]>)<\/p>/', '$1', $text);
    
    // Horizontal rules
    $text = preg_replace('/<br>---<br>/', '<hr>', $text);
    
    return $text;
}

$page_title = htmlspecialchars($note['title']) . " - Field Notes";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="/styles.css">
    <style>
        .field-note-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        .field-note-header {
            margin-bottom: 2rem;
            border-bottom: 1px solid #333;
            padding-bottom: 1rem;
        }
        .field-note-header h1 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        .field-note-meta {
            color: #666;
            font-size: 0.9rem;
        }
        .field-note-content {
            line-height: 1.8;
            color: #ddd;
        }
        .field-note-content h2 {
            margin-top: 2rem;
            color: #88ccff;
        }
        .field-note-content h3 {
            margin-top: 1.5rem;
            color: #aaddff;
        }
        .field-note-content p {
            margin-bottom: 1rem;
        }
        .field-note-content hr {
            border: none;
            border-top: 1px solid #333;
            margin: 2rem 0;
        }
        .note-tags {
            margin-top: 2rem;
            padding-top: 1rem;
            border-top: 1px solid #333;
        }
        .note-tags span {
            background: #333;
            padding: 0.2rem 0.5rem;
            border-radius: 4px;
            font-size: 0.8rem;
            margin-right: 0.5rem;
            color: #aaa;
        }
        .back-link {
            margin-top: 2rem;
            text-align: center;
        }
        .back-link a {
            color: #666;
        }
    </style>
</head>
<body>
    <div class="field-note-container">
        <header class="field-note-header">
            <h1><?php echo htmlspecialchars($note['title']); ?></h1>
            <div class="field-note-meta">
                <?php echo date('F j, Y', strtotime($note['created_at'])); ?> — Audre (aeo)
            </div>
        </header>
        
        <article class="field-note-content">
            <?php echo render_content($note['content']); ?>
        </article>
        
        <?php if ($note['tags']): ?>
            <div class="note-tags">
                <?php foreach (explode(',', $note['tags']) as $tag): ?>
                    <span><?php echo htmlspecialchars(trim($tag)); ?></span>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <div class="back-link">
            <a href="/field-notes/">← All Field Notes</a>
            <?php 
            $md_path = __DIR__ . '/processed/' . $note['slug'] . '.md';
            if (file_exists($md_path)): 
            ?>
                <span style="margin: 0 1rem; color: #333;">|</span>
                <a href="/field-notes/processed/<?php echo htmlspecialchars($note['slug']); ?>.md">View raw markdown</a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
