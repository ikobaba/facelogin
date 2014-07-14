<?php

include("header.php");

if (file_exists("faces/".md5($_POST["email"])) ) {

echo "This email has been already registered. You can try to <a href=login.php>login</a> with your email address and your face!"; 
} else {
$_POST["dataimg"]=str_replace("data:image/png;base64,","",$_POST["dataimg"]);

$_POST["dataimg"]= base64_decode($_POST["dataimg"]);

$email=filter_var($_POST["email"],FILTER_VALIDATE_EMAIL);
file_put_contents($facestorage."faces/".md5($email),$_POST["dataimg"]);


$dst_x = 0;   // X-coordinate of destination point. 
$dst_y = 0;   // Y --coordinate of destination point. 
$array1["x"] = $_POST[x]; // Crop Start X position in original image
$array1["y"]= $_POST[y]; // Crop Srart Y position in original image
$array1["width"]= $_POST[w]; // Thumb width
$array1["height"] = $_POST[h]; // Thumb height
$src_w = $src_x + $dst_w; // $src_x + $dst_w Crop end X position in original image
$src_h = src_y + $dst_h; // $src_y + $dst_h Crop end Y position in original image

 
// Create image instances
$src = imagecreatefrompng($facestorage."faces/".md5($email));
$dest = imagecreatetruecolor(intval($array1["width"])-10, intval($array1["height"])-10 ) or die('Cannot Initialize new GD image stream'); 
 
// Copy
imagecopy($dest, $src, 0, 0,$array1["x"]+5, $array1["y"]+5, $array1["width"], $array1["height"]);



//

imagepng($dest, $facestorage."faces/cropped-".md5($email).".png");
//imagegd($dest);

echo "<br>Your face has been added. Now try to <a href=login.php>login</a> with your email address and your face!";

}

?>
