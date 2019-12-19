-- MySQL dump 10.17  Distrib 10.3.20-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: TweetsNPosts
-- ------------------------------------------------------
-- Server version	10.3.20-MariaDB-1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `Post`
--

DROP TABLE IF EXISTS `Post`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `author_id` int(11) DEFAULT NULL,
  `createdat` datetime NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_FAB8C3B3F675F31B` (`author_id`),
  CONSTRAINT `FK_FAB8C3B3F675F31B` FOREIGN KEY (`author_id`) REFERENCES `User` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3216 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Post`
--

LOCK TABLES `Post` WRITE;
/*!40000 ALTER TABLE `Post` DISABLE KEYS */;
INSERT INTO `Post` VALUES (3201,38,'2019-12-18 22:20:24','Title 5for user 1','Content 5 for user 1'),(3202,38,'2019-12-18 22:20:24','Title 4for user 1','Content 4 for user 1'),(3203,38,'2019-12-18 22:20:24','Title 3for user 1','Content 3 for user 1'),(3204,38,'2019-12-18 22:20:24','Title 2for user 1','Content 2 for user 1'),(3205,38,'2019-12-18 22:20:24','Title 1for user 1','Content 1 for user 1'),(3206,39,'2019-12-18 22:20:24','Title 4for user 2','Content 4 for user 2'),(3207,39,'2019-12-18 22:20:24','Title 3for user 2','Content 3 for user 2'),(3208,39,'2019-12-18 22:20:24','Title 2for user 2','Content 2 for user 2'),(3209,39,'2019-12-18 22:20:24','Title 1for user 2','Content 1 for user 2'),(3210,40,'2019-12-18 22:20:24','Title 3for user 3','Content 3 for user 3'),(3211,40,'2019-12-18 22:20:24','Title 2for user 3','Content 2 for user 3'),(3212,40,'2019-12-18 22:20:24','Title 1for user 3','Content 1 for user 3'),(3213,41,'2019-12-18 22:20:24','Title 2for user 4','Content 2 for user 4'),(3214,41,'2019-12-18 22:20:24','Title 1for user 4','Content 1 for user 4'),(3215,42,'2019-12-18 22:20:24','Title 1for user 5','Content 1 for user 5');
/*!40000 ALTER TABLE `Post` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `User`
--

DROP TABLE IF EXISTS `User`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `User` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `twitterid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_2DA17977F85E0677` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `User`
--

LOCK TABLES `User` WRITE;
/*!40000 ALTER TABLE `User` DISABLE KEYS */;
INSERT INTO `User` VALUES (37,'Name','testuser','$2y$14$g/CJdzMwsvUuuHTkMsTcmuimNruv4J8mgGkzucps2Cx9iQSgNL/nS','example@example.com','someid'),(38,'User 1','user1','$2y$14$0PngGhLWJUvLKmRuZkSDDOFcYp04jns5FonbIuz743JZ3vn37YptO','user1@example.com','user1'),(39,'User 2','user2','$2y$14$V2zgl6qY/GS86XmSVrAoWOoAttR6BmJ/TThTyl01M4xEuiqLgPMRa','user2@example.com','user2'),(40,'User 3','user3','$2y$14$aEdOJBaai.r3Op25cxTczOHNuEU0CkyrL83iiUui2t.X13BVbJlbK','user3@example.com','user3'),(41,'User 4','user4','$2y$14$j4KLb5pGXbYodPou7evs6Okv9ia2B1n.cT3cB.4ZeLpdiBnVef8xW','user4@example.com','user4'),(42,'User 5','user5','$2y$14$u3hfiPBWCItbmLQWAJKc7etFC5tEchc97T405ys44TyGEWObQJrpy','user5@example.com','user5');
/*!40000 ALTER TABLE `User` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migration_versions`
--

DROP TABLE IF EXISTS `migration_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migration_versions` (
  `version` varchar(14) COLLATE utf8mb4_unicode_ci NOT NULL,
  `executed_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migration_versions`
--

LOCK TABLES `migration_versions` WRITE;
/*!40000 ALTER TABLE `migration_versions` DISABLE KEYS */;
INSERT INTO `migration_versions` VALUES ('20191216174309','2019-12-19 00:20:21'),('20191219012937','2019-12-19 01:33:47');
/*!40000 ALTER TABLE `migration_versions` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-12-18 23:51:41
