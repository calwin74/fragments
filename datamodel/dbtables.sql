--
-- Definition of table `active_guests`
--

DROP TABLE IF EXISTS `active_guests`;
CREATE TABLE `active_guests` (
  `ip` varchar(15) NOT NULL,
  `timestamp` int(11) unsigned NOT NULL,
  PRIMARY KEY  (`ip`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Definition of table `active_users`
--

DROP TABLE IF EXISTS `active_users`;
CREATE TABLE `active_users` (
  `username` varchar(30) NOT NULL,
  `timestamp` int(11) unsigned NOT NULL,
  PRIMARY KEY  (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


--
-- Definition of table `banned_users`
--

DROP TABLE IF EXISTS `banned_users`;
CREATE TABLE `banned_users` (
  `username` varchar(30) NOT NULL,
  `timestamp` int(11) unsigned NOT NULL,
  PRIMARY KEY  (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Definition of table `lands`
--

DROP TABLE IF EXISTS `lands`;
CREATE TABLE `lands` (
  `x` int(10) NOT NULL default '0',
  `y` int(10) NOT NULL default '0',
  `x_hex` int(10) NOT NULL default '0',
  `y_hex` int(10) NOT NULL default '0',
  `type` int(10) NOT NULL default '0',
  `owner` varchar(30) default NULL,
  `toxic` int(10) NOT NULL default '0',
  UNIQUE KEY `Index_1` (`x`,`y`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Definition of table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `username` varchar(30) NOT NULL,
  `password` varchar(32) default NULL,
  `userid` varchar(32) default NULL,
  `userlevel` tinyint(1) unsigned NOT NULL,
  `email` varchar(50) default NULL,
  `timestamp` int(11) unsigned NOT NULL,
  `init` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY  (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Definition of table `characters`
--

DROP TABLE IF EXISTS `characters`;
CREATE TABLE `characters` (
  `name` varchar(30) NOT NULL,
  `username` varchar(30) default NULL,
  `tribe` varchar(30) default NULL,
  `x` int(10) NOT NULL default '0',
  `y` int(10) NOT NULL default '0',
  `soldiers` int(10) NOT NULL default '0',
  `explorers` int(10) NOT NULL default '0',
  `home_x` int(10) NOT NULL default '0',
  `home_y` int(10) NOT NULL default '0',
  PRIMARY KEY  (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Definition of table `tribe`
--

DROP TABLE IF EXISTS `tribe`;
CREATE TABLE `tribe` (
  `name` varchar(30) NOT NULL,
  PRIMARY KEY  (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `tribe` (`name`) VALUES 
 ("Eastern Empire"),
 ("Western Empire"),
 ("Rebels"),
 ("Mutants");

--
-- Definition of table `move_queue`
--

DROP TABLE IF EXISTS `action_queue`;
CREATE TABLE `action_queue` (
  `name` varchar(30) default NULL,
  `x` int(10) NOT NULL default '0',
  `y` int(10) NOT NULL default '0',
  `due_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `type` int(10) unsigned NOT NULL default '0',
  `add_info` int(10) unsigned NOT NULL default '0',
  UNIQUE KEY `Index_1` (`name`,`x`,`y`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Definition of table `treasury`
--

DROP TABLE IF EXISTS `treasury`;
CREATE TABLE `treasury` (
  `character_name` varchar(30) default NULL,
  `gold` double unsigned NOT NULL default '0',
  `gold_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `tax` double unsigned NOT NULL default '0',
  UNIQUE KEY `Index_1` (`character_name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Definition of table `building_types`
--

DROP TABLE IF EXISTS `building_types`;
CREATE TABLE `building_types` (
  `type` varchar(30) NOT NULL,
  `cost` int(10) NOT NULL default 1,
  PRIMARY KEY  (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `building_types` (`type`,`cost`) VALUES 
 ("barrack",50),
 ("cityhall",0),
 ("bunker",25);
 ("factory", 50);

--
-- Definition of table `buildings`
--

DROP TABLE IF EXISTS `buildings`;
CREATE TABLE `buildings` (
  `type` varchar(30) NOT NULL,
  `x` int(10) NOT NULL default '0',
  `y` int(10) NOT NULL default '0',
  `constructing` int(10) NOT NULL default 1,
  `removing` int(10) NOT NULL default 0,
  UNIQUE KEY `Index_1` (`x`,`y`, `type`)  
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Definition of table `build_queue`
--

DROP TABLE IF EXISTS `build_queue`;
CREATE TABLE `build_queue` (
  `name` varchar(30) default NULL,
  `x` int(10) NOT NULL default '0',
  `y` int(10) NOT NULL default '0',
  `due_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `type` varchar(30) NOT NULL,
  `action` int(10) unsigned NOT NULL default '0',
  UNIQUE KEY `Index_1` (`name`,`x`,`y`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Definition of table `unit_queue`
--

DROP TABLE IF EXISTS `unit_queue`;
CREATE TABLE `unit_queue` (
  `name` varchar(30) default NULL,
  `x` int(10) NOT NULL default '0',
  `y` int(10) NOT NULL default '0',
  `due_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `type` varchar(30) NOT NULL,
  UNIQUE KEY `Index_1` (`name`,`x`,`y`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Definition of table `unit_types`
--

DROP TABLE IF EXISTS `unit_types`;
CREATE TABLE `unit_types` (
  `type` varchar(30) NOT NULL,
  `cost` int(10) NOT NULL default 1,
  `upkeep` int(10) NOT NULL default 1,
  `building` varchar(30) default NULL,  
  PRIMARY KEY  (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `unit_types` (`type`,`cost`,`upkeep`,`building`) VALUES 
 ("soldier",10,1,"barrack"),
 ("explorer",10,1,"cityhall");

--
-- Definition of table `garrison`
--

DROP TABLE IF EXISTS `garrison`;
CREATE TABLE `garrison` (
  `name` varchar(30) default NULL,
  `soldiers` int(10) NOT NULL default '0',
  PRIMARY KEY  (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Definition of table `population`
--
DROP TABLE IF EXISTS `population`;
  `owner` varchar(30) default NULL,
  `civilians` double unsigned NOT NULL default '0',
  `civilians_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `explorers` int(10) NOT NULL default '0',
  UNIQUE KEY `Index_1` (`owner`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;