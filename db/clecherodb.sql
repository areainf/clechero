-- MySQL dump 10.13  Distrib 5.5.41, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: clecherodb
-- ------------------------------------------------------
-- Server version	5.5.41-0ubuntu0.14.04.1

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
-- Current Database: `clecherodb`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `clecherodb` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `clecherodb`;

--
-- Table structure for table `cattle`
--

DROP TABLE IF EXISTS `cattle`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cattle` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `caravana` varchar(250) DEFAULT NULL,
  `dairy_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_cattle_dairies_idx` (`dairy_id`),
  CONSTRAINT `fk_cattle_dairies` FOREIGN KEY (`dairy_id`) REFERENCES `dairies` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=376 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cattle`
--

LOCK TABLES `cattle` WRITE;
/*!40000 ALTER TABLE `cattle` DISABLE KEYS */;
INSERT INTO `cattle` VALUES (1,'1',3),(2,'2',3),(3,'3',3),(4,'4',3),(5,'5',3),(6,'6',3),(7,'7',3),(8,'8',3),(9,'9',3),(10,'10',3),(11,'11',3),(12,'12',3),(13,'13',3),(14,'14',3),(15,'15',3),(16,'16',3),(17,'17',3),(18,'18',3),(19,'19',3),(20,'20',3),(21,'21',3),(22,'22',3),(23,'23',3),(24,'24',3),(25,'25',3),(26,'26',3),(27,'28',3),(28,'29',3),(29,'30',3),(30,'31',3),(31,'32',3),(32,'33',3),(33,'34',3),(34,'35',3),(35,'36',3),(36,'37',3),(37,'38',3),(38,'39',3),(39,'40',3),(40,'41',3),(41,'42',3),(42,'43',3),(43,'44',3),(44,'45',3),(45,'27',3),(46,'50',3),(47,'46',3),(48,'47',3),(49,'48',3),(50,'49',3),(51,'51',3),(52,'52',3),(53,'53',3),(54,'54',3),(55,'55',3),(56,'56',3),(57,'57',3),(58,'58',3),(59,'59',3),(60,'60',3),(61,'61',3),(62,'62',3),(63,'63',3),(64,'64',3),(65,'65',3),(66,'66',3),(67,'67',3),(68,'68',3),(69,'69',3),(70,'70',3),(71,'71',3),(72,'72',3),(73,'73',3),(74,'74',3),(75,'75',3),(76,'76',3),(77,'77',3),(78,'78',3),(79,'79',3),(80,'80',3),(81,'81',3),(82,'82',3),(83,'83',3),(84,'84',3),(85,'85',3),(86,'86',3),(87,'87',3),(88,'88',3),(89,'89',3),(90,'90',3),(91,'91',3),(92,'92',3),(93,'93',3),(94,'94',3),(95,'95',3),(96,'96',3),(97,'97',3),(98,'98',3),(99,'99',3),(100,'100',3),(101,'101',3),(102,'102',3),(103,'103',3),(104,'104',3),(105,'105',3),(106,'106',3),(107,'107',3),(108,'108',3),(109,'109',3),(110,'110',3),(111,'111',3),(112,'112',3),(113,'113',3),(114,'114',3),(115,'115',3),(116,'116',3),(117,'117',3),(118,'118',3),(119,'119',3),(120,'120',3),(121,'121',3),(122,'122',3),(123,'123',3),(124,'124',3),(125,'125',3),(126,'126',3),(127,'127',3),(128,'128',3),(129,'129',3),(130,'130',3),(131,'131',3),(132,'132',3),(133,'133',3),(134,'134',3),(135,'135',3),(136,'136',3),(137,'137',3),(138,'138',3),(139,'139',3),(140,'140',3),(141,'141',3),(142,'142',3),(143,'143',3),(144,'144',3),(145,'145',3),(146,'146',3),(147,'147',3),(148,'148',3),(149,'149',3),(150,'150',3),(151,'151',3),(152,'152',3),(153,'153',3),(154,'154',3),(155,'155',3),(156,'156',3),(157,'157',3),(158,'158',3),(159,'159',3),(160,'160',3),(161,'161',3),(162,'162',3),(163,'163',3),(164,'164',3),(165,'165',3),(166,'166',3),(167,'167',3),(168,'168',3),(169,'169',3),(170,'170',3),(171,'171',3),(172,'172',3),(173,'173',3),(175,'1',4),(176,'2',4),(177,'3',4),(178,'4',4),(179,'5',4),(180,'6',4),(181,'7',4),(182,'8',4),(183,'9',4),(184,'10',4),(185,'11',4),(186,'12',4),(187,'13',4),(188,'14',4),(189,'15',4),(190,'16',4),(191,'17',4),(192,'18',4),(193,'19',4),(194,'20',4),(195,'21',4),(196,'22',4),(197,'23',4),(198,'24',4),(199,'25',4),(200,'26',4),(201,'28',4),(202,'29',4),(203,'30',4),(204,'31',4),(205,'32',4),(206,'33',4),(207,'34',4),(208,'35',4),(209,'36',4),(210,'37',4),(211,'38',4),(212,'39',4),(213,'40',4),(214,'41',4),(215,'42',4),(216,'43',4),(217,'44',4),(218,'45',4),(219,'46',4),(220,'47',4),(221,'48',4),(222,'49',4),(223,'50',4),(224,'51',4),(225,'52',4),(226,'53',4),(227,'54',4),(228,'55',4),(229,'56',4),(230,'57',4),(231,'58',4),(232,'59',4),(233,'60',4),(234,'61',4),(235,'62',4),(236,'63',4),(237,'64',4),(238,'65',4),(239,'66',4),(240,'67',4),(241,'68',4),(242,'69',4),(243,'70',4),(244,'71',4),(245,'72',4),(246,'73',4),(247,'74',4),(248,'75',4),(249,'76',4),(250,'77',4),(251,'78',4),(252,'79',4),(253,'80',4),(254,'81',4),(255,'82',4),(256,'83',4),(257,'84',4),(258,'85',4),(259,'86',4),(260,'87',4),(261,'88',4),(262,'89',4),(263,'90',4),(264,'91',4),(265,'92',4),(266,'93',4),(267,'94',4),(268,'95',4),(269,'96',4),(270,'97',4),(271,'98',4),(272,'99',4),(273,'100',4),(274,'101',4),(275,'102',4),(276,'103',4),(277,'104',4),(278,'105',4),(279,'106',4),(280,'107',4),(281,'108',4),(282,'109',4),(283,'110',4),(284,'111',4),(285,'112',4),(286,'113',4),(287,'114',4),(288,'115',4),(289,'116',4),(290,'117',4),(291,'118',4),(292,'119',4),(293,'120',4),(294,'121',4),(295,'122',4),(296,'123',4),(297,'124',4),(298,'125',4),(299,'126',4),(300,'127',4),(301,'128',4),(302,'129',4),(303,'130',4),(304,'131',4),(305,'132',4),(306,'133',4),(307,'134',4),(308,'135',4),(309,'136',4),(310,'137',4),(311,'138',4),(312,'139',4),(313,'140',4),(314,'141',4),(315,'142',4),(316,'143',4),(317,'144',4),(318,'145',4),(319,'146',4),(320,'147',4),(321,'148',4),(322,'149',4),(323,'150',4),(324,'151',4),(325,'152',4),(326,'153',4),(327,'154',4),(328,'155',4),(329,'156',4),(330,'157',4),(331,'158',4),(332,'159',4),(333,'160',4),(334,'161',4),(335,'162',4),(336,'163',4),(337,'164',4),(338,'165',4),(339,'166',4),(340,'167',4),(341,'168',4),(342,'169',4),(343,'170',4),(344,'171',4),(345,'172',4),(346,'173',4),(347,'1',10),(348,'2',10),(349,'3',10),(350,'4',10),(351,'5',10),(352,'6',10),(353,'7',10),(354,'8',10),(355,'9',10),(356,'10',10),(357,'11',10),(358,'12',10),(359,'13',10),(360,'14',10),(361,'15',10),(362,'16',10),(363,'17',10),(364,'18',10),(365,'19',10),(366,'20',10),(367,'21',10),(368,'22',10),(369,'23',10),(370,'24',10),(371,'25',10),(372,'26',10),(373,'28',10),(374,'29',10),(375,'30',10);
/*!40000 ALTER TABLE `cattle` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dairies`
--

DROP TABLE IF EXISTS `dairies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dairies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) DEFAULT NULL,
  `email` varchar(250) DEFAULT NULL,
  `phone` varchar(250) DEFAULT NULL,
  `owner_id` int(11) DEFAULT NULL,
  `veterinary_id` int(11) DEFAULT NULL,
  `location` tinytext,
  `industry` varchar(250) CHARACTER SET big5 DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dairies`
--

LOCK TABLES `dairies` WRITE;
/*!40000 ALTER TABLE `dairies` DISABLE KEYS */;
INSERT INTO `dairies` VALUES (3,'Tambito','','',4,10,'Sur de la provincia de cordoba, camino rural n° 3','SANCOR'),(4,'Manchita','','',5,2,NULL,NULL),(5,'Loquito','e@cd.exa','454545555',6,3,NULL,NULL),(8,'La espiga central','','45456456',4,2,NULL,NULL),(9,'El Tambero','','',4,9,NULL,NULL),(10,'JOOOOOO','','',4,10,NULL,NULL);
/*!40000 ALTER TABLE `dairies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dairy_control`
--

DROP TABLE IF EXISTS `dairy_control`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dairy_control` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nop` int(11) DEFAULT NULL COMMENT 'Numero ordinal de parto, 1 o 2 y mas',
  `dl` int(11) DEFAULT NULL COMMENT 'Dia Lactancia, cuanto hace que pario',
  `rcs` int(11) DEFAULT NULL,
  `mc` tinyint(1) DEFAULT NULL,
  `liters_milk` int(11) DEFAULT NULL,
  `cow_id` int(11) NOT NULL,
  `schema_id` int(11) NOT NULL,
  `date_dl` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_dairy_control_cattle1_idx` (`cow_id`),
  KEY `fk_dairy_control_schema1_idx` (`schema_id`),
  CONSTRAINT `fk_dairy_control_schema1` FOREIGN KEY (`schema_id`) REFERENCES `schema_controls` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_dairy_control_cattle1` FOREIGN KEY (`cow_id`) REFERENCES `cattle` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=1326 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dairy_control`
--

LOCK TABLES `dairy_control` WRITE;
/*!40000 ALTER TABLE `dairy_control` DISABLE KEYS */;
INSERT INTO `dairy_control` VALUES (1297,33,143,2070,0,100,1,58,'2015-02-25'),(1298,1,69,881,0,101,2,58,'2015-02-25'),(1299,33,88,251,0,102,3,58,'2015-02-24'),(1300,33,418,178,0,103,4,58,'2015-02-24'),(1301,33,255,541,0,104,5,58,'2015-02-25'),(1302,33,88,216,0,105,6,58,'2015-02-25'),(1303,33,184,1162,0,106,7,58,'2015-02-24'),(1304,33,224,415,0,107,8,58,'2015-02-24'),(1305,1,110,180,0,108,9,58,'2015-02-25'),(1306,1,256,387,0,109,10,58,'2015-02-25'),(1307,33,48,382,0,110,11,58,'2015-02-24'),(1308,33,148,136,0,111,12,58,'2015-02-24'),(1309,33,260,940,0,112,13,58,'2015-02-25'),(1310,33,129,491,0,113,14,58,'2015-02-25'),(1311,33,181,659,0,114,15,58,'2015-02-24'),(1312,33,221,325,0,115,16,58,'2015-02-24'),(1313,33,238,568,0,116,17,58,'2015-02-25'),(1314,1,114,177,0,117,18,58,'2015-02-25'),(1315,1,223,93,0,118,19,58,'2015-02-24'),(1316,33,279,2027,0,119,20,58,'2015-02-24'),(1317,1,222,505,0,120,21,58,'2015-02-25'),(1318,33,67,638,0,121,22,58,'2015-02-25'),(1319,1,226,724,0,122,23,58,'2015-02-24'),(1320,1,117,167,0,123,24,58,'2015-02-24'),(1321,1,0,198,0,124,25,58,'2015-02-25'),(1322,1,248,144,0,125,26,58,'2015-02-25'),(1323,1,47,366,0,126,27,58,'2015-02-24'),(1324,33,229,478,0,127,28,58,'2015-02-24'),(1325,33,112,107,0,128,29,58,'2015-02-24');
/*!40000 ALTER TABLE `dairy_control` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `people`
--

DROP TABLE IF EXISTS `people`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `people` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(250) DEFAULT NULL,
  `first_name` varchar(250) DEFAULT NULL,
  `last_name` varchar(250) DEFAULT NULL,
  `email` varchar(250) DEFAULT NULL,
  `phone` varchar(250) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `people`
--

LOCK TABLES `people` WRITE;
/*!40000 ALTER TABLE `people` DISABLE KEYS */;
INSERT INTO `people` VALUES (2,'veterinary','Sergio','Gomez','sgomez@gmail.com','0358-4646553',4),(3,'veterinary','Marcos','Lucero','','',4),(4,'owner','Marco','Florit','','',NULL),(5,'owner','Nicolás','Cellucci','','',NULL),(6,'owner','Ezequiel','Ortiz','','',NULL),(7,'owner','Mariano','Luchini','','',NULL),(8,'owner','Emilio','Molinuevo','','',NULL),(9,'veterinary','Juan Carlos','Zapata','','',4),(10,'veterinary','pedro','ortiz','','',4);
/*!40000 ALTER TABLE `people` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `schema_controls`
--

DROP TABLE IF EXISTS `schema_controls`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `schema_controls` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sealant_price` decimal(10,2) DEFAULT '0.00',
  `sealant_treatment_days` int(11) DEFAULT '0',
  `abmc_treatment_price` decimal(10,2) DEFAULT '0.00',
  `abmc_treatment_days` int(11) DEFAULT '0',
  `abs_treatment_price` decimal(10,2) DEFAULT '0.00',
  `machine_control_price` decimal(10,2) DEFAULT '0.00',
  `machine_control_days` int(11) DEFAULT '0',
  `liters_milk` int(11) DEFAULT NULL,
  `dairy_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `milk_price` decimal(10,2) DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `fk_schema_dairies1_idx` (`dairy_id`),
  CONSTRAINT `fk_schema_dairies1` FOREIGN KEY (`dairy_id`) REFERENCES `dairies` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `schema_controls`
--

LOCK TABLES `schema_controls` WRITE;
/*!40000 ALTER TABLE `schema_controls` DISABLE KEYS */;
INSERT INTO `schema_controls` VALUES (55,10.00,10,120.00,0,232.00,11.00,12,5110,4,'2015-03-19',0.00),(58,0.00,0,0.00,0,0.00,0.00,0,3306,10,'2015-03-10',10.00);
/*!40000 ALTER TABLE `schema_controls` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `disable` tinyint(1) DEFAULT '0',
  `role` int(11) DEFAULT '1',
  `person_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username_UNIQUE` (`username`),
  KEY `fk_users_1_idx` (`person_id`),
  CONSTRAINT `fk_users_1` FOREIGN KEY (`person_id`) REFERENCES `people` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (5,'mmarozzi',NULL,'2bTHm3i5rgfYo',0,1,NULL),(8,'mflorit','mflorit@gmail.com','2bTHm3i5rgfYo',0,2,4),(9,'ncellucci','nicocelluccio@gmail.com','2b9z3QeiieEa2',0,2,5);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-03-11 10:08:28
