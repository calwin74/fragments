<?php
include_once("session.php");
include_once("utils.php");
include_once("constants.php");
include_once("garrison.php");

/**
 * units.php
 * This module handle units and building of units
 */

class Units
{
   private $my_builds;         //unit build container

   /* Class constructor */
   public function Units(){
      /* empty for now */
   }

   public function readUnitBuilds($character, $x, $y){
      global $session;
      $database = $session->database;

      $this->my_builds = array();
      $builds = $database->getUnitBuilds($character, $x, $y);

      if ($builds){
         foreach ($builds as $build){
            $this->my_builds[] = $build;
         }
      }
   }

   public function getBuilds(){
      return $this->my_builds;
   }

   public function processBuilds(){
      global $session;
      $database = $session->database;

      $units = $database->checkUnitQueue();

      for ($i = 0; $i < count($units); $i++) {
         $unit = $units[$i];
         $type = $unit["type"];

         if (strcmp($type, "soldier") == 0){
            $garrison = new Garrison($unit["name"]);
            $soldiers = $garrison->getSoldiers();
            $garrison->setSoldiers($soldiers + 1);
         }
         if (strcmp($type, "explorer") == 0){
            $x = $unit["x"];
            $y = $unit["y"];

            $land = $database->getLand($x, $y);
            $explorers = $land["explorers"];

            $database->updateExplorers($explorers + 1, $x, $y);
         }            
         else{
            //not supported yet
         }
         $database->removeFromUnitQueue($unit["x"], $unit["y"], $unit["name"]);
      }
   }

   public function getAvailableUnits($buildings){
      global $session;
      $database = $session->database;
      $newUnits = array();      

      if (count($buildings)){
         $unitTypes = $database->getUnitTypes();      
         
         foreach($unitTypes as $unitType){
            $needed = $unitType["building"];
           
            foreach ($buildings as $building){
               if (strcmp($needed, $building["type"]) == 0){
                  $newUnits[] = $unitType;             
               }
            }
         }
      }
      else{
         //just return, nothing can be built.
      }

      return $newUnits;
   }

   /* this should be removed from here and from builds.php and added to generic code ... */
   public function getDiff($time){
      global $session;
      $database = $session->database;
 
      return $database->getTimeDiff($time, getNow(0));
   }
}
