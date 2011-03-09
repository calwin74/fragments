<?php

include_once("include/session.php");

global $session;
$database = $session->database;  //The database connection

/* set this to override php max execute timeout */
ini_set('max_execution_time', 0);

for ($y=16; $y>-17; $y--)
{
  for ($x=-4; $x<5; $x++)
  {
    $type = rand(1,3);
    $yield = 1;
    $toxic = 0;
    $database->addLand($x, $y, $type, $yield, $toxic);
  } 
}

?>