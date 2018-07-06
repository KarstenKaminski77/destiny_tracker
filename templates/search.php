<?php

  // Instantiate the object
  $destiny = new Destiny();

?>

<div class="container">
  <form method="post">
    <div class="row jumbotron jumbotron-top-margin">
      <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2 col-xl-2">
        <select name="platform" class="form-control">
          <option>Select...</option>
          <option value="2">Playstation</option>
          <option value="1">XBOX</option>
        </select>
      </div>
      <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 col-xl-9">
        <input type="text" name="gamer-tag" class="form-control" placeholder="Player Search">
      </div>
      <div class="col-xs-12 col-sm-12 col-md-1 col-lg-1 col-xl-1">
        <input type="submit" name="search" class="btn btn-primary" value="SEARCH">
      </div>
    </div>
  </form>
</div>

<?php

  $gamer_tag = '';

  if(isset($_POST['gamer-tag'])){

    $gamer_tag = $_POST['gamer-tag'];
  }

  $platform = '';

  if(isset($_POST['platform'])){

    $platform = $_POST['platform'];
  }

  // Set the object properties
  $destiny->setGamerTag($gamer_tag);
  $destiny->setPlatform($platform);

  // Open container and row divs
  echo '<div class="container">';
  echo '<div class="row">';

  // Gamer tag
  echo '<h1>' . $destiny->getGamerTag() . '</h1>';

  // Close container and row divs
  echo '</div>';
  echo '</div>';

  // Open container and row divs
  echo '<div class="container">';
  echo '<div class="row">';

  foreach ($destiny->getGamerClass() as $gamer_class){

    //echo '<pre>', var_dump($gamer_class->Response->character->data), '</pre>';

    echo '<div class="col-xs-12 col-md-4 col-xl-4">';
    echo '<img src="http://www.bungie.net/'. $gamer_class->Response->character->data->emblemBackgroundPath .'" class="img-responsive">';
    echo '</div>';
  }

  // Close container and row divs
  echo '</div>';
  echo '</div>';

?>
