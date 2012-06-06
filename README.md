# PHPTextPost 1.2
### Description

PHPTextPost is a lightweight, database-less and highly customizable news system that can also act as a blogroll. It features:

* Automatic thumbnail creation
* Integrated markdown formating
* Page listing or optional scroll auto-loading
* Permalinks for pages and posts

### Requirements

PHP > 5 and GD 2.0+

### Installation

For an out-of-the-box installation, include `parser.php` in a file contained in the same directory that holds `files/`, `images/` and `phptextpost/`.

###### Example: index.php

	<?php
	  include('phptextpost/parser.php');
	?>

You may have to specify your script directory in the .htaccess for the url rewriting to work properly:

###### Example

	RewriteBase /news/

### Configuration

There are several variables that can be changed in order to customize PHPTextPost in `parser.php` or `thumbnails.php`.

### Adding posts

* Put each post in a different **.txt** file.
* Filenames must be formatted in this format: `YYYY-MM-DD HH.MM.SS.txt`. For example: `2012-02-17 00.00.00.txt`.
* The first line of each file is considered as being the title, and the rest is considered as content. Content will be markdown-formatted.

In the file, the first line will be considered as being the title, and the rest will be the content. By default, the content is markdown-formatted.
	  
### Adding images

PHPTextPost features an automatic thumbnail generating feature. To use it, you must put your original images in the `images/` directory, then embed an image which points to: `thumbs/[filename]-[size].[ext]`, where **filename** is the original filename minus the extension, **size** the desired thumbnail size, and **ext** the original filename's extension.

There are three thumbnail size presets in the code, `small`, `medium` and `large`, but you can add your own presets in `phptextpost/thumbnails.php`. You can also directly specify a custom size in `400x300` form.

Note that if the original image's size is greater than the asked thumbnail size, the original file will be displayed instead, and no file will be created.

###### Example of image thumbnailing with preset:

	Original: images/dog.jpg
	Markdown: ![Dog](thumbs/dog-medium.jpg)

###### Example of image thumbnailing without preset:

	Original: images/dog.jpg
	Markdown: ![Dog](thumbs/dog-200x150.jpg)

###### Example of image thumbnailing with link to the original:

	Original: images/dog.jpg
	Markdown: [![Dog](thumbs/dog-large.jpg)](images/dog.jpg)

### Changelog

v.1.2 (current)

	Added custom thumbnail sizes
	Added php limits override for thumbnail class

v.1.1

	Bug fixes:
		Thumbnails first-time generation header problem
		Pagelist when only one page
	No thumbnail creation when original is of smaller size
	Fixed htaccess

v.1.0

	Initial release
	
### Planned features

	Scroll autoload
	URL rewriting for pages and posts
	Full post view
	Add thumbnail upscaling to thumbnails.php