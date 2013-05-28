<?php

/* General configuration */

$originaldir =      '../assets/images';             // Original files directory without trailing slash, relative to this file
$thumbdir =         '../assets/images/thumbs';      // Thumbnails directory without trailing slash, relative to this file
$phpthumbfactory =  "vendor/phpthumb/ThumbLib.inc.php"; // PhpThumbFactory's path, relative to this file

$allowed_types = array('jpg','jpeg','gif','png');   // Allowed images types, case-insensitive. Please only put jpg, jpeg, gif or png.
$jpegquality = 70;                                  // Default thumbnail quality (thus filesize). 100 is best, 0 is worst.
$resizeup = true;                                   // Whether the script should resize small images up to the asked size. false by default.

$sizeinfo = array(
  'small'   => array(150,150),
  'medium'  => array(300,300),
  'large'   => array(500,500),
  'full'    => array(770,770)
);

$phpoverride = false;                               // If set to true, the script will try to override php's memory and time limitations.
                                                    // Only try this if you have problems running your script on large images

/* Do not edit now */

if( !function_exists('apache_request_headers') ) {
  function apache_request_headers() {
    $arh = array();
    $rx_http = '/\AHTTP_/';
    foreach($_SERVER as $key => $val) {
      if( preg_match($rx_http, $key) ) {
        $arh_key = preg_replace($rx_http, '', $key);
        $rx_matches = array();
        $rx_matches = explode('_', $arh_key);
        if( count($rx_matches) > 0 and strlen($arh_key) > 2 ) {
          foreach($rx_matches as $ak_key => $ak_val) $rx_matches[$ak_key] = ucfirst($ak_val);
          $arh_key = implode('-', $rx_matches);
        }
        $arh[$arh_key] = $val;
      }
    }
    return( $arh );
  }
}

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

// Run security tests and set variables

if( !isset($_GET['size']) || empty($_GET['size']) ||
    !isset($_GET['filename']) || empty($_GET['filename']) )
  die('You must specify a filename and a size');

$file = pathinfo($_GET['filename']);
$originalpath = $originaldir.'/'.$file['basename'];

if(!file_exists($originaldir.'/'.$file['basename']))
  die('The required file does not exist');
if(!in_array($file['extension'], $allowed_types))
  die('The provided file type is not supported');
if(!array_key_exists($_GET['size'], $sizeinfo))
  die('The specified size is invalid');

$size = $_GET['size'];
$finalsize['width'] = $sizeinfo[$size][0];
$finalsize['height'] = $sizeinfo[$size][1];

$thumbpath = $thumbdir.'/'.$file['filename'].'-'.$size.'.jpg';
$imagesize = getimagesize($originalpath);

// Resizing

if(($imagesize[0] > $finalsize['width']) || ($imagesize[1] > $finalsize['height'])){ // It's a large enough image, resize it

  if(!file_exists($thumbpath)){ // Create thumbnail if it doesn't already exist.

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

} else { // The image is too small.

  output_image($originalpath); // Show the original

}

?>