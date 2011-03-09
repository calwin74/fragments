<?php
/**
 * agent_process.php
 *
 * Handle processing of actions for agents
 *
 * Written by: matkar01
 */
include_once("include/session.php");
include_once("include/resources.php");
include_once("include/utils.php");

class AgentProcess
{
   /* Class constructor */
   function AgentProcess(){
      global $session;

      $type = $_POST['type'];

      /* User submitted action form */
      if(isset($_POST['type'])){
         $this->handleAction($type);
      }
      else {
         echo "AgentProcess Error: Failed to get type";
      }
   }

   /**
    * handleAction - handle actions
    */
   function handleAction($type){
      global $session;
      $database = $session->database;

      if (!strcmp($type, "resources")){
         /* calculate all clients productions */
         calculateResources();         
         
         /* Report timestamp back to agent */
         echo getNow(0);
      }
      else if (!strcmp($type, "actions")){
         /* handle all clients actions */                  
         $this->handleClientActions();

         /* Report timestamp back to agent */
         echo getNow(0);
      }
      else{
        echo "AgentProcess Error: type not supported";
      }
   }

   /**
    * handleClientActions - handle client actions
    * Handle actions that are due.
    */
   function handleClientActions(){
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

/* Initialize process */
$agent_process = new AgentProcess;

?>
