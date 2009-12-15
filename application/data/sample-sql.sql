-- MySQL dump 10.13  Distrib 5.1.37, for debian-linux-gnu (i486)
--
-- Host: prepro    Database: bender
-- ------------------------------------------------------
-- Server version	5.0.67-0ubuntu6

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
-- Not dumping tablespaces as no INFORMATION_SCHEMA.FILES table on this server
--

--
-- Table structure for table `bender_persons`
--

DROP TABLE IF EXISTS `bender_persons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bender_persons` (
  `id_person` int(11) NOT NULL auto_increment,
  `first_name` varchar(100) collate latin1_spanish_ci NOT NULL COMMENT 'Nombre',
  `last_name` varchar(100) collate latin1_spanish_ci NOT NULL COMMENT 'apellidos',
  `birth_date` date NOT NULL COMMENT 'fecha de nacimiento',
  PRIMARY KEY  (`id_person`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bender_posts`
--

DROP TABLE IF EXISTS `bender_posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bender_posts` (
  `id_post` int(11) NOT NULL auto_increment,
  `id_user` int(11) NOT NULL,
  `title` varchar(150) collate latin1_spanish_ci NOT NULL COMMENT 'titulo del post',
  `content` text collate latin1_spanish_ci NOT NULL COMMENT 'Contenido del post',
  `slug` varchar(170) collate latin1_spanish_ci NOT NULL COMMENT 'slug, util para permalinks',
  PRIMARY KEY  (`id_post`),
  UNIQUE KEY `slug` (`slug`),
  UNIQUE KEY `title` (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bender_users`
--

DROP TABLE IF EXISTS `bender_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bender_users` (
  `id_user` int(11) NOT NULL auto_increment,
  `id_person` int(11) NOT NULL,
  `username` varchar(30) collate latin1_spanish_ci NOT NULL COMMENT 'Nombre de usuario',
  `password` varchar(60) collate latin1_spanish_ci NOT NULL COMMENT 'Password del usuario',
  `email` varchar(70) collate latin1_spanish_ci NOT NULL,
  PRIMARY KEY  (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bender_workers`
--

DROP TABLE IF EXISTS `bender_workers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bender_workers` (
  `id_worker` int(11) NOT NULL auto_increment,
  `id_person` int(11) NOT NULL COMMENT 'Id de la persona ',
  `hired_at` date NOT NULL COMMENT 'La fecha de contratación del trabajador',
  `salary` float NOT NULL default '10' COMMENT 'El sueldo del trabajador',
  PRIMARY KEY  (`id_worker`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2009-12-14 19:37:09
