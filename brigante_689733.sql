-- Progettazione Web 
DROP DATABASE if exists brigante_689733; 
CREATE DATABASE brigante_689733; 
USE brigante_689733; 
-- MySQL dump 10.13  Distrib 5.7.28, for Win64 (x86_64)
--
-- Host: localhost    Database: brigante_689733
-- ------------------------------------------------------
-- Server version	5.7.28

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
-- Table structure for table `esercizi_sala`
--

DROP TABLE IF EXISTS `esercizi_sala`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `esercizi_sala` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_utente` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `serie` int(11) NOT NULL,
  `ripetizioni` int(11) NOT NULL,
  `carico` float NOT NULL,
  `data_inserimento` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `esercizi_sala`
--

LOCK TABLES `esercizi_sala` WRITE;
/*!40000 ALTER TABLE `esercizi_sala` DISABLE KEYS */;
INSERT INTO `esercizi_sala` VALUES (4,8,'Panca Piana',3,6,90,'2026-06-05 21:20:25'),(5,8,'Chest Press',4,6,120,'2026-06-05 21:20:41'),(6,8,'Squat',3,6,100,'2026-06-05 21:20:53'),(7,8,'Leg Extension',4,8,90,'2026-06-05 21:21:19'),(8,8,'Lat Machine',4,4,80,'2026-06-05 21:21:34'),(9,9,'Rematore one Harm',4,8,50,'2026-06-05 21:25:29');
/*!40000 ALTER TABLE `esercizi_sala` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prenotazioni`
--

DROP TABLE IF EXISTS `prenotazioni`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prenotazioni` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_utente` int(11) NOT NULL,
  `corso` varchar(50) NOT NULL,
  `orario` varchar(50) NOT NULL,
  `data_prenotazione` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prenotazioni`
--

LOCK TABLES `prenotazioni` WRITE;
/*!40000 ALTER TABLE `prenotazioni` DISABLE KEYS */;
INSERT INTO `prenotazioni` VALUES (11,8,'MMA','Lunedì 15:30 - 17:00','2026-06-05 21:19:55'),(12,8,'Boxing','Venerdì 18:00 - 19:30','2026-06-05 21:20:00'),(13,9,'Boxing','Mercoledì 19:00 - 20:30','2026-06-05 21:24:48');
/*!40000 ALTER TABLE `prenotazioni` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prenotazioni_sala`
--

DROP TABLE IF EXISTS `prenotazioni_sala`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prenotazioni_sala` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_utente` int(11) NOT NULL,
  `giorno` date NOT NULL,
  `fascia` varchar(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_utente` (`id_utente`,`giorno`,`fascia`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prenotazioni_sala`
--

LOCK TABLES `prenotazioni_sala` WRITE;
/*!40000 ALTER TABLE `prenotazioni_sala` DISABLE KEYS */;
INSERT INTO `prenotazioni_sala` VALUES (13,8,'2026-06-10','15:00-18:00'),(15,9,'2026-06-10','15:00-18:00'),(14,9,'2026-06-11','21:00-24:00');
/*!40000 ALTER TABLE `prenotazioni_sala` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `utenti`
--

DROP TABLE IF EXISTS `utenti`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `utenti` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `utenti`
--

LOCK TABLES `utenti` WRITE;
/*!40000 ALTER TABLE `utenti` DISABLE KEYS */;
INSERT INTO `utenti` VALUES (8,'Luca','briganteluca75@gmail.com','$2y$10$VwHmH3QkZUUlFzoeH3flOO8zS6UCqcB6cLy0Rxm8CyXCJM6Z/rr4.','2026-06-05 21:19:19'),(9,'Ivan','ivan@gmail.com','$2y$10$cyBv1Z9C15mcLKBU/gD1nuWy9neG/IZ9fKEx6bvZlhmKwrf5QYA7K','2026-06-05 21:24:24');
/*!40000 ALTER TABLE `utenti` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-06-05 23:26:08
