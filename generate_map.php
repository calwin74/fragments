<?php

include_once("include/session.php");
include_once("include/constants.php");

global $session;
$database = $session->database;  //The database connection

/* set this to override php max execute timeout */
ini_set('max_execution_time', 0);

for ($y=Y_GLOBAL_MAP_SIZE; $y>=-Y_GLOBAL_MAP_SIZE; $y--)
{
  for ($x=-X_GLOBAL_MAP_SIZE; $x<=X_GLOBAL_MAP_SIZE; $x++)
  {
    $type = rand(1,20);
    $toxic = DEFAULT_TOXIC;
 
    $database->addLand($x, $y, $type, $toxic);
  } 
}

/* add admin user */
$database->addNewUser('admin', md5("aaa123"), 'admin@home.org')

?>