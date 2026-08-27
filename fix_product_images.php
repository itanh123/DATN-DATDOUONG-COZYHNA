<?php
$db = new PDO('sqlite:' . __DIR__ . '/database/database.sqlite');
$stmt = $db->query("SELECT image, name FROM products WHERE image LIKE '/storage/products/%'");

$dir = __DIR__ . '/storage/app/public/products/';
if (!is_dir($dir)) {
    mkdir($dir, 0777, true);
}

$colors = [
    [135, 206, 235], // Blue
    [139, 69, 19],   // Brown
    [255, 182, 193], // Pink
    [144, 238, 144], // Light Green
    [255, 215, 0],   // Gold
];

$count = 0;
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $path = $row['image'];
    // path is like /storage/products/abc.jpg
    $filename = basename($path);
    $filepath = $dir . $filename;
    
    if (!file_exists($filepath)) {
        $im = imagecreatetruecolor(400, 400);
        $colorIdx = crc32($filename) % count($colors);
        $c = $colors[$colorIdx];
        $bg = imagecolorallocate($im, $c[0], $c[1], $c[2]);
        $tc = imagecolorallocate($im, 0, 0, 0); // Black text
        imagefilledrectangle($im, 0, 0, 400, 400, $bg);
        
        // Wrap text
        $text = $row['name'];
        $wrapped = wordwrap($text, 20, "\n");
        $lines = explode("\n", $wrapped);
        $y = 180;
        foreach ($lines as $line) {
            imagestring($im, 5, 20, $y, $line, $tc);
            $y += 20;
        }
        
        imagejpeg($im, $filepath);
        imagedestroy($im);
        $count++;
    }
}

// Also make sure storage is linked
shell_exec('php artisan storage:link');

echo "Generated $count missing product images.";
