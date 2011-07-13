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
      $this->my_gold = floor($treasury["gold"]);
      $this->my_tax = $treasury["tax"];
      $this->my_owner = $treasury["character_name"];
   }

   public function getGold(){
      return $this->my_gold;
   }

   public function getTax(){
      return $this->my_tax;
   }

   public function setTax($tax){
      $this->my_tax = $tax;
   }

   public function getIncome(){
      global $session;

      $population = new Population();
      $pop = $population->getPopulation($this->my_owner);
      $income = $this->calculateIncome($pop, $this->my_tax);

      return $income;      
   }

   public function getCost(){
      global $session;
      $database = $session->database;

      $garrison = new Garrison($this->my_owner);
      $character = $database->getCharacterByName($this->my_owner);
      $soldiers = $garrison->getSoldiers() + $character["soldiers"];

      $cost = $this->calculateCost($soldiers);

      return $cost;
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

         $garrison = new Garrison($owner);
         $character = $database->getCharacterByName($owner);
         $soldiers = $garrison->getSoldiers() + $character["soldiers"];

         $income = $this->calculateIncome($pop, $tax);
         $cost = $this->calculateCost($soldiers);
         $newGold = $this->calculateGold($gold, $income, $cost);

         $database->updateGold($newGold, $owner, $newTime); 
      }
      
      return $i;
   }

   private function calculateGold($gold, $income, $cost){
      $newGold = $gold + income - cost;
      
      return $newGold;
   }

   private function calculateIncome($pop, $tax){
      $income = $pop * $tax/100;
   
      return $income;
   }

   private function calculateCost($soldiers){
      $cost = $soldiers * 1;
      
      return $cost;
   }
}
