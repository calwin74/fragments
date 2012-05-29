<?php
/*
 * This model creates JSON map updates
 */

include_once("include/session.php");
include_once("include/land_descr.php");
include_once("include/lands.php");
include_once("include/character.php");

global $session;
$database = $session->database;  //The database connection

$x = 0;
$y = 0;

$x = $_GET["x_position"];
$y = $_GET["y_position"];
$x_batch_size = $_GET["x_batch_size"];
$y_batch_size = $_GET["y_batch_size"];

/* get character */
$character = new Character();

/* get lands */
// 0 isAction
// NULL mark_key
$lands = new Lands($x, $y, $character->getName(), 0, 
                   $x_batch_size, $y_batch_size,
                   NULL);

/* generate json response */
$descr = $lands->getLandArrayDescr(0); // isAction is 0 for now
$json = json_encode($descr);
echo $json;

?>