<?php
include_once("constants.php");
include_once("land_utils.php");

/**
 * land_descr.php
 */

class Land
{
   private $x;                  //x coordinate
   private $y;                  //y coordinate
   private $name;               //object name and key
   private $type;               //type of land
   private $owner;              //owner of land
   private $character;          //character in land
   private $available;          //available to enter
   private $toxic;              //toxic level in land
   private $marked;             //marked
   private $explore;            //available to colonize
   private $my_army;            //my army is present in land
   private $my_soldiers;        //soldiers in land
   /* Class constructor */
   public function Land(){
     /* nothing yet */
   }

   /* Public methods */

   /* Init object, need to be called first */
   public function init($x, $y, $type, $toxic){
     $this->x = $x;
     $this->y = $y;
     $this->type = $type;
     $this->name = createKey($x, $y);
     $this->toxic = $toxic;
     
     /* defaults */
     $this->available = 0;
     $this->marked = 0;
     $this->colonize = 0;
     $this->character = 0;
     $this->bunker = 0;
     $this->building = "";
     $this->my_army = 0; /* this is my character */
   }

   /* misc get and set functions */
   public function setX($x){
      $this->x = $x;
   }

   public function getX(){
      return $this->x;
   }

   public function setY($y){
      $this->y = $y;
   }

   public function getY(){
      return $this->y;
   }

   public function setType($type){
      $this->type = $type;
   }
   
   public function getType(){
      return $this->type;
   }

   public function setName($name){
      $this->name = $name;
   }

   public function getName(){
      return $this->name;
   }

   public function setOwner($owner){
      $this->owner = $owner;
   }

   public function getOwner(){
      return $this->owner;
   }

   public function setCharacter($enable){
      $this->character = $enable;
   }  

   public function getCharacter(){
      return $this->character;
   }

   public function getAvailable(){
      return $this->available;
   }

   public function setAvailable($available){
      $this->available = $available;
   }

   public function getToxic(){
      return $this->toxic;
   }

   public function markLand($set){
      $this->marked = $set;
   }

   public function isMarkedLand(){
      return $this->marked;
   }

   public function setExplore($enable){
      $this->explore = $enable;
   }

   public function getExplore(){
      return $this->explore;
   }

   public function setBunker($bunker){
      $this->bunker = $bunker;
   }

   public function getBunker(){
      return $this->bunker;
   }

   public function setBuilding($type){
      $this->building .= $type;
   }

   public function getBuilding(){
      return $this->building;
   }

   public function setSoldiers($count){
      $this->my_soldiers = $count;
   }

   public function getSoldiers(){
      return $this->my_soldiers;
   }
   
   public function getArrayDescr($isAction) {
      $descr = array();

      //key
      //$descr["key"] = $this->getName();

      //x
      $descr["x"] = $this->getX();

      //y
      $descr["y"] = $this->getY();

      //toxic
      $descr["toxic"] = $this->getToxic();

      //background classes
      $descr["bclasses"] = $this->getClassesBack();

      //background image
      //$descr["bimage"] = $this->getBuildingAnimation();

      //front classes
      $descr["classes"] = $this->getClassesFront($isAction);

      return $descr; 
   }

   public function isMyArmy(){
      return $this->my_army;
   }

   public function setMyArmy($enable){
      $this->my_army = $enable;
   }

   /* Private methods */

   /**
    * Get land class types.
    */
   private function getClassesBack(){
      $class = "hex";
      $building = "";

      $building = $this->getBuilding();
      if (strlen($building)){
         $class .= " ".$building;                       

         if ($this->getBunker()){
            $class .= "-bunker";
         }
      }      
      else {
         $class .= $this->getTerrainClass();   
      }

      return $class;
   }

   private function getClassesFront($isAction){
      $class = "front";

      $army = NULL;

      $army = $this->getArmyClass();

      if ($army) {
         $class .= $army;
      }

      //$class .= $this->getActionClass($isAction);

      return $class;
    }


   /**
    * Get image of building(s)
    */
   private function getBuildingAnimation(){
      $img = NULL;

      $building = $this->getBuilding();
         
      if ($building){
         if (!strcmp("factory", $building)) {
            $image = "img/factoryinprogres.gif";
         }
         else if (!strcmp("cityhall", $building) && $this->getBunker()) {
            $image = "img/cityhall-bunker-onfire.gif";
         }
      }

      return $image;
   }

   private function getArmyClass(){
      $class = NULL;

      if ($this->getCharacter()){
         if ($this->isMyArmy()) {
            $class = " army";
         }
         else{
            $class = " army-enemy";
         }
      }

      return $class;
   }

   private function getTerrainClass(){
      $class = "";

     /* get terrain type */
      $type = $this->getType();

      if ($this->marked){
         if (($type == DIRT1) || ($type == DIRT2) || ($type == DIRT3) || ($type == DIRT4) || ($type == DIRT5)) {
            $class .= " markeddirt";
         }
         else if (($type == VEG1) || ($type == VEG2) || ($type == VEG3) || ($type == VEG4) || ($type == VEG5)) {
            $class .= " markedveg";
         }
         else if (($type == DIRTVEG1) || ($type == DIRTVEG2) || ($type == DIRTVEG3) || ($type == DIRTVEG4) || ($type == DIRTVEG5)) {
            $class .= " markeddirtveg";         
         }
         else {
            $class .= " markeddirt";
         }
         return $class;
      }

      if ($type == DIRT1){
         $class .= " dirt1";
      }
      else if ($type == DIRT2){
         $class .= " dirt2";
      }
      else if ($type == DIRT3){
         $class .= " dirt3";
      }
      else if ($type == DIRT4){
         $class .= " dirt4";
      }
      else if ($type == DIRT5){
         $class .= " dirt5";
      }
      else if ($type == DIRTVEG1){
         $class .= " dirtveg1";
      }
      else if ($type == DIRTVEG2){
         $class .= " dirtveg2";
      }
      else if ($type == DIRTVEG3){
         $class .= " dirtveg3";
      }
      else if ($type == DIRTVEG4){
         $class .= " dirtveg4";
      }
      else if ($type == DIRTVEG5){
         $class .= " dirtveg5";
      }
      else if ($type == URBAN1){
         $class .= " urban1";
      }
      else if ($type == URBAN2){
         $class .= " urban2";
      }
      else if ($type == URBAN3){
         $class .= " urban3";
      }
      else if ($type == URBAN4){
         $class .= " urban4";
      }
      else if ($type == URBAN5){
         $class .= " urban5";
      }
      else if ($type == VEG1){
         $class .= " veg1";
      }
      else if ($type == VEG2){
         $class .= " veg2";
      }
      else if ($type == VEG3){
         $class .= " veg3";
      }
      else if ($type == VEG4){
         $class .= " veg4";
      }
      else if ($type == VEG5){
         $class .= " veg5";
      }
      else{ /* default */
         $class .= " gray";
      }

      return $class;
   }

  private function getActionClass($isAction){
      $class = "";
      $marked = 0;

      if ( !$isAction && ($this->getAvailable() == AVAILABLE) ){ /* available for my army */
         $class .= " move";
      }
      else if (!$this->isMarkedLand()){
         $class .= " mark";
      }
      else if ($this->isMarkedLand() && !$this->isMyArmy()){
         $class .= " unmark";
      }

      if ( !$isAction && $this->getExplore() && $this->getCharacter() ){
         $class .= " explore";
      }

      return $class;
  }
}
?>
