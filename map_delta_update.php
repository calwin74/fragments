<?php
/*
 * This model creates JSON map delta updates
 */

include_once("include/session.php");

global $session;
$database = $session->database;

$x = 0;
$y = 0;

$x = $_GET["x_position"];
$y = $_GET["y_position"];
$x_batch_size = $_GET["x_batch_size"];
$y_batch_size = $_GET["y_batch_size"];

/* get characters in map batch */
$units = $database->units($x, $y, $x_batch_size, $y_batch_size);

/* generate json response */
if ($units) {
   $desc = array();

   foreach ($units as $unit){
      $unit_desc = array();
            
      //x
      $unit_desc["x"] =  $unit["x"];

      //y
      $unit_desc["y"] =  $unit["y"];

      //army type
      if (!strcmp($unit["username"], $session->username)) {
         $unit_desc["army"] = "army";
      }
      else {
         $unit_desc["army"] = "army-enemy";
      }

      $desc[] = $unit_desc;
   }
}

$json = json_encode($desc);
echo $json;
?>