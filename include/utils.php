<?php
/**
 * utils.php
 * 
 * Utils funcitons
 *
 * Written by: matkar01
 */

/**
 * getNow - get time now
 * Optional to add positive time jump
 */
function getNow($future){
   $jump = "";

   if ($future > 0){
      $jump = "+".$future." seconds";
   }
   else{
      $jump = "+0 seconds";
   }

   $now = strtotime($jump);
   $now = strftime("%Y-%m-%d %H:%M:%S", $now);

   return $now;
}
