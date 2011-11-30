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
   public function Lands($x, $y, $characterName, $isAction, $x_size, $y_size, $marked_key){
     global $session;
     $database = $session->database;

     $character_x = NULL;
     $character_y = NULL;

     $this->my_lands = array();
     $land_rows = $database->map($x, $y, $x_size, $y_size);

     foreach ($land_rows as $row){
       $land = new Land;
       $land->init($row["x"], $row["y"], $row["type"], $row["toxic"]);

       /* handle land ownership */
       if ($row["owner"] && ($characterName != NULL)){
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
     $units = $database->units($x, $y, $x_size, $y_size);

     if ($units) {
       foreach ($units as $unit){
         $key = createKey($unit["x"], $unit["y"]);
         $land = $this->getLand($key);
         $land->setCharacter(1);
         $land->setSoldiers($unit["soldiers"]);

         if (!strcmp($unit["name"], $characterName)) {
           $character_x = $unit["x"];
           $character_y = $unit["y"];
           $explorers = $unit["explorers"];
           $land->setMyArmy(1);
         }
       }
     }

     if ( ($character_x != NULL) && ($character_y != NULL) ){
       /* handle available lands if character land is marked */
       $unit_land = $this->getLand(createKey($character_x, $character_y));       

       if ( $marked_key && !strcmp($marked_key, $unit_land->getName()) ){
         $this->fixAvailableLands($character_x, $character_y, $explorers, $x_size, $y_size);
       }
     }

     /* mark buildings */
     $buildings = $database->allBuildings($x, $y, $x_size, $y_size);

     foreach ($buildings as $building){
       $key = createKey($building["x"], $building["y"]);  
       $land = $this->getLand($key);
       if (!$building["constructing"]){
         if (strcmp("bunker", $building["type"])) {
            $land->setBuilding($building["type"]);
         }
         else {
            $land->setBunker(1);
         }
       }
     }

     /* mark that move is in progress */
     $this->isAction = $isAction;
   }

   /* Public methods */  

   /* printMap - prints map
      Loops laying the heagon tiles:
      1. First row is always even.   
      2. The first odd tile is special.
      3. NOTE: The x and y coordinates in the for-loops are there to set up the map
      4. $level 0: background is terrain, img is buildings
         $level 1: background is unit, img is effect.
   */

   public function printMap($x, $y, $x_size, $y_size, $level){
      $first_row = 1;
      $is_odd = 0;

      for ($y_pos = $y + $y_size; $y_pos >= $y - $y_size; $y_pos--){
         $is_first_odd = 1;
         $is_first_even = 1;
         $position = "";

         for ($x_pos = $x - $x_size; $x_pos <= $x + $x_size; $x_pos++){
            if ($first_row){
               /* test */
            }
            else {
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

            if ($level == 1) {
               /* handle tile in the back */
               $land_descr = $land->getDescrBack();
               $classes = $land_descr["class"];
               $image = $land_descr["image"];   
            }
            else if ($level == 2) {
               /* handle tile in the front */
               $land_descr = $land->getDescrFront($this->isAction);
               $classes = $land_descr["class"];
               $soldiers = $land_descr["soldiers"];
            }
         
            // start tile
            $s = "<span ";
            // classes and position
            if ($classes && strlen($classes)) {
               if ($position && strlen($position)) {
                  $s .= "class=\"$classes $position\" ";
               }
               else {
                  $s .= "class=\"$classes\" ";
               }
            }
            // id
            if ($level == 1){
               /* id for back end tile is b%key% */
               $s .= "id=b$key> ";
            }
            else if ($level == 2) {
               $s .= "id=$key> ";
            }  
            if ($level == 1){
               // image
               if ($image){
                  $s .= "<img src=\"$image\"></img> ";
               }
            }
            else if ($level == 2){
               // soldiers
               if ($soldiers){
                  $s .= "<p>".$soldiers."</p>";                  
               }
            }  
            // close tile
            $s .= "</span>";
            
            echo $s;
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

   public function setAvailableLand($x, $y, $x_size, $y_size){
     /* check input */
     /*
     if (($x > $x_size) || ($x < -$x_size)){
       return;
     }
     if (($y > $y_size) || ($y < -$y_size)){
       return;
     }
     */

     $key = createKey($x, $y);
     $land = $this->getLand($key);

     if ($land && $land->getType() != SEA){
       $land->setAvailable(AVAILABLE);
     }
   }

   private function fixAvailableLands($unit_x, $unit_y, $explorers, $x_size, $y_size){
     /* check if character is within land borders, if so mark land neighbourhood as available */   
     $unit_land = $this->getLand(createKey($unit_x, $unit_y));

     if($unit_land->getOwner() == I_OWN){
       foreach ($this->my_lands as $land){
         if ($land->getOwner() == I_OWN){
           $land->setAvailable(AVAILABLE, $x_size, $y_size);

           /* Mark neighborhood as available
            * - Note: This will change if changing map to odd numbers
            **/

           $x = $land->getX();
           $y = $land->getY();

           $nhood = '';
           $this->getNeighbourhood($x, $y, $nhood);

           foreach ($nhood as $l){
             $this->setAvailableLand($l->getX(), $l->getY(), $x_size, $y_size);
           }
         }
       }
     }
     else if( isset($unit_x) && isset($unit_y) && ($unit_land->getOwner() == NOT_OWNED) ){
       /* if some tile in the neighbourhood is owned, current tile can be explored */
       $nhood = '';
       $ok = 0;

       $this->getNeighbourhood($unit_x, $unit_y, $nhood);
       foreach ($nhood as $l){
         if( ($l->getOwner() == I_OWN) && ($explorers > 0) ){
           $unit_land->setExplore(1);
           break;         
         }
       }
     }

     /* mark character neighbourhood as available */     
     if (isset($unit_x) && isset($unit_y)){
       $nhood = '';

       $this->getNeighbourhood($unit_x, $unit_y, $nhood);

       foreach ($nhood as $l){
         $this->setAvailableLand($l->getX(), $l->getY(), $x_size, $y_size);
       }                     
     } 
   }

   private function getNeighbourhood($x, $y, &$nhood){
      $nhood = array();

      if ($y % 2){
         /* y is odd */

         /* x,y+2 */
         $land = $this->getLand(createKey($x, $y+2)); 
         if($land){
            $nhood[$land->getName()] = $land;
         }

         /* x-1,y+1 */
         $land = $this->getLand(createKey($x-1, $y+1));             
         if($land){
            $nhood[$land->getName()] = $land;
         }

         /* x-1,y-1 */
         $land = $this->getLand(createKey($x-1, $y-1)); 
         if($land){
            $nhood[$land->getName()] = $land;
         }

         /* x,y-2 */
         $land = $this->getLand(createKey($x, $y-2)); 
         if($land){
            $nhood[$land->getName()] = $land;
         }

         /* x,y-1 */
         $land = $this->getLand(createKey($x, $y-1)); 
         if($land){
            $nhood[$land->getName()] = $land;
         }

         /* x,y+1 */
         $land = $this->getLand(createKey($x, $y+1)); 
         $nhood[$land->getName()] = $land;
      }   
      else{
         /* y is even */

         /* x,y+2 */
         $land = $this->getLand(createKey($x, $y+2));
         if($land){
            $nhood[$land->getName()] = $land;
         }

         /* x,y+1 */
         $land = $this->getLand(createKey($x, $y+1)); 
         if($land){
            $nhood[$land->getName()] = $land;
         }

         /* x,y-1 */
         $land = $this->getLand(createKey($x, $y-1)); 
         if($land){
            $nhood[$land->getName()] = $land;
         }
       
         /* x,y-2 */
         $land = $this->getLand(createKey($x, $y-2)); 
         if($land){
            $nhood[$land->getName()] = $land;
         }

         /* x+1,y-1 */
         $land = $this->getLand(createKey($x+1, $y-1));            
         if($land){
            $nhood[$land->getName()] = $land;
         }

         /* x+1,y+1 */
         $land = $this->getLand(createKey($x+1, $y+1)); 
         if($land){
            $nhood[$land->getName()] = $land;
         }
      }
   }

   public function getLandsXML(){
     foreach($this->my_lands as $land){
       $xml = $land->getDescrXML();
       echo $xml."\n";
     }
   }

   public function getSurrounding($x, $y, &$nhood){
     $this->getNeighbourhood($x, $y, $nhood);
   }

   public function markLand($key){
     $land = $this->getLand($key);

     if ($land) {
       if ($land->isMarkedLand()){
         $land->markLand(0);
       }
       else{
         $land->markLand(1);
       }
     }
   }

   public function isAction(){
      return $this->isAction;
   }

}

?>
