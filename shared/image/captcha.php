<?php

// Get started
header("Content-Type: image/png");
if(!isset($_SESSION)) session_start();

// Start image canvas
$image = imagecreate(140, 38);

// Allocate colors
imagecolorallocate($image,249,237,190); // Backgorund
$color_black = imagecolorallocate($image,0,0,0);

// Generate code and put it into the image
$random_string = rand();
$random_string = sha1($random_string);
$random_string = substr($random_string, 0, 3);
$random_string = str_replace("0","x",$random_string);
$random_string = str_replace("O","x",$random_string);

// Save random string in session
$_SESSION['captcha'] = strtoupper($random_string);

// function imagestring($image, $font_size, $x_pos, $y_pos, $random_string, $color_black);
imagestring($image, 5, 55, 12,  $random_string, $color_black);

// Output image and free up memory
imagepng($image);
imagedestroy($image);