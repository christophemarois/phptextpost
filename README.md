# PHPTextPost 1.0

### Requirements

* PHP5

### Installation

* Define `$directory` in your file to point to the directory containing the posts, with trailing slash
* Include `parser.php`

###### Example

	<?php
	  $directory = 'phptextnews/news/';
	  include('phptextnews/parser.php');
	?>


Then, create a file for each post in the directory specified.

The filename must be formatted in this format: `YYYY-MM-DD HH.MM.SS.txt`. By example:

    2012-02-17 00.00.00.txt

In the file, the first line will be considered as being the title, and the rest will be the content. By default, the content is markdown-formatted.
	  
### Configuration

In **parser.php**, you can edit the following variables:

### Changelog

v.1.1

	Todo: 
	Scroll autoload
	

v.1.0

	Initial release