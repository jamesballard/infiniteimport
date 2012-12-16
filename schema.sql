-- MySQL dump 10.13  Distrib 5.5.27, for osx10.8 (i386)
--
-- Host: localhost    Database: infinitecake
-- ------------------------------------------------------
-- Server version	5.5.27

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `acos`
--

DROP TABLE IF EXISTS `acos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `model` varchar(255) DEFAULT NULL,
  `foreign_key` int(11) DEFAULT NULL,
  `alias` varchar(255) DEFAULT NULL,
  `lft` int(11) DEFAULT NULL,
  `rght` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=255 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `action_by_user_day`
--

DROP TABLE IF EXISTS `action_by_user_day`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `action_by_user_day` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) unsigned DEFAULT NULL,
  `group` int(11) unsigned DEFAULT NULL,
  `module` varchar(20) DEFAULT NULL,
  `action` varchar(60) DEFAULT NULL,
  `time` int(11) unsigned DEFAULT NULL,
  `total` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `day_add_ix` (`user`,`group`,`module`,`action`,`time`),
  KEY `user_ix` (`user`,`time`),
  KEY `group_ix` (`group`,`time`),
  KEY `user_group_ix` (`user`,`group`,`time`),
  KEY `time_ix` (`time`)
) ENGINE=MyISAM AUTO_INCREMENT=2266103 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `action_by_user_hour`
--

DROP TABLE IF EXISTS `action_by_user_hour`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `action_by_user_hour` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) unsigned DEFAULT NULL,
  `group` int(11) unsigned DEFAULT NULL,
  `module` varchar(20) DEFAULT NULL,
  `action` varchar(60) DEFAULT NULL,
  `hour` bigint(2) unsigned DEFAULT NULL,
  `time` int(11) unsigned DEFAULT NULL,
  `total` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hour_add_ix` (`user`,`group`,`module`,`action`,`hour`,`time`),
  KEY `user_ix` (`user`,`hour`),
  KEY `group_ix` (`group`,`hour`),
  KEY `user_group_ix` (`user`,`group`,`hour`),
  KEY `hour_ix` (`hour`)
) ENGINE=MyISAM AUTO_INCREMENT=3099523 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `action_by_user_month`
--

DROP TABLE IF EXISTS `action_by_user_month`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `action_by_user_month` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) unsigned DEFAULT NULL,
  `group` int(11) unsigned DEFAULT NULL,
  `module` varchar(20) DEFAULT NULL,
  `action` varchar(60) DEFAULT NULL,
  `time` int(11) unsigned DEFAULT NULL,
  `total` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `month_add_ix` (`user`,`group`,`module`,`action`,`time`),
  KEY `user_ix` (`user`,`time`),
  KEY `group_ix` (`group`,`time`),
  KEY `user_group_ix` (`user`,`group`,`time`),
  KEY `time_ix` (`time`),
  KEY `user_module_ix` (`user`,`module`,`time`),
  KEY `group_module_ix` (`group`,`module`,`time`),
  KEY `module_ix` (`module`,`time`),
  KEY `action_ix` (`action`,`time`)
) ENGINE=MyISAM AUTO_INCREMENT=888727 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `action_by_user_week`
--

DROP TABLE IF EXISTS `action_by_user_week`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `action_by_user_week` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) unsigned DEFAULT NULL,
  `group` int(11) unsigned DEFAULT NULL,
  `module` varchar(20) DEFAULT NULL,
  `action` varchar(60) DEFAULT NULL,
  `year` bigint(4) unsigned DEFAULT NULL,
  `week` bigint(2) unsigned DEFAULT NULL,
  `total` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `week_add_ix` (`user`,`group`,`module`,`action`,`year`,`week`),
  KEY `user_ix` (`user`,`year`,`week`),
  KEY `group_ix` (`group`,`year`,`week`),
  KEY `user_group_ix` (`user`,`group`,`year`,`week`)
) ENGINE=MyISAM AUTO_INCREMENT=1490788 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `actions`
--

DROP TABLE IF EXISTS `actions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `actions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) DEFAULT NULL,
  `type` bigint(2) unsigned DEFAULT NULL,
  `time` int(11) unsigned DEFAULT NULL,
  `user_id` int(11) unsigned DEFAULT NULL,
  `group_id` int(11) unsigned DEFAULT NULL,
  `module_id` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `time_ix` (`time`),
  KEY `user_ix` (`user_id`),
  KEY `group_ix` (`group_id`),
  KEY `module_ix` (`module_id`),
  KEY `user_time_ix` (`user_id`,`time`),
  KEY `group_time_ix` (`group_id`,`time`),
  KEY `module_time_ix` (`module_id`,`time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `aggregrate_action_by_user` BEFORE INSERT ON `actions` FOR EACH ROW BEGIN
	DECLARE hour_id INTEGER;
	DECLARE hour_total INTEGER;
	DECLARE day_id INTEGER;
	DECLARE day_total INTEGER;
	DECLARE week_id INTEGER;
	DECLARE week_total INTEGER;
	DECLARE month_id INTEGER;
	DECLARE month_total INTEGER;
	
	SELECT id INTO hour_id
	  FROM action_by_user_hour
	  WHERE `user` = new.user_id AND
		`group` = new.group_id AND
		`module` = new.module_id AND
		`action` = new.name AND
		`time` = UNIX_TIMESTAMP(FROM_UNIXTIME(new.time, '%Y-%m-%d %H:00:00')) AND
                `hour` = FROM_UNIXTIME(new.time, '%H');	
        
        SELECT total INTO hour_total
	    FROM action_by_user_hour
	    WHERE `id` = hour_id;
                
        IF hour_total > 0 THEN 
	  UPDATE action_by_user_hour
	    SET total = hour_total+1
	    WHERE `id` = hour_id;
	
	ELSE
	  INSERT INTO action_by_user_hour
	    (`user`, `group`, `module`, `action`, `time`, `hour`, `total`)
	    VALUES (new.user_id, new.group_id, new.module_id, new.name, UNIX_TIMESTAMP(FROM_UNIXTIME(new.time, '%Y-%m-%d %H:00:00')), FROM_UNIXTIME(new.time, '%H'), 1);   
	END IF;
	
	SELECT id INTO day_id
	  FROM action_by_user_day
	  WHERE `user` = new.user_id AND
		`group` = new.group_id AND
		`module` = new.module_id AND
		`action` = new.name AND
		`time` = UNIX_TIMESTAMP(FROM_UNIXTIME(new.time, '%Y-%m-%d'));	
        
        SELECT total INTO day_total
	    FROM action_by_user_day
	    WHERE `id` = day_id;
                
        IF day_total > 0 THEN 
	  UPDATE action_by_user_day
	    SET total = day_total+1
	    WHERE `id` = day_id;
	
	ELSE
	  INSERT INTO action_by_user_day
	    (`user`, `group`, `module`, `action`, `time`, `total`)
	    VALUES (new.user_id, new.group_id, new.module_id, new.name, UNIX_TIMESTAMP(FROM_UNIXTIME(new.time, '%Y-%m-%d')), 1);   
	END IF;
	
	SELECT id INTO week_id
	  FROM action_by_user_week
	  WHERE `user` = new.user_id AND
		`group` = new.group_id AND
		`module` = new.module_id AND
		`action` = new.name AND
		`year` = FROM_UNIXTIME(new.time, '%x') AND
                `week` = FROM_UNIXTIME(new.time, '%v');	
        
        SELECT total INTO week_total
	    FROM action_by_user_week
	    WHERE `id` = week_id;
                
        IF week_total > 0 THEN 
	  UPDATE action_by_user_week
	    SET total = week_total+1
	    WHERE `id` = week_id;
	
	ELSE
	  INSERT INTO action_by_user_week
	    (`user`, `group`, `module`, `action`, `year`, `week`, `total`)
	    VALUES (new.user_id, new.group_id, new.module_id, new.name, FROM_UNIXTIME(new.time, '%x'), FROM_UNIXTIME(new.time, '%v'), 1);   
	END IF;
	
	SELECT id INTO month_id
	  FROM action_by_user_month
	  WHERE `user` = new.user_id AND
		`group` = new.group_id AND
		`module` = new.module_id AND
		`action` = new.name AND
		`time` = UNIX_TIMESTAMP(FROM_UNIXTIME(new.time, '%Y-%m-01'));	
        
        SELECT total INTO month_total
	    FROM action_by_user_month
	    WHERE `id` = month_id;
                
        IF month_total > 0 THEN 
	  UPDATE action_by_user_month
	    SET total = month_total+1
	    WHERE `id` = month_id;
	
	ELSE
	  INSERT INTO action_by_user_month
	    (`user`, `group`, `module`, `action`, `time`, `total`)
	    VALUES (new.user_id, new.group_id, new.module_id, new.name, UNIX_TIMESTAMP(FROM_UNIXTIME(new.time, '%Y-%m-01')), 1);   
	END IF;
	
    END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `aros`
--

DROP TABLE IF EXISTS `aros`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `aros` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `model` varchar(255) DEFAULT NULL,
  `foreign_key` int(11) DEFAULT NULL,
  `alias` varchar(255) DEFAULT NULL,
  `lft` int(11) DEFAULT NULL,
  `rght` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `aros_acos`
--

DROP TABLE IF EXISTS `aros_acos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `aros_acos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `aro_id` int(11) NOT NULL,
  `aco_id` int(11) NOT NULL,
  `_create` varchar(2) NOT NULL DEFAULT '0',
  `_read` varchar(2) NOT NULL DEFAULT '0',
  `_update` varchar(2) NOT NULL DEFAULT '0',
  `_delete` varchar(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ARO_ACO_KEY` (`aro_id`,`aco_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `artefacts`
--

DROP TABLE IF EXISTS `artefacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `artefacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idnumber` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `type` bigint(2) unsigned DEFAULT NULL,
  `community_id` int(11) unsigned DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `community_ix` (`community_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `communitys`
--

DROP TABLE IF EXISTS `communitys`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `communitys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idnumber` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `type` bigint(2) unsigned DEFAULT NULL,
  `customer_id` int(11) unsigned DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `customer_ix` (`customer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `conditions`
--

DROP TABLE IF EXISTS `conditions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `conditions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sysid` int(11) unsigned DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  `module_id` int(11) unsigned DEFAULT NULL,
  `group_id` int(11) unsigned DEFAULT NULL,
  `user_id` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `module_ix` (`module_id`),
  KEY `group_ix` (`group_id`),
  KEY `user_ix` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `zip` varchar(255) DEFAULT NULL,
  `lat` float DEFAULT NULL,
  `lon` float DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dirobjects`
--

DROP TABLE IF EXISTS `dirobjects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dirobjects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idnumber` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `artefact_id` int(11) unsigned DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `artefact_ix` (`artefact_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS `groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sysid` int(11) unsigned DEFAULT NULL,
  `idnumber` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `system_id` int(11) unsigned DEFAULT NULL,
  `community_id` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `community_ix` (`community_id`),
  KEY `system_ix` (`system_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `materials`
--

DROP TABLE IF EXISTS `materials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `materials` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sysid` int(11) unsigned DEFAULT NULL,
  `idnumber` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `module_id` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `module_ix` (`module_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `members`
--

DROP TABLE IF EXISTS `members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` char(40) NOT NULL,
  `membership_id` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `membership_ix` (`membership_id`),
  CONSTRAINT `membership` FOREIGN KEY (`membership_id`) REFERENCES `memberships` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `memberships`
--

DROP TABLE IF EXISTS `memberships`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `memberships` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `modules`
--

DROP TABLE IF EXISTS `modules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sysid` int(11) unsigned DEFAULT NULL,
  `idnumber` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `artefact_id` int(11) unsigned DEFAULT NULL,
  `group_id` int(11) unsigned DEFAULT NULL,
  `system_id` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `artefact_ix` (`artefact_id`),
  KEY `group_ix` (`group_id`),
  KEY `system_ix` (`system_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `moodle_course`
--

DROP TABLE IF EXISTS `moodle_course`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `moodle_course` (
  `system_id` int(11) NOT NULL,
  `id` bigint(10) unsigned NOT NULL,
  `fullname` varchar(254) NOT NULL DEFAULT '',
  `shortname` varchar(255) NOT NULL DEFAULT '',
  `idnumber` varchar(100) NOT NULL DEFAULT '',
  `startdate` bigint(10) unsigned NOT NULL DEFAULT '0',
  `visible` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`system_id`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `moodle_log`
--

DROP TABLE IF EXISTS `moodle_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `moodle_log` (
  `system_id` int(11) NOT NULL,
  `id` bigint(10) unsigned NOT NULL,
  `time` bigint(10) unsigned NOT NULL,
  `userid` bigint(10) unsigned NOT NULL,
  `ip` varchar(45) NOT NULL DEFAULT '',
  `course` bigint(10) unsigned NOT NULL DEFAULT '0',
  `module` varchar(20) NOT NULL DEFAULT '',
  `cmid` bigint(10) unsigned NOT NULL DEFAULT '0',
  `action` varchar(40) NOT NULL DEFAULT '',
  `url` varchar(100) NOT NULL DEFAULT '',
  `info` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`system_id`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `moodle_module`
--

DROP TABLE IF EXISTS `moodle_module`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `moodle_module` (
  `system_id` int(11) NOT NULL,
  `id` bigint(10) unsigned NOT NULL,
  `name` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`system_id`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `moodle_user`
--

DROP TABLE IF EXISTS `moodle_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `moodle_user` (
  `system_id` int(11) NOT NULL,
  `id` bigint(10) unsigned NOT NULL,
  `username` varchar(100) NOT NULL DEFAULT '',
  `idnumber` varchar(255) NOT NULL DEFAULT '',
  `firstname` varchar(100) NOT NULL DEFAULT '',
  `lastname` varchar(100) NOT NULL DEFAULT '',
  `institution` varchar(40) NOT NULL DEFAULT '',
  `department` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`system_id`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `persons`
--

DROP TABLE IF EXISTS `persons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `persons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idnumber` varchar(255) DEFAULT NULL,
  `firstname` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `dob` varchar(255) DEFAULT NULL,
  `nationality` varchar(255) DEFAULT NULL,
  `ethnicity` varchar(255) DEFAULT NULL,
  `disability` varchar(255) DEFAULT NULL,
  `customer_id` int(11) unsigned DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `customer_ix` (`customer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `positions`
--

DROP TABLE IF EXISTS `positions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `positions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idnumber` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `person_id` int(11) unsigned DEFAULT NULL,
  `community_id` int(11) unsigned DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `community_ix` (`community_id`),
  KEY `person_ix` (`person_id`),
  KEY `position_ix` (`person_id`,`community_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sysid` int(11) unsigned DEFAULT NULL,
  `idnumber` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `user_id` int(11) unsigned DEFAULT NULL,
  `group_id` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_ix` (`user_id`),
  KEY `group_ix` (`group_id`),
  KEY `role_ix` (`user_id`,`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rules`
--

DROP TABLE IF EXISTS `rules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  `artefact_id` int(11) unsigned DEFAULT NULL,
  `community_id` int(11) unsigned DEFAULT NULL,
  `person_id` int(11) unsigned DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `artefact_ix` (`artefact_id`),
  KEY `community_ix` (`community_id`),
  KEY `person_ix` (`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `systems`
--

DROP TABLE IF EXISTS `systems`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `systems` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` bigint(4) unsigned DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `customer_id` int(11) unsigned DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `certificate` text,
  `site_name` varchar(200) DEFAULT NULL,
  `contact_email` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `customer_ix` (`customer_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sysid` int(11) unsigned DEFAULT NULL,
  `idnumber` varchar(255) DEFAULT NULL,
  `person_id` int(11) unsigned DEFAULT NULL,
  `system_id` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `person_ix` (`person_id`),
  KEY `system_ix` (`system_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2012-12-16 15:50:48
