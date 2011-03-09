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
  `type` int(10) NOT NULL default '0',
  `yield` int(10) NOT NULL default '0',
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
  `code` int(10) NOT NULL default '0',
  PRIMARY KEY  (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Definition of table `resources`
--

DROP TABLE IF EXISTS `resources`;
CREATE TABLE `resources` (
  `character_name` varchar(30) default NULL,
  `production` int(10) NOT NULL default '0',
  `production_growth` double unsigned NOT NULL default '0',
  `due_time` datetime NOT NULL default '0000-00-00 00:00:00',
  UNIQUE KEY `Index_1` (`character_name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Definition of table `tribe`
--

DROP TABLE IF EXISTS `tribe`;
CREATE TABLE `tribe` (
  `name` varchar(30) NOT NULL,
  PRIMARY KEY  (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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