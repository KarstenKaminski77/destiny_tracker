<!-- Player search -->
<div class="container">
  <form method="post">
    <div class="row jumbotron jumbotron-top-margin">
      <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2 col-xl-2">
        <select name="platform" class="form-control" required>
          <option>Select...</option>
          <option value="2">Playstation</option>
          <option value="1">XBOX</option>
        </select>
      </div>
      <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 col-xl-9">
        <input type="text" name="gamer-tag" class="form-control" placeholder="Player Search" required>
      </div>
      <div class="col-xs-12 col-sm-12 col-md-1 col-lg-1 col-xl-1">
        <input type="submit" name="search" class="btn btn-primary" value="SEARCH">
      </div>
    </div>
  </form>
</div>
<!-- End player search -->

<?php

  if(isset($_POST['search'])){

    $gamer_tag = $_POST['gamer-tag'];
    $platform = $_POST['platform'];


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

    for($i=0;$i<3;$i++){

      $destiny->setInc($i);
      $destiny->getCharacterClass();
      $emblem = $destiny->getEmblem();
      $light_level = $destiny->getLightLevel();
      $class = $destiny->getClass();
      $race = $destiny->getRace();
      $gender = $destiny->getGender();

      // Class emblem
      echo '<div class="col-xs-12 col-md-4 col-xl-4">';
      echo '  <div class="wrapper emblem">';
      echo '    <img src="http://www.bungie.net/'. $emblem[$i] .'" class="img-responsive">';
      echo '    <div class="overlay-top-right">';
      echo '      <span class="emblem-light-level">'. $light_level[$i] .'</span>';
      echo '    </div>';
      echo '    <div class="overlay-top-left">'. $class .'</div>';
      echo '    <div class="overlay-btm-left">';
      echo '      <span class="emblem-light-level">'. $race . ' ' . $gender .'</span>';
      echo '    </div>';
      echo '  </div>';

      // Weapons
      $destiny->getEquippedItems();
      $weapons = ['kinetic', 'energy', 'power'];

      foreach ($weapons as $weapon){

        // Equipped weapon
        $image = $destiny->getEquippedItems()[$weapon][0];
        $title = $destiny->getEquippedItems()[$weapon][1];
        $power_level = $destiny->getEquippedItems()[$weapon][3];

        echo '<div class="row">';
        echo '  <div class="col-xs-12 col-md-12 col-xl-12">';
        echo      '<h6>' . ucfirst($weapon) . ' Weapons</h1>';
        echo '  </div>';
        echo '</div>';
        echo '<div class="row">';
        echo '  <div class="col-xs-3 col-md-3 col-xl-3">';
        echo '    <div class="wrapper">';
        echo '      <img src="'. BASE_URL . $image .'" title="'. $title .'" class="bdr-equiupped ">';
        echo '      <span class="power_level">' . $power_level . '</span>';
        echo '    </div>';
        echo '  </div>';
        echo '  <div class="col-xs-9 col-md-9 col-xl-9">';

        // 9 slots available for unequipped weapons
        for($c=0;$c<9;$c++){

          // Only display weapon if it exists
          if(isset($destiny->getInventoryItems()[$weapon])){

            $count = count($destiny->getInventoryItems()[$weapon]);

          } else {

            $count = 0;
          }

          if($c < $count){

            $item = $destiny->getInventoryItems()[$weapon][$c];

            echo '    <div class="col-xs-4 col-md-4 col-xl-4">';
            echo '      <div class="wrapper">';
            echo '        <img src="'. BASE_URL . $item[0] .'" title="'. $item[1] .'" class="bdr-inventory center-block">';
            echo '        <span class="power_level">' . $item[3] . '</span>';
            echo '      </div>';
            echo '    </div>';

          // Display empty slot
          } else {

            echo '    <div class="col-xs-4 col-md-4 col-xl-4">';
            echo '      <span class="empty-container">';
            echo '    </div>';
          }
        }

        echo '  </div>';
        echo '</div>';
      }

      echo '</div>';
    }

    // Close container and row divs
    echo '</div>';
    echo '</div>';
  }

?>
