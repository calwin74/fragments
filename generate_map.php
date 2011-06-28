<?php

include_once("include/session.php");
include_once("include/constants.php");

global $session;
$database = $session->database;  //The database connection

/* set this to override php max execute timeout */
ini_set('max_execution_time', 0);

for ($y=Y_LOCAL_MAP_SIZE; $y>=-Y_LOCAL_MAP_SIZE; $y--)
{
  for ($x=-X_LOCAL_MAP_SIZE; $x<=X_LOCAL_MAP_SIZE; $x++)
  {
    $type = rand(1,21);
    $yield = 1;
    $toxic = rand(0,10);
 
    $database->addLand($x, $y, $type, $yield, $toxic);
  } 
}

?>