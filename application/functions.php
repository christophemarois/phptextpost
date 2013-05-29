<?php
// Usort template for ording array by ['date'] index
// where ['date'] index is a valid date() format
function datesort($a, $b) {
   return(strtotime($b['date']) - strtotime($a['date']));
}

// Puts a navigation bar on the page
function pagenav() {

  global $currentpage, $lang, $totalpagenumber;

  if($totalpagenumber > 1){

    // Open containers
    echo('<div class="pagenav">'); echo("\n");
    echo('<form method="GET">'); echo("\n");

    // Show First/Prev buttons
    if($currentpage != 1){
      $prev = $currentpage-1;

      echo('<a href="' . $_SERVER['PHP_SELF'] . '?p=1">' . $lang['first'] . "</a> ");
      echo('<a href="' . $_SERVER['PHP_SELF'] . '?p=' . $prev . '">' . $lang['prev'] . "</a> ");
    }

    // Dropdown selector
    echo($lang['page'] . ': <select name="p">'); echo("\n");

    for($i=0; $i < $totalpagenumber; $i++){
      $page_number = $i+1;

      if($page_number == $currentpage){
        echo('<option value="' . $page_number . '" selected>' . $page_number . '</option>');
      }else{
        echo('<option value="' . $page_number . '">' . $page_number . '</option>');
      }

      echo("\n");
    }

    echo('</select>'); echo("\n");
    echo('<input type="submit" value="' . $lang['navbutton'] . '" />'); echo("\n");

    // Show Next/Last buttons
    if($currentpage != $totalpagenumber){
      $next = $currentpage+1;

      echo('<a href="' . $_SERVER['PHP_SELF'] . '?p=' . $next . '">' . $lang['next'] . "</a> ");
      echo('<a href="' . $_SERVER['PHP_SELF'] . '?p=' . $totalpagenumber . '">' . $lang['last'] . "</a> ");
    }

    echo("\n");

    // Close containers
    echo('</form>'); echo("\n");
    echo('</div>'); echo("\n");

  }

}