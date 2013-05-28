<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <title>PHPTextPost Example page</title>
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
          <a href="./">PHPTextPost Example page</a>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="span8 offset2">
        <div id="description">
          <p>You can modify this example page to fit your needs, or even create a whole new one if you need to. Follow the readme!</p>
        </div>
        <?php
          include('./application/parser.php');
        ?>
      </div>
    </div>
  </div>

  <script type="text/javascript" src="js/bootstrap.min.js" />

</body>
</html>