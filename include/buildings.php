<?php
include_once("session.php");
include_once("utils.php");
include_once("constants.php");

/**
 * buildings.php
 * This module handle buildings and builds
 */

class Buildings
{
   private $my_builds;           //build container

   /* Class constructor */
   public function Buildings(){
      /* empty for now */
   }

   public function readBuilds($character, $x, $y){
      global $session;
      $database = $session->database;

      $this->my_builds = array();
      $builds = $database->getBuilds($character, $x, $y);

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

      $builds = $database->checkBuildQueue();

      for ($i = 0; $i < count($builds); $i++) {
         $build = $builds[$i];
         
         if ($build["action"] == B_CREATE) {
            $database->createBuildingDone($build["x"], $build["y"], $build["type"]);
            $database->removeFromBuildQueue($build["x"], $build["y"], $build["name"]);
         }
         else {
            /* not supported yet */
         }
      }
   }

   public function getBuildingsDone($x, $y){
      global $session;
      $database = $session->database;

      $buildings = $database->getBuildingsDone($x, $y);

      return $buildings;
   }

   public function getBuildings($x, $y){
      global $session;
      $database = $session->database;

      $buildings = $database->getBuildings($x, $y);

      return $buildings;
   }

   public function getNewBuildings($x, $y){
      global $session;
      $database = $session->database;
      $new = array();   
      $current_buildings = $this->getBuildings($x, $y);
      $building_types = $database->getBuildingTypes();

      if(count($current_buildings) < MAX_BUILDINGS){
         foreach ($building_types as $building){
            $type = $building["type"];
            $exist = 0;
   
            if ($current_buildings){
               foreach($current_buildings as $current){
                  if(!strcmp($current["type"], $type)){
                     $exist = 1;
                  }
               }
            }

            /* existing buildings can't be built again */         
            if(!$exist){
               $new[] = $building;
            }
         }
      }

      return $new;
   }

   public function getDiff($time){
      global $session;
      $database = $session->database;
 
      return $database->getTimeDiff($time, getNow(0));
   }
}
