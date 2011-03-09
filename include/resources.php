<?php
/**
 * resources.php
 * 
 * This module include utility functions for resources like population, money and goods.
 *
 * Written by: matkar01
 */
include_once("session.php");
include_once("database.php");
include_once("constants.php");

/**
 * initResources Initialize the economy for a character
 * Returns Nothing
 */
function initResources($characterName) {
   global $session;
   $database = $session->database; // The database connection

   $now = strtotime("+0 seconds");
   $now = strftime("%Y-%m-%d %H:%M:%S", $now);
   $growth = characterResources($characterName);

   $database->initResources($characterName, INIT_PRODUCTION, $growth, $now);
}

/**
 * calculateResources - calculate resources for all characters
 * Returns Nothing
 */
function calculateResources() {
   global $session;
   $database = $session->database;

   $resources = $database->checkResourceTime();
   for ($i = 0; $i < count($resources); $i++) {
      $new_production = 0;
      $current_production = 0;
      $next = "+".PRODUCTION_FREQUENCY." seconds";
      $now = strtotime($next);
      $now = strftime("%Y-%m-%d %H:%M:%S", $now);

      $resource = $resources[$i];

      /* get production */
      $current_production = $resource["production"];

      /* get new production */
      $new_production = characterResources($resource["character_name"]);

      $current_production += round($new_production * PRODUCTION_FREQUENCY);
      $database->updateResources($resource["character_name"], $current_production, $new_production, $now);
   }           
}

/**
 * characterResources - calculate resources a character gets every second
 * Returns new resources
 */
function characterResources($owner)
{
   global $session;
   $database = $session->database;
   $production = 0;

   $lands = $database->getLandFromOwner($owner);
   for($i = 0; $i < count($lands); $i++) {
      /* toxic * yield / 60 */
      $production += ($lands[$i]["toxic"] * $lands[$i]["yield"]) / 60;
   }

   return $production;
}
?>
