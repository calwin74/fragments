<?php
/**
 * game_process.php
 *
 * The game_process class is meant to simplify the task of processing
 * user submitted forms, redirecting the user to the correct
 * pages if errors are found, or if form is successful, either
 * way.
 *
 * Written by: matkar01 the super hero
 */
include_once("include/session.php");
include_once("include/constants.php");
include_once("include/form.php");

class GameProcess
{
   /* Class constructor */
   function GameProcess(){
      global $session;

      /* User submitted move form */
      if(isset($_POST['subaction'])){
         $this->gamePlayerAction();
      }
      else if(isset($_POST['subbuild'])) {
         if (isset($_POST['buildaction'])) {
            $session->logger->LogInfo("gameBuildAction");
            $this->gameBuildAction();
         }
      }
      else {
         /**
          * Should not get here, which means user is viewing this page
          * by mistake and therefore is redirected.
          */
          header("Location: my_main.php");
      }
   }

   /**
    * gameBuildAction - Processes the build actions.
    */
   function gameBuildAction(){
      global $session;

      $type = $_POST['buildaction'];
      $x = $_POST['x'];
      $y = $_POST['y'];
      $town = $_POST['town'];
      $character = $_POST['character'];

      if($this->checkBuildResources($type, $character))
      {
         if (!strcmp($type, "upgrade")) {
            $level = $_POST['level'];
            $level++;

            $this->gameUpgradeBuilding($x, $y, $town, $character, $type, $level);
         }
         else if (!strcmp($type, "remove")) {
            $level = $_POST['level'];

            $this->gameRemoveBuilding($x, $y, $town, $character, $type, $level);

         }
         else {
            $this->gameCreateBuilding($x, $y, $town, $character, $type);
         }
      }
   }

   /**
    * gamePlayerAction - Processes the user actions.
    */
   function gamePlayerAction(){
      global $session;

      $action = $_POST['playeraction'];
      $x = $_POST['x'];
      $y = $_POST['y'];
      $character = $_POST['character'];

      if (!strcmp($action, "Move")){
         $session->logger->LogInfo("Move");
         $this->gameMove($x, $y, $character);
      }
      else if (!strcmp($action, "Colonize")){
         $curr_x = $_POST['curr_x'];
         $curr_y = $_POST['curr_y'];
         $session->logger->LogInfo("Colonize");

         $session->logger->LogInfo($curr_x);
         $session->logger->LogInfo($curr_y);

         $this->gameColonize($curr_x, $curr_y, $character);
      }
      else if (!strcmp($action, "Attack")){
         $session->logger->LogInfo("Attack");
         $this->gameAttack($x, $y, $character);
      }
      else if (!strcmp($action, "Defend")){
         $session->logger->LogInfo("Defend");
         $this->gameDefend($character);
      }
      else{
         /**
         * Should not get here, which means user is viewing this page
         * by mistake and therefore is redirected.
         */
        header("Location: ".$session->referrer);
      }
   }

   /**
    * invalidMove - Validate move and return 0 if valid, otherwise
    * non zero.
    */
   function invalidMove($x, $y) {
      global $session;
      global $form;
      $database = $session->database;

      //Check that the coordinates are valid
      if ($x < 0 || $x > X_MAX) {
         $field = "y";
         $form->setError($field, "Invalid x coordinate");
         return 1;
      }         
      if ($y < 0 || $y > Y_MAX) {
         $field = "y";
         $form->setError($field, "Invalid y coordinate");
         return 1;
      }

      //Check that the coordinate is free
      if (!$database->landIsEmpty($x, $y)) {
         $field = "y";
         $form->setError($field, "Coordinate already occupied");
         return 1;      
      }

      //Check that the terrain is valid
      if ($database->getLandType($x, $y) == SEA1) {
         $field = "y";
         $form->setError($field, "Coordinate is sea");
         return 1;          
      }

      return 0;   
   }

   /**
    * gameMove - Processes the user move action.
    */
   function gameMove($x, $y, $characterName){
      global $session;
      global $form;
      $database = $session->database;

      if ($this->invalidMove($x, $y)) {
         $_SESSION['value_array'] = $_POST;
         $_SESSION['error_array'] = $form->getErrorArray();
         header("Location: ".$session->referrer);
         return;
      }
      /*
      $distance = 1;
      $dueTime = strtotime("+".floor($distance)." hours ".floor(($distance-floor($distance))*60)." minutes");
      */

      $character = $database->getCharacter($characterName);

      /* note that this will only work up to 59 seconds */
      $distance = $this->getDistance($character["x"], $character["y"], $x, $y);
      if ($distance > 59) {
         $session->logger->LogError("gameMove - can't handle distances over 59 seconds");
      }
      $moveTime = "+".$distance." seconds";

      $dueTime = strtotime($moveTime);

      $dueTime = strftime("%y-%m-%d %H:%M:%S", $dueTime);

      $database->addToMoveQueue($x, $y, $characterName, $dueTime, MOVE);

      header("Location: ".$session->referrer);
   }

   /**
    * gameAttack - Processes the user attack action.
    */
   function gameAttack($x, $y, $character){
      global $session;
      $database = $session->database;

      //currently only move

      /* distance is always 10 seconds */
      $dueTime = strtotime("+10 seconds");

      $dueTime = strftime("%y-%m-%d %H:%M:%S", $dueTime);

      $database->addToMoveQueue($x, $y, $character, $dueTime, ATTACK);

      header("Location: ".$session->referrer);
   }

   /**
    * gameColonize - Processes the user colonize action.
    */
   function gameColonize($x, $y, $character){
      global $session;
      $database = $session->database;

      /* colonize time is always 10 seconds */
      $dueTime = strtotime("+10 seconds");

      $dueTime = strftime("%y-%m-%d %H:%M:%S", $dueTime);

      $database->addToMoveQueue($x, $y, $character, $dueTime, COLONIZE);
      
      header("Location: ".$session->referrer);
   }

   /**
    * gameDefend - Processes the user defend action.
    */
   function gameDefend($character){
      global $session;
      $database = $session->database;

      /* defend time is always 10 seconds */
      $dueTime = strtotime("+10 seconds");

      $dueTime = strftime("%y-%m-%d %H:%M:%S", $dueTime);

      $database->addToMoveQueue(0, 0, $character, $dueTime, DEFEND);

      header("Location: ".$session->referrer);
   }

   /**
    * gameCreateBuilding - Processes the user build action.
    */
   function gameCreateBuilding($x, $y, $town, $character, $type){
      global $session;
      $database = $session->database;
      $level = 1;

      /* build time is always 10 seconds */
      $dueTime = strtotime("+10 seconds");

      $dueTime = strftime("%y-%m-%d %H:%M:%S", $dueTime);

      $database->addToBuildQueue($x, $y, $town, $character, $dueTime, $type, B_CREATE, $level);
      $database->createBuilding($type, $x, $y, $town);

      header("Location: ".$session->referrer);
   }

   /**
    * gameUpgradeBuild - Processes the user upgrade build action.
    */
   function gameUpgradeBuilding($x, $y, $town, $character, $type, $level){
      global $session;
      $database = $session->database;

      /* upgrade time is always 10 seconds */
      $dueTime = strtotime("+10 seconds");

      $dueTime = strftime("%y-%m-%d %H:%M:%S", $dueTime);

      $database->addToBuildQueue($x, $y, $town, $character, $dueTime, $type, B_UPGRADE, $level);
      $database->updateBuildingLevel($x, $y, $town);

      header("Location: ".$session->referrer);
   }

   /**
    * gameRemoveBuild - Processes the user remove build action.
    */
   function gameRemoveBuilding($x, $y, $town, $character, $type, $level){
      global $session;
      $database = $session->database;

      /* remove time is always 10 seconds */
      $dueTime = strtotime("+10 seconds");

      $dueTime = strftime("%y-%m-%d %H:%M:%S", $dueTime);

      $database->addToBuildQueue($x, $y, $town, $character, $dueTime, $type, B_REMOVE, $level);
      $database->removeBuilding($x, $y, $town);

      header("Location: ".$session->referrer);
   }

   /**
    * getDistance - Calculate distance using Pythagoras.
    * Result is rounded down.
    */
   function getDistance($x1, $y1, $x2, $y2) {
      $xDiff = $x2-$x1;
      $yDiff = $y2-$y1;      
      $result = sqrt($xDiff*$xDiff + $yDiff*$yDiff);
      $result = floor($result);
      $result = $result * TIME_FACTOR;

      return $result;      
   }            

   /**
    * checkBuildResources - Check if enough resources are available
    * If so the cost is deducted from the resources.
    *
    */
   function checkBuildResources($type, $character) {
      global $session;
      $database = $session->database;
      $result = 1;

      $cost = $database->getBuildingCost($type);
      $session->logger->LogInfo($cost);

      $gulden = $database->getGulden($character);
      $session->logger->LogInfo($gulden);

      if ($cost < $gulden || $cost == $gulden) {
         $gulden -= $cost;
         $session->logger->LogInfo($gulden);
         $database->setGulden($character, $gulden, NULL);
      }
      else {
         /* no enough gulden */
         $result = 0;
      }

      return $result;
   }

};

/* Initialize process */
$game_process = new GameProcess;

?>
