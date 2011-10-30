<?php
include_once("session.php");

/**
 * battle.php
 * This module handle the a battle.
 */
include_once("garrison.php");
include_once("lands.php");
include_once("land_descr.php");

class Battle
{
   private $my_x;              //character x coordinate
   private $my_y;              //character y coordinate
   private $my_attacker;       //attacker name
   private $my_defender;       //defender name
   private $my_land;           //battle land
   private $my_escape_x;          //defender escape x coordinate
   private $my_escape_y;          //defender escape y coordinate  

   private $my_battle_result;  //result of battle, 1=attacker win, 0=defender win
   private $my_attacker_loss;  //attacker soldier losses
   private $my_defender_loss;  //defender soldier losses
   private $my_garrison_loss;  //garrison soldier losses

   private $my_n_attacker;     //attacker force
   private $my_n_defender;     //defender force
   private $my_n_garrison;       //garrison force
   
   /* Class constructor */
   public function Battle($x, $y, $attacker){
     global $session;
     $database = $session->database;

     $this->my_attacker = $database->getCharacterByName($attacker);
     $this->my_defender = $database->getCharacterByXY($x, $y);
     $this->my_x = $x;
     $this->my_y = $y;     
     $this->my_land = $database->getLand($x, $y);

     /* default */
     $this->my_battle_result = 0;
     $this->my_attacker_loss = 0;
     $this->my_defender_loss = 0;
     $this->my_garrison_loss = 0;     
     $this->my_escape_x = 0;
     $this->my_escape_y = 0;
     $this->my_n_attacker = 0;
     $this->my_n_defender = 0;
     $this->my_n_garrison = 0;
   }

   public function isBattle(){
     global $session;

     if ($this->my_defender){
       return 1;
     }

     if ($this->my_land["owner"] && strcmp($this->my_land["owner"], $this->my_attacker["name"])){
       return 1;
     }

     return 0;
   }

   public function executeBattle(){
     global $session;
     $database = $session->database;

     $this->my_n_attacker = $this->my_attacker["soldiers"];
     $garrison = NULL;

     if ($this->my_land["owner"]){
       $garrison = new Garrison($this->my_land["owner"]);
       $this->my_n_garrison = $garrison->getSoldiers();     
     }

     if($this->my_defender){
       $this->my_n_defender = $this->my_defender["soldiers"];
     }

     /*
     $s = "n_attacker:".$this->my_n_attacker;
     $session->logger->LogInfo($s);

     $s = "n_garrison:".$this->my_n_garrison;
     $session->logger->LogInfo($s);

     $s = "n_defender:".$this->my_n_defender;
     $session->logger->LogInfo($s);
     */

     if($this->my_n_attacker > ($this->my_n_defender + $this->my_n_garrison)){
       /* attacker wins */
       $this->my_battle_result = 1;

       /* attacker losses */
       $rest = $this->my_n_attacker - ($this->my_n_defender + $this->my_n_garrison);
       $database->updateCharacterSoldiers($rest, $this->my_attacker["name"]);
       $this->my_attacker_loss = $this->my_n_attacker - $rest;

       /* defender losses */
       if($this->my_defender){
         $database->updateCharacterSoldiers(0, $this->my_defender["name"]);
         $this->my_defender_loss = $this->my_n_defender;
         $database->updateCharacterExplorers(0, $this->my_defender["name"]);
       }
       if($this->my_land["owner"]){
         $garrison->setSoldiers(0);
         $this->my_garrison_loss = $this->my_n_garrison;

         $database->setLandOwner($this->my_x, $this->my_y, NULL);
         $database->updateCivilians2(0, $this->my_x, $this->my_y);
         $database->updateExplorers(0, $this->my_x, $this->my_y);
       }
       /* defender moves to neighbourhood */
       $lands = new Lands($this->my_x, $this->my_y, $this->my_defender["name"], 0, 2, 2, null);

       $nhood = '';
       $lands->getSurrounding($this->my_x, $this->my_y, $nhood);

       $escape = NULL;

       /* 
          1. return to owned land
          2. return to free and empty land
       */
       foreach($nhood as $land){
         if($land->getOwner() == I_OWN){
           $escape = $land;
           break;
         }
       }

       if(!$escape){
         foreach($nhood as $land){
           if( ($land->getOwner() == NOT_OWNED) && !$land->getCharacter()){
             $escape = $land;
             break;
           }
         }
       }

       if($escape){
         $this->my_escape_x = $escape->getX();
         $this->my_escape_y = $escape->getY();
       }
       else{
         $session->logger->LogInfo("no place to escape, moving to (0|0)");
       }

       $database->addToActionQueue($this->my_escape_x, $this->my_escape_y, $this->my_defender["name"], getNow(MOVE_TIME), MOVE, 0);
     }
     else{
       /* defender wins */       
       $this->my_battle_result = 0;

       /* attacker losses */
       $database->updateCharacterSoldiers(0, $this->my_attacker["name"]);
       $this->my_attacker_loss = $this->my_n_attacker;

       $database->updateCharacterExplorers(0, $this->my_attacker["name"]);

       /* defender losses */
       if($this->my_defender && !$this->my_land["owner"]){
         $rest = $this->my_n_defender - $this->my_n_attacker;
         $database->updateCharacterSoldiers($rest, $this->my_defender["name"]);
         $this->my_defender_loss = $this->my_n_defender - $rest;
       }
       else if(!$this->my_defender && $this->my_land["owner"]){
         $rest = $this->my_n_garrison - $this->my_n_attacker;
         $garrison->setSoldiers($rest);
         $this->my_garrison_loss = $this->my_n_garrison - $rest;
       }
       else{
         $rest = $this->my_n_defender - $this->my_n_attacker;
         if ($rest < 0){
           $database->updateCharacterSoldiers(0, $this->my_defender["name"]);
           $rest = $this->my_n_garrison - ($this->my_n_attacker - $this->my_n_defender);
           $garrison->setSoldiers($rest);
           $this->my_defender_loss = $this->my_n_defender;
           $this->my_garrison_loss = $this->my_n_garrison - $rest;
         }
         else{
           $database->updateCharacterSoldiers($rest, $this->my_defender["name"]);
           $this->my_defender_loss = $this->my_n_defender - $rest;
         }
       }
       /* attacker stays in the pre-attack position, no movement needed. */
     }

     return $this->my_battle_result;
   }

   public function reportBattle(){
     global $session;

     $session->logger->LogInfo("*** Start battle report ***");

     $s = "Battle in (".$this->my_x."|".$this->my_y.")";
     $session->logger->LogInfo($s);

     $s = "Attacker soldiers:".$this->my_n_attacker;
     $session->logger->LogInfo($s);

     $s = "Defender soldiers:".$this->my_n_defender;
     $session->logger->LogInfo($s);

     $s = "Garrison soldiers:".$this->my_n_garrison;
     $session->logger->LogInfo($s);

     if ($this->my_battle_result){
       $s = "- Attacker wins -";
     }
     else{
       $s = "- Defender wins -";
     }
     $session->logger->LogInfo($s);

     $s = "- Attacker losses -";
     $session->logger->LogInfo($s);
     $s = "Soldiers:".$this->my_attacker_loss;
     $session->logger->LogInfo($s);

     $s = "- Defender losses -";
     $session->logger->LogInfo($s);

     if($this->my_defender){
       $s = "Soldiers:".$this->my_defender_loss;
       $session->logger->LogInfo($s);
     }
    
     if($this->my_land["owner"]){
       $s = "Garrison soldiers:".$this->my_garrison_loss;
       $session->logger->LogInfo($s);   
     }
  
     if ($this->my_battle_result){
       if($this->my_land["owner"]){
         $s = "Defender loses land (".$this->my_x."|".$this->my_y.")";
         $session->logger->LogInfo($s);
       }

       if($this->my_defender){
         $s = "Defender moves back to (".$this->my_escape_x."|".$this->my_escape_y.")";
         $session->logger->LogInfo($s);
       }
     }
   }
}
?>