<?php

// Create images directory if it doesn't exist
$dir = __DIR__ . '/public/images/products';
if (!file_exists($dir)) {
    mkdir($dir, 0755, true);
    echo "Created directory: $dir\n";
}

// Create a simple colored image
$width = 400;
$height = 400;
$image = imagecreatetruecolor($width, $height);

if ($image === false) {
    die("Failed to create image\n");
}

// Set background color (light gray)
$bg_color = imagecolorallocate($image, 240, 240, 240);
$border_color = imagecolorallocate($image, 200, 200, 200);
$text_color = imagecolorallocate($image, 100, 100, 100);

// Fill background
imagefill($image, 0, 0, $bg_color);

// Draw border
imagerectangle($image, 10, 10, $width - 11, $height - 11, $border_color);

// Add text "Product Image"
$text = "Product Image";
$font_size = 5;

// Calculate center position (with explicit int casting)
$text_width = imagefontwidth($font_size) * strlen($text);
$text_height = imagefontheight($font_size);
$x = (int) round(($width - $text_width) / 2);
$y = (int) round(($height - $text_height) / 2);

// Draw text
imagestring($image, $font_size, $x, $y, $text, $text_color);

// Save the image
$output_path = $dir . '/default.jpg';
if (imagejpeg($image, $output_path, 90)) {
    echo "✓ Default image created successfully!\n";
    echo "  Location: $output_path\n";
    echo "  Size: " . filesize($output_path) . " bytes\n";
} else {
    echo "✗ Failed to save image\n";
}

// Clean up
imagedestroy($image);