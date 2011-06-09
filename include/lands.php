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
   private $isAction;
   
   /* Class constructor */
   public function Lands($x, $y, $characterName, $isAction){
     global $session;
     $database = $session->database;

     $character_x = NULL;
     $character_y = NULL;

     $this->my_lands = array();
     $land_rows = $database->map($x, $y, X_LOCAL_MAP_SIZE, Y_LOCAL_MAP_SIZE);

     foreach ($land_rows as $row){
       $land = new Land;
       $land->init($row["x"], $row["y"], $row["type"], $row["toxic"], $row["civilians"]);
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

     /* mark units */
     $units = $database->units($x, $y, X_LOCAL_MAP_SIZE, Y_LOCAL_MAP_SIZE);
     foreach ($units as $unit){
       $key = createKey($unit["x"], $unit["y"]);
       $land = $this->getLand($key);
       $land->setCharacter(1);

       if (!strcmp($unit["name"], $characterName)) {
         $character_x = $unit["x"];
         $character_y = $unit["y"];
       }
     }

     /* handle available lands */
     $this->fixAvailableLands($character_x, $character_y);

     /* mark that move is in progress */
     $this->isAction = $isAction;
   }

   /* Public methods */  

   /* printMap - prints map
      Loops laying the heagon tiles:
      1. First row is always even.   
      2. The first odd tile is special.
      3. NOTE: The x and y coordinates in the for-loops are there to set up the map
   */

   public function printMap($x, $y){
      $first_row = 1;
      $is_odd = 0;

      for ($y_pos = $y + Y_LOCAL_MAP_SIZE; $y_pos >= $y - Y_LOCAL_MAP_SIZE; $y_pos--){
         $is_first_odd = 1;
         $is_first_even = 1;
         $position = "";

         for ($x_pos = $x - X_LOCAL_MAP_SIZE; $x_pos <= $x + X_LOCAL_MAP_SIZE; $x_pos++){
            if ($first_row){
               $position = "first";
            }
            else{
               if ($is_odd){
                  if ($is_first_odd){
                     $is_first_odd = 0;
                     $position = "br firstodd";           
                  }
                  else{
                     $position = "odd";
                  }
               }
               else{
                  if ($is_first_even){
                     $is_first_even = 0;
                     $position = "br even";
                  }
                  else{
                     $position = "even";
                  }
               }
            }

            $key = createKey($x_pos, $y_pos);
            $land = $this->getLand($key);
            $land_descr = $land->getDescr($this->isAction());
            $classes = $land_descr["class"];

            /* ugly code to mark characters, should use css classes instead */
            if (strstr($classes, "character")){
               $unit = "$";
            }
            else{
               $unit = "";
            }

            echo "<span class=\"$classes $position\" id=$key><p>$unit</p></span>";
         }
  
         if ($is_odd){
            $is_odd = 0;
         }
         else{
            $is_odd = 1;
         }

         if ($first_row){
            $first_row = 0;
         }
      }
   }

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
       $land->setAvailable(AVAILABLE);
     }
   }

   private function fixAvailableLands($unit_x, $unit_y){
     global $session;

     /* check if character is within land borders, if so mark land neighbourhood as available */   
     $unit_land = $this->getLand(createKey($unit_x, $unit_y));

     if($unit_land->getOwner() == I_OWN){
       foreach ($this->my_lands as $land){
         if ($land->getOwner() == I_OWN){
           $land->setAvailable(AVAILABLE);

           /* Mark neighborhood as available
            * - Note: This will change if changing map to odd numbers
            **/

           $x = $land->getX();
           $y = $land->getY();

           $nhood = '';
           $this->getNeighbourhood($x, $y, $nhood);
           
           foreach ($nhood as $l){
             $this->setAvailableLand($l->getX(), $l->getY());
           }
         }
       }
     }
     else if( isset($unit_x) && isset($unit_y) && ($unit_land->getOwner() == NOT_OWNED) ){
       /* if some tile in the neighbourhood is owned, current tile can be colonized */
       $nhood = '';
       $ok = 0;

       $this->getNeighbourhood($unit_x, $unit_y, $nhood);
       foreach ($nhood as $l){
         if($l->getOwner() == I_OWN){
           $unit_land->setColonize(1);
           break;         
         }
       }
     }

     /* mark character neighbourhood as available */     
     if (isset($unit_x) && isset($unit_y)){
       $nhood = '';
       $this->getNeighbourhood($unit_x, $unit_y, $nhood);

       foreach ($nhood as $l){
         $this->setAvailableLand($l->getX(), $l->getY());
       }       

              
     } 
   }

   private function getNeighbourhood($x, $y, &$nhood){
     $nhood = array();

     if ($y % 2){
       /* y is odd */

       /* x,y+2 */
       $land = $this->getLand(createKey($x, $y+2)); 
       $nhood[$land->getName()] = $land;

       /* x+1,y+1 */
       $land = $this->getLand(createKey($x+1, $y+1)); 
       $nhood[$land->getName()] = $land;

       /* x+1,y-1 */
       $land = $this->getLand(createKey($x+1, $y-1)); 
       $nhood[$land->getName()] = $land;
  
       /* x,y-2 */
       $land = $this->getLand(createKey($x, $y-2)); 
       $nhood[$land->getName()] = $land;

       /* x,y-1 */
       $land = $this->getLand(createKey($x, $y-1)); 
       $nhood[$land->getName()] = $land;

       /* x,y+1 */
       $land = $this->getLand(createKey($x, $y+1)); 
       $nhood[$land->getName()] = $land;
     }
     else{
       /* y is even */

       /* x,y+2 */
       $land = $this->getLand(createKey($x, $y+2)); 
       $nhood[$land->getName()] = $land;

       /* x,y+1 */
       $land = $this->getLand(createKey($x, $y+1)); 
       $nhood[$land->getName()] = $land;

       /* x,y-1 */
       $land = $this->getLand(createKey($x, $y-1)); 
       $nhood[$land->getName()] = $land;

       /* x,y-2 */
       $land = $this->getLand(createKey($x, $y-2)); 
       $nhood[$land->getName()] = $land;

       /* x-1,y-1 */
       $land = $this->getLand(createKey($x-1, $y-1)); 
       $nhood[$land->getName()] = $land;

       /* x-1,y+1 */
       $land = $this->getLand(createKey($x-1, $y+1)); 
       $nhood[$land->getName()] = $land;
     }
   }

   public function getLandsXML(){
     foreach ($this->my_lands as $land){
       $xml = $land->getDescrXML();
       echo $xml."\n";
     }
   }

   public function markLand($key){
     $land = $this->getLand($key);
     $land->markLand();
   }

   public function isAction(){
      return $this->isAction;
   }

}

?>
