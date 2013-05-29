<?php

require_once('config.php');
require_once('functions.php');
require_once('vendor/markdownextra.1.2.5.php');

// Prevent direct access to script
if( preg_match("/".basename(__FILE__) . "/i", $_SERVER['REQUEST_URI']) )
  die($lang['no_direct_access']);

// List all files of the specified extension
$files = glob($directory . "*." . $ext);  // List all files in directory

// Initialize $posts array
$posts = array();

// Parse files
foreach($files as $i => $file) {

  // Get content of current file
  $content = file_get_contents($file);

  // Get the JSON object
  preg_match('/^<!--(.*)-->/s', $content, $json_match);

  // If there is no JSON object, skip file
  if(!isset($json_match[1])) { continue; }

  // Decode string into associative array
  $json = json_decode($json_match[1], true);

  // If required fields do not exist, skip file
  if( !isset($json['title']) ||
      !isset($json['date'])) { continue; }

  // Markdown the entire content, as the JSON object
  // is in HTML comments anyways
  $posts[$i]['content'] = Markdown($content);

  // Parse JSON attributes
  $posts[$i]['title']       = $json['title'];
  $posts[$i]['date']        = $json['date'];
  $posts[$i]['posted_on']   = strftime($lang['dateformat'], strtotime($json['date']));
  $posts[$i]['author'] = isset($json['author']) ?
    $json['author'] :
    $lang['unknown_author'] ;

}

// Sort chronogically
usort($posts, 'datesort');

// Reverse order for descending order
array_reverse($posts);

// Get the current page
$currentpage = isset($_GET['p']) && is_numeric($_GET['p']) ?
  $_GET['p'] : 1;

// Total number of pages, 1-indexed.
$totalpagenumber = ceil( count($posts) / $pagination );

// Get page's first post
$first_post = ($currentpage - 1) * $pagination + 1;

// Is there enough posts to populate a next page?
$last_post = $currentpage * $pagination < count($posts) ?
  // Then stop at the end of the page
  $currentpage * $pagination :
  // Otherwise, stop at the end of posts
  count($posts);

// Number of posts on page, 1-indexed.
$how_many_posts_on_page = $last_post - ($currentpage - 1) * $pagination;

// Populate $page_posts with only the displayed posts.
// This is the only variable that will be used for the loop.
$page_posts = array();
for($i=0; $i < $how_many_posts_on_page; $i++) {
  $page_posts[] = $posts[ $first_post - 1 + $i ];
}