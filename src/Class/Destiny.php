<?php

  class Destiny {

    // Set the class properties

    private $platform;
    private $gamer_tag;
    private $membership_id;
    private $gamer_class_id;
    private $manifest;
    private $cache_file_path;
    private $emblem;
    private $icon;
    private $light_level;
    private $class;
    private $race;
    private $gender;
    private $inc;
    private $equipped_items_array;
    private $inventory_items_array;
    private $component;
    private $item_status;

    // Setter methods

    public function setGamerTag($gamer_tag){

      if(isset($gamer_tag)){

        $this->gamer_tag = strip_tags(trim($gamer_tag));
      }
    }

    public function setPlatform($platform){

      if(isset($platform)){

        $this->platform = strip_tags(trim($platform));
      }
    }

    public function setClassHash($class_hash){

      if(isset($class_hash)){

        $this->class_hash = $class_hash;
      }
    }

    public function setGenderHash($gender_hash){

      if(isset($gender_hash)){

        $this->gender_hash = $gender_hash;
      }
    }

    public function setRaceHash($race_hash){

      if(isset($race_hash)){

        $this->race_hash = $race_hash;
      }
    }

    public function setHash($hash){

      if(isset($hash)){

        $this->hash = $hash;
      }
    }

    public function setDbTable($db_table){

      if(isset($db_table)){

        $this->db_table = $db_table;
      }
    }

    public function setInc($i){

        $this->inc = $i;
    }

    // Getter methods

    public function getGamerTag(){

      return $this->gamer_tag;
    }

    public function getEmblem(){

      return $this->emblem;
    }

    public function getLightLevel(){

      return $this->light_level;
    }

    public function getClass(){

      return $this->class;
    }

    public function getGender(){

      return $this->gender;
    }

    public function getRace(){

      return $this->race;
    }

    public function getManifest(){

      if(isset($_GET['GetManifest'])){

        $this->extractManifest();

        header('Location: '. HOST . PATH);
      }
    }

    public function getInventoryItems(){

      return $this->inventory_items_array;
    }

    public function getEquippedItems(){

      return $this->equipped_items_array;
    }

    // Class methods

    private function multi_curl(){

      // array of curl handles
      $multiCurl = array();

      // data to be returned
      $result = array();

      // multi handle
      $mh = curl_multi_init();

      for($i=0;$i<count($this->endpoint);$i++){

        // URL from which data will be fetched
        $multiCurl[$i] = curl_init();
        curl_setopt($multiCurl[$i], CURLOPT_URL,$this->endpoint[$i]);
        curl_setopt($multiCurl[$i], CURLOPT_HTTPHEADER, array('X-API-Key: ' . API_KEY));
        curl_setopt($multiCurl[$i], CURLOPT_RETURNTRANSFER, TRUE);
        curl_multi_add_handle($mh, $multiCurl[$i]);
      }

      $index = NULL;
      do {

        curl_multi_exec($mh,$index);

      } while($index > 0);

      // get content and remove handles
      foreach($multiCurl as $k => $ch) {

        $this->result[$k] = curl_multi_getcontent($ch);

        curl_multi_remove_handle($mh, $ch);
      }

      // close
      curl_multi_close($mh);
    }

    private function curl(){

      // Create a cURL handle
      $ch = curl_init();

      // Set the cURL Options
      curl_setopt($ch, CURLOPT_URL, BASE_URL . $this->endpoint);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-API-Key: ' . API_KEY));

      // If there was an error, throw an Exception
      if(curl_errno($ch)){
          throw new Exception(curl_error($ch));
      }

      $json = curl_exec($ch);

      curl_close($ch);

      return json_decode($json);
    }

    // Returns the membership_id.
    public function getMembershipId(){

      if(isset($this->platform) && isset($this->gamer_tag)){

        $this->endpoint = 'Platform/Destiny2/SearchDestinyPlayer/'. $this->platform .'/'. $this->gamer_tag .'/';

        $return = $this->curl()->Response[0];

        if(strlen($return->displayName) > 0){

          $this->membership_id = $return->membershipId;
          $this->gamer_tag = $return->displayName;
        }
      }
    }

    // Returns the gamer class id's
    public function getCharacterClassIds(){

      $this->getMembershipId();

      if(isset($this->membership_id)){

        $this->endpoint = 'Platform/Destiny2/' . $this->platform . '/Profile/' . $this->membership_id .'/?components=100';
        $this->gamer_class_id = $this->curl()->Response->profile->data->characterIds;
      }
    }

    // Get weapons on character classes
    public function getItems(){

      $this->endpoint  = 'Platform/Destiny2/'. $this->platform;
      $this->endpoint .= '/Profile/'. $this->membership_id .'/Character/';
      $this->endpoint .= $this->gamer_class_id[$this->inc] .'/?components=' . $this->component;

      $json = $this->curl();

      // Initialise array to store weapons
      $this->class_weapons = [];

      foreach($json->Response->{$this->item_status}->data->items as $items){

        $this->class_weapons[] = $items;
      }
    }

    // Returns the gamer class info
    public function getCharacterClass(){

      $this->getCharacterClassIds();

      // Get emblem & light level
      $this->endpoint  = 'Platform/Destiny2/' . $this->platform;
      $this->endpoint .= '/Profile/'. $this->membership_id .'/Character/'. $this->gamer_class_id[$this->inc] .'/?components=200';
      $json = $this->curl();

      $this->light_level[] = $json->Response->character->data->light;
      $this->emblem[] = $json->Response->character->data->emblemBackgroundPath;

      // Get class name
      $this->setDbTable('DestinyClassDefinition');
      $this->hash = $json->Response->character->data->classHash;
      $this->class = $this->dbQuery()->displayProperties->name;

      // Get race
      $this->setDbTable('DestinyRaceDefinition');
      $this->hash = $json->Response->character->data->raceHash;
      $this->race = $this->dbQuery()->displayProperties->name;

      // Get gender
      $this->setDbTable('DestinyGenderDefinition');
      $this->hash = $json->Response->character->data->genderHash;
      $this->gender = $this->dbQuery()->displayProperties->name;

      // Weapon types
      $weapon_types = [
        'kinetic' => 1498876634,
        'energy'  => 2465295065,
        'power'   => 953998645,
      ];

      // Get equipped items
      $this->component = 205;
      $this->item_status = 'equipment';
      $this->getItems();

      $this->endpoint = [];
      $this->equipped_items_array = [];

      // Array of endpoints
      for($i=0;$i<count($this->class_weapons);$i++){

        if(isset($this->class_weapons[$i]->itemInstanceId)){

          $endpoint = BASE_URL . 'Platform/Destiny2/'. $this->platform;
          $endpoint .= '/Profile/'. $this->membership_id .'/Item/';
          $endpoint .= $this->class_weapons[$i]->itemInstanceId.'/?components=300';

          $this->endpoint[] = $endpoint;
        }
      }

      // Send parallel HTTP requests
      $this->multi_curl();

      $power_level = '';

      for($i=0;$i<count($this->result);$i++){

        $result = json_decode($this->result[$i]);

        if(isset($result->Response->instance->data->primaryStat)){

          $power_level = $result->Response->instance->data->primaryStat->value;
        }

        if(isset($this->class_weapons[$i]->itemHash)){

          $this->hash = $this->class_weapons[$i]->itemHash;
          $this->setDbTable('DestinyInventoryItemDefinition');
          $res = $this->dbQuery();

          // Get the weapon type
          $key = array_search($this->class_weapons[$i]->bucketHash, $weapon_types);

          // Add weapon properties to the array
          $this->equipped_items_array[$key] = [
            $res->displayProperties->icon,
            $res->displayProperties->name,
            $res->inventory->bucketTypeHash,
            $power_level
          ];
        }
      }

      //Get inventory items
      $this->component = 201;
      $this->item_status = 'inventory';
      $this->getItems();

      $this->inventory_items_array = [];
      $this->endpoint = [];

      for($i=0;$i<count($this->class_weapons);$i++){

        if(isset($this->class_weapons[$i]->itemInstanceId)){

          // Get the weapon power level
          $endpoint = BASE_URL . 'Platform/Destiny2/'. $this->platform;
          $endpoint .= '/Profile/'. $this->membership_id .'/Item/';
          $endpoint .= $this->class_weapons[$i]->itemInstanceId.'/?components=300';

          $this->endpoint[] = $endpoint;
        }
      }

      $power_level = '';
      $this->multi_curl();

      for($i=0;$i<count($this->result);$i++){

        $result = json_decode($this->result[$i]);

        if(isset($result->Response->instance->data->primaryStat)){

          $power_level = $result->Response->instance->data->primaryStat->value;

          $this->hash = $this->class_weapons[$i]->itemHash;
          $this->setDbTable('DestinyInventoryItemDefinition');
          $res = $this->dbQuery();

          // Get the weapon type
          $key = array_search($this->class_weapons[$i]->bucketHash, $weapon_types);

          // Add weapon properties to the array
          $this->inventory_items_array[$key][] = [
            @$res->displayProperties->icon,
            $res->displayProperties->name,
            $res->inventory->bucketTypeHash,
            $power_level
          ];
        }
      }
    }

    public function getRecentActivity(){

      $this->endpoint = 'Platform/Destiny2/' . $this->platform;
      $this->endpoint .= '/Account/'. $this->membership_id .'/Character/'. $this->gamer_class_id[$this->inc] .'/Stats/Activities/';
    }

    private function getManifestUrl(){

      $this->endpoint = 'Platform/Destiny2/Manifest/';
      $this->manifest = $this->curl()->Response->mobileWorldContentPaths->en;

      return BASE_URL . trim($this->manifest, '/');
    }

    private function downloadManifest(){

      // The manifest to download
      $url = $this->getManifestUrl();

      // The path & filename to save to
      $this->cache_file_path = ROOT . PATH . 'src/cache/' . basename($url);

      // Open file handler.
      $fp = fopen($this->cache_file_path, 'w+');

      //If $fp is FALSE, something went wrong.
      if($fp === false){
          throw new Exception('Could not open: ' . $this->cache_file_path);
      }

      //Create a cURL handle
      $ch = curl_init(trim($url, '/'));

      // Set options
      curl_setopt($ch, CURLOPT_FILE, $fp);
      curl_setopt($ch, CURLOPT_TIMEOUT, 20);
      curl_exec($ch);

      // If there was an error, throw an Exception
      if(curl_errno($ch)){
          throw new Exception(curl_error($ch));
      }

      // Get the HTTP status code.
      $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

      curl_close($ch);
    }

    private function extractManifest(){

      // Download the manifest
      $this->downloadManifest();

      // Open and extract the manifest
      $zip = new ZipArchive;
      $res = $zip->open($this->cache_file_path);

      if ($res === TRUE) {

        $zip->extractTo(ROOT . PATH . 'src/cache/zip');
        $zip->close();

        // Rename the manifest file to easily reference it when querying the db
        rename(ROOT . PATH . 'src/cache/zip/' . basename($this->cache_file_path), ROOT . PATH . 'src/cache/zip/manifest.content');

      } else {

        echo 'Error extracting file.';
      }
    }

    public function dbQuery(){

      // Our unique index
      $hash = $this->hash;

      if($db = new SQLite3(ROOT . PATH . 'src/cache/zip/manifest.content')){

        $result = $db->query("SELECT * FROM $this->db_table WHERE id + 4294967296 = $hash OR id = $hash");
        $row = $result->fetchArray();
        $arr = json_decode($row['json']);

        return $arr;
      }
    }

  }

?>
