<?php
/**
 * Database.php
 * 
 * The Database class is meant to simplify the task of accessing
 * information from the website's database.
 *
 * Written by: Jpmaster77 a.k.a. The Grandmaster of C++ (GMC)
 * Last Updated: December 21, 2009
 */
include_once("constants.php");
include_once("session.php");

class MySQLDB
{
   var $connection;         //The MySQL database connection
   var $num_active_users;   //Number of active users viewing site
   var $num_active_guests;  //Number of active guests viewing site
   var $num_members;        //Number of signed-up users
   /* Note: call getNumMembers() to access $num_members! */

   /* Class constructor */
   function MySQLDB(){
      /* Make connection to database */
      $this->connection = mysql_connect(DB_SERVER, DB_USER, DB_PASS) or die(mysql_error());
      mysql_select_db(DB_NAME, $this->connection) or die(mysql_error());
      
      /**
       * Only query database to find out number of members
       * when getNumMembers() is called for the first time,
       * until then, default value set.
       */
      $this->num_members = -1;
      
      if(TRACK_VISITORS){
         /* Calculate number of users at site */
         $this->calcNumActiveUsers();
      
         /* Calculate number of guests at site */
         $this->calcNumActiveGuests();
      }
   }

   /**
    * confirmUserPass - Checks whether or not the given
    * username is in the database, if so it checks if the
    * given password is the same password in the database
    * for that user. If the user doesn't exist or if the
    * passwords don't match up, it returns an error code
    * (1 or 2). On success it returns 0.
    */
   function confirmUserPass($username, $password){
      /* Add slashes if necessary (for query) */
      if(!get_magic_quotes_gpc()) {
	      $username = addslashes($username);
      }

      /* Verify that user is in database */
      $q = "SELECT password FROM ".TBL_USERS." WHERE username = '$username'";
      $result = mysql_query($q, $this->connection);
      if(!$result || (mysql_numrows($result) < 1)){
         return 1; //Indicates username failure
      }

      /* Retrieve password from result, strip slashes */
      $dbarray = mysql_fetch_array($result);
      $dbarray['password'] = stripslashes($dbarray['password']);
      $password = stripslashes($password);

      /* Validate that password is correct */
      if($password == $dbarray['password']){
         return 0; //Success! Username and password confirmed
      }
      else{
         return 2; //Indicates password failure
      }
   }
   
   /**
    * confirmUserID - Checks whether or not the given
    * username is in the database, if so it checks if the
    * given userid is the same userid in the database
    * for that user. If the user doesn't exist or if the
    * userids don't match up, it returns an error code
    * (1 or 2). On success it returns 0.
    */
   function confirmUserID($username, $userid){
      /* Add slashes if necessary (for query) */
      if(!get_magic_quotes_gpc()) {
	      $username = addslashes($username);
      }

      /* Verify that user is in database */
      $q = "SELECT userid FROM ".TBL_USERS." WHERE username = '$username'";
      $result = mysql_query($q, $this->connection);
      if(!$result || (mysql_numrows($result) < 1)){
         return 1; //Indicates username failure
      }

      /* Retrieve userid from result, strip slashes */
      $dbarray = mysql_fetch_array($result);
      $dbarray['userid'] = stripslashes($dbarray['userid']);
      $userid = stripslashes($userid);

      /* Validate that userid is correct */
      if($userid == $dbarray['userid']){
         return 0; //Success! Username and userid confirmed
      }
      else{
         return 2; //Indicates userid invalid
      }
   }
   
   /**
    * usernameTaken - Returns true if the username has
    * been taken by another user, false otherwise.
    */
   function usernameTaken($username){
      if(!get_magic_quotes_gpc()){
         $username = addslashes($username);
      }
      $q = "SELECT username FROM ".TBL_USERS." WHERE username = '$username'";
      $result = mysql_query($q, $this->connection);
      return (mysql_numrows($result) > 0);
   }
   
   /**
    * usernameBanned - Returns true if the username has
    * been banned by the administrator.
    */
   function usernameBanned($username){
      if(!get_magic_quotes_gpc()){
         $username = addslashes($username);
      }
      $q = "SELECT username FROM ".TBL_BANNED_USERS." WHERE username = '$username'";
      $result = mysql_query($q, $this->connection);
      return (mysql_numrows($result) > 0);
   }
   
   /**
    * addNewUser - Inserts the given user
    * info into the database. Appropriate user level is set and init is set to false.
    * Returns true on success, false otherwise.
    */
   function addNewUser($username, $password, $email){
      $time = time();
      /* If admin sign up, give admin user level */
      if(strcasecmp($username, ADMIN_NAME) == 0){
         $ulevel = ADMIN_LEVEL;
      }else{
         $ulevel = USER_LEVEL;
      }
      /* Not initialised yet so init is set to 0 */
      $init = 0;
      $q = "INSERT INTO ".TBL_USERS." VALUES ('$username', '$password', '0', $ulevel, '$email', $time, $init)";
      return mysql_query($q, $this->connection);
   }
   
   /**
    * updateUserField - Updates a field, specified by the field
    * parameter, in the user's row of the database.
    */
   function updateUserField($username, $field, $value){
      $q = "UPDATE ".TBL_USERS." SET ".$field." = '$value' WHERE username = '$username'";
      return mysql_query($q, $this->connection);
   }
   
   /**
    * getUserInfo - Returns the result array from a mysql
    * query asking for all information stored regarding
    * the given username. If query fails, NULL is returned.
    */
   function getUserInfo($username){
      $q = "SELECT * FROM ".TBL_USERS." WHERE username = '$username'";
      $result = mysql_query($q, $this->connection);
      /* Error occurred, return given name by default */
      if(!$result || (mysql_numrows($result) < 1)){
         return NULL;
      }
      /* Return result array */
      $dbarray = mysql_fetch_array($result);
      return $dbarray;
   }
   
   /**
    * getNumMembers - Returns the number of signed-up users
    * of the website, banned members not included. The first
    * time the function is called on page load, the database
    * is queried, on subsequent calls, the stored result
    * is returned. This is to improve efficiency, effectively
    * not querying the database when no call is made.
    */
   function getNumMembers(){
      if($this->num_members < 0){
         $q = "SELECT * FROM ".TBL_USERS;
         $result = mysql_query($q, $this->connection);
         $this->num_members = mysql_numrows($result);
      }
      return $this->num_members;
   }
   
   /**
    * calcNumActiveUsers - Finds out how many active users
    * are viewing site and sets class variable accordingly.
    */
   function calcNumActiveUsers(){
      /* Calculate number of users at site */
      $q = "SELECT * FROM ".TBL_ACTIVE_USERS;
      $result = mysql_query($q, $this->connection);
      $this->num_active_users = mysql_numrows($result);
   }
   
   /**
    * calcNumActiveGuests - Finds out how many active guests
    * are viewing site and sets class variable accordingly.
    */
   function calcNumActiveGuests(){
      /* Calculate number of guests at site */
      $q = "SELECT * FROM ".TBL_ACTIVE_GUESTS;
      $result = mysql_query($q, $this->connection);
      $this->num_active_guests = mysql_numrows($result);
   }
   
   /**
    * addActiveUser - Updates username's last active timestamp
    * in the database, and also adds him to the table of
    * active users, or updates timestamp if already there.
    */
   function addActiveUser($username, $time){
      $q = "UPDATE ".TBL_USERS." SET timestamp = '$time' WHERE username = '$username'";
      mysql_query($q, $this->connection);
      
      if(!TRACK_VISITORS) return;
      $q = "REPLACE INTO ".TBL_ACTIVE_USERS." VALUES ('$username', '$time')";
      mysql_query($q, $this->connection);
      $this->calcNumActiveUsers();
   }
   
   /* addActiveGuest - Adds guest to active guests table */
   function addActiveGuest($ip, $time){
      if(!TRACK_VISITORS) return;
      $q = "REPLACE INTO ".TBL_ACTIVE_GUESTS." VALUES ('$ip', '$time')";
      mysql_query($q, $this->connection);
      $this->calcNumActiveGuests();
   }
   
   /* These functions are self explanatory, no need for comments */
   
   /* removeActiveUser */
   function removeActiveUser($username){
      if(!TRACK_VISITORS) return;
      $q = "DELETE FROM ".TBL_ACTIVE_USERS." WHERE username = '$username'";
      mysql_query($q, $this->connection);
      $this->calcNumActiveUsers();
   }
   
   /* removeActiveGuest */
   function removeActiveGuest($ip){
      if(!TRACK_VISITORS) return;
      $q = "DELETE FROM ".TBL_ACTIVE_GUESTS." WHERE ip = '$ip'";
      mysql_query($q, $this->connection);
      $this->calcNumActiveGuests();
   }
   
   /* removeInactiveUsers */
   function removeInactiveUsers(){
      if(!TRACK_VISITORS) return;
      $timeout = time()-USER_TIMEOUT*60;
      $q = "DELETE FROM ".TBL_ACTIVE_USERS." WHERE timestamp < $timeout";
      mysql_query($q, $this->connection);
      $this->calcNumActiveUsers();
   }

   /* removeInactiveGuests */
   function removeInactiveGuests(){
      if(!TRACK_VISITORS) return;
      $timeout = time()-GUEST_TIMEOUT*60;
      $q = "DELETE FROM ".TBL_ACTIVE_GUESTS." WHERE timestamp < $timeout";
      mysql_query($q, $this->connection);
      $this->calcNumActiveGuests();
   }
   
   /**
    * query - Performs the given query on the database and
    * Returns the result, which may be false, true or a
    * resource identifier.
    */
   function query($query){
      global $session;
      $session->logger->LogInfo($query);
      return mysql_query($query, $this->connection);
   }

  /**
   * map - Get a shunk of land based on coordinats and size.
   * Returns array of lands
   */
   function map($x, $y, $x_size, $y_size){
      global $session;
      $q = "SELECT * FROM ".TBL_LANDS." WHERE (y BETWEEN ".($y - $y_size)." AND ".($y + $y_size).") AND (x BETWEEN ".($x - $x_size)." AND ".($x + $x_size).") ORDER BY y DESC, x ASC";
      $result = $this->query($q);
      /* Error occurred */
      if(!$result || (mysql_numrows($result) < 1)){
         $session->logger->LogError("Error or no results from map");
         return NULL;
      }
      /* Return result array */
      $dbarray = array();
      for ($i=0; $row = mysql_fetch_assoc($result); $i++){
         $dbarray[$i] = $row;
      }
   
      mysql_free_result($result);

      return $dbarray;
   }

  /**
   * map_all - Get all lands
   * Returns array of lands
   */
   function map_all(){
      $q = "SELECT * FROM ".TBL_LANDS." ORDER BY y ASC, x ASC";
      $result = $this->query($q);
      /* Error occurred */
      if(!$result || (mysql_numrows($result) < 1)){
         $session->logger->LogError("Error or no results from map_all");
         return NULL;
      }
      /* Return result array */
      $dbarray = array();
      for ($i=0; $row = mysql_fetch_assoc($result); $i++){
         $dbarray[$i] = $row;
      }

      mysql_free_result($result);

      return $dbarray;
   }

  /**
   * units - Get units based on coordinates and size.
   * Returns array of units
   */
   function units($x, $y, $size){
      global $session;

      $q = "SELECT * FROM ".TBL_CHARACTERS." WHERE (y BETWEEN ".($y - $size)." AND ".($y + $size).") AND (x BETWEEN ".($x - $size)." AND ".($x + $size).") ORDER BY y ASC, x ASC";
      $result = $this->query($q);
      /* Error occurred */
      if(!$result || (mysql_numrows($result) < 1)){
         $session->logger->LogError("Error or no results from units");
         return NULL;
      }

      $rows = mysql_numrows($result);

      /* Return result array */
      $dbarray = array();
      for ($i=0; $row = mysql_fetch_assoc($result); $i++){
         $dbarray[$i] = $row;
      }

      mysql_free_result($result);

      return $dbarray;
   }

  /**
   * getAllUnits - Get all units
   * Returns array of units
   */
   function getAllUnits(){
      global $session;

      $q = "SELECT * FROM ".TBL_CHARACTERS." ORDER BY y ASC, x ASC";
      $result = $this->query($q);
      /* Error occurred */
      if(!$result){
         $session->logger->LogError("Error in getAllUnits");
         return NULL;
      }

      $rows = mysql_numrows($result);
      
      /* Return result array */
      $dbarray = array();
      for ($i=0; $row = mysql_fetch_assoc($result); $i++){
         $dbarray[$i] = $row;
      }

      mysql_free_result($result);

      return $dbarray;
   }

   /**
    * addToActionQueue - insert an action in the action queue
    * Returns nothing
    */
   function addToActionQueue($x, $y, $character, $dueTime, $type, $addInfo){
     $q = "INSERT INTO ".TBL_ACTION_QUEUE." VALUES ('$character', $x, $y, '$dueTime', $type, $addInfo)";
     return $this->query($q);
   }

   /**
    * removeFromActionQueue - remove an action from action queue
    * Returns nothing
    */
   function removeFromActionQueue($x, $y, $character){
      global $session;

      $q = "DELETE FROM ".TBL_ACTION_QUEUE." WHERE name = '$character' and x = ".$x." and y = ".$y."";
      $session->logger->LogInfo($q);

      mysql_query($q, $this->connection);
      return NULL;
   }

   /**
    * checkActionQueue - check if actions are due
    * Returns due moves
    */
   function checkActionQueue(){
      global $session;

      $q = "SELECT * FROM ".TBL_ACTION_QUEUE." ORDER BY due_time ASC";
      $result = $this->query($q);

      /* Error occurred */
      if (!$result){
         $session->logger->LogError("Error in checkActionQueue");
         return NULL;
      }

      /* No moves are due */
      if(mysql_numrows($result) < 1){
         $session->logger->LogInfo("No result in checkActionQueue");
         return NULL;
      }
      
      $rows = mysql_numrows($result);

      /* Return result array */
      $dbarray = array();
      
      $now = strtotime("+0 seconds");
      $now = strftime("%Y-%m-%d %H:%M:%S", $now);

      for ($i=0; $row = mysql_fetch_assoc($result); $i++){
         $dueTime = $row["due_time"];
         // need to replace this function with local function
         $diff = $this->getTimeDiff($dueTime, $now);
         
         /* if first char in the time string is a minus sign the move is due */
         if ($diff[0] == "-"){
            $dbarray[$i] = $row;
         }
         /* if time string is zero the move is due */
         else if(!strcmp($diff, "00:00:00"))
         {
            $dbarray[$i] = $row;
         }
      }

      mysql_free_result($result);      

      return $dbarray;
   }

   /**
    * getActions - get actions including time left for a action for a character
    * Returns the actions
    */
   function getActions($character){
      global $session;

      $q = "SELECT * FROM ".TBL_ACTION_QUEUE." WHERE name = '".$character."' ORDER BY due_time ASC";

      $result = $this->query($q);
      /* Error occurred */
      if(!$result){
         $session->logger->LogError("Error in getAction");
         return NULL;
      }

      $rows = mysql_numrows($result);
      if ($rows < 1){
         return NULL;
      }

      /* Return result array */
      $dbarray = array();

      for ($i=0; $row = mysql_fetch_assoc($result); $i++){
         $dbarray[$i] = $row;
      }

      mysql_free_result($result);
      
      return $dbarray;
   }

   /**
    * addToBuildQueue - insert a build action in the build queue
    * Returns nothing
    */
   function addToBuildQueue($x, $y, $town, $character, $dueTime, $type, $action, $level){
     $q = "INSERT INTO ".TBL_BUILD_QUEUE." VALUES ('$character', '$town', $x, $y, '$dueTime', '$type', $action, $level)";
     return $this->query($q);
   }

   /**
    * removeFromBuildQueue - remove a build action in the build queue
    * Returns nothing
    */
   function removeFromBuildQueue($x, $y, $town){
      global $session;

      $q = "DELETE FROM ".TBL_BUILD_QUEUE." WHERE town = '$town' and x = ".$x." and y = ".$y."";
      $session->logger->LogInfo($q);

      mysql_query($q, $this->connection);
      return NULL;
   }

   /**
    * checkBuildQueue - check if builds are due
    * returns due builds
    */
   function checkBuildQueue(){
      global $session;

      $q = "SELECT * FROM ".TBL_BUILD_QUEUE." ORDER BY dueTime ASC";
      $result = $this->query($q);

      /* Error occurred */
      if(!$result){
         $session->logger->LogError("Error in checkBuildQueue");
         return NULL;
      }

      /* No builds are due */
      if(mysql_numrows($result) < 1){
         $session->logger->LogInfo("No result in checkBuildQueue");
         return NULL;
      }
      
      $rows = mysql_numrows($result);

      /* Return result array */
      $dbarray = array();
      
      $now = strtotime("+0 seconds");
      $now = strftime("%Y-%m-%d %H:%M:%S", $now);

      for ($i=0; $row = mysql_fetch_assoc($result); $i++){
         $dueTime = $row["dueTime"];
         // need to replace this function with local function
         $diff = $this->getTimeDiff($dueTime, $now);
         
         /* if first char in the time string is a minus sign the move is due */
         if ($diff[0] == "-"){
            $dbarray[$i] = $row;
         }
         /* if time string is zero the move is due */
         else if(!strcmp($diff, "00:00:00"))
         {
            $dbarray[$i] = $row;
         }
      }

      mysql_free_result($result);      

      return $dbarray;
   }

   /**
    * getTimeDiff - get a time diff from MYSSQL Server
    * Returns the diff.
    */

   function getTimeDiff($time1, $time2) {
      global $session;

      $q = "SELECT TIMEDIFF('$time1', '$time2')";
      
      $result = $this->query($q);
      
      /* Error occurred */
      if(!$result || (mysql_numrows($result) < 1)){
         $session->logger->LogInfo("getTimeDiff - Error or no row return");
         return NULL;
      }
      
      $row = mysql_fetch_row($result);
      mysql_free_result($result);

      return $row[0];            
   }

   /**
    * getBuildEvents - get events from build queue
    * Returns build events
    */

   function getBuildEvents($character){
      global $session;

      $q = "SELECT * FROM ".TBL_BUILD_QUEUE." WHERE character = '".$character."'";

      $result = $this->query($q);
      /* Error occurred */
      if(!$result){
         $session->logger->LogError("Error in getBuilds");
         return NULL;
      }

      $rows = mysql_numrows($result);

      $row = mysql_fetch_assoc($result);

      mysql_free_result($result);
      
      return $row;
   }

   /**
    * move - move a character to some coordinate
    * Returns nothing
    */
   function move($x, $y, $character){
     $q = "UPDATE ".TBL_CHARACTERS." SET x = ".$x." ,y = ".$y." WHERE name = '".$character."'";
     return $this->query($q);
   }

   /**
    * createTown - create a town on a coordinate
    * returns nothing
    */
   function createTown($x, $y, $owner, $name){
     $q = "INSERT INTO ".TBL_TOWN." VALUES ('$owner', '$name', '$x', '$y', '1', '1')";
     $this->query($q);

     $dueTime = strtotime("+60 seconds");
     $dueTime = strftime("%Y-%m-%d %H:%M:%S", $dueTime);
     
     $this->addToPopQueue($name, $dueTime);
   }

   /**
    * addToPopQueue - create an entry in the population queue
    * returns nothing
    */
   function addToPopQueue($name, $dueTime){
     
     $q = "INSERT INTO ".TBL_POPULATION_QUEUE." VALUES ('$name', '$dueTime')";
     return $this->query($q);
   }
   
   /**
    * createBuilding - create a building on a coordinate
    * Returns nothing
    */
   function createBuilding($type, $x, $y, $town){
     /* level is always 1 when creating a building */
     $level = 1;

     /* constructing, removing and upgrading */
     $constructing = 1;
     $removing = 0;
     $upgrading = 0;

     /* hp is always 10 when creating a building */
     $hp = 10;

     $q = "INSERT INTO ".TBL_BUILDINGS." VALUES ('$type', '$level', '$hp', '$x', '$y', '$town', '$constructing', '$removing', '$upgrading')";
     return $this->query($q);
   }

   /**
    * createBuildingDone - Building is created.
    * Returns nothing
    */
   function createBuildingDone($x, $y, $town){
     $q = "UPDATE ".TBL_BUILDINGS." SET constructing = 0 WHERE town = '$town' AND x = ".$x." AND y = ".$y;
     return $this->query($q);
   }

   /**
    * updateBuildingLevel - update building level on a coordinate
    * Returns nothing
    */
   function updateBuildingLevel($x, $y, $town){
     $q = "UPDATE ".TBL_BUILDINGS." SET upgrading = 1 WHERE town = '$town' AND x = ".$x." AND y = ".$y;

     return $this->query($q);
   }

   /**
    * updateBuildingLevelDone - Done updating building level.
    * Returns nothing
    */
   function updateBuildingLevelDone($level, $x, $y, $town){
     $q = "UPDATE ".TBL_BUILDINGS." SET level = ".$level.", upgrading = 0 WHERE town = '$town' AND x = ".$x." AND y = ".$y;

     return $this->query($q);
   }

   /**
    * deleteBuilding - delete a building on a coordinate
    * Returns nothing
    */
   function removeBuilding($x, $y, $town){
     $q = "UPDATE ".TBL_BUILDINGS." SET removing = 1 WHERE town = '$town' AND x = ".$x." AND y = ".$y;
     return $this->query($q);
   }

   /**
    * deleteBuildingDone - Done deleting a building.
    * Returns nothing
    */
   function removeBuildingDone($x, $y, $town){
     $q = "DELETE FROM ".TBL_BUILDINGS." WHERE town = '$town' and x = ".$x." and y = ".$y."";
     return $this->query($q);
   }

   /**
    * getBuilding - get a building
    * Returns the building
    */
   function getBuilding($town, $x, $y){
      global $session;

      $q = "SELECT * FROM ".TBL_BUILDINGS." WHERE town = '$username' and x = ".$x." and y = ".$y."";
      $result = $this->query($q);
      /* Error occurred */
      if(!$result || (mysql_numrows($result) < 1)){
         $session->logger->LogError("getBuilding - Error or no result");
         return NULL;
      }
      /* Return result array */
      $dbarray = array();
      for ($i=0; $row = mysql_fetch_assoc($result); $i++){
         $dbarray[$i] = $row;
      }

      mysql_free_result($result);

      return $dbarray;
   }

   /**
    * getAllBuildings - get all buildings in a town
    * Returns all buildings in a town
    */
   function getAllBuildings($town){
      global $session;

      $q = "SELECT * FROM ".TBL_BUILDINGS." WHERE town = '$town' order by y ASC, x ASC";
      $result = $this->query($q);
      /* Error occurred */
      if(!$result){
         $session->logger->LogError("Error in getAllBuildings");
         return NULL;
      }

      /* Return result array */
      $dbarray = array();
      for ($i=0; $row = mysql_fetch_assoc($result); $i++){
         $dbarray[$i] = $row;
      }

      mysql_free_result($result);

      return $dbarray;
   }

   /**
    * updateBuildingHP - update building hp on a coordinate
    * Returns nothing
    */
   function updateBuildingHP($hp, $x, $y, $town){
     $q = "UPDATE ".TBL_BUILDINGS." SET hp = ".$hp." WHERE town = '$town' AND x = ".$x." AND y = ".$y;

     return $this->query($q);
   }
        
    /**
    * getBuildingTypes - get building types
    * Returns all building types
    */
   function getBuildingTypes(){
      global $session;

      $q = "SELECT * FROM ".TBL_BUILDING_TYPES." ORDER BY type DESC";

      $result = $this->query($q);
      /* Error occurred */
      if(!$result || (mysql_numrows($result) < 1)) {
         $session->logger->LogError("getBuildingTypes - Error or no result");
         return NULL;
      }
      /* Return result array */
      $dbarray = array();
      for ($i=0; $row = mysql_fetch_assoc($result); $i++){
         $dbarray[$i] = $row;
      }

      mysql_free_result($result);

      return $dbarray;
   }

    /**
    * getBuildingCost - get building cost
    * Returns cost for a building of a certain type
    */
   function getBuildingCost($type){
      global $session;

      $q = "SELECT cost FROM ".TBL_BUILDING_TYPES." where type = '$type'";

      $result = $this->query($q);
      /* Error occurred */
      if(!$result || (mysql_numrows($result) < 1)) {
         $session->logger->LogError("getBuildingCost - Error or no result");
         return NULL;
      }

      return $result;
   }

   /**
    * charnameTaken - 
    * Returns true if the character name has
    * been taken by another user, false otherwise.
    */
   function charnameTaken($charname){
      if(!get_magic_quotes_gpc()){
         $charname = addslashes($charname);
      }
      $q = "SELECT name FROM ".TBL_CHARACTERS." WHERE username = '$charname'";
      $result = mysql_query($q, $this->connection);
      $count = mysql_numrows($result);

      mysql_free_result($result);

      return $count > 0;
   }

   /**
    * addNewCharacter - Inserts the given character info into the database.
    * Returns true on success, false otherwise.
    */
   function addNewCharacter($charname, $username, $tribe, $x, $y, $code){
      $q = "INSERT INTO ".TBL_CHARACTERS." VALUES ('$charname', '$username', '$tribe', '$x', '$y', '$code')";
      return mysql_query($q, $this->connection);
   }

   /**
    * updateLandType - Update the terrain type for a land entity
    * Returns true on success, false otherwise.
    */
   function updateLand($type, $yield, $x, $y){
      $q = "UPDATE ".TBL_LANDS." SET type = ".$type.", yield = ".$yield."  WHERE x = ".$x." AND y = ".$y;
      return $this->query($q);
   }

   /**
    * addLand - Add land
    * Returns true on success, false otherwise.
    */
   function addLand($x, $y, $type, $yield, $toxic){
      $q = "INSERT into ".TBL_LANDS." (x, y, type, yield, toxic) values (".$x.", ".$y.", ".$type.", ".$yield.", ".$toxic.")";
      return $this->query($q);
   }

   /**
    * updateLandOwner - Change owner of a land
    * Returns true on success, false otherwise.
    */
   function updateLandOwner($owner, $x, $y){
      $q = "UPDATE ".TBL_LANDS." SET owner = '$owner' WHERE x = ".$x." AND y = ".$y;
      return $this->query($q);
   }

  /**
   * getCharacters - Get characters from username.
   * Returns array of units
   */
   function getCharacters($username){
      global $session;

      $q = "SELECT * FROM ".TBL_CHARACTERS." WHERE username = '$username'";
      $result = $this->query($q);
      /* Error occurred */
      if(!$result || (mysql_numrows($result) < 1)){
         $session->logger->LogError("getCharacter - Error or no result");
         return NULL;
      }
      /* Return result array */
      $dbarray = array();
      for ($i=0; $row = mysql_fetch_assoc($result); $i++){
         $dbarray[$i] = $row;
      }

      mysql_free_result($result);

      return $dbarray;
   }

  /**
   * getCharacter - Get a character from unique name
   * Returns the character
   */
   function getCharacter($name){
      global $session;

      $q = "SELECT * FROM ".TBL_CHARACTERS." WHERE username = '$name'";
      $result = $this->query($q);
      /* Error occurred */
      if(!$result || (mysql_numrows($result) != 1)){
         $session->logger->LogError("getCharacter - Error or didn�t return unique row");
         return NULL;
      }

      $row = mysql_fetch_assoc($result);

      mysql_free_result($result);

      return $row;
   }

   /**
    * landIsEmpty - check that no character is in this coordinate
    * Returns 1 if ok, otherwise 0.
    */
   function landIsEmpty($x, $y) {
      global $session;
      $status = 1;

      $q = "SELECT * FROM ".TBL_CHARACTERS." WHERE x = ".$x." AND y = ".$y;
      $result = $this->query($q);
      if (!$result) {
         $session->logger->LogError("Bad result from landIsEmpty");
         return NULL;
      }
      if (mysql_numrows($result) > 0) {
         $status = 0;
      }

      mysql_free_result($result);

      return $status;
   }

   /**
    * getLandOwner - get land owner
    */
   function getLandOwner($x, $y) {
      global $session;

      $q = "SELECT owner FROM ".TBL_LANDS." WHERE x = ".$x." AND y = ".$y;
      $result = $this->query($q);
      if (!$result) {
         $session->logger->LogError("Bad result from getLandOwner");
         return 0;
      }
      if (mysql_numrows($result) != 1) {
         $session->logger->LogError("getLandOwner didn't return one row");         
         return 0;
      }
      else {
         $row = mysql_fetch_assoc($result);
         mysql_free_result($result);

         return $row["owner"];
      }
   }

   /**
    * getLandType - get type for a land
    * Returns row if ok, otherswise NULL or 0.
    */
   function getLandType($x, $y) {
      global $session;

      $q = "SELECT * FROM ".TBL_LANDS." WHERE x = ".$x." AND y = ".$y;
      $result = $this->query($q);
      if (!$result) {
         $session->logger->LogError("Bad result from getLandType");
         return NULL;
      }
      if (mysql_numrows($result) != 1) {
         $session->logger->LogError("getLandType didn't return a land");         
         return 0;
      }
      else {
         $row = mysql_fetch_assoc($result);
         mysql_free_result($result);

         return $row["type"];
      }
   }

   /**
    * initResources - initialize reources for character
    * Returns nothing
    */
   function initResources($characterName, $production, $production_growth, $now) {
      global $session;

      $q = "INSERT INTO ".TBL_RESOURCES." VALUES ('$characterName', ".$production.", ".$production_growth.", '$now')";
      $result = $this->query($q);
      if (!$result) {
         $session->logger->LogError("Error in initResources");
      }
   }

   /**
    * updateResources - update reources for character
    * Returns nothing
    */
   function updateResources($characterName, $production, $growth, $now) {
      global $session;

      if ($now) {
         $q = "UPDATE ".TBL_RESOURCES." SET production = ".$production.", production_growth = ".$growth.", due_time = '$now' WHERE character_name = '$characterName'";
      }
      else {
         $q = "UPDATE ".TBL_RESOURCES." SET production = ".$production." WHERE character_name = '$characterName'";
      }

      $result = $this->query($q);
      if (!$result) {
         $session->logger->LogError("Error in updateResources");
      }
   }

   /**
    * getResources - get resources for a character
    * Returns resources
    */
   function getResources($characterName) {
      global $session;

      $q = "SELECT * FROM ".TBL_RESOURCES." WHERE character_name = '".$characterName."'";
      $result = $this->query($q);
      /* Error occurred */
      if (!$result) {
         $session->logger->LogError("Error in getResources");
         return NULL;
      }
      if (mysql_numrows($result) != 1) {
         $session->logger->LogError("getResources didn't return one resourse row");         
         return 0;
      }

      $row = mysql_fetch_assoc($result);
      mysql_free_result($result);

      return $row;
   }

   /**
    * getAllTribes - get all tribes
    * Returns array tribes
    */
   function getAllTribes() {
      global $session;

      $q = "SELECT * FROM ".TBL_TRIBE." ORDER by name ASC";
      $result = $this->query($q);
      if (!$result) {
         $session->logger->LogError("Error in getAllTribes");         
         return 0;
      }
      else {
         /* Return result array */
         $dbarray = array();
         for ($i=0; $row = mysql_fetch_assoc($result); $i++){
            $dbarray[$i] = $row;
         }

         mysql_free_result($result);

         return $dbarray;
      }      
   }

   /**
    * setLandOwner - set owner of land
    */
   function setLandOwner($x, $y, $owner) {
      global $session;

      $q = "UPDATE ".TBL_LANDS." SET owner = '$owner' WHERE x = ".$x." AND y = ".$y;
      $session->logger->LogInfo($q);
      return mysql_query($q, $this->connection);
   }

   /**
    * setLandToxic - set toxic level of land
    */
   function setLandToxic($x, $y, $toxic) {
      global $session;

      $q = "UPDATE ".TBL_LANDS." SET toxic = ".$toxic." WHERE x = ".$x." AND y = ".$y;
      $session->logger->LogInfo($q);
      return mysql_query($q, $this->connection);    
   }

   /* could be generic very easy ... */
   function getLandToxic($x, $y) {
      global $session;

      $q = "SELECT * FROM ".TBL_LANDS." WHERE x = ".$x." AND y = ".$y;
      $result = $this->query($q);
      if (!$result) {
         $session->logger->LogError("Bad result from getLandToxic");
         return NULL;
      }
      if (mysql_numrows($result) != 1) {
         $session->logger->LogError("getLandToxic didn't return a land");         
         return 0;
      }
      else {
         $row = mysql_fetch_assoc($result);
         mysql_free_result($result);

         return $row["toxic"];
      }
   }

   function getLandFromOwner($owner) {
      global $session;

      $q = "SELECT * FROM ".TBL_LANDS." WHERE owner = '$owner'";
      $result = $this->query($q);
      if (!$result) {
         $session->logger->LogError("Bad result from getLandFromOwner");
         return NULL;
      }
      else {
         /* Return result array */
         $dbarray = array();
         for ($i=0; $row = mysql_fetch_assoc($result); $i++){
            $dbarray[$i] = $row;
         }

         mysql_free_result($result);

         return $dbarray;
      }
   }

   /**
    * checkResourceTime - check if it's time to get resources
    * Returns due resource rows
    */
   function checkResourceTime() {
      global $session;

      $q = "SELECT * FROM ".TBL_RESOURCES." ORDER BY due_time ASC";
      $result = $this->query($q);

      /* Error occurred */
      if (!$result){
         $session->logger->LogError("Error in checkResourceTime");
         return NULL;
      }

      /* No resources are due */
      if(mysql_numrows($result) < 1){
         $session->logger->LogInfo("No result in checkResourceTime");
         return NULL;
      }
      
      $rows = mysql_numrows($result);

      /* Return result array */
      $dbarray = array();
      
      $now = strtotime("+0 seconds");
      $now = strftime("%Y-%m-%d %H:%M:%S", $now);

      for ($i=0; $row = mysql_fetch_assoc($result); $i++){
         $dueTime = $row["due_time"];
         // need to replace this function with local function
         $diff = $this->getTimeDiff($dueTime, $now);
         
         /* if first char in the time string is a minus sign the move is due */
         if ($diff[0] == "-"){
            $dbarray[$i] = $row;
         }
         /* if time string is zero the move is due */
         else if(!strcmp($diff, "00:00:00"))
         {
            $dbarray[$i] = $row;
         }
      }

      mysql_free_result($result);      

      return $dbarray;
   }

};

?>