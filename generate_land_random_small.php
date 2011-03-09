<?php

include_once("include/session.php");

global $session;
$database = $session->database;  //The database connection

/* set this to override php max execute timeout */
ini_set('max_execution_time', 0);

for ($y=2; $y>-3; $y--)
{
  for ($x=-2; $x<3; $x++)
  {
    $type = rand(1,3);
    $yield = 1;
 
    $database->addLand($x, $y, $type, $yield);
  } 
}

?>