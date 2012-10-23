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

      $y_key_start = round(($y + $y_size)/2);

      for ($y_pos = $y + $y_size; $y_pos >= $y - $y_size; $y_pos--) {
         $is_first_odd = 1;
         $is_first_even = 1;
         $position = "";

	      $x_key = $x - $x_size * 2;
	      $y_key = $y_key_start;

	      if ($is_odd) {
	         $x_key++;
	         $y_key_start--;
	      }

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
	         $hex_key = createKey($x_key, $y_key);
	         $x_key = $x_key + 2;
	         $y_key = $y_key + 1;

            // start tile
            $s = "<span ";
            if ($position && strlen($position)) {
               if ($level == 1) {
                  $s .= "class=\"$position hex\" ";
               }
               else if ( ($level == 2) || ($level == 3) || ($level == 4) ) {
                  $s .= "class=\"$position front\" ";
               }
            }
            else {
               if ($level == 1) {
                  $s .= "class=\"hex\" ";
               }
               else if ( ($level == 2) || ($level == 3) || ($level == 4) ) {
                  $s .= "class=\"front\" ";
               }
            }

            // id
            if ($level == 1) {
               $s .= "id=a$key> ";
            }
            else if ($level == 2) {
               $s .= "id=b$key> ";               
            }
            else if ($level == 3) {
               $s .= "id=c$key> ";
            }
            else if ($level == 4) {
               $s .= "id=d$key> ";
            }
/*
            if ($level == 1 && 
               (($x_pos == 0 && $y_pos == 0) ||
               ($x_pos == 0 && $y_pos == 2) ||
               ($x_pos == -4 && $y_pos == 2) ||  
               ($x_pos == 0 && $y_pos == 4)))
            {
               $s .= "<img src=\"\"></img> ";

               $s .= "<div class=\"smoke\">";
		         $s .= "<img src=\"img/smoke1.png\" width=\"70\" height=\"49\" />";
		         $s .= "<img src=\"img/smoke2.png\" width=\"70\" height=\"49\" />";
		         $s .= "<img src=\"img/smoke3.png\" width=\"70\" height=\"49\" />";
	            $s .= "</div>";
            }
*/
	         //Doesn't work with empty src values except for FireFox.
            //$s .= "<img src=\"\"></img> ";

	         //Add this to allow for coordinates or information
            /*
	         if ($level == 2) {
	            $s .= "<p>";
	            //$s .= $key;
	            $s .= "</p>";
            }
            */
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
