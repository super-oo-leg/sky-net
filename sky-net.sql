-- MySQL dump 10.13  Distrib 5.5.38, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: sky-net
-- ------------------------------------------------------
-- Server version	5.5.38-0ubuntu0.14.04.1

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
-- Table structure for table `friends`
--

DROP TABLE IF EXISTS `friends`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `friends` (
  `user` varchar(16) NOT NULL,
  `friend` varchar(16) NOT NULL,
  KEY `user` (`user`(6)),
  KEY `friend` (`friend`(6))
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `friends`
--

LOCK TABLES `friends` WRITE;
/*!40000 ALTER TABLE `friends` DISABLE KEYS */;
INSERT INTO `friends` VALUES ('Roman','OSMIUM'),('Roman','Elvis'),('OSMIUM','Elvis');
/*!40000 ALTER TABLE `friends` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `members`
--

DROP TABLE IF EXISTS `members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `members` (
  `user` varchar(16) NOT NULL,
  `pass` char(40) NOT NULL,
  `ip` varchar(30) NOT NULL,
  `register_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user`),
  UNIQUE KEY `user_2` (`user`),
  KEY `user` (`user`(6))
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `members`
--

LOCK TABLES `members` WRITE;
/*!40000 ALTER TABLE `members` DISABLE KEYS */;
INSERT INTO `members` VALUES ('Elvis','eb3f489cdffa25e80a711d1152bef1f5d1af993c','127.0.0.1','2014-07-11 17:55:28'),('OSMIUM','0874a5f6891e5ef990717f049a71b5883059adf8','127.0.0.1','0000-00-00 00:00:00'),('Roman','0874a5f6891e5ef990717f049a71b5883059adf8','127.0.0.1','0000-00-00 00:00:00');
/*!40000 ALTER TABLE `members` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `messages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `auth` varchar(16) NOT NULL,
  `recip` varchar(16) NOT NULL,
  `pm` char(1) NOT NULL,
  `time` int(10) unsigned NOT NULL,
  `message` varchar(1250) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `auth` (`auth`(6)),
  KEY `recip` (`recip`(6))
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messages`
--

LOCK TABLES `messages` WRITE;
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
INSERT INTO `messages` VALUES (3,'Roman','Roman','1',1405090966,'Little private message'),(4,'Roman','Roman','0',1405090975,'Little public message'),(7,'OSMIUM','OSMIUM','1',1405100958,'I\'m not exactly made of money!'),(8,'OSMIUM','Roman','1',1405101076,'Why you berrely have no messages yet?'),(9,'Elvis','Elvis','0',1405103870,'I like to fuck the dogs'),(10,'Elvis','OSMIUM','0',1405103885,'Wazzzup, man! How is it going?'),(11,'Elvis','Elvis','1',1405104088,'I like when red dog sucks my dick'),(12,'Elvis','OSMIUM','1',1405104123,'Rent me a car please, I\'m pregnant!'),(13,'OSMIUM','Elvis','0',1405321663,'Hi, Dude!'),(14,'OSMIUM','Elvis','1',1405322606,'hello'),(16,'OSMIUM','Elvis','0',1405326318,'Hey'),(21,'Elvis','Elvis','0',1405337989,'how'),(22,'OSMIUM','OSMIUM','1',1405338034,'Test');
/*!40000 ALTER TABLE `messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `profiles`
--

DROP TABLE IF EXISTS `profiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `profiles` (
  `user` varchar(16) NOT NULL DEFAULT '',
  `text` varchar(2000) DEFAULT NULL,
  `firstname` varchar(20) DEFAULT NULL,
  `lastname` varchar(20) DEFAULT NULL,
  `score` smallint(5) unsigned NOT NULL DEFAULT '0',
  `last_visit` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `time_offset` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user`),
  UNIQUE KEY `user_2` (`user`),
  KEY `user` (`user`(6))
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `profiles`
--

LOCK TABLES `profiles` WRITE;
/*!40000 ALTER TABLE `profiles` DISABLE KEYS */;
INSERT INTO `profiles` VALUES ('Elvis','I have fat ass =)','All-Ex-Say','Grand King',100,'2014-07-14 11:39:51',180),('OSMIUM','Physical abuse is an act of another party involving contact intended to cause feelings of physical pain, injury, or other physical suffering or bodily harm. Physical abuse has been described among animals too, for example among the Ad&eacute;lie penguins. In most cases, children are the victims of physical abuse, but adults can be the sufferers too. Physically abused children are at risk for later interpersonal problems involving aggressive behavior, and adolescents are at a much greater risk for substance abuse.','Johny','Good',333,'2014-09-30 12:34:53',180),('Roman','I\'m a pretty cool guy. Kiev is not a capital of Russia, you stoopid people!','Bilbo','Baggins',444,'2014-07-11 17:46:42',0);
/*!40000 ALTER TABLE `profiles` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-10-01 10:20:11
