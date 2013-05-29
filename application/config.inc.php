<?php

$directory          = 'assets/';      // Directory which will contain the files, with trailing slash

$ext                = 'md';           // Posts' file extension. 'md' by default.
setlocale(LC_ALL, 'en_US.UTF-8');     // Date locale for time translation. By default: 'en_US.UTF-8'

$pagination         = 5;              // Number of posts before page splitting

$lang['dateformat'] = 'Posted on %B %e, %Y';  // Date formatted in php's strftime() format.

$lang['no_direct_access'] = 'This file cannot be accessed directly, it must be included. See README.md';
$lang['no_posts']   = "There are no posts to show. Either there are no files,
                      either they are not correctly formatted. Refer to the
                      README to format your posts correctly.";

$lang['by']         = "by";
$lang['unknown_author'] = "Anonymous";

$lang['prev']       = "Previous";
$lang['next']       = "Next";
$lang['first']      = "First";
$lang['last']       = "Last";
$lang['page']       = "Page";
$lang['navbutton']  = "Go";