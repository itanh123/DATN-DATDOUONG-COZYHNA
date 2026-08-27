<?php
$dir = __DIR__ . '/public/images/captcha/';
if (!is_dir($dir)) {
    mkdir($dir, 0777, true);
}

function createImage($text, $filename, $r, $g, $b) {
    global $dir;
    $im = imagecreatetruecolor(200, 200);
    $bg = imagecolorallocate($im, $r, $g, $b);
    $tc = imagecolorallocate($im, 255, 255, 255);
    imagefilledrectangle($im, 0, 0, 200, 200, $bg);
    imagestring($im, 5, 50, 90, $text, $tc);
    imagejpeg($im, $dir . $filename);
    imagedestroy($im);
}

// Bubble Tea (Blue)
createImage("Tra Sua 1", "ts1.jpg", 135, 206, 235);
createImage("Tra Sua 2", "ts2.jpg", 135, 206, 235);
createImage("Tra Sua 3", "ts3.jpg", 135, 206, 235);

// Coffee (Brown)
createImage("Ca Phe 1", "cp1.jpg", 139, 69, 19);
createImage("Ca Phe 2", "cp2.jpg", 139, 69, 19);
createImage("Ca Phe 3", "cp3.jpg", 139, 69, 19);

// Cake (Pink)
createImage("Banh Ngot 1", "bn1.jpg", 255, 182, 193);
createImage("Banh Ngot 2", "bn2.jpg", 255, 182, 193);
createImage("Banh Ngot 3", "bn3.jpg", 255, 182, 193);

echo "Images generated!";
