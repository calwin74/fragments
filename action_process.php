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
include_once("include/land_utils.php");

class ActionProcess
{
   /* Class constructor */
   function ActionProcess(){
      global $session;

      /* User submitted action form */
      if(isset($_POST['subaction'])){
         $this->handleAction();
      }
      else if (isset($_POST['subadmin'])){
         $this->handleAdmin();
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
    * handleAdmin - handle admin actions
    */
   function handleAdmin(){
      global $session;
      $database = $session->database;

      $action = $_POST['action'];
      $key = $_POST['key'];

      if (!strcmp($action, "terrain")){
         $terrain = $_POST['terrain'];
         $radius = $_POST['radius'];
         $x = getXFromKey($key);
         $y = getYFromKey($key);

         if ($radius == 0) {
            /* update just this tile */         
            $database->updateLandType($terrain, $x, $y);
         }
         else if ($radius == 1) {
            /* identify terrain base */
            if ($terrain >= DIRT1 && $terrain <= DIRT5) {
               $terrain = DIRT1;
            }
            else if ($terrain >= DIRTVEG1 && $terrain <= DIRTVEG5) {
               $terrain = DIRTVEG1;
            }
            else if ($terrain >= URBAN1 && $terrain <= URBAN5) {
               $terrain = URBAN1;
            }
            else if ($terrain >= VEG1 && $terrain <= VEG5) {
               $terrain = VEG1;
            }

            /* update marked land */ 
            if ($terrain != SEA) {
               $database->updateLandType(rand($terrain, $terrain + 4), $x, $y);
            }
            else {
               $database->updateLandType($terrain, $x, $y);
            }

            /* find neighbourhood and update using random mix */
            $lands = new Lands($x, $y, null, 0, 2, 2, null);

            $nhood = '';
            $lands->getSurrounding($x, $y, $nhood);

            foreach($nhood as $land){
               if ($terrain != SEA) {
                  $database->updateLandType(rand($terrain, $terrain + 4), $land->getX(), $land->getY());
               }
               else {
                  $database->updateLandType($terrain, $land->getX(), $land->getY());               
               }
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

   /**
    * handleAction - handle actions
    */
   function handleAction(){
      global $session;
      $database = $session->database;

      $action = $_POST['action'];
      $key = $_POST['key'];
      $mark_key = $_POST['mark_key'];
      $focus_key = $_POST['focus_key'];
      $dst = "home.php";

      if (!strcmp($action, "army")){
         if (isset($_POST['soldiers'])){
            $character_soldiers = $_POST['character'];
            $garrison = $_POST['garrison'];
            $soldiers = $_POST['soldiers'];
            $name = $_POST['name'];
         
            if ($character_soldiers != $soldiers){
               /* update garrison */
               $new_garrison = $garrison - ($soldiers - $character_soldiers);
               $database->updateGarrison($name, $new_garrison);
               /* update character */
               $database->updateCharacterSoldiers($soldiers, $name);
            }
            $lnk = createLnk($dst, $mark_key, $focus_key);
            header("Location: ".$lnk);
         }

         if (isset($_POST['explorers'])){
            $character_explorers = $_POST['character'];
            $land_explorers = $_POST['land'];
            $explorers = $_POST['explorers'];
            $name = $_POST['name'];
         
            if ($character_explorers != $explorers){
               /* update land */
               $new_land_explorers = $land_explorers - ($explorers - $character_explorers);

               $database->updateExplorers($new_land_explorers, $name);
               /* update character */
               $database->updateCharacterExplorers($explorers, $name);
            }
            $lnk = createLnk($dst, $mark_key, $focus_key);
            header("Location: ".$lnk);
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
            $constructing = 1;
            $database->createBuilding($type, getXFromKey($key), getYFromKey($key), $constructing);

            /* add to build queue */
            $database->addToBuildQueue(getXFromKey($key), getYFromKey($key), $name, getNow(BUILD_TIME), $type, B_CREATE);
         }
         $lnk = createLnk($dst, $mark_key, $focus_key);
         header("Location: ".$lnk);
      }
      else if (!strcmp($action, "train")){
         $type = $_POST['type'];
         $name = $_POST['name'];

         $unit_type = $database->getUnitType($type);
         $cost = $unit_type["cost"];

         $treasury = $database->getTreasuryFromOwner($name);
         $gold = $treasury["gold"];

         if ($gold >= $cost) {
            $database->updateGold($gold - $cost, $name, NULL);

            $population = $database->getPopulation($name);
            $new_civilians = $population["civilians"] - 1;

            $database->updateCivilians2($new_civilians, $name);

            /* add to unit queue */
            $database->addToUnitQueue(getXFromKey($key), getYFromKey($key), $name, getNow(UNIT_BUILD_TIME), $type);
         }
         $lnk = createLnk($dst, $mark_key, $focus_key);
         header("Location: ".$lnk);
      }
      else if (!strcmp($action, "economy")){
         if (isset($_POST['tax'])){
            $tax = $_POST['tax'];
            $name = $_POST['name'];

            $database->updateTax($name, $tax);
         }         
         $lnk = createLnk($dst, $mark_key, $focus_key);
         header("Location: ".$lnk);
      }
      else if (!strcmp($action, "mark")){
         $lnk = createLnk($dst, $key, $focus_key);
         header("Location: ".$lnk);
      }
      else if (!strcmp($action, "unmark")){
         $lnk = createLnk($dst, null, $focus_key);
         header("Location: ".$lnk);
      }
      else if (!strcmp($action, "up") ||
               !strcmp($action, "down") ||
               !strcmp($action, "right") ||
               !strcmp($action, "left"))
      {
         $focus_x = getXfromKey($focus_key);
         $focus_y = getYfromKey($focus_key);

         if (!strcmp($action, "up")) {
            $focus_y += 2;
         }
         else if (!strcmp($action, "down")) {
            $focus_y -= 2;
         }
         else if (!strcmp($action, "right")) {
            $focus_x++;
         }
         else {
            $focus_x--;
         }

         /* create new focus key */
         $focus_key = $focus_x."_".$focus_y;

         $lnk = createLnk($dst, $mark_key, $focus_key);
         header("Location: ".$lnk);
      }
      else if (!strcmp($action, "home")) {
         /* set mark and focus key to home */
         $character = $database->getCharacterByUser($session->username);
         $home_x = $character["home_x"];
         $home_y = $character["home_y"];
         $home_key = createKey($home_x, $home_y);
         $lnk = createLnk($dst, $home_key, $home_key);
         header("Location: ".$lnk);
      }
      else if (!strcmp($action, "army_home")) {
         /* set mark and focus key to army */
         $character = $database->getCharacterByUser($session->username);
         $army_x = $character["x"];
         $army_y = $character["y"];
         $army_key = createKey($army_x, $army_y);
         $lnk = createLnk($dst, $army_key, $army_key);
         header("Location: ".$lnk);
      }
      else{
         $x = getXfromKey($key);
         $y = getYfromKey($key);

         $character = $database->getCharacterByUser($session->username);

         if (!strcmp($action, "move")){
            $database->addToActionQueue($x, $y, $character["name"], getNow(MOVE_TIME), MOVE, 0);
            $lnk = createLnk($dst, $mark_key, $focus_key);
            header("Location: ".$lnk);
         }
         else if (!strcmp($action, "explore")){
            $database->addToActionQueue($x, $y, $character["name"], getNow(EXPLORE_TIME), EXPLORE, 0);
            $lnk = createLnk($dst, $mark_key, $focus_key);
            header("Location: ".$lnk);
         }
         else{
            /**
            * Should not get here, which means user is viewing this page
            * by mistake and therefore is redirected.
            */
            $lnk = createLnk($dst, $key, $focus_key);
            header("Location: ".$lnk);
         }
      }
   }
};

/* Initialize process */
$action_process = new ActionProcess;
?>