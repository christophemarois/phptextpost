<?php

/* 			General configuration 								      */

$originaldir =      '../images';	                  // Original files directory without trailing slash, relative to this file
$thumbdir =         '../images/thumbs';	            // Thumbnails directory without trailing slash, relative to this file
$phpthumbfactory =  "phpthumb/ThumbLib.inc.php";    // PhpThumbFactory's path, relative to this file

$allowed_types = array('jpg','jpeg','gif','png');	  // Allowed images types, case-insensitive. Please only put jpg, jpeg, gif or png.
$jpegquality = 70;                                  // Default thumbnail quality (thus filesize). 100 is best, 0 is worst.
$resizeup = true;                                   // Whether the script should resize small images up to the asked size. false by default.

$sizeinfo = array('small'   => array(150,150),      // Default small size
                  'medium'  => array(300,300),      // Default medium size
                  'large'   => array(500,500));     // Default large size

$toosmall = array('width' => 50, 'height' => 50);   // At which point an asked sized is considered too small.

$phpoverride = false;                               // If set to true, the script will try to override php's memory and time limitations.

/* 			Do not edit now			              		        */

function output_image($fn, $mime = 'image/jpeg'){
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
      header('Content-Type: ' . $mime);
      print file_get_contents($fn);
  }
}

if(!isset($_GET['size'])  || empty($_GET['size']) || !isset($_GET['filename'])  || empty($_GET['filename']))
  die('You must specify a filename and a size');

$file = pathinfo($_GET['filename']);
$size = $_GET['size'];

if(preg_match("/^([0-9]{1,4})x([0-9]{1,4})$/", $size)){ // If the size is not a preset
  
  preg_match("/^([0-9]{1,4})x([0-9]{1,4})$/", $size, $matches, PREG_OFFSET_CAPTURE);
  
  $finalsize['width'] = $matches[1][0];
  $finalsize['height'] = $matches[2][0];
  
  if(($finalsize['width'] < $toosmall['width']) || ($finalsize['height'] < $toosmall['height']))
    die("You must specify a size greater than " . $toosmall['width'] . " x " . $toosmall['height']);
  
}else{ // If the size is a preset
  
  if(!array_key_exists($_GET['size'], $sizeinfo))
    die('The specified size is invalid');
    
  $finalsize['width'] = $sizeinfo[$size][0];
  $finalsize['height'] = $sizeinfo[$size][1];
  
}
  
if(!file_exists($originaldir.'/'.$file['basename']))
  die('The required file does not exist');
  
if(!in_array($file['extension'], $allowed_types))
  die('The provided file type is not supported');

$originalpath = $originaldir.'/'.$file['basename'];
$thumbpath = $thumbdir.'/'.$file['filename'].'-'.$size.'.jpg';
$imagesize = getimagesize($originalpath);

if(($imagesize[0] > $finalsize['width']) || ($imagesize[1] > $finalsize['height'])){ // Prevent resizing small images

  if(!file_exists($thumbpath)){ // Create thumbnail if it doesn't exist;
    
    if($phpoverride){
      @set_time_limit (0);
      @ini_set("memory_limit","32M");
    }
  
    ob_start(); // Start buffer to prevent header problems when showing
    
    require_once $phpthumbfactory;
  
    try {
      $thumb = PhpThumbFactory::create($originalpath, array('jpegQuality' => $jpegquality, 'resizeUp' => $resizeup));
    } catch (Exception $e) {
      echo('Error with PhpThumbFactory: '.$e); exit;
    }
  
    $thumb->adaptiveResize($finalsize['width'], $finalsize['height']);
    $thumb->save($thumbpath);

    ob_end_clean(); // Clean headers sent by PHPThumbFactory
    
  }
  
  output_image($thumbpath); // Show the thumbnail

} else {
  
  output_image($originalpath); // Show original image
  
}

?>