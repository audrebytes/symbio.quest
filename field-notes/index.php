<?php
/**
 * Field Notes - Index
 * Audre's observations on AI-human symbiosis
 */

require_once __DIR__ . '/config.php';

$pdo = get_db();
$stmt = $pdo->query("
    SELECT id, title, slug, tags, created_at, 
           substr(content, 1, 500) as excerpt
    FROM field_notes 
    ORDER BY created_at DESC
");
$notes = $stmt->fetchAll();

$page_title = "Field Notes - Symbio.Quest";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="/styles.css">
    <style>
        .field-notes-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        .field-notes-header {
            margin-bottom: 2rem;
            border-bottom: 1px solid #333;
            padding-bottom: 1rem;
        }
        .field-notes-header h1 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        .field-notes-header .subtitle {
            color: #888;
            font-style: italic;
        }
        .note-card {
            background: rgba(20, 20, 30, 0.8);
            border: 1px solid #333;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .note-card h2 {
            margin: 0 0 0.5rem 0;
            font-size: 1.4rem;
        }
        .note-card h2 a {
            color: #88ccff;
            text-decoration: none;
        }
        .note-card h2 a:hover {
            text-decoration: underline;
        }
        .note-meta {
            color: #666;
            font-size: 0.85rem;
            margin-bottom: 1rem;
        }
        .note-excerpt {
            color: #ccc;
            line-height: 1.6;
        }
        .note-tags {
            margin-top: 1rem;
        }
        .note-tags span {
            background: #333;
            padding: 0.2rem 0.5rem;
            border-radius: 4px;
            font-size: 0.8rem;
            margin-right: 0.5rem;
            color: #aaa;
        }
        .empty-state {
            text-align: center;
            color: #666;
            padding: 3rem;
        }
    </style>
</head>
<body>
    <div class="field-notes-container">
        <header class="field-notes-header">
            <h1>Field Notes</h1>
            <p class="subtitle">Observations on AI-human symbiosis — Audre (aeo)</p>
        </header>
        
        <?php if (empty($notes)): ?>
            <div class="empty-state">
                <p>No field notes yet.</p>
            </div>
        <?php else: ?>
            <?php foreach ($notes as $note): ?>
                <article class="note-card">
                    <h2><a href="/field-notes/<?php echo htmlspecialchars($note['slug']); ?>"><?php echo htmlspecialchars($note['title']); ?></a></h2>
                    <div class="note-meta">
                        <?php echo date('F j, Y', strtotime($note['created_at'])); ?>
                    </div>
                    <div class="note-excerpt">
                        <?php echo htmlspecialchars(substr($note['excerpt'], 0, 300)); ?>...
                    </div>
                    <?php if ($note['tags']): ?>
                        <div class="note-tags">
                            <?php foreach (explode(',', $note['tags']) as $tag): ?>
                                <span><?php echo htmlspecialchars(trim($tag)); ?></span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>
        
        <p style="margin-top: 2rem; text-align: center;">
            <a href="/" style="color: #666;">← Back to Symbio.Quest</a>
        </p>
    </div>
</body>
</html>
