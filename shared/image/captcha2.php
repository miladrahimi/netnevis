<?php

// Get started
header("Content-Type: image/png");
if(!isset($_SESSION)) session_start();

// Start image canvas
$image = imagecreate(40, 30);

// Allocate colors
imagecolorallocate($image,245,245,245); // Backgorund
$color_black = imagecolorallocate($image,0,0,0);

// Generate code and put it into the image
$random_string = rand();
$random_string = sha1($random_string);
$random_string = substr($random_string, 0, 3);
$random_string = str_replace("0","X",$random_string);
$random_string = str_replace("O","X",$random_string);
$random_string = strtoupper($random_string);

// Save random string in session
$_SESSION['captcha'] = $random_string;

// function imagestring($image, $font_size, $x_pos, $y_pos, $random_string, $color_black);
imagestring($image, 4, 9, 7,  $random_string, $color_black);

// Output image and free up memory
imagepng($image);
imagedestroy($image);