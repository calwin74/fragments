<?php

include_once("include/session.php");
include_once("include/constants.php");

global $session;
$database = $session->database;  //The database connection

/* set this to override php max execute timeout */
ini_set('max_execution_time', 300);

$is_odd = 0;
$y_hex_start = Y_GLOBAL_MAP_SIZE + 1;

for ($y=Y_GLOBAL_MAP_SIZE; $y>=-Y_GLOBAL_MAP_SIZE; $y--)
{
   if ($is_odd) {
      $y_hex_start = $y_hex_start;
   }
   else {
      $y_hex_start = $y_hex_start - 1; 
   }

   $x_hex = -X_GLOBAL_MAP_SIZE;
   $y_hex = $y_hex_start;

   if ($is_odd) {
      $x_hex++;
   }

   for ($x=-X_GLOBAL_MAP_SIZE; $x<=X_GLOBAL_MAP_SIZE; $x++)
   {
      $type = rand(1,10);
      $toxic = DEFAULT_TOXIC;

      $database->addLand($x, $y, $x_hex, $y_hex, $type, $toxic);

      $x_hex = $x_hex + 2;
      $y_hex = $y_hex + 1;
   }

   if ($is_odd) {
      $is_odd = 0;
   }
   else {
      $is_odd = 1;
   }
}

/* add admin user */
$database->addNewUser('admin', md5("aaa123"), 'admin@home.org')

?>