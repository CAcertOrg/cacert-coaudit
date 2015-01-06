CREATE DATABASE  IF NOT EXISTS `coauditdb` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `coauditdb`;
-- MySQL dump 10.13  Distrib 5.6.11, for Win32 (x86)
--
-- Host: localhost    Database: coauditdb
-- ------------------------------------------------------
-- Server version	5.5.38-0+wheezy1

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
-- Table structure for table `cacertuser`
--

DROP TABLE IF EXISTS `cacertuser`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cacertuser` (
  `cacertuser_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `primaryemail` text NOT NULL,
  `webdb_account_id` bigint(20) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `assurer` tinyint(1) NOT NULL,
  `expierencepoints` int(11) NOT NULL,
  `country` varchar(2) NOT NULL,
  `created_by` int(11) NOT NULL,
  `location` text NOT NULL,
  `coauditdate` date NOT NULL,
  `active` int(11) NOT NULL,
  `deleted` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) NOT NULL,
  PRIMARY KEY (`cacertuser_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `coaudit_refdata`
--

DROP TABLE IF EXISTS `coaudit_refdata`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `coaudit_refdata` (
  `coaudit_refdata_id` int(11) NOT NULL AUTO_INCREMENT,
  `coaudit_session_id` int(11) NOT NULL,
  `session_year` int(11) NOT NULL,
  `assurances` int(11) NOT NULL,
  `target` decimal(10,3) NOT NULL,
  PRIMARY KEY (`coaudit_refdata_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `coauditor`
--

DROP TABLE IF EXISTS `coauditor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `coauditor` (
  `coauditor_id` int(11) NOT NULL AUTO_INCREMENT,
  `coauditor_name` text NOT NULL,
  `email` text NOT NULL,
  `read_permission` int(11) NOT NULL,
  `write_permission` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` int(11) NOT NULL,
  `last_change` timestamp NULL DEFAULT NULL,
  `last_change_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`coauditor_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `coauditor`
--

LOCK TABLES `coauditor` WRITE;
/*!40000 ALTER TABLE `coauditor` DISABLE KEYS */;
INSERT INTO `coauditor` VALUES (1,'administrator','needs@replacement.com',15,15,'2014-05-25 16:50:43',1,NULL,NULL);
/*!40000 ALTER TABLE `coauditor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `coauditsession`
--

DROP TABLE IF EXISTS `coauditsession`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `coauditsession` (
  `session_id` int(11) NOT NULL AUTO_INCREMENT,
  `session_name` text NOT NULL,
  `from` date NOT NULL,
  `to` date DEFAULT NULL,
  `default` tinyint(4) DEFAULT '0',
  `active` int(11) NOT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `coauditsession`
--

LOCK TABLES `coauditsession` WRITE;
/*!40000 ALTER TABLE `coauditsession` DISABLE KEYS */;
INSERT INTO `coauditsession` VALUES (1,'2009','0000-00-00','2009-12-31',0,1),(2,'2010','2010-01-01','0000-00-00',1,1),(3,'Test','0000-00-00','0000-00-00',0,1);
/*!40000 ALTER TABLE `coauditsession` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `result`
--

DROP TABLE IF EXISTS `result`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `result` (
  `result_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `session_topic_id` int(11) NOT NULL,
  `coauditsession_id` int(11) NOT NULL,
  `cacertuser_id` bigint(20) NOT NULL,
  `coauditor_id` int(11) NOT NULL,
  `result` tinyint(1) NOT NULL,
  `comment` text,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `active` int(11) NOT NULL,
  `deleted` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) NOT NULL,
  PRIMARY KEY (`result_id`),
  KEY `session_topic_id` (`session_topic_id`,`coauditsession_id`,`cacertuser_id`,`coauditor_id`),
  KEY `coauditsession_id` (`coauditsession_id`),
  KEY `cacertuser_id` (`cacertuser_id`),
  KEY `coauditor_id` (`coauditor_id`),
  CONSTRAINT `result_ibfk_2` FOREIGN KEY (`coauditsession_id`) REFERENCES `coauditsession` (`session_id`),
  CONSTRAINT `result_ibfk_3` FOREIGN KEY (`session_topic_id`) REFERENCES `session_topic` (`session_topic_id`),
  CONSTRAINT `result_ibfk_4` FOREIGN KEY (`cacertuser_id`) REFERENCES `cacertuser` (`cacertuser_id`),
  CONSTRAINT `result_ibfk_5` FOREIGN KEY (`coauditor_id`) REFERENCES `coauditor` (`coauditor_id`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;



DROP TABLE IF EXISTS `session_topic`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `session_topic` (
  `session_topic_id` int(11) NOT NULL AUTO_INCREMENT,
  `session_topic` text NOT NULL,
  `topic_explaination` text NOT NULL,
  `activ` int(11) NOT NULL,
  PRIMARY KEY (`session_topic_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `session_topics`
--

DROP TABLE IF EXISTS `session_topics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `session_topics` (
  `session_topics_id` int(11) NOT NULL AUTO_INCREMENT,
  `session_topic_id` int(11) NOT NULL,
  `coaudit_session_id` int(11) NOT NULL,
  `topic_no` int(11) NOT NULL,
  `active` int(11) NOT NULL,
  PRIMARY KEY (`session_topics_id`),
  KEY `session_topic_id` (`session_topic_id`,`coaudit_session_id`),
  KEY `coaudit_session_id` (`coaudit_session_id`),
  CONSTRAINT `session_topics_ibfk_1` FOREIGN KEY (`session_topic_id`) REFERENCES `session_topic` (`session_topic_id`),
  CONSTRAINT `session_topics_ibfk_2` FOREIGN KEY (`coaudit_session_id`) REFERENCES `coauditsession` (`session_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `view_rights`
--

DROP TABLE IF EXISTS `view_rights`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `view_rights` (
  `view_rigths_id` int(11) NOT NULL AUTO_INCREMENT,
  `view_name` text NOT NULL,
  `read_permission` int(11) NOT NULL,
  `write_permission` int(11) NOT NULL,
  `active` int(11) NOT NULL,
  PRIMARY KEY (`view_rigths_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `view_rights`
--

LOCK TABLES `view_rights` WRITE;
/*!40000 ALTER TABLE `view_rights` DISABLE KEYS */;
INSERT INTO `view_rights` VALUES (1,'user',26,8,1),(2,'userlist',26,8,1),(3,'result',18,18,1),(4,'view',26,24,1),(5,'viewlist',26,26,1),(6,'session',30,12,1),(7,'sessionlist',14,12,1),(8,'sessiontopiclist',30,12,1),(9,'sessiontopic',30,12,1),(10,'topic',30,12,1),(11,'topiclist',30,12,1),(12,'resultlist',26,0,1),(13,'adminmenue',28,20,1),(14,'test',16,0,0),(15,'kpilist',28,28,1),(16,'kpi',28,28,1);
/*!40000 ALTER TABLE `view_rights` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-01-06 10:12:55
