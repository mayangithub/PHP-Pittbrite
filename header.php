<?php session_start();
	require_once("functions.php");
	
		
 ?>
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="images/logo_big.png">

    <title>Pittbrite</title>

    <!-- Bootstrap core CSS -->
    <link href="Narrow%20Jumbotron%20Template%20for%20Bootstrap_files/bootstrap.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="Narrow%20Jumbotron%20Template%20for%20Bootstrap_files/jumbotron-narrow.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="Narrow%20Jumbotron%20Template%20for%20Bootstrap_files/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <div class="container">
      <div class="header">
        <nav>
          <ul class="nav nav-pills pull-right">
            <li role="presentation" <?php echo nav("index.php"); ?>><a href="index.php">Home</a></li>
            <li role="presentation" <?php echo nav("discover.php"); ?>><a href="discover.php">Discover</a></li>
            <?php  
            if ($_SESSION['username']) {
            	echo "<li role=\"presentation\" " . nav("create.php") . "><a href=\"create.php\">Create Event</a></li>";
            	echo "<li role=\"presentation\" " . nav("profile.php") . "><a href=\"profile.php\">" . $_SESSION['username'] . "</a></li>";
            	echo "<li role=\"presentation\" " . nav("logout.php") . "><a href=\"logout.php\">Logout</a></li>";
            }else {
            	echo "<li role=\"presentation\" " . nav("login.php") . "><a href=\"login.php\">Login</a></li>";
            	echo "<li role=\"presentation\" " . nav("register.php") . "><a href=\"register.php\">Register</a></li>";
            }
            
            ?>
            
          </ul>
        </nav>
        <h1 class="text-muted" ><a href="index.php" id="title"><img id="header_logo" src="images/logo_big.png" alt="logo" /> Pittbrite</a></h1>
        
      </div>
