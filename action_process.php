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

      $session->logger->LogInfo("ActionProcess");

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
      global $lands;
      $database = $session->database;

      $action = $_POST['action'];
      $key = $_POST['key'];
      $x = getXfromKey($key);
      $y = getYfromKey($key);

      if (!strcmp($action, "move")){
         $character = $database->getCharacter($session->username);

         /* check production */
         $resources = $database->getResources($character["name"]);
         $production = $resources["production"];
         if ($production >= MOVE_COST) {
            $database->addToActionQueue($x, $y, $character["name"], getNow(MOVE_TIME), 2, 0);
            $database->updateResources($character["name"], $production - MOVE_COST, NULL, NULL);
         }
         header("Location: ".$session->referrer);
      }
      else if (!strcmp($action, "clean")){
         /* check production and toxic level */
         $character = $database->getCharacter($session->username);
         $resources = $database->getResources($character["name"]);
         $production = $resources["production"];
         $toxic = $database->getLandToxic($x, $y);
         /* cost is level times CLEAN_COST */
         $clean_cost = $toxic * CLEAN_COST;

         if ($production >= $clean_cost) {
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
               $database->updateResources($character["name"], $production - $clean_cost, NULL, NULL);
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
};

/* Initialize process */
$action_process = new ActionProcess;
?>