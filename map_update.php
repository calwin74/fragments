<?php
/*
 * This model creates XML map updates
 *
 */

include_once("include/session.php");
include_once("include/land_descr.php");
include_once("include/lands.php");

global $session;

$database = $session->database;  //The database connection

$lands = new Lands();

$x = 0;
$y = 0;

$land_rows = $database->map($x, $y, X_LOCAL_MAP_SIZE, Y_LOCAL_MAP_SIZE);
$character = $database->getCharacter($session->username);
$characterName = $character["name"];

foreach ($land_rows as $row){
   $land = new Land;
   $land->init($row["x"], $row["y"], $row["type"], $row["toxic"]);
   /* handle land ownership */
   if ($row["owner"]){
      if (strcmp($characterName, $row["owner"]) == 0){
         $land->setOwner(I_OWN);
      }
      else{
         $land->setOwner(YOU_OWN);
      }
   }
   else{
      $land->setOwner(NOT_OWNED);
   }
   $lands->addLand($land);
}

/* handle available lands */
$lands->fixAvailableLands();

/* generate XML response */
header('Content-Type: text/xml');
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
echo "\n<lands>\n";

$lands->getLandsXML();

echo "</lands>";

?>