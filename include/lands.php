<?php
include_once("land_descr.php");
include_once("land_utils.php");
include_once("constants.php");

/**
 * lands.php
 * This module handle the land containter.
 */

class Lands
{
   private $my_lands;           //land container
   
   /* Class constructor */
   public function Lands(){
     $this->my_lands = array();
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

   public function fixAvailableLands(){
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
}

?>
