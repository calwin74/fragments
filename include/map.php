<?php
include_once("constants.php");

/*
   map.php
   This module handles the html represention of the map.
*/

class Map {
   /* class constructor */
   public function Map() {
      /* empty for now ... */
   }

   /* --------------- public methods --------------- */

   /*
      printMap
      Create html for the board map.
      Loops laying the heagon tiles:
      1. First row is always even.   
      2. The first odd tile is special.
      3. NOTE: The x and y coordinates in the for-loops are there to set up the map
      4. $level 1: background is terrain, img is buildings
         $level 2: background is unit, img is effect.
   */    

   public function printMap($x, $y, $x_size, $y_size, $level) {
      $first_row = 1;
      $is_odd = 0;

      for ($y_pos = $y + $y_size; $y_pos >= $y - $y_size; $y_pos--) {
         $is_first_odd = 1;
         $is_first_even = 1;
         $position = "";

         for ($x_pos = $x - $x_size; $x_pos <= $x + $x_size; $x_pos++) {
            if ($is_odd) {
               if ($is_first_odd) {
                  $is_first_odd = 0;
                  $position = "br firstodd";           
               }
               else {
                  $position = "odd";
               }
            }
            else {
               if ($is_first_even) {
                  $is_first_even = 0;
                  $position = "br even";
               }
               else {
                  $position = "even";
               }
            }

            $key = createKey($x_pos, $y_pos);

            // start tile
            $s = "<span ";
            if ($position && strlen($position)) {
              if ($level == 1) {
                $s .= "class=\"$position hex\" ";
              }
              else {
                $s .= "class=\"$position front\" ";            
              }
            }
            else {
              if ($level == 1) {
                $s .= "class=\"hex\" ";
              }
              else {
                $s .= "class=\"front\" ";
              }
            }

            // id
            if ($level == 1) {
               /* id for back end tile is b%key% */
               $s .= "id=b$key> ";
            }
            else if ($level == 2) {
               $s .= "id=$key> ";
            }  

	    // Doesn't work with empty src values except for FireFox.
            //$s .= "<img src=\"\"></img> ";

            // close tile
            $s .= "</span>";
            
            echo $s;
         }
  
         if ($is_odd) {
            $is_odd = 0;
         }
         else {
            $is_odd = 1;
         }

         if ($first_row) {
            $first_row = 0;
         }
      }
   }
}

?>
