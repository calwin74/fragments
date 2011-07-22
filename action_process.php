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
         
         $database->updateLandType($terrain, getXFromKey($key), getYFromKey($key));
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

      if (!strcmp($action, "army")){
         if (isset($_POST['civilians'])){
            $character_civilians = $_POST['character'];
            $land_civilians = $_POST['land'];
            $civilians = $_POST['civilians'];
            $name = $_POST['name'];
         
            if ($character_civilians != $civilians){
               /* update land */
               $new_land_civilians = $land_civilians - ($civilians - $character_civilians);

               /* check land civilians */
               $land = $database->getLand(getXFromKey($key), getYFromKey($key));
               if ($row["civilians"] + $new_land_civilians >= CIVILIANS_MAX){
                  $new_land_civilians = CIVILIANS_MAX;
               }

               $database->updateCivilians($new_land_civilians, getXFromKey($key), getYFromKey($key), getNow(0));
               /* update character */
               $database->updateCharacterCivilians($civilians, $name);
            }
            header("Location: ".$session->referrer);
         }

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
            header("Location: ".$session->referrer);
         }

         if (isset($_POST['explorers'])){
            $character_explorers = $_POST['character'];
            $land_explorers = $_POST['land'];
            $explorers = $_POST['explorers'];
            $name = $_POST['name'];
         
            if ($character_explorers != $explorers){
               /* update land */
               $new_land_explorers = $land_explorers - ($explorers - $character_explorers);

               $database->updateExplorers($new_land_explorers, getXFromKey($key), getYFromKey($key));
               /* update character */
               $database->updateCharacterExplorers($explorers, $name);
            }
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
      else if (!strcmp($action, "train")){
         $type = $_POST['type'];
         $name = $_POST['name'];

         $unit_type = $database->getUnitType($type);
         $cost = $unit_type["cost"];

         $treasury = $database->getTreasuryFromOwner($name);
         $gold = $treasury["gold"];

         $s = $type." cost:".$cost;
         $session->logger->LogInfo($s);

         if ($gold >= $cost) {
            $database->updateGold($gold - $cost, $name, NULL);

            $land = $database->getLand(getXFromKey($key), getYFromKey($key));
            $new_civilians = $land["civilians"] - 1;

            $s = "new civilians:".$new_civilians;
            $session->logger->LogInfo($s);            

            $database->updateCivilians2($new_civilians, getXFromKey($key), getYFromKey($key));

            /* add to unit queue */
            $database->addToUnitQueue(getXFromKey($key), getYFromKey($key), $name, getNow(UNIT_BUILD_TIME), $type);
         }

         header("Location: ".$session->referrer);
      }
      else if (!strcmp($action, "economy")){
         if (isset($_POST['tax'])){
            $tax = $_POST['tax'];
            $name = $_POST['name'];

            $database->updateTax($name, $tax);
         }
         
         header("Location: ".$session->referrer);
      }
      else if (!strcmp($action, "mark")){
         header("Location: ".$session->referrer."?mark_key=".$key);
      }
      else{
         $x = getXfromKey($key);
         $y = getYfromKey($key);

         $character = $database->getCharacterByUser($session->username);

         if (!strcmp($action, "move")){
            $database->addToActionQueue($x, $y, $character["name"], getNow(MOVE_TIME), MOVE, 0);
            header("Location: ".$session->referrer);
         }
         else if (!strcmp($action, "explore")){
            $database->addToActionQueue($x, $y, $character["name"], getNow(EXPLORE_TIME), EXPLORE, 0);
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