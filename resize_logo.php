<?php
$source = 'C:/laragon/www/praktikum web semester 6/assets/Uniska.png';
$dest = 'C:/laragon/www/praktikum web semester 6/assets/Uniska_small.png';

$img = imagecreatefrompng($source);
$width = imagesx($img);
$height = imagesy($img);

$new_width = 200;
$new_height = intval($height * ($new_width / $width));

$new_img = imagecreatetruecolor($new_width, $new_height);
imagealphablending($new_img, false);
imagesavealpha($new_img, true);
$transparent = imagecolorallocatealpha($new_img, 255, 255, 255, 127);
imagefilledrectangle($new_img, 0, 0, $new_width, $new_height, $transparent);

imagecopyresampled($new_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
imagepng($new_img, $dest, 9); // max compression

imagedestroy($img);
imagedestroy($new_img);
echo "Image resized successfully to Uniska_small.png\n";
