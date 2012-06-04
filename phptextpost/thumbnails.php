<?php

/* 			General configuration 								        */

$originaldir =  'files/images';	                // Original files directory without trailing slash, relative to this file
$thumbdir =     'files/images/thumbs';	        // Thumbnails directory without trailing slash, relative to this file

$allowed_types = array('jpg','jpeg','gif','png');	// Allowed images types, case-insensitive. Please only put jpg, jpeg, gif or png.

$sizeinfo['small'] = array(150,150);            // Default small size
$sizeinfo['medium'] = array(300,300);           // Default medium size
$sizeinfo['large'] = array(500,500);            // Default large size

/* 			Do not edit now			              		        */

function output_image($fn){
  // Getting headers sent by the client.
  $headers = apache_request_headers(); 
  // Checking if the client is validating his cache and if it is current.
  if (isset($headers['If-Modified-Since']) && (strtotime($headers['If-Modified-Since']) == filemtime($fn))) {
      // Client's cache IS current, so we just respond '304 Not Modified'.
      header('Last-Modified: '.gmdate('D, d M Y H:i:s', filemtime($fn)).' GMT', true, 304);
  } else {
      // Image not cached or cache outdated, we respond '200 OK' and output the image.
      header('Last-Modified: '.gmdate('D, d M Y H:i:s', filemtime($fn)).' GMT', true, 200);
      header('Content-Length: '.filesize($fn));
      header('Content-Type: image/png');
      print file_get_contents($fn);
  }
}

if(!isset($_GET['size'])  || empty($_GET['size']) || !isset($_GET['filename'])  || empty($_GET['filename']))
  die('You must specify a filename and a size');
  
  echo ($_GET['size'] . ' ' . $_GET['filename']); exit;

$file = pathinfo($_GET['filename']);

if(!array_key_exists($_GET['size'], $sizeinfo))
  die('The specified size is invalid');
  
$size = $sizeinfo[$_GET['size']];

if(!file_exists($originaldir.'/'.$file['basename']))
  die('The required file does not exist');

if(!in_array($file['extension'], $allowed_types))
  die('The provided file type is not supported');

if(!file_exists($thumbdir.'/'.$file['filename'].'.jpg')){ // Create thumbnail if it doesn't exist;
  
  require_once 'phpthumb/ThumbLib.inc.php';       // PHPThumb lib, relative to this file
  
  $thumb = PhpThumbFactory::create($originaldir.'/'.$file['basename']);
  $thumb->adaptiveResize($size[0], $size[1]);
  $thumb->save($thumbdir.'/'.$file['filename'].'.jpg');
  $thumb->show();
  
}else{ // Otherwise, show it.
  
  output_image($thumbdir.'/'.$file['filename'].'.jpg');
  
}

?>