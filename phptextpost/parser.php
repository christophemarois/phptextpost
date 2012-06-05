<?php
/*
PHPTextPost 1.1
by Christophe Marois
*/

/* 			General configuration 								        */

if(!isset($directory))
  $directory = 'files/';              // Directory which will contain the news, with trailing slash

$ext = 'txt';                         // Posts file extension. 'txt' by default, could also be 'md' markdown.
$dateformat = 'Posted on %B %e, %Y';  // Date formatted in php's strftime() format.
setlocale(LC_ALL, 'en_US.UTF-8');     // Date locale for time translation. By default: 'en_US.UTF-8'
$use_markdown = true; 					      // If set to true, title, author and content of news will be formatted by phpmarkdown

$pagination = 5;		                  // Number of posts before page splitting
$pagelisting = true; 	                // True will show page list at the end. False will enable infinite AJAX loading of posts (not yet implemented).

$lang['prev'] = "Previous";
$lang['next'] = "Next";
$lang['first'] = "First";
$lang['last'] = "Last";
$lang['page'] = "Page";
$lang['navbutton'] = "Go";

/* 			Do not edit now (unless, you know.) 	  			*/

if(preg_match("/".basename(__FILE__)."/i",$_SERVER['REQUEST_URI']))
  die('This file cannot be accessed directly, it must be included. See README.md');

if($use_markdown) include_once('markdown.php');

// LIST FILES, PARSE AND SORT


$files = glob($directory . "*." . $ext);	// List all files in directory

if(count($files) == 0)
  die('Welcome to phptextpost! Please read the readme to add your first news!</body></html>');

function select_in_array($array, $beginning=0, $ending=0){
	$new_array = array();
	if($ending == 0) $ending = count($array)-1;
	for($i=$beginning;$i<=$ending;$i++){
		if($i == $ending){
			$new_array[] = $array[$i]; // no line break for last line
		}else{
			$new_array[] = $array[$i]."\n"; // ."\n" is for preserving line breaks
		}
	}
	return $new_array;
}

foreach($files as $i => $file) { 				// Loop through files
	$content = file_get_contents($file);
	$lines = preg_split("/((\r?\n)|(\n?\r))/", $content); // Split the file in lines. Regexp has cross-encoding capabilities

  $infos[$i]['title'] = $lines[0]; // Line 1 will be title
	$infos[$i]['content'] = implode(select_in_array($lines,1)); // Lines 2 to end will be content, then, reassemble the array elements into a string.
	
	$infos[$i]['date'] = basename($file);
	$infos[$i]['date'] = substr($infos[$i]['date'], 0, -4);
	$infos[$i]['date'] = str_replace('.',':',$infos[$i]['date']);
	
	if($use_markdown){					// Apply Markdown
		$infos[$i]['content'] = Markdown($infos[$i]['content']);
	}
}

function datesort($a, $b) { 			// Usort template for ording $infos array by ['date'] index
   return(strtotime($b['date']) - strtotime($a['date'])); 
}
usort($infos,'datesort'); 				// Sort using datesort template
$ordered = array_reverse($infos);		// Reverse order for descending order

// PAGE MANAGEMENT


if(isset($_GET['p']) && !empty($_GET['p']) && is_numeric($_GET['p'])){
	$currentpage = $_GET['p'];
}else{
	$currentpage = 1;
}

// Explanation: check if there is a news (an index of $infos array that contains the news) that matches the first post that should appear on the given page
$first_post = $currentpage*$pagination-($pagination-1); 

if(isset($infos[$first_post-1])){
	if($currentpage*$pagination < count($infos)){ // If there is more news then the maximum allowed on the page
		$last_post = $currentpage*$pagination; // Stop at last one allowed by pagination 
	}else{
		$last_post = count($infos); // Otherwise, stop at last news
	}
	
}else{
	die("Not enough posts to show page</body></html>");
}

$posts_on_page = $last_post-($currentpage-1)*$pagination; // Number of posts on page, not in index format
$totalpagenumber = ceil(count($infos)/$pagination);


// PAGE LISTING


function pagelist() {
	
	global $currentpage, $lang, $totalpagenumber;
  
  if($totalpagenumber > 1){
  
  	echo('<div class="pagelist">'); echo("\n");
  	echo('<form method="GET">'); echo("\n");

  	if($currentpage != 1){ // Show First/Prev buttons
  		$prev = $currentpage-1;
  		echo('<a href="' . $_SERVER['PHP_SELF'] . '?p=1">' . $lang['first'] . "</a> ");
  		echo('<a href="' . $_SERVER['PHP_SELF'] . '?p=' . $prev . '">' . $lang['prev'] . "</a> ");
  	}

  	echo($lang['page'] . ': <select name="p">');echo("\n");
	
  	for($i=0; $i < $totalpagenumber; $i++){

  		$page_number = $i+1;
  		if($page_number == $currentpage){
  			echo('<option value="' . $page_number . '" selected>' . $page_number . '</option>');
  		}else{
  			echo('<option value="' . $page_number . '">' . $page_number . '</option>');
  		}

  	}
  	echo("\n"); // Only for HTML's sake

  	echo('</select>'); echo("\n");
  	echo('<input type="submit" value="' . $lang['navbutton'] . '" />'); echo("\n");

  	if($currentpage != $totalpagenumber){ // Show Next/Last buttons
  		$next = $currentpage+1;
  		echo('<a href="' . $_SERVER['PHP_SELF'] . '?p=' . $next . '">' . $lang['next'] . "</a> ");
  		echo('<a href="' . $_SERVER['PHP_SELF'] . '?p=' . $totalpagenumber . '">' . $lang['last'] . "</a> ");
  	}
	
  	echo("\n");
  	echo('</form>'); echo("\n");
  	echo('</div>'); echo("\n");
	
	}

}


// LOOP


for($i=0; $i <= ($posts_on_page-1); $i++) { // Loop as many times as the number of posts on page (put in index format [-1])
	
	$current_index = $first_post+$i-1;
	// Begin news template
	?>
	
	<div class="news<?php if($i == ($posts_on_page-1)) echo(' last-news'); ?>">
		<div class="header">
			<h3><?php echo($infos[$current_index]['title']); ?></h3> 
			<div class="info">
			  <?php
  			echo(strftime($dateformat, strtotime($infos[$current_index]['date'])));
  			?>
			</div>
		</div>
		<?php echo($infos[$current_index]['content']); ?>
	</div>
	
	<?php
	// End template
}

if($pagelisting) pagelist(); // Put page listing on the bottom of the page

?>