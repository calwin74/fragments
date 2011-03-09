<?php
/**
 * land_utils.php
 * 
 * This model handles util functions for lands.
 * 
 */
include_once("constants.php");

/**
 * Create key to use with land hash array
 */
function createKey($x, $y){
   $key = $x."_".$y;

   return $key;
}

/**
 * Decompose key into x and y.
 */
function getXfromKey($key){
   $data = explode("_", $key);
   $x = $data[0];

   return $x;
}

function getYfromKey($key){
   $data = explode("_", $key);
   $y = $data[1];
   
   return $y;
}

?>