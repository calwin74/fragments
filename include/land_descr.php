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
   private $civilians;          //civilians in land
   private $marked;             //marked
   private $colonize;           //available to colonize   
   /* Class constructor */
   public function Land(){
     /* nothing yet */
   }

   /* Public methods */

   /* Init object, need to be called first */
   public function init($x, $y, $type, $toxic, $civilians){
     $this->x = $x;
     $this->y = $y;
     $this->type = $type;
     $this->name = createKey($x, $y);
     /* round to lower integer */
     $this->civilians = floor($civilians);
     $this->toxic = $toxic;
     
     /* defaults */
     $this->available = 0;
     $this->marked = 0;
     $this->colonize = 0;
     $this->character = 0;
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

   public function getCivilians(){
      return $this->civilians;
   }

   public function markLand(){
      $this->marked = 1;
   }

   public function isMarkedLand(){
      return $this->marked;
   }

   public function setColonize($enable){
      $this->colonize = $enable;
   }

   public function getColonize(){
      return $this->colonize;
   }

   public function getDescr($isAction) {
      $descr = array();

      $classes = $this->getClasses($this->getCharacter(), $isAction);
      $descr["class"] = $classes;

      return $descr;
   }

   public function getDescrXML() {
      $classes = $this->getClasses($this->getCharacter());
      $name = $this->getName();
      $toxic = "";
      if (($this->getAvailable() == AVAILABLE) || ($this->getOwner() == I_OWN)){
         $toxic = $this->getToxic();
      }
      
      $xml_descr = "<land key=\"$name\" class=\"$classes\" toxic=\"$toxic\" />";

      return $xml_descr;
   }

   /* Private methods */

   /**
´   * Get land class types.
    */
   private function getClasses($character, $isAction){
      $class = "hex";

      $class .= $this->getOwnerClass();

      $class .= $this->getActionClass($isAction);

      if ($character){
         $class .= " character";
      }

      return $class;
    }

   private function getOwnerClass(){
      $class = "";

      if ($this->marked){
         $class .= " green";
      }
      else if ($this->getOwner() == NOT_OWNED){
         $class .= " gray";
      }
      else if ($this->getOwner() == I_OWN){
         $class .= " cyan";
      }
      else if ($this->getOwner() == YOU_OWN){
         $class .= " blue";
      }

      return $class;
   }

  private function getActionClass($isAction){
      $class = "";

      if ( !$isAction && $this->getAvailable() && !$this->getCharacter() ){
         $class .= " move";
      }

      if ( !$isAction && $this->getColonize() && $this->getCharacter() ){
         $class .= " colonize";
      }

      return $class;
  }
}
?>
