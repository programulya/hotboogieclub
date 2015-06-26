<?php if (!isset($_SESSION)) session_start(); 

$enc_num = rand(0, 9999);
$key_num = rand(0, 24);
$hash_string = substr(md5($enc_num), $key_num, 5); // Length of String
$hash_md5 = md5($hash_string);

$_SESSION['verify'] = $hash_md5;

// Fallback -> Save cookie without MD5 hash for JavaScript captcha validation
setcookie("verify", $hash_string, time()+3600, "/");
session_write_close();

// Create the image if you dont need BG image for captcha
//$im =  imagecreatetruecolor(130, 40);  
$im = imagecreatefrompng('captcha.png') or die("Cannot Initialize new GD image stream");

// Create some colors
$white = imagecolorallocate($im, 255, 255, 255);
$black = imagecolorallocate($im, 0, 0, 0);
// Use this if you decide to have plain white background for captcha
//imagefilledrectangle($im, 0, 0, 399, 39, $white); 

// The text to draw
$text = $hash_string;

// Set the enviroment variable for GD
putenv('GDFONTPATH=' . realpath('.'));

// Name the font to be used (If font is outside of script directory attach the .ttf extension)
$font = 'league';

// Add some shadow to the text
imagettftext($im, 30, 4, 17, 39, $black, $font, $text);

// Add the text
imagettftext($im, 30, 4, 15, 38, $white, $font, $text);

// Using imagepng() results in clearer text compared with imagejpeg()
header( "Content-type: image/png" );
imagepng($im);
imagedestroy($im);
?>
