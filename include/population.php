<?php
include_once("constants.php");
include_once("include/session.php");

/**
 * population.php
 * This module handle population
 */

class Population
{
   /* Class constructor */
   public function Population(){
      /* empty for now */
   }

   public function getPopulation($owner){
      global $session;
      $database = $session->database;

      $lands = $database->getLandFromOwner($owner);
      $population = 0;

      for($i = 0; $i < count($lands); $i++) {
         /* sum all complete people, for instance 4.3 + 3.9 = 7 people */
         $population += floor($lands[$i]["population"]);
      }

      return $population;
   }

   public function updateAllPopulation(){
      while ($this->updatePopulationChunk());
   }

   private function updatePopulationChunk(){
      global $session;
      $database = $session->database;

      $lands = $database->getPopulationUpdate();
      $i = 0;

      for ($i = 0; $i < count($lands); $i++){
         $land = $lands[$i];
         $newTime = strtotime($land["population_time"]) + GAME_TIME_UNIT;
         $newTime = strftime("%Y-%m-%d %H:%M:%S", $newTime);
         $population = $land["population"];
         $x = $land["x"];
         $y = $land["y"];
         $toxic = $land["toxic"];

         $newPopulation = $this->calculatePopulation($population, $toxic);

         if ($newPopulation > $population){
            $database->updatePopulation($newPopulation, $x, $y, $newTime); 
         }
      }
      
      return $i;
   }

   private function calculatePopulation($pop, $toxic){
      $tax = 0.25; /* set tax to 25% for now */
   
      /* need at least 2 people */
      if ($pop < 2) {
         /* no growth */
         return $pop;
      }

      $newPop = $pop + $pop * (1 - $tax) * ((POPULATION_MAX - $toxic)/POPULATION_MAX) * NATIVITY;
      
      if ($newPop > POPULATION_MAX){
         $newPop = POPULATION_MAX;
      }

      return $newPop;
   }
}
