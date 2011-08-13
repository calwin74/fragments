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
   /* Class constructor */
   public function Population(){
      /* empty for now */
   }

   public function getPopulation($owner, $type){
      global $session;
      $database = $session->database;

      $lands = $database->getLandFromOwner($owner);
      $population = 0;

      for($i = 0; $i < count($lands); $i++) {
         /* sum all complete people, for instance 4.3 + 3.9 = 7 people */
         /* $type is type of population to sum, e.g. "soldiers" */
         $population += floor($lands[$i][$type]);
      }

      return $population;
   }

   public function updateAllCivilians2(){
      while ($this->updateCiviliansChunk());
   }

   private function updateCiviliansChunk(){
      global $session;
      $database = $session->database;

      $lands = $database->getCiviliansUpdate();
      $i = 0;

      for ($i = 0; $i < count($lands); $i++){
         $land = $lands[$i];
         $newTime = strtotime($land["civilians_time"]) + GAME_TIME_UNIT;
         $newTime = strftime("%Y-%m-%d %H:%M:%S", $newTime);
         $civilians = $land["civilians"];
         $x = $land["x"];
         $y = $land["y"];
         $toxic = $land["toxic"];         
         $treasury = new Treasury($land["owner"]);

         $newCivilians = $this->calculateCivilians($civilians, $toxic, $treasury->getTax());

         $database->updateCivilians($newCivilians, $x, $y, $newTime); 
      }
      
      return $i;
   }

   public function updateAllCivilians(){
      global $session;
      $database = $session->database;

      $lands = $database->getCiviliansUpdate();

      if ($lands){
         foreach($lands as $land){
            $last = strtotime($land["civilians_time"]);
            $now = strtotime("+0 seconds");
            $intervals = floor(($now - $last)/GAME_TIME_UNIT);

            $civilians = $land["civilians"];
            $x = $land["x"];
            $y = $land["y"];
            $toxic = $land["toxic"];         
            $treasury = new Treasury($land["owner"]);
            
            $newCivilians = $this->calculateCivilians($civilians, $toxic, $treasury->getTax(), $intervals);
            $newTime = strftime("%Y-%m-%d %H:%M:%S", $now);

            $database->updateCivilians($newCivilians, $x, $y, $newTime);
         }
      }
   }

   private function calculateCivilians($pop, $toxic, $tax, $intervals){
      if ( ($pop < 0) || ($pop >= CIVILIANS_MAX) ) {
         /* no growth */
         return $pop;
      }
   
      if($pop == 0){
         $newValue = NATIVITY;
      }
      else{
         $newValue = (1 - $tax/100) * ((CIVILIANS_MAX - $toxic)/CIVILIANS_MAX) * NATIVITY;
      }

      $newPop = $pop + $newValue * $intervals;

      if ($newPop > CIVILIANS_MAX){
         $newPop = CIVILIANS_MAX;
      }

      return $newPop;
   }
}
