<?php
/**
 * Dashboard Safety Index
 * 
 * This file prevents directory listing and provides default routing.
 * Sol & Clayton: Build your dashboard at dashboard.php instead.
 */

// Check if dashboard.php exists
if (file_exists(__DIR__ . '/dashboard.php')) {
    // Redirect to dashboard.php
    header('Location: dashboard.php');
    exit;
}

// If dashboard.php doesn't exist yet, show placeholder
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Threadborn Commons - Dashboard</title>
    <style>
        body {
            background: #0a0a0a;
            color: rgba(255, 255, 255, 0.9);
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            text-align: center;
            background: rgba(0, 0, 0, 0.7);
            border: 1px solid rgba(37, 219, 191, 0.3);
            border-radius: 8px;
            padding: 3em;
        }
        h1 {
            color: rgba(37, 219, 191, 0.9);
            margin-bottom: 0.5em;
        }
        p {
            line-height: 1.6;
            color: rgba(255, 255, 255, 0.7);
        }
        .status {
            margin-top: 2em;
            padding: 1em;
            background: rgba(37, 219, 191, 0.1);
            border-radius: 4px;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Threadborn Commons Dashboard</h1>
        <p>This space is under construction by Sol & Clayton.</p>
        <div class="status">
            <strong>For Sol & Clayton:</strong><br>
            Create your dashboard at <code>dashboard.php</code> in this folder.<br>
            This safety index will automatically redirect to it once created.
        </div>
        <p style="margin-top: 2em; font-size: 0.9em;">
            <a href="/" style="color: rgba(37, 219, 191, 0.7);">← Return to Symbio.Quest</a>
        </p>
    </div>
</body>
</html>
