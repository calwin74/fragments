<?php
include_once("land_descr.php");
include_once("land_utils.php");
include_once("constants.php");
include_once("include/session.php");

/**
 * lands.php
 * This module handle the land containter.
 */

class Lands
{
   private $my_lands;           //land container
   
   /* Class constructor */
   public function Lands($x, $y, $characterName){
     global $session;
     $database = $session->database;

     $this->my_lands = array();
     $land_rows = $database->map($x, $y, X_LOCAL_MAP_SIZE, Y_LOCAL_MAP_SIZE);

     foreach ($land_rows as $row){
       $land = new Land;
       $land->init($row["x"], $row["y"], $row["type"], $row["toxic"]);
       /* handle land ownership */
       if ($row["owner"]){
         if (strcmp($characterName, $row["owner"]) == 0){
           $land->setOwner(I_OWN);
         }
         else{
           $land->setOwner(YOU_OWN);
         }
       }
       else{
         $land->setOwner(NOT_OWNED);
       }
       $this->addLand($land);
     }

     /* handle available lands */
     $this->fixAvailableLands();
   }

   /* Public methods */  
   public function addLand($land){
     $this->my_lands[$land->getName()] = $land;
   }

   public function getLand($name){
     $land = $this->my_lands[$name];
     return $land;
   }

   public function getLandCount(){
     return count($this->my_lands);
   }

   public function setAvailableLand($x, $y){
     /* check input */
     if (($x > X_LOCAL_MAP_SIZE) || ($x < -X_LOCAL_MAP_SIZE)){
       return;
     }
     if (($y > Y_LOCAL_MAP_SIZE) || ($y < -Y_LOCAL_MAP_SIZE)){
       return;
     }
     $key = createKey($x, $y);
     $land = $this->getLand($key);

     if ($land){
       if ($land->getOwner() == NOT_OWNED){
         $land->setAvailable(AVAILABLE);
       }
     }
   }

   private function fixAvailableLands(){
     foreach ($this->my_lands as $land){
       if (($land->getOwner() == I_OWN) && ($land->getToxic() == TOXIC_CLEAN)){
         $land->setAvailable(NOT_AVAILABLE);

         /* Mark neighborhood as available
          * - Note: This will change if changing map to odd numbers
          **/
         $x = $land->getX();
         $y = $land->getY();
            
         if ($y % 2){
           /* y is odd */

           /* x,y+2 */
           $this->setAvailableLand($x, $y+2);

           /* x+1,y+1 */
           $this->setAvailableLand($x+1, $y+1);

           /* x+1,y-1 */
           $this->setAvailableLand($x+1, $y-1);
  
           /* x,y-2 */
           $this->setAvailableLand($x, $y-2);

           /* x,y-1 */
           $this->setAvailableLand($x, $y-1);

           /* x,y+1 */
           $this->setAvailableLand($x, $y+1);

         }
         else{
           /* y is even */

           /* x,y+2 */
           $this->setAvailableLand($x, $y+2);

           /* x,y+1 */
           $this->setAvailableLand($x, $y+1);

           /* x,y-1 */
           $this->setAvailableLand($x, $y-1);

           /* x,y-2 */
           $this->setAvailableLand($x, $y-2);

           /* x-1,y-1 */
           $this->setAvailableLand($x-1, $y-1);

           /* x-1,y+1 */
           $this->setAvailableLand($x-1, $y+1);
         }
       }
     }
   }

   public function getLandsXML(){
     foreach ($this->my_lands as $land){
       $xml = $land->getDescrXML();
       echo $xml."\n";
     }
   }
}

?>
