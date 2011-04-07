<?php
include_once("session.php");

/**
 * character.php
 * This module handle the character
 */

class Character
{
   private $my_name;           //character name
   
   /* Class constructor */
   public function Character(){
     global $session;
     $database = $session->database;

     $character = $database->getCharacter($session->username);
     $this->my_name = $character["name"];     
   }

   public function getName(){
     return $this->my_name;
   }
}
?>