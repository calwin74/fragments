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
            
            $owner = $population["owner"];
            $treasury = new Treasury($population["owner"]);
            $character = $database->getCharacterByName($owner);
            $garrison = new Garrison($owner);

            $explorers = $population["explorers"] + $character["explorers"];
            $soldiers = $garrison->getSoldiers() + $character["soldiers"];
            $workers = 0;
            
            $newCivilians = $this->calculateCivilians($civilians, $explorers, $soldiers, $workers, $treasury->getTax(), $intervals, $owner);

            $newTime = strftime("%Y-%m-%d %H:%M:%S", $now);

            $database->updateCivilians($newCivilians, $owner, $newTime);

            if(!strcmp($this->my_owner, $population["owner"])){
               /* refreash civilian count */
               $this->my_civilians = $newCivilians;
            }
         }
      }
   }

   private function calculateCivilians($civilians, $explorers, $soldiers, $workers, $tax, $intervals, $owner){
      global $session;
      $database = $session->database;      

      $delta = 0;
      $toxicSum = 0;

      $lands = $database->getLandFromOwner($owner);

      if ($lands){
         foreach($lands as $land){
            $toxicSum += $land["toxic"];
         }
         $toxicAvg = $toxicSum/count($lands);

         $delta = (1 - $tax/100) * ((CIVILIANS_MAX - $toxicAvg)/CIVILIANS_MAX) * NATIVITY * $intervals;

         $maxValue = (CIVILIANS_MAX * count($lands) - $toxicSum) - $explorers - $soldiers - $workers;
     
         if($delta + $civilians <= $maxValue){
            $newCivilians = $delta + $civilians;            
         }
         else{
            $newCivilians = $civilians;
         }

         $session->logger->LogInfo("--- population growth ---");

         $s = "toxicSum: ".$toxicSum;
         $session->logger->LogInfo($s);

         $s = "toxicAvg: ".$toxicAvg;
         $session->logger->LogInfo($s);

         $s = "maxValue: ".$maxValue;
         $session->logger->LogInfo($s);

         $s = "delta: ".$delta;
         $session->logger->LogInfo($s);

         $s = "explorers: ".$explorers;
         $session->logger->LogInfo($s);

         $s = "soldiers: ".$soldiers;
         $session->logger->LogInfo($s);

         $s = "intervals: ".$intervals;
         $session->logger->LogInfo($s);            

         $s = "newCivilians: ".$newCivilians;
         $session->logger->LogInfo($s);
      }
      else{
         $newCivilians = $civilians;
      }

      return $newCivilians;
   }
}
