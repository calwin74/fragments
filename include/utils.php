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

/**
 * createLnk - create a link based on destination, mark and focus key.
 */
function createLnk($dst, $mark_key, $focus_key) {
   $lnk = $dst;

   if ($mark_key && $focus_key) {
      $lnk = $lnk."?mark_key=".$mark_key."&focus_key=".$focus_key;
   }
   else if ($mark_key) {
      $lnk = $lnk."?mark_key=".$mark_key;
   }
   else if ($focus_key) {
      $lnk = $lnk."?focus_key=".$focus_key;
   }

   return $lnk;
}