<?php
/**
 * action.php
 *
 * Handle actions for clients
 *
 * Written by: matkar01
 */
include_once("session.php");
include_once("utils.php");
include_once("constants.php");
include_once("battle.php");

class Action
{
   private $my_actions;           //action container

   /* Class constructor */
   public function Action(){
      /* nothing yet */
   }

   public function readActions($character){
      global $session;
      $database = $session->database;

      $this->my_actions = array();
      $actions = $database->getActions($character);

      if ($actions){
         foreach ($actions as $action){
            $this->my_actions[] = $action;
         }
      }
   }

   public function getActions(){
      return $this->my_actions;
   }

   public function isAction(){
      foreach ($this->my_actions as $action){
         if ( ($action["type"] == MOVE) || ($action["type"] == EXPLORE) ){
            return 1;
         }
      }
      
      return 0;
   }

   public function getDiff($time){
      global $session;
      $database = $session->database;
 
      return $database->getTimeDiff($time, getNow(0));
   }

   public function typeToString($type){
      switch($type){
         case EXPLORE: $what = "explore"; break;
         case MOVE: $what = "move"; break;
      }

      return $what;
   }

   public function processActions(){
      global $session;
      $database = $session->database;

      $actions = $database->checkActionQueue();

      for ($i = 0; $i < count($actions); $i++) {
         $action = $actions[$i];
         $x = $action["x"];
         $y = $action["y"];
         $name = $action["name"];

         if($action["type"] == MOVE){
            /* move action */

            $database->removeFromActionQueue($x, $y, $name);

            $battle = new Battle($x, $y, $name);            

            if($battle->isBattle()){
               /* there will be blood ... */
               if($battle->executeBattle()){
                 /* attacker won, move into position */
                 $database->moveCharacter($x, $y, $name);
               }
               $battle->reportBattle();
            }
            else{
              $database->moveCharacter($x, $y, $name);
            }
         }
         else if($action["type"] == EXPLORE){
            /* explore action */
            $database->setLandOwner($x, $y, $name);

            /* two explorers transform into two civilians */
            $database->updateCivilians(2, $x, $y, getNow(0));

            $character = $database->getCharacterByName($name);
            $database->updateCharacterExplorers($character["explorers"] - 2, $name);

            $database->removeFromActionQueue($x, $y, $name);
         }
         else {
            /* not supported yet */
         }
      }
   }
};
?>
