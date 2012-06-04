# PHPTextPost 1.0

### Requirements

* PHP5

### Installation

* In the file in which you wish to include PHPTextPost, define `$directory` to point to the directory containing the posts, with trailing slash, then include `parser.php`

###### Example

	<?php
	  $directory = 'phptextnews/news/';
	  include('phptextnews/parser.php');
	?>

### Adding posts

* Put each post in a different **.txt** file.
* Filenames must be formatted in this format: `YYYY-MM-DD HH.MM.SS.txt`. For example: `2012-02-17 00.00.00.txt`.
* The first line of each file is considered as being the title, and the rest is considered as content. Content will be markdown-formatted.

In the file, the first line will be considered as being the title, and the rest will be the content. By default, the content is markdown-formatted.
	  
### Adding images

PHPTextPost features an automatic thumbnail generating feature. To use it, you must put your original images in the `images/` directory, then embed an image which points to: `thumbs/[filename]-[size].[ext]`, where **filename** is the original filename minus the extension, **size** the desired thumbnail size, and **ext** the original filename's extension.

There are three thumbnail size presets in the code, `small`, `medium` and `large`, but you can add your own presets in `phptextpost/thumbnails.php`. You can also specify your own size in `400x300`form (not yet implemented).

Note that if the original image's size is greater than the asked thumbnail size, the original file will be displayed instead, and no file will be created (not yet implemented).

###### Example of image thumbnailing with preset:

	Original: images/dog.jpg
	Markdown: ![Dog](thumbs/dog-medium.jpg)

###### Example of image thumbnailing without preset:

	Original: images/dog.jpg
	Markdown: ![Dog](thumbs/dog-200x150.jpg)

###### Example of image thumbnailing with link to the original:

	Original: images/dog.jpg
	Markdown: [![Dog](thumbs/dog-large.jpg)](images/dog.jpg)
	  
### Configuration

In `parser.php`, you can edit the following variables:

### Changelog

v.1.1

	Todo: 
	Scroll autoload
	Thumbnails custom size
	Thumbnails first-time generation header problem
	No thumbnail creation when original is of smaller size
	Unified configuration file

v.1.0

	Initial release