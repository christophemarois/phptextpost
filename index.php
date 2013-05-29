<?php include('./application/phptextpost.php'); ?>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <title>PHPTextPost</title>
  <meta charset="utf-8" />

  <?php
    $stylesPaths = array('public/css/vendor/', 'public/css/application/');

    // Glob stylesPaths elements for css, sass anc scss files
    $stylesheets = array();
    foreach ( $stylesPaths as $stylesPath ) {

      $files = glob($stylesPath . '*.{css}', GLOB_BRACE);
      $stylesheets = array_merge($stylesheets, $files);

    }

    // Link every found stylesheet
    foreach ( $stylesheets as $stylesheet ) {
      echo('<link href="' . $stylesheet . '" rel="stylesheet" />'); echo("\n");
    }
  ?>

</head>

<body>

  <div class="container">
    <div class="row">
      <div class="span6 offset3">
        <div id="logo">
          <a href="./">phpTextPost</a>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="span8 offset2">
        <div id="description">
          <p>
            You can modify this page to fit your needs, or integrate the script into an existing one.<br />
            Follow the readme!
          </p>
        </div>

        <?php pagenav(); ?>

        <!-- Begin loop -->

        <?php
        for($i=0; $i < $posts_on_page; $i++) {
          $post = $posts[ $first_post - 1 + $i ]; ?>

          <div class="news clearfix">
            <div class="header">
              <h3><?php echo($post['title']); ?></h3>

              <div class="info">
                <?php echo(
                  $post['posted_on'] . " " .
                  $lang['by'] . " " .
                  $post['author']
                ); ?>
              </div>
            </div>

            <?php echo($post['content']); ?>
          </div>

        <?php } ?>

        <!-- End loop -->

        <?php pagenav(); ?>

      </div>
    </div>
  </div>

  <script type="text/javascript" src="js/bootstrap.min.js" />
</body>
</html>