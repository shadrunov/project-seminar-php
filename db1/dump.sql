-- MariaDB dump 10.19  Distrib 10.6.5-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: lms
-- ------------------------------------------------------
-- Server version	10.6.5-MariaDB-1:10.6.5+maria~focal

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
-- Table structure for table `Courses`
--

DROP TABLE IF EXISTS `Courses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Courses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `department` int(11) DEFAULT NULL,
  `faculty` int(11) DEFAULT NULL,
  `credits` int(11) DEFAULT NULL,
  `is_elective` tinyint(1) DEFAULT NULL,
  `first_module` int(11) DEFAULT NULL,
  `last_module` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `department` (`department`),
  KEY `faculty` (`faculty`),
  CONSTRAINT `Courses_ibfk_1` FOREIGN KEY (`department`) REFERENCES `Department` (`id`),
  CONSTRAINT `Courses_ibfk_2` FOREIGN KEY (`faculty`) REFERENCES `Faculty` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Courses`
--

LOCK TABLES `Courses` WRITE;
/*!40000 ALTER TABLE `Courses` DISABLE KEYS */;
INSERT INTO `Courses` VALUES (1,'History',11,3,4,0,1,1),(2,'Safe Living Basics',1,2,1,0,1,1),(3,'English for General Communication Purposes. Advanced Course - 1',1,2,3,1,1,2),(4,'Computer workshop: administration of systems and networks',1,2,2,0,1,4),(5,'Computer Science ',1,2,4,0,1,2),(6,'Calculus',2,2,5,0,1,4),(7,'Algorithmization and Programming',3,2,5,0,1,4),(8,'Physics',2,2,6,0,2,4),(9,'Economics of “Green” Innovations: from Theory to Practice',3,2,3,1,1,3),(10,'Project seminar on information security ',2,2,4,0,1,4),(11,'Project Seminar 1',1,2,2,0,3,4),(12,'Physical Training',1,2,4,0,1,4),(13,'English for General Communication Purposes. Advanced Course - 2 ',2,2,1,1,3,4),(14,'English Language Integrative Exam',3,2,2,0,4,4),(15,'Discrete Mathematics',2,2,3,0,3,4),(16,'Algebra and Geometry ',2,2,4,0,3,4);
/*!40000 ALTER TABLE `Courses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Department`
--

DROP TABLE IF EXISTS `Department`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Department` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `faculty_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `faculty_id` (`faculty_id`),
  CONSTRAINT `Department_ibfk_1` FOREIGN KEY (`faculty_id`) REFERENCES `Faculty` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Department`
--

LOCK TABLES `Department` WRITE;
/*!40000 ALTER TABLE `Department` DISABLE KEYS */;
INSERT INTO `Department` VALUES (1,'dei',2),(2,'dpm',2),(3,'dki',2),(4,'dpi',1),(5,'dadii',1),(6,'dbdiii',1),(7,'dpe',4),(8,'dte',4),(9,'dm',4),(10,'dsad',4),(11,'media',3);
/*!40000 ALTER TABLE `Department` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Entrance`
--

DROP TABLE IF EXISTS `Entrance`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Entrance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dt` datetime DEFAULT NULL,
  `inside` tinyint(1) DEFAULT NULL,
  `student` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `student` (`student`),
  CONSTRAINT `Entrance_ibfk_1` FOREIGN KEY (`student`) REFERENCES `Students` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Entrance`
--

LOCK TABLES `Entrance` WRITE;
/*!40000 ALTER TABLE `Entrance` DISABLE KEYS */;
INSERT INTO `Entrance` VALUES (1,'2020-12-09 13:47:58',1,12),(2,'2020-12-10 13:47:58',0,20),(3,'2020-12-11 13:47:58',1,27),(4,'2020-12-12 13:47:58',0,58),(5,'2020-12-13 13:47:58',0,37),(6,'2020-12-14 13:47:58',1,41),(7,'2020-12-15 13:47:58',0,8),(8,'2020-12-16 13:47:58',1,60),(9,'2020-12-17 13:47:58',0,40),(10,'2020-12-18 13:47:58',1,59),(11,'2020-12-19 13:47:58',0,7),(12,'2020-12-20 13:47:58',0,48),(13,'2020-12-21 13:47:58',0,56),(14,'2020-12-22 13:47:58',0,17),(15,'2020-12-23 13:47:58',1,44),(16,'2020-12-24 13:47:58',0,23),(17,'2020-12-25 13:47:58',1,47),(18,'2020-12-26 13:47:58',0,16),(19,'2020-12-27 13:47:58',0,51),(20,'2020-12-28 13:47:58',0,40),(21,'2020-12-29 13:47:58',0,19),(22,'2020-12-30 13:47:58',1,38),(23,'2020-12-31 13:47:58',0,2),(24,'2021-01-01 13:47:58',1,8),(25,'2021-01-02 13:47:58',0,42),(26,'2021-01-03 13:47:58',0,3),(27,'2021-01-04 13:47:58',1,13),(28,'2021-01-05 13:47:58',1,50),(29,'2021-01-06 13:47:58',1,34),(30,'2021-01-07 13:47:58',1,14),(31,'2021-01-08 13:47:58',0,47),(32,'2021-01-09 13:47:58',0,32),(33,'2021-01-10 13:47:58',0,53),(34,'2021-01-11 13:47:58',1,58),(35,'2021-01-12 13:47:58',1,56),(36,'2021-01-13 13:47:58',0,45),(37,'2021-01-14 13:47:58',1,25),(38,'2021-01-15 13:47:58',1,9),(39,'2021-01-16 13:47:58',0,7),(40,'2021-01-17 13:47:58',1,28),(41,'2021-01-18 13:47:58',1,15),(42,'2021-01-19 13:47:58',1,12),(43,'2021-01-20 13:47:58',1,3),(44,'2021-01-21 13:47:58',0,4),(45,'2021-01-22 13:47:58',0,20),(46,'2021-01-23 13:47:58',1,41),(47,'2021-01-24 13:47:58',1,53),(48,'2021-01-25 13:47:58',0,52),(49,'2021-01-26 13:47:58',1,10),(50,'2021-01-27 13:47:58',0,45),(51,'2021-01-28 13:47:58',1,19),(52,'2021-01-29 13:47:58',0,48),(53,'2021-01-30 13:47:58',0,14),(54,'2021-01-31 13:47:58',1,60),(55,'2021-02-01 13:47:58',1,59),(56,'2021-02-02 13:47:58',1,30),(57,'2021-02-03 13:47:58',1,41),(58,'2021-02-04 13:47:58',1,53),(59,'2021-02-05 13:47:58',1,37),(60,'2021-02-06 13:47:58',0,40);
/*!40000 ALTER TABLE `Entrance` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Faculty`
--

DROP TABLE IF EXISTS `Faculty`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Faculty` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Faculty`
--

LOCK TABLES `Faculty` WRITE;
/*!40000 ALTER TABLE `Faculty` DISABLE KEYS */;
INSERT INTO `Faculty` VALUES (1,'fkn'),(2,'miem'),(3,'fkmd'),(4,'fen'),(5,'fsn'),(6,'institut urbanistiki'),(7,'mief'),(8,'math'),(9,'liceum'),(10,'phys'),(11,'vshb'),(12,'fp');
/*!40000 ALTER TABLE `Faculty` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Student_Courses`
--

DROP TABLE IF EXISTS `Student_Courses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Student_Courses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student` int(11) DEFAULT NULL,
  `course` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `student` (`student`),
  KEY `course` (`course`),
  CONSTRAINT `Student_Courses_ibfk_1` FOREIGN KEY (`student`) REFERENCES `Students` (`id`),
  CONSTRAINT `Student_Courses_ibfk_2` FOREIGN KEY (`course`) REFERENCES `Courses` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Student_Courses`
--

LOCK TABLES `Student_Courses` WRITE;
/*!40000 ALTER TABLE `Student_Courses` DISABLE KEYS */;
INSERT INTO `Student_Courses` VALUES (1,1,1),(2,2,1),(3,3,1),(4,5,1),(5,10,1),(6,16,1),(7,17,1),(8,20,1),(9,21,1),(10,26,1),(11,31,1),(12,36,1),(13,38,1),(14,39,1),(15,42,1),(16,45,1),(17,49,1),(18,57,1),(19,59,1),(20,1,2),(21,2,2),(22,3,2),(23,5,2),(24,10,2),(25,16,2),(26,17,2),(27,20,2),(28,21,2),(29,26,2),(30,31,2),(31,36,2),(32,38,2),(33,39,2),(34,42,2),(35,45,2),(36,49,2),(37,57,2),(38,59,2);
/*!40000 ALTER TABLE `Student_Courses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Students`
--

DROP TABLE IF EXISTS `Students`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Students` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `surname` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `nickname` varchar(255) DEFAULT NULL,
  `DoB` date DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `vax` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Students`
--

LOCK TABLES `Students` WRITE;
/*!40000 ALTER TABLE `Students` DISABLE KEYS */;
INSERT INTO `Students` VALUES (1,'Иванов','Авдей','student_1','2003-12-01',1,0),(2,'Смирнов','Авксентий','student_2','2003-12-02',1,0),(3,'Кузнецов','Агапит','student_3','2003-12-03',1,1),(4,'Попов','Агафон','student_4','2003-12-04',2,1),(5,'Васильев','Акакий','student_5','2003-12-05',1,0),(6,'Петров','Александр','student_6','2003-12-06',4,1),(7,'Соколов','Алексей','student_7','2003-12-07',4,0),(8,'Михайлов','Альберт','student_8','2003-12-08',2,0),(9,'Новиков','Альвиан','student_9','2003-12-09',4,1),(10,'Фёдоров','Анатолий','student_10','2003-12-10',1,0),(11,'Морозов','Андрей','student_11','2003-12-11',3,0),(12,'Волков','Аникита','student_12','2003-12-12',4,0),(13,'Алексеев','Антон','student_13','2003-12-13',3,0),(14,'Лебедев','Антонин','student_14','2003-12-14',3,0),(15,'Семёнов','Анфим','student_15','2003-12-15',4,1),(16,'Егоров','Аристарх','student_16','2003-12-16',1,1),(17,'Павлов','Аркадий','student_17','2003-12-17',1,1),(18,'Козлов','Арсений','student_18','2003-12-18',3,0),(19,'Степанов','Артём','student_19','2003-12-19',2,0),(20,'Николаев','Артемий','student_20','2003-12-20',1,0),(21,'Орлов','Артур','student_21','2003-12-21',1,0),(22,'Андреев','Архипп','student_22','2003-12-22',4,0),(23,'Макаров','Афанасий','student_23','2003-12-23',2,0),(24,'Никитин','Авдей','student_24','2003-12-24',4,0),(25,'Фадеев','Авксентий','student_25','2003-12-25',2,0),(26,'Комиссаров','Агапит','student_26','2003-12-26',1,0),(27,'Мамонтов','Агафон','student_27','2003-12-27',3,0),(28,'Носов','Акакий','student_28','2003-12-28',3,1),(29,'Гуляев','Александр','student_29','2003-12-29',3,1),(30,'Шаров','Алексей','student_30','2003-12-30',2,1),(31,'Устинов','Альберт','student_31','2003-12-31',1,0),(32,'Вишняков','Альвиан','student_32','2004-01-01',2,0),(33,'Евсеев','Анатолий','student_33','2004-01-02',2,0),(34,'Лаврентьев','Андрей','student_34','2004-01-03',3,0),(35,'Брагин','Аникита','student_35','2004-01-04',2,1),(36,'Константинов','Антон','student_36','2004-01-05',1,1),(37,'Корнилов','Антонин','student_37','2004-01-06',2,0),(38,'Авдеев','Анфим','student_38','2004-01-07',1,0),(39,'Зыков','Аристарх','student_39','2004-01-08',1,0),(40,'Бирюков','Аркадий','student_40','2004-01-09',3,0),(41,'Шарапов','Арсений','student_41','2004-01-10',2,1),(42,'Никонов','Артём','student_42','2004-01-11',1,1),(43,'Щукин','Артемий','student_43','2004-01-12',2,0),(44,'Дьячков','Артур','student_44','2004-01-13',2,1),(45,'Одинцов','Архипп','student_45','2004-01-14',1,0),(46,'Сазонов','Афанасий','student_46','2004-01-15',3,1),(47,'Якушев','Авдей','student_47','2004-01-16',4,1),(48,'Красильников','Авксентий','student_48','2004-01-17',4,1),(49,'Гордеев','Агапит','student_49','2004-01-18',1,0),(50,'Самойлов','Агафон','student_50','2004-01-19',4,1),(51,'Князев','Акакий','student_51','2004-01-20',4,0),(52,'Беспалов','Александр','student_52','2004-01-21',3,1),(53,'Уваров','Алексей','student_53','2004-01-22',3,0),(54,'Шашков','Альберт','student_54','2004-01-23',4,1),(55,'Бобылёв','Альвиан','student_55','2004-01-24',4,0),(56,'Доронин','Анатолий','student_56','2004-01-25',2,1),(57,'Белозёров','Андрей','student_57','2004-01-26',1,1),(58,'Рожков','Аникита','student_58','2004-01-27',3,1),(59,'Самсонов','Антон','student_59','2004-01-28',1,1),(60,'Мясников','Антонин','student_60','2004-01-29',3,1);
/*!40000 ALTER TABLE `Students` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-12-08 11:00:56
