<?php
/**
 * resources_process.php
 *
 * Handle processing of resources for player
 *
 * Written by: matkar01
 */
include_once("include/session.php");

class ResourcesProcess
{
   /* Class constructor */
   function ResourcesProcess(){
      global $session;
      $type = $_POST['type'];

      if ($type) {
         $session->logger->LogInfo("here 0");
         $this->handleAction($type);
      }
      else {
         /**
         * Should not get here, which means user is viewing this page
         * by mistake and therefore is redirected.
         */
         $session->logger->LogInfo("here 1");
         //header("Location: ".$session->referrer);
      }
   }

   /**
    * handleAction - handle actions
    */
   function handleAction($type){
      global $session;
      $database = $session->database;

      if (!strcmp($type, "money")){
         $cash = 0;

         $character = $database->getCharacter($session->username);
         $row = $database->getResources($character["name"]);
         $cash = $row["cash"];
         $session->logger->LogInfo($cash);
         echo $cash;
      }
      else {
        $session->logger->LogInfo("here 2");
      }
   }
};

/* Initialize process */
$resources_process = new ResourcesProcess;

?>
