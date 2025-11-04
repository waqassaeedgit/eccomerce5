<?php

$categories = [
    'Electronics' => '#3498db',
    'Fashion' => '#e74c3c',
    'Home & Garden' => '#2ecc71',
    'Sports' => '#f39c12',
    'Books' => '#9b59b6'
];

// Create images directory
$dir = __DIR__ . '/public/images/categories';
if (!file_exists($dir)) {
    mkdir($dir, 0755, true);
}

foreach ($categories as $name => $color) {
    $width = 400;
    $height = 400;
    $image = imagecreatetruecolor($width, $height);
    
    // Parse hex color
    $hex = str_replace('#', '', $color);
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));
    
    // Set background color
    $bg_color = imagecolorallocate($image, $r, $g, $b);
    imagefill($image, 0, 0, $bg_color);
    
    // Add text
    $text_color = imagecolorallocate($image, 255, 255, 255);
    $font = 5;
    
    $text_width = imagefontwidth($font) * strlen($name);
    $text_height = imagefontheight($font);
    $x = (int) round(($width - $text_width) / 2);
    $y = (int) round(($height - $text_height) / 2);
    
    imagestring($image, $font, $x, $y, $name, $text_color);
    
    // Save
    $filename = strtolower(str_replace(['&', ' '], ['', '-'], $name)) . '.jpg';
    imagejpeg($image, $dir . '/' . $filename, 90);
    imagedestroy($image);
    
    echo "Created: $filename\n";
}

echo "All category images created successfully!\n";