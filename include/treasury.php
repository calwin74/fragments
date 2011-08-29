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
   private $my_gold;
   private $my_tax;

   /* Class constructor */
   public function Treasury($owner){
      global $session;
      $database = $session->database;

      $treasury = $database->getTreasuryFromOwner($owner);
      $this->my_gold = $treasury["gold"];
      $this->my_tax = $treasury["tax"];
      $this->my_owner = $treasury["character_name"];
   }

   public function getGold(){
      return floor($this->my_gold);
   }

   public function getTax(){
      return $this->my_tax;
   }

   public function setTax($tax){
      $this->my_tax = $tax;
   }

   public function getIncome(){
      $income = $this->calculateIncome($this->my_owner, $this->my_tax);

      return $income;      
   }

   public function getCost(){
      $cost = $this->calculateCost($this->my_owner);

      return $cost;
   }

   public function updateAllTreasury(){
      global $session;
      $database = $session->database;

      $treasuries = $database->getGoldUpdate();

      if ($treasuries){
         foreach($treasuries as $treasury){
            $last = strtotime($treasury["gold_time"]);
            $now = strtotime("+0 seconds");
            $intervals = floor(($now - $last)/GAME_TIME_UNIT);

            $owner = $treasury["character_name"];
            $gold = $treasury["gold"];
            $tax = $treasury["tax"];

            $income = $this->calculateIncome($owner, $tax) * $intervals;
            $cost = $this->calculateCost($owner) * $intervals;
            $newGold = $this->calculateGold($gold, $income, $cost);

            $newTime = strftime("%Y-%m-%d %H:%M:%S", $now);
            $database->updateGold($newGold, $owner, $newTime);

            if(!strcmp($this->my_owner, $owner)){
               /* refresh gold count */
               $this->my_gold = $newGold;
            }
         }
      }
   }

   private function calculateGold($gold, $income, $cost){
      $newGold = $gold + $income - $cost;
      
      return $newGold;
   }

   private function calculateIncome($owner, $tax){
      $population = new Population($owner);
      $pop = $population->getCivilians();

      $income = $pop * $tax/100;
   
      return $income;
   }

   private function calculateCost($owner){
      global $session;
      $database = $session->database;

      $cost = 0;

      /* soldiers */
      $garrison = new Garrison($owner);
      $character = $database->getCharacterByName($owner);
      $soldiers = $garrison->getSoldiers() + $character["soldiers"];

      /* explorers */
      $population = new Population($owner);
      $explorers = $population->getExplorers() + $character["explorers"];

      if($soldiers > 0){
         $type = $database->getUnitType("soldier");
         $upkeep = $type["upkeep"];
         $cost += $soldiers * $upkeep;
      }
      
      if($explorers > 0){
         $type = $database->getUnitType("explorer");
         $upkeep = $type["upkeep"];
         $cost += $explorers * $upkeep;
      }

      return $cost;
   }
}
