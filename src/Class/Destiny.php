<?php

  class Destiny {

    // Set the class properties

    private $platform;
    private $gamer_tag;
    private $gamer_id;
    private $gamer_class_id;

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

    // Getter methods

    public function getGamerTag(){

      return $this->gamer_tag;
    }

    // Class methods

    private function curl(){

      $ch = curl_init();

      curl_setopt($ch, CURLOPT_URL, API_HOST . $this->endpoint);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-API-Key: ' . API_KEY));

      $json = curl_exec($ch);

      curl_close($ch);

      return json_decode($json);
    }

    // Returns the gamer_id.
    public function getGamerId(){

      if(isset($this->platform) && isset($this->gamer_tag)){

        $this->endpoint = 'SearchDestinyPlayer/'. $this->platform .'/'. $this->gamer_tag .'/';

        $return = $this->curl()->Response[0];

        if(strlen($return->displayName) > 0){

          $this->gamer_id = $return->membershipId;
          $this->gamer_tag = $return->displayName;
        }
      }
    }

    // Returns the gamer class id's
    public function getGamerProfile(){

      $this->getGamerId();

      $this->gamer_class_id = [];

      if(isset($this->gamer_id)){

        $this->endpoint = $this->platform . '/Profile/' . $this->gamer_id .'/?components=100';

        $this->gamer_class_id = [];

        for($i=0;$i<3;$i++){

          $this->gamer_class_id[] = $this->curl()->Response->profile->data->characterIds[$i];
        }
      }
    }

    // Returns the gamer class info
    public function getGamerClass(){

      $this->getGamerProfile();

      $json = [];

      for($i=0;$i<3;$i++){

        if(isset($this->gamer_class_id)){

          foreach($this->gamer_class_id as $class_id){

            $this->endpoint = $this->platform . '/Profile/'. $this->gamer_id .'/Character/'. $class_id .'/?components=200';
            $json[] = $this->curl();
          }
        }

        return $json;
      }

    }
  }

?>
