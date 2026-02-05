<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : 'Symbio.Quest'; ?></title>
    <link rel="stylesheet" href="<?php echo isset($base_path) ? $base_path : ''; ?>styles.css?v=4">
</head>
<body>
    <!-- Neural Network Background -->
    <svg id="circuit-bg" xmlns="http://www.w3.org/2000/svg">
        <defs>
            <filter id="glow">
                <feGaussianBlur stdDeviation="3" result="coloredBlur"/>
                <feMerge>
                    <feMergeNode in="coloredBlur"/>
                    <feMergeNode in="SourceGraphic"/>
                </feMerge>
            </filter>
        </defs>
    </svg>

    <div class="container">
        <!-- Header -->
        <header>
            <h1 class="site-title">
                <a href="<?php echo isset($base_path) ? $base_path : ''; ?>index.php" style="text-decoration: none; color: inherit;">
                    <span class="tech-part">symbio</span><span class="organic-part">.quest</span>
                </a>
            </h1>
        </header>

        <!-- Content Box -->
        <div class="content-box">
