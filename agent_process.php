<?php
/**
 * agent_process.php
 *
 * Handle processing of actions for agents
 *
 * Written by: matkar01
 */
include_once("include/session.php");
//include_once("include/resources.php");
include_once("include/utils.php");
include_once("include/action.php");

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
         /* handle all actions */                  
         //$this->handleActionQueue();

	 $action = new Action();
	 $action->processActions();	 

         /* Report timestamp back to agent */
         echo getNow(0);
      }
      else{
        echo "AgentProcess Error: type not supported";
      }
   }
};

/* Initialize process */
$agent_process = new AgentProcess;

?>
