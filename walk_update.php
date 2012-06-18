<?php
/*
 * This model handles walk updates, just writing to the action queue.
 */

include_once("include/session.php");
include_once("include/constants.php");
include_once("include/character.php");
include_once("include/utils.php");

global $session;
$database = $session->database;  //The database connection

$steps;
$steps = $_GET["steps"];

/* get character */
$character = new Character();

$moves = explode(",", $steps); 
$times = 1;

foreach($moves as $move){
   $coord = explode("|", $move);
   $database->addToActionQueue($coord[0], $coord[1], $character->getName(), getNow(MOVE_TIME * $times++), MOVE, 0);
}

/* some response */
echo count($moves);

?>