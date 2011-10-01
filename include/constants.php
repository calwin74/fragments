<?php
/**
 * Constants.php
 *
 * This file is intended to group all constants to
 * make it easier for the site administrator to tweak
 * the login script.
 *
 * Written by: Jpmaster77 a.k.a. The Grandmaster of C++ (GMC)
 * Last Updated: August 19, 2004
 */

include_once("site.php");

/**
 * Database Constants - these constants are required
 * in order for there to be a successful connection
 * to the MySQL database. Make sure the information is
 * correct.
 */

/*
 * Database Table Constants - these constants
 * hold the names of all the database tables used
 * in the script.
 */
define("TBL_USERS", "users");
define("TBL_ACTIVE_USERS",  "active_users");
define("TBL_ACTIVE_GUESTS", "active_guests");
define("TBL_BANNED_USERS",  "banned_users");
define("TBL_LANDS",  "lands");
define("TBL_CHARACTERS",  "characters");
define("TBL_TRIBE",  "tribe");
define("TBL_ACTION_QUEUE",  "action_queue");
define("TBL_TREASURY", "treasury");
define("TBL_BUILDING_TYPES", "building_types");
define("TBL_BUILDINGS", "buildings");
define("TBL_BUILD_QUEUE", "build_queue");
define("TBL_UNIT_QUEUE", "unit_queue");
define("TBL_UNIT_TYPES", "unit_types");
define("TBL_GARRISON", "garrison");
define("TBL_POPULATION", "population");


/**
 * Special Names and Level Constants - the admin
 * page will only be accessible to the user with
 * the admin name and also to those users at the
 * admin user level. Feel free to change the names
 * and level constants as you see fit, you may
 * also add additional level specifications.
 * Levels must be digits between 0-9.
 */
define("ADMIN_NAME", "admin");
define("AGENT_NAME", "agent");
define("GUEST_NAME", "Guest");
define("ADMIN_LEVEL", 9);
define("AGENT_LEVEL", 8);
define("USER_LEVEL",  1);
define("GUEST_LEVEL", 0);

/**
 * This boolean constant controls whether or
 * not the script keeps track of active users
 * and active guests who are visiting the site.
 */
define("TRACK_VISITORS", true);

/**
 * Timeout Constants - these constants refer to
 * the maximum amount of time (in minutes) after
 * their last page fresh that a user and guest
 * are still considered active visitors.
 */
define("USER_TIMEOUT", 10);
define("GUEST_TIMEOUT", 5);

/**
 * Cookie Constants - these are the parameters
 * to the setcookie function call, change them
 * if necessary to fit your website. If you need
 * help, visit www.php.net for more info.
 * <http://www.php.net/manual/en/function.setcookie.php>
 */
define("COOKIE_EXPIRE", 60*60*24*100);  //100 days by default
define("COOKIE_PATH", "/");  //Avaible in whole domain

/**
 * Email Constants - these specify what goes in
 * the from field in the emails that the script
 * sends to users, and whether to send a
 * welcome email to newly registered users.
 */
define("EMAIL_FROM_NAME", "lak-test");
define("EMAIL_FROM_ADDR", "lak-test@hotmail.com");
define("EMAIL_WELCOME", true);

/**
 * This constant forces all users to have
 * lowercase usernames, capital letters are
 * converted automatically.
 */
define("ALL_LOWERCASE", false);

/**
 * title prefix constant
 */
define("FRAGMENTS_TITLE", "Fragments Project - made in STHLM");

/**
 * terrain types
 */
define("DIRT1", 1);
define("DIRT2", 2);
define("DIRT3", 3);
define("DIRT4", 4);
define("DIRT5", 5);

define("DIRTVEG1", 6);
define("DIRTVEG2", 7);
define("DIRTVEG3", 8);
define("DIRTVEG4", 9);
define("DIRTVEG5", 10);

define("URBAN1", 11);
define("URBAN2", 12);
define("URBAN3", 13);
define("URBAN4", 14);
define("URBAN5", 15);

define("VEG1", 16);
define("VEG2", 17);
define("VEG3", 18);
define("VEG4", 19);
define("VEG5", 20);

define("SEA", 21);


/**
 *  Map constants
 */

/*
define("X_LOCAL_MAP_SIZE", 6);
define("Y_LOCAL_MAP_SIZE", 14);
*/

define("X_LOCAL_MAP_SIZE", 7);
define("Y_LOCAL_MAP_SIZE", 20);

/**
 * Toxic levels
 */

define("TOXIC_CLEAN", 10);

/**
 * Land ownership
 */

define("NOT_OWNED", 0);
define("I_OWN", 1);
define("YOU_OWN", 2);

/**
 * Land availability
 */
define("NOT_AVAILABLE", 0);
define("AVAILABLE", 1);

/**
 * Times
 */
define("MOVE_TIME", 5);
define("EXPLORE_TIME", 10);
define("BUILD_TIME", 30);
define("UNIT_BUILD_TIME", 10);

/**
 * Defined action types
 */
define("UNDEFINED", 0);
define("MOVE", 1);
define("EXPLORE", 2);

/**
 * Defined game time unit in seconds
 */
define("GAME_TIME_UNIT", 60);

/**
 * Population definitions
 */
define("INIT_POPULATION", 10);
define("CIVILIANS_MAX", 20);
define("NATIVITY", 0.1);
define("INIT_EXPLORERS", 0);

/**
 * Treasury
 */
define("INIT_GOLD", 500);
define("INIT_TAX", 25);

/**
 * Toxic
 */
define("INIT_USER_TOXIC", 1);
define("DEFAULT_TOXIC", 9);

/**
 * Build operations
 */
define("B_UNDEFINED", 0);
define("B_CREATE", 1);
define("B_REMOVE", 2);

/**
 * Buildings
 */
define("MAX_BUILDINGS", 1);

/**
 * Logging
 */
define("DB_VERBOSE", 0);
?>
