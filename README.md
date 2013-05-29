# phpTextPost 1.3
### Description

**phpTextPost** is a lightweight, database-less blogging system with pagination and markdown formatting, that is super easy to install, integrate and customize. It also features automatic thumnailing and built-in pagination.

### Requirements

PHP > 5 and GD 2.0+.

### Configuration

There are several variables that can be changed in order to customize phpTextPost in `application/config.php`.

### Customization and integration



For an new installation (not using the provided template), include `parser.php` in a file contained in the same directory that holds `files/`, `images/` and `phptextpost/`.

###### Example: index.php

	<?php
	  include('phptextpost/parser.php');
	?>

You may have to specify your script directory in the .htaccess for the url rewriting to work properly:

###### Example

	RewriteBase /news/



### Adding posts

* Put each post in a different **.md** file.
* Filenames must be formatted in this format: `YYYY-MM-DD HH.MM.SS.md`. For example: `2012-02-17 00.00.00.md`.
* The first line of each file is considered as being the title, and the rest is considered as content. Content will be markdown-formatted.

In the file, the first line will be considered as being the title, and the rest will be the content. By default, the content is markdown-formatted.

### Adding images

PHPTextPost features an automatic thumbnail generating feature. To use it, you must put your original images in the `images/` directory, then embed an image which source os: `thumbs/[filename.ext]?[size]`, where **filename.ext** is the original filename and **size** the desired thumbnail size.

There are three thumbnail size presets in the code, `small`, `medium` and `large`, but you can add your own presets in `phptextpost/thumbnails.php`.

Note that if the original image's size is greater than the asked thumbnail size, the original file will be displayed instead, and no file will be created.

###### Example of image thumbnailing with preset:

	Original: images/dog.jpg
	Markdown: ![A dog](thumbs/dog.jpg?medium)

###### Example of custom preset in thumbnails.php:

	$sizeinfo = array(
	  'small'   => array(150,150),                      // Default small size
	  'medium'  => array(300,300),                      // Default medium size
	  'large'   => array(500,500),                      // Default large size
	  'custom1' => array(125,230),
	  'custom2' => array(200,600),
	);

You can then call `thumbs/dog.jpg?custom1` and `thumbs/dog.jpg?custom2`. Note that all pixel specification will always respect aspect ratio.

###### Example of image thumbnailing with link to the original:

	Original: images/dog.jpg
	Markdown: [![A dog](thumbs/dog.jpg?small)](images/dog.jpg)

###### Image alignment

PHPTextPost's original CSS comes with a trick meant for aligning images: you have to put `imgleft` and `imgright` as the **alt** of the image. The downsize of this method is the disrespect of valid HTML, and the unusability of the alt option:

	Original: images/dog.jpg
	Markdown: [![imgleft](thumbs/dog.jpg?small)](images/dog.jpg)

If you don't want to use this shortcut, you can always use pure HTML, which is valid and provides an **alt**:

	Original: images/dog.jpg
	Markdown: <a href="images/dog.jpg" class="alignleft"><img src="thumbs/dog.jpg?small" alt="A dog" /></a>

### Changelog

v.1.2.1

	Changed the default post filetype to .md
	Modified example template
	Improved accuracy of markdown styling

v.1.2

	Removed custom thumbnail sizes for security issues
	Added php limits override option in thumbnails.php
	Replaced PHP Markdown 1.0.1o by PHP Markdown Extra 1.2.5
	Changed thumbnail syntax
	Various bugfixes

v.1.1

	Thumbnails first-time generation header problem
	No more pagelist when only one page
	No thumbnail creation when original is of smaller size
	Fixed htaccess

v.1.0

	Initial release

### To-do list

* Scroll autoload
* URL rewriting for pages and posts
* Excerpts and full post view
* Add thumbnail upscaling to thumbnails.php