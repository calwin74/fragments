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
     $this->my_army = 0;
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

   public function markLand(){
      $this->marked = 1;
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
   
   public function getDescr($isAction) {
      $descr = array();

      $classes = $this->getClasses($isAction);
      $descr["class"] = $classes;
      
      $image = $this->getImage();
      $descr["image"] = $image;
 
      return $descr;
   }

   public function getMyArmy(){
      return $this->my_army;
   }

   public function setMyArmy($enable){
      $this->my_army = $enable;
   }

   /* Private methods */

   /**
�   * Get land class types.
    */
   private function getClasses($isAction){
      $class = "hex";

      $class .= $this->getTerrainClass();

      $class .= $this->getActionClass($isAction);

      return $class;
    }

   /**
    * Get image of building(s)
    */
   private function getImage(){
      $image = NULL;

      $building = $this->getBuilding();
         
      if ($building){
         $image = "img/".$building;                       

         if ($this->getBunker()){
            $image .= "-bunker";
         }
         if ($this->getCharacter()){
            $image .= "-army";
            if ($this->getOwner() == YOU_OWN){
               $image .= "-enemy";
            }
         }
      }
      else {
         if ($this->getCharacter()){
            if ($this->getMyArmy()) {
               $image .= "img/army";
            }
            else{
               $image .= "img/army-enemy";
            }
         }
      }

      if ($image){
         $image .= ".png";
      }
      
      return $image;
   }

   private function getTerrainClass(){
      $class = "";

      if ($this->marked){
         $class .= " marked";
         return $class;
      }

      /* get terrain type */
      $type = $this->getType();

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

      /*
      if ($this->getOwner() == I_OWN){
         $class .= " mark";
      }
      */

      /*
      else if ($this->getOwner() == YOU_OWN){
         $class .= " blue";
      }
      */

      return $class;
   }

  private function getActionClass($isAction){
      $class = "";
      $marked = 0;

      if ( !$isAction && ($this->getAvailable() == AVAILABLE) ){
         if ($this->getOwner() == I_OWN){
            $class .= " move_mark";
            $marked = 1;
         }
         else{
            $class .= " move";
         }
      }

      if ( !$marked && ($this->getOwner() == I_OWN) ){
         $class .= " mark";
      }

      if ( !$isAction && $this->getExplore() && $this->getCharacter() ){
         $class .= " explore";
      }

      return $class;
  }
}
?>
