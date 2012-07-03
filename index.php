<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<title>PHPTextPost Example page</title>
	<meta charset="utf-8" />
	
	<link href="css/bootstrap.min.css" rel="stylesheet" />
	<link href="css/bootstrap-responsive.min.css" rel="stylesheet" />
	<link href="css/style.css" rel="stylesheet" />
	
	<style type="text/css">
	  #logo {
	    margin: 20px 0;
	    text-align: center;
	  }
	  #logo a {
	    font: 38px/68px 'Helvetica Neue', Helvetica, Arial, sans-serif; letter-spacing: 0;
      display: block;
      margin: 0 auto;
      border-bottom: 1px solid #222;
      padding-bottom: 10px;
      text-decoration: none;
      color: #000;
	  }
    #logo a:hover {
      text-decoration: none;
    }
	  #description {
	    margin-bottom: 20px;
      text-align: center;
      font-style: italic;
	  }
	  #description p {
	    font-size: 12px
	  }
	</style>
	
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
        include('phptextpost/parser.php'); // That's it!
        ?>
      </div>
    </div>
  </div>
  
  <script type="text/javascript" src="js/bootstrap.min.js" />
  
</body>
</html>