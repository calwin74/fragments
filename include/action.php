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
         if ( ($action["type"] == MOVE) || ($action["type"] == COLONIZE) ){
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
         case COLONIZE: $what = "colonize"; break;
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
         if($action["type"] == MOVE){
            /* move action */
            $database->moveCharacter($action["x"], $action["y"], $action["name"]);  
            $database->removeFromActionQueue($action["x"], $action["y"], $action["name"]);
         }
         else if($action["type"] == COLONIZE){
            /* colonize action */
            $database->setLandOwner($action["x"], $action["y"], $action["name"]);  
            $database->removeFromActionQueue($action["x"], $action["y"], $action["name"]);
         }
         else {
            /* not supported yet */
         }
      }
   }
};
?>
