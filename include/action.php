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

class Action
{
   /* Class constructor */
   public function Action(){
      /* empty for now */
   }

   public function getActions($character){
      global $session;
      $database = $session->database;

      $actions = $database->getActions($character);
      
      return $actions;
   }

   public function getDiff($time){
      global $session;
      $database = $session->database;
 
      return $database->getTimeDiff($time, getNow(0));
   }

   public function typeToString($type){
      switch($type){
         case 1: $what = "clean"; break;
         case 2: $what = "move"; break;
      }

      return $what;
   }

   public function processActions(){
      global $session;
      $database = $session->database;

      $actions = $database->checkActionQueue();

      for ($i = 0; $i < count($actions); $i++) {
         $action = $actions[$i];
         if ($action["type"] == 1){
            /* clean action */
            $toxic = $action["add_info"] + 1;
            $database->setLandToxic($action["x"], $action["y"], $toxic);
            $database->removeFromActionQueue($action["x"], $action["y"], $action["name"]);
         }
         else if($action["type"] == 2){
            /* move action */
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
