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
     $character = 0;
     $this->toxic = $toxic;
     $this->available = 0;
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

   public function getDescr($position) {
      $classes = $this->getClasses($this->getOwner(), $this->getCharacter());
      $tag = $this->getTag();
      $name = $this->getName();
      $x = $this->getX();
      $y = $this->getY();
      //$coord = $x.",".$y;
      $toxic = "";
      if (($this->getAvailable() == AVAILABLE) || ($this->getOwner() == I_OWN)){
         $toxic = $this->getToxic();
      }
      $html_descr = "<$tag class=\"$classes $position\" id=$name><p>$toxic</p></$tag>";

      return $html_descr;
   }

   /* Private methods */

   /**
´   * Get land class types.
    */

   private function getClasses($owner, $character){
      $img = "hex";

      if ($owner == 0){
         $img .= " gray";
      }
      else if ($owner == 1){
         $img .= " cyan";
      }
      else if ($owner == 2){
         $img .= " blue";
      }
      /* else */

      /*
      if ($character){
         $img .= " character";
      }
      */

      return $img;
    }

   /**
    * The tag decides if land is clickable ...
    */
   private function getTag(){
      $tag = "default";

      if (($this->owner == NOT_OWNED) && ($this->available == AVAILABLE)){
         /* possible to move here */
         return "move";
      }
      else if (($this->owner == I_OWN) && ($this->toxic < TOXIC_CLEAN)){
         /* possible to clean */
         return "clean";
      }
      else if ($this->owner == YOU_OWN){
         /* someone owns this already - not applicable */
         return "na";
      }      
      return $tag;
   }
}
?>
