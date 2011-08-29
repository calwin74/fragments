<?php
include_once("constants.php");
include_once("session.php");
include_once("treasury.php");

/**
 * populaation.php
 * This module handle population
 */

class Population
{
   private $my_civilians;
   private $my_explorers;
   private $my_owner;

   /* Class constructor */
   public function Population($owner){
      $population = $this->getPopulation($owner);

      $this->my_owner = $population["owner"];
      $this->my_civilians = $population["civilians"];
      $this->my_explorers = $population["explorers"];
   }

   public function getPopulation($owner){
      global $session;
      $database = $session->database;

      $population = $database->getPopulation($owner);

      return $population;
   }
   
   public function getCivilians(){
      return floor($this->my_civilians);
   }

   public function getExplorers(){
      return $this->my_explorers;
   }

   public function updateAllCivilians(){
      global $session;
      $database = $session->database;

      $populations = $database->getCiviliansUpdate();

      if ($populations){
         foreach($populations as $population){
            $last = strtotime($population["civilians_time"]);
            $now = strtotime("+0 seconds");
            $intervals = floor(($now - $last)/GAME_TIME_UNIT);

            $civilians = $population["civilians"];
            $explorers = $population["explorers"];
            $owner = $population["owner"];
            $treasury = new Treasury($population["owner"]);

            if($civilians < CIVILIANS_MAX){
               $newCivilians = $this->calculateCivilians($civilians, $treasury->getTax(), $intervals, $owner);
            }
            else{
               $newCivilians = $civilians;
            }

            $newTime = strftime("%Y-%m-%d %H:%M:%S", $now);

            $database->updateCivilians($newCivilians, $owner, $newTime);

            if(!strcmp($this->my_owner, $population["owner"])){
               /* refreash civilian count */
               $this->my_civilians = $newCivilians;
            }
         }
      }
   }

   private function calculateCivilians($civilians, $tax, $intervals, $owner){
      global $session;
      $database = $session->database;      

      $newValue = 0;

      $lands = $database->getLandFromOwner($owner);

      if ($lands){
         foreach($lands as $land){
            $toxic = $land["toxic"];
            $newValue += (1 - $tax/100) * ((CIVILIANS_MAX - $toxic)/CIVILIANS_MAX) * NATIVITY;
         }
      }

      $newCivilians = $civilians + $newValue * $intervals;

      return $newCivilians;
   }
}
