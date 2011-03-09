<?php

include_once("include/session.php");

global $session;
$database = $session->database;  //The database connection

/* set this to override php max execute timeout */
ini_set('max_execution_time', 0);

for ($y=50; $y>-51; $y--)
{
  for ($x=-50; $x<51; $x++)
  {
    $type = rand(1,3);
    $yield = 1;
 
    $database->addLand($x, $y, $type, $yield);
  } 
}

?>