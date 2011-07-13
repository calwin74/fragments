<?php
include_once("session.php");

/**
 * garrison.php
 * This module handle the player garrison
 */

class Garrison
{
   private $my_soldiers;         //number of soldiers
   private $my_name;             //character name

   /* Class constructor */
   public function Garrison($characterName){
     global $session;

     $database = $session->database;

     $garrison = $database->getGarrison($characterName);     

     $this->my_soldiers = $garrison["soldiers"];
     $this->my_name = $garrison["name"];
   }

   public function getSoldiers(){
     return $this->my_soldiers;
   }

   public function setSoldiers($soldiers){
     global $session;

     $database = $session->database;
     $database->updateGarrison($this->my_name, $soldiers);
   }
}
?>