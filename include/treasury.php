<?php
include_once("constants.php");
include_once("include/session.php");
include_once("population.php");

/**
 * treasury.php
 * This module handle treasury attributes like gold and tax
 */

class Treasury
{
   /* Class constructor */
   public function Treasury(){
      /* empty for now */
   }

   public function getGold($owner){
      global $session;
      $database = $session->database;

      $treasury = $database->getTreasuryFromOwner($owner);

      return floor($treasury["gold"]);
   }

   public function updateAllTreasury(){
      while ($this->updateTreasuryChunk());
   }

   private function updateTreasuryChunk(){
      global $session;
      $database = $session->database;

      $treasuries = $database->getGoldUpdate();
      $i = 0;

      for ($i = 0; $i < count($treasuries); $i++){
         $treasury = $treasuries[$i];
         $newTime = strtotime($treasury["gold_time"]) + GAME_TIME_UNIT;
         $newTime = strftime("%Y-%m-%d %H:%M:%S", $newTime);
         $owner = $treasury["character_name"];
         $gold = $treasury["gold"];
         $tax = $treasury["tax"];

         $population = new Population();
         $pop = $population->getPopulation($owner);

         $newGold = $this->calculateGold($gold, $tax, $pop);

         $database->updateGold($newGold, $owner, $newTime); 
      }
      
      return $i;
   }

   private function calculateGold($gold, $tax, $pop){
      $newGold = $gold + $pop * $tax;
      
      return $newGold;
   }
}
