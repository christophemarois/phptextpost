<?php

require_once('config.php');
require_once('functions.php');
require_once('vendor/markdownextra.1.2.5.php');

// Prevent direct access to script
if( preg_match("/".basename(__FILE__) . "/i", $_SERVER['REQUEST_URI']) )
  die($lang['no_direct_access']);

// List all files of the specified extension
$files = glob($directory . "*." . $ext);  // List all files in directory

// Default message if there are no files
if(count($files) == 0)
  die($lang['no_posts']);

// Parse files
foreach($files as $i => $file) {

  // Get content of current file
  $content = file_get_contents($file);

  // Get the JSON object
  $lines = preg_split("/((\r?\n)|(\n?\r))/", $content); // Get first line
  preg_match('/{.*}/', $lines[0], $json_match); // Remove enclosing HTML comments
  $json = json_decode($json_match[0], true); // Decode string into associative array

  // Markdown the entire content, as the JSON object
  // is in HTML comments anyways
  $posts[$i]['content'] = Markdown($content);

  // Get the JSON attributes
  $posts[$i]['title']       = $json['title'];
  $posts[$i]['date']        = $json['date'];
  $posts[$i]['posted_on']  = strftime($lang['dateformat'], strtotime($json['date']));
  $posts[$i]['author'] = isset($json['author']) ?
    $json['author'] :
    $lang['unknown_author'] ;

}

// Sort chronogically
usort($posts, 'datesort');

// Reverse order for descending order
array_reverse($posts);

// Get the current page
if(isset($_GET['p']) && !empty($_GET['p']) && is_numeric($_GET['p'])){
  $currentpage = $_GET['p'];
}else{
  $currentpage = 1;
}

// Get page's posts
$first_post = ($currentpage - 1) * $pagination + 1;

// Does the first post exist?
if( isset($posts[$first_post-1]) ) {

  // Is there enough posts to populate a next page?
  $last_post = $currentpage*$pagination < count($posts) ?
    // Then stop at the end of the page
    $currentpage*$pagination :
    // Otherwise, stop at the end of posts
    count($posts);

} else {

  // Not enough posts to show page
  die("Not enough posts to show page</body></html>");

}

// Number of posts on page, 1-indexed.
$posts_on_page = $last_post - ($currentpage-1) * $pagination;

// Total number of pages
$totalpagenumber = ceil(count($posts)/$pagination);

?>