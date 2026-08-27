<?php

$directories = [
    __DIR__ . '/resources/views/admin',
    __DIR__ . '/resources/views/staff',
    __DIR__ . '/resources/views/shipper',
];

function processDirectory($dir) {
    $files = scandir($dir);
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;
        
        $path = $dir . '/' . $file;
        if (is_dir($path)) {
            processDirectory($path);
        } elseif (is_file($path) && pathinfo($path, PATHINFO_EXTENSION) === 'php') {
            $content = file_get_contents($path);
            
            // Match <table> tags
            $count = 0;
            $newContent = preg_replace_callback('/<table\b[^>]*>.*?<\/table>/is', function($matches) {
                return "<div class=\"overflow-x-auto custom-scrollbar\">\n" . $matches[0] . "\n</div>";
            }, $content, -1, $count);
            
            if ($count > 0) {
                // If the table was ALREADY wrapped in overflow-x-auto, we might have double wrappers.
                // Let's clean up double wrappers:
                $newContent = preg_replace('/<div[^>]*overflow-x-auto[^>]*>\s*<div class="overflow-x-auto custom-scrollbar">/is', '<div class="overflow-x-auto custom-scrollbar">', $newContent);
                $newContent = preg_replace('/<\/div>\s*<\/div>/is', '</div>', $newContent); // this is a bit unsafe, but let's see.
                
                // Actually, a safer way to avoid double wrap is to use negative lookbehind if possible, or string operations.
            }
            // To avoid regex mess, let's just do a manual string replace.
        }
    }
}
