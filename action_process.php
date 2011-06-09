<?php
/**
 * action_process.php
 *
 * Handle processing of actions
 *
 * Written by: matkar01
 */
include_once("include/session.php");
include_once("include/constants.php");
include_once("include/form.php");
include_once("include/lands.php");
include_once("include/utils.php");

class ActionProcess
{
   /* Class constructor */
   function ActionProcess(){
      global $session;

      /* User submitted action form */
      if(isset($_POST['subaction'])){
         $this->handleAction();
      }
      else {
         /**
          * Should not get here, which means user is viewing this page
          * by mistake and therefore is redirected.
          */
          header("Location: ".$session->referrer);
       }
   }

   /**
    * handleAction - handle actions
    */
   function handleAction(){
      global $session;
      $database = $session->database;

      $action = $_POST['action'];
      $key = $_POST['key'];

      if (!strcmp($action, "army")){
         $character_civilians = $_POST['character'];
         $land_civilians = $_POST['land'];
         $civilians = $_POST['civilians'];
         $name = $_POST['name'];
         
         if ($character_civilians == $civilians){
            /* no changes */
            header("Location: ".$session->referrer);
         }
         else{
            /* update land */
            $new_land_civilians = $land_civilians - ($civilians - $character_civilians);

            /* check land civilians */
            $land = $database->getLand(getXFromKey($key), getYFromKey($key));
            if ($row["civilians"] + $new_land_civilians >= CIVILIANS_MAX){
               $new_land_civilians = CIVILIANS_MAX;
            }

            $database->updateCivilians($new_land_civilians, getXFromKey($key), getYFromKey($key), getNow(0));

            /* update character */
            $database->updateCharacter($civilians, $name);

            header("Location: ".$session->referrer);
         }
      }
      else if (!strcmp($action, "build")){
         $type = $_POST['type'];
         $name = $_POST['name'];

         $cost = $database->getBuildingCost($type);
         $treasury = $database->getTreasuryFromOwner($name);
         $gold = $treasury["gold"];

         if ($gold >= $cost) {   
            $database->updateGold($gold - $cost, $name, NULL);

            /* create in database */
            $database->createBuilding($type, getXFromKey($key), getYFromKey($key));

            /* add to build queue */
            $database->addToBuildQueue(getXFromKey($key), getYFromKey($key), $name, getNow(BUILD_TIME), $type, B_CREATE);
         }

         header("Location: ".$session->referrer);
      }
      else if (!strcmp($action, "mark")){
         header("Location: ".$session->referrer."?mark_key=".$key);
      }
      else{
         $x = getXfromKey($key);
         $y = getYfromKey($key);

         $character = $database->getCharacter($session->username);
         //$treasury = $database->getTreasuryFromOwner($character["name"]);
         //$gold = $treasury["gold"];

         if (!strcmp($action, "move")){
            $database->addToActionQueue($x, $y, $character["name"], getNow(MOVE_TIME), MOVE, 0);
/*
            if ($gold >= MOVE_COST) {   
               $database->updateGold($gold - MOVE_COST, $character["name"], NULL);
            }
*/
            header("Location: ".$session->referrer);
         }
         else if (!strcmp($action, "colonize")){
            $database->addToActionQueue($x, $y, $character["name"], getNow(COLONIZE_TIME), COLONIZE, 0);
            header("Location: ".$session->referrer);
         }
         else if (!strcmp($action, "clean")){
            /* check toxic level */
            $toxic = $database->getLandToxic($x, $y);
            /* cost is level times CLEAN_COST */
            $clean_cost = $toxic * CLEAN_COST;

            if ($gold >= $clean_cost) {
               if ($toxic < TOXIC_CLEAN) {
                  /* time is level times CLEAN TIME */
                  if ($toxic == 0){
                     /* takes 5 seconds to reach level 1 */
                     $dueTime = 5;
                  }
                  else{
                     $dueTime = CLEAN_TIME*$toxic;               
                  }
                  $database->addToActionQueue($x, $y, $character["name"], getNow($dueTime), 1, $toxic);
                  $database->updateGold($gold - $clean_cost, $character["name"], NULL);
               }
            }
            header("Location: ".$session->referrer);
         }
         else{
            /**
            * Should not get here, which means user is viewing this page
            * by mistake and therefore is redirected.
            */
            header("Location: ".$session->referrer);
         }
      }
   }   
};

/* Initialize process */
$action_process = new ActionProcess;
?>