<?php

// Get the router and configuration settings
require_once('../config/config.php');
require_once('../router.php');
require_once('../src/Class/' . strtolower($class) . '.php');

?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>DESTINY TRACKER</title>

  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Inlude jQuery -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>

  <!-- Include Fontawesome -->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">

  <!-- Include Bootstrap Files -->
  <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>

  <!-- Include CSS Files -->
  <link href="https://fonts.googleapis.com/css?family=Dosis:300" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo CSS . 'layout.css'; ?>" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo CSS . 'navbar.css'; ?>" rel="stylesheet">

</head>

<body>

  <!-- Navbar -->
  <?php if(isset($_SESSION['user_id'])){ ?>
    <nav class="navbar navbar-default">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="<?php echo HOST . PATH; ?>">
            <img src="images/logo-bungie.png">
          </a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li <?php echo $home_active; ?>><a href="<?php echo HOST . PATH; ?>">HOME</a></li>
            <li <?php echo $email_active; ?>><a href="<?php echo HOST . PATH . 'Email/View'; ?>">EMAILS</a></li>
            <li <?php echo $lists_active; ?>><a href="<?php echo HOST . PATH . 'Lists/View'; ?>">LISTS</a></li>
            <li <?php echo $subscribers_active; ?>><a href="<?php echo HOST . PATH . 'Subscriber/View'; ?>">SUBSCRIBERS</a></li>
            <li><a href="<?php echo HOST . PATH . 'User/Logout'; ?>">LOGOUT</a></li>
          </ul>
        </div>
      </div>
    </nav>
  <?php } ?>
  <!-- End Navbar -->

  <!-- Main Body -->
  <?php include(TEMPLATES . $method . '.php'); ?>
  <!-- End Main Body -->

  <!-- Footer -->
  <footer>
    <div class="navbar navbar-default navbar-fixed-bottom footer">
    <div class="container">
      <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
          <p class="navbar-text text-center">2018 - Destiny 2 API By Karsten Kaminski
          </p>
        </div>
      </div>
    </div>
  </footer>
  <!-- End Footer -->

</body>

</html>
