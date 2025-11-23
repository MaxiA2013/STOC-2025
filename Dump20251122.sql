-- MySQL dump 10.13  Distrib 8.0.41, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: clinica
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `agenda`
--

DROP TABLE IF EXISTS `agenda`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `agenda` (
  `id_agenda` int(11) NOT NULL AUTO_INCREMENT,
  `fecha_agenda` date NOT NULL,
  `hora_desde` time NOT NULL,
  `hora_hasta` time NOT NULL,
  `estados_id_estados` int(11) NOT NULL,
  `doctor_id_doctor` int(11) NOT NULL,
  PRIMARY KEY (`id_agenda`),
  KEY `fk_calendario_doctor1_idx` (`doctor_id_doctor`),
  KEY `fk_agenda_estados1_idx` (`estados_id_estados`),
  CONSTRAINT `fk_agenda_estados1` FOREIGN KEY (`estados_id_estados`) REFERENCES `estados` (`id_estados`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_calendario_doctor1` FOREIGN KEY (`doctor_id_doctor`) REFERENCES `doctor` (`id_doctor`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `agenda`
--

LOCK TABLES `agenda` WRITE;
/*!40000 ALTER TABLE `agenda` DISABLE KEYS */;
INSERT INTO `agenda` VALUES (1,'2025-09-29','23:37:00','00:37:00',2,2),(2,'2025-09-30','04:10:00','05:10:00',2,2),(3,'2025-09-30','04:14:00','05:14:00',2,2),(4,'2025-09-30','03:25:00','04:25:00',2,3),(5,'2025-09-30','22:33:00','23:33:00',2,2),(6,'2025-10-29','08:40:00','13:30:00',2,4),(7,'2025-10-02','19:15:00','20:15:00',2,2),(8,'2025-10-02','18:16:00','19:16:00',2,3),(9,'2025-10-02','18:23:00','22:20:00',2,4),(10,'2025-10-03','23:09:00','23:13:00',2,4),(11,'2025-10-06','05:30:00','06:30:00',2,4),(12,'2025-10-10','06:33:00','07:33:00',1,2),(13,'2025-10-29','05:57:00','07:57:00',1,4);
/*!40000 ALTER TABLE `agenda` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `agenda_turno`
--

DROP TABLE IF EXISTS `agenda_turno`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `agenda_turno` (
  `id_agenda_turno` int(11) NOT NULL AUTO_INCREMENT,
  `paciente_id_paciente` int(11) NOT NULL,
  `turno_id_turnos` int(11) NOT NULL,
  `estados_id_estados` int(11) NOT NULL,
  PRIMARY KEY (`id_agenda_turno`),
  KEY `fk_agenda_turno_paciente1_idx` (`paciente_id_paciente`),
  KEY `fk_agenda_turno_turno1_idx` (`turno_id_turnos`),
  KEY `fk_agenda_turno_estados1_idx` (`estados_id_estados`),
  CONSTRAINT `fk_agenda_turno_estados1` FOREIGN KEY (`estados_id_estados`) REFERENCES `estados` (`id_estados`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_agenda_turno_paciente1` FOREIGN KEY (`paciente_id_paciente`) REFERENCES `paciente` (`id_paciente`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_agenda_turno_turno1` FOREIGN KEY (`turno_id_turnos`) REFERENCES `turno` (`id_turnos`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `agenda_turno`
--

LOCK TABLES `agenda_turno` WRITE;
/*!40000 ALTER TABLE `agenda_turno` DISABLE KEYS */;
/*!40000 ALTER TABLE `agenda_turno` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `analisis_procedimiento`
--

DROP TABLE IF EXISTS `analisis_procedimiento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `analisis_procedimiento` (
  `id_analisis_procedimiento` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(500) DEFAULT NULL,
  `descripcion` varchar(500) DEFAULT NULL,
  `que_esperar_id_que_esperar` int(11) NOT NULL,
  `preparacion_id_preparacion` int(11) NOT NULL,
  PRIMARY KEY (`id_analisis_procedimiento`),
  KEY `fk_analisis-procedimiento_que-esperar1_idx` (`que_esperar_id_que_esperar`),
  KEY `fk_analisis-procedimiento_preparacion1_idx` (`preparacion_id_preparacion`),
  CONSTRAINT `fk_analisis-procedimiento_preparacion1` FOREIGN KEY (`preparacion_id_preparacion`) REFERENCES `preparacion` (`id_preparacion`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_analisis-procedimiento_que-esperar1` FOREIGN KEY (`que_esperar_id_que_esperar`) REFERENCES `que_esperar` (`id_que_esperar`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `analisis_procedimiento`
--

LOCK TABLES `analisis_procedimiento` WRITE;
/*!40000 ALTER TABLE `analisis_procedimiento` DISABLE KEYS */;
/*!40000 ALTER TABLE `analisis_procedimiento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `asistente`
--

DROP TABLE IF EXISTS `asistente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `asistente` (
  `id_asistente` int(11) NOT NULL AUTO_INCREMENT,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `hora_inicio` time DEFAULT NULL,
  `hora_fin` time DEFAULT NULL,
  `salario` float DEFAULT NULL,
  `usuario_id_usuario` int(11) NOT NULL,
  PRIMARY KEY (`id_asistente`),
  KEY `fk_asistente_usuario1_idx` (`usuario_id_usuario`),
  CONSTRAINT `fk_asistente_usuario1` FOREIGN KEY (`usuario_id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asistente`
--

LOCK TABLES `asistente` WRITE;
/*!40000 ALTER TABLE `asistente` DISABLE KEYS */;
/*!40000 ALTER TABLE `asistente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auditoria_turno`
--

DROP TABLE IF EXISTS `auditoria_turno`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `auditoria_turno` (
  `id_auditoria-turno` int(11) NOT NULL AUTO_INCREMENT,
  `doctor_antes` varchar(45) DEFAULT NULL,
  `fechaHora_inicio_anterior` datetime DEFAULT NULL,
  `fechaHora_fin_anterior` datetime DEFAULT NULL,
  `fechaHora_modificacion` datetime DEFAULT NULL,
  `persona_modificacion` varchar(45) DEFAULT NULL,
  `turnos_id_turnos` int(11) NOT NULL,
  PRIMARY KEY (`id_auditoria-turno`),
  KEY `fk_auditoria-turno_turnos1_idx` (`turnos_id_turnos`),
  CONSTRAINT `fk_auditoria-turno_turnos1` FOREIGN KEY (`turnos_id_turnos`) REFERENCES `turno` (`id_turnos`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auditoria_turno`
--

LOCK TABLES `auditoria_turno` WRITE;
/*!40000 ALTER TABLE `auditoria_turno` DISABLE KEYS */;
/*!40000 ALTER TABLE `auditoria_turno` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `banner`
--

DROP TABLE IF EXISTS `banner`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `banner` (
  `id_banner` int(11) NOT NULL AUTO_INCREMENT,
  `foto` varchar(500) DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `fecha_alta` date DEFAULT NULL,
  `fecha_baja` date DEFAULT NULL,
  `obligatorio` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_banner`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `banner`
--

LOCK TABLES `banner` WRITE;
/*!40000 ALTER TABLE `banner` DISABLE KEYS */;
/*!40000 ALTER TABLE `banner` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `banner-noticia`
--

DROP TABLE IF EXISTS `banner-noticia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `banner-noticia` (
  `id_banner-noticia` int(11) NOT NULL AUTO_INCREMENT,
  `noticias_id_noticias` int(11) NOT NULL,
  `banner_id_banner` int(11) NOT NULL,
  PRIMARY KEY (`id_banner-noticia`),
  KEY `fk_banner-noticia_noticias1_idx` (`noticias_id_noticias`),
  KEY `fk_banner-noticia_banner1_idx` (`banner_id_banner`),
  CONSTRAINT `fk_banner-noticia_banner1` FOREIGN KEY (`banner_id_banner`) REFERENCES `banner` (`id_banner`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_banner-noticia_noticias1` FOREIGN KEY (`noticias_id_noticias`) REFERENCES `noticias` (`id_noticias`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `banner-noticia`
--

LOCK TABLES `banner-noticia` WRITE;
/*!40000 ALTER TABLE `banner-noticia` DISABLE KEYS */;
/*!40000 ALTER TABLE `banner-noticia` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `barrio`
--

DROP TABLE IF EXISTS `barrio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `barrio` (
  `id_barrio` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_barrio` varchar(500) DEFAULT NULL,
  `localidad_id_localidad` int(11) NOT NULL,
  PRIMARY KEY (`id_barrio`),
  KEY `fk_barrio_localidad1_idx` (`localidad_id_localidad`),
  CONSTRAINT `fk_barrio_localidad1` FOREIGN KEY (`localidad_id_localidad`) REFERENCES `localidad` (`id_localidad`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `barrio`
--

LOCK TABLES `barrio` WRITE;
/*!40000 ALTER TABLE `barrio` DISABLE KEYS */;
/*!40000 ALTER TABLE `barrio` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `biblioteca_salud`
--

DROP TABLE IF EXISTS `biblioteca_salud`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `biblioteca_salud` (
  `id_biblioteca_salud` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(500) DEFAULT NULL,
  `clinica_id_clinica` int(11) NOT NULL,
  `con-sin_has_an-pro_id_condicion-analisis` int(11) NOT NULL,
  PRIMARY KEY (`id_biblioteca_salud`),
  KEY `fk_biblioteca-salud_clinica1_idx` (`clinica_id_clinica`),
  KEY `fk_biblioteca-salud_con-sin_has_an-pro1_idx` (`con-sin_has_an-pro_id_condicion-analisis`),
  CONSTRAINT `fk_biblioteca-salud_clinica1` FOREIGN KEY (`clinica_id_clinica`) REFERENCES `clinica` (`id_clinica`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_biblioteca-salud_con-sin_has_an-pro1` FOREIGN KEY (`con-sin_has_an-pro_id_condicion-analisis`) REFERENCES `con-sin_has_an-pro` (`id_condicion-analisis`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `biblioteca_salud`
--

LOCK TABLES `biblioteca_salud` WRITE;
/*!40000 ALTER TABLE `biblioteca_salud` DISABLE KEYS */;
/*!40000 ALTER TABLE `biblioteca_salud` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `calle`
--

DROP TABLE IF EXISTS `calle`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `calle` (
  `id_calle` int(11) NOT NULL,
  `nombre_calle` varchar(450) DEFAULT NULL,
  `calle_altura` varchar(10) DEFAULT NULL,
  `barrio_id_barrio` int(11) NOT NULL,
  PRIMARY KEY (`id_calle`),
  KEY `fk_calle_barrio1_idx` (`barrio_id_barrio`),
  CONSTRAINT `fk_calle_barrio1` FOREIGN KEY (`barrio_id_barrio`) REFERENCES `barrio` (`id_barrio`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `calle`
--

LOCK TABLES `calle` WRITE;
/*!40000 ALTER TABLE `calle` DISABLE KEYS */;
/*!40000 ALTER TABLE `calle` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cita`
--

DROP TABLE IF EXISTS `cita`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cita` (
  `id_cita` int(11) NOT NULL AUTO_INCREMENT,
  `razon` varchar(500) NOT NULL,
  `asistencia_doctor` tinyint(1) NOT NULL DEFAULT 1,
  `asistencia_paciente` tinyint(1) NOT NULL DEFAULT 1,
  `agenda_turno_id_agenda_turno` int(11) NOT NULL,
  PRIMARY KEY (`id_cita`),
  KEY `fk_cita_agenda_turno1_idx` (`agenda_turno_id_agenda_turno`),
  CONSTRAINT `fk_cita_agenda_turno1` FOREIGN KEY (`agenda_turno_id_agenda_turno`) REFERENCES `agenda_turno` (`id_agenda_turno`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cita`
--

LOCK TABLES `cita` WRITE;
/*!40000 ALTER TABLE `cita` DISABLE KEYS */;
/*!40000 ALTER TABLE `cita` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clinica`
--

DROP TABLE IF EXISTS `clinica`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `clinica` (
  `id_clinica` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_clinica` varchar(150) DEFAULT NULL,
  `direccion_id_direccion` int(11) NOT NULL,
  PRIMARY KEY (`id_clinica`),
  KEY `fk_clinica_direccion1_idx` (`direccion_id_direccion`),
  CONSTRAINT `fk_clinica_direccion1` FOREIGN KEY (`direccion_id_direccion`) REFERENCES `direccion` (`id_direccion`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clinica`
--

LOCK TABLES `clinica` WRITE;
/*!40000 ALTER TABLE `clinica` DISABLE KEYS */;
/*!40000 ALTER TABLE `clinica` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clinica_contacto`
--

DROP TABLE IF EXISTS `clinica_contacto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `clinica_contacto` (
  `id_clinica_contacto` int(11) NOT NULL AUTO_INCREMENT,
  `contacto_id_contacto` int(11) NOT NULL,
  `clinica_id_clinica` int(11) NOT NULL,
  PRIMARY KEY (`id_clinica_contacto`),
  KEY `fk_clinica-contacto_contacto1_idx` (`contacto_id_contacto`),
  KEY `fk_clinica-contacto_clinica1_idx` (`clinica_id_clinica`),
  CONSTRAINT `fk_clinica-contacto_clinica1` FOREIGN KEY (`clinica_id_clinica`) REFERENCES `clinica` (`id_clinica`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_clinica-contacto_contacto1` FOREIGN KEY (`contacto_id_contacto`) REFERENCES `contacto` (`id_contacto`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clinica_contacto`
--

LOCK TABLES `clinica_contacto` WRITE;
/*!40000 ALTER TABLE `clinica_contacto` DISABLE KEYS */;
/*!40000 ALTER TABLE `clinica_contacto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `con-sin_has_an-pro`
--

DROP TABLE IF EXISTS `con-sin_has_an-pro`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `con-sin_has_an-pro` (
  `id_condicion-analisis` int(11) NOT NULL AUTO_INCREMENT,
  `condicion-sintomas_id_condicion-sintomas` int(11) NOT NULL,
  `analisis-procedimiento_id_analisis-procedimiento` int(11) NOT NULL,
  PRIMARY KEY (`id_condicion-analisis`),
  KEY `fk_con-sin_has_an-pro_condicion-sintomas1_idx` (`condicion-sintomas_id_condicion-sintomas`),
  KEY `fk_con-sin_has_an-pro_analisis-procedimiento1_idx` (`analisis-procedimiento_id_analisis-procedimiento`),
  CONSTRAINT `fk_con-sin_has_an-pro_analisis-procedimiento1` FOREIGN KEY (`analisis-procedimiento_id_analisis-procedimiento`) REFERENCES `analisis_procedimiento` (`id_analisis_procedimiento`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_con-sin_has_an-pro_condicion-sintomas1` FOREIGN KEY (`condicion-sintomas_id_condicion-sintomas`) REFERENCES `condicion_sintomas` (`id_condicion_sintomas`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `con-sin_has_an-pro`
--

LOCK TABLES `con-sin_has_an-pro` WRITE;
/*!40000 ALTER TABLE `con-sin_has_an-pro` DISABLE KEYS */;
/*!40000 ALTER TABLE `con-sin_has_an-pro` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `condicion`
--

DROP TABLE IF EXISTS `condicion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `condicion` (
  `id_condicion` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_condicion` varchar(500) DEFAULT NULL,
  `detalle` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id_condicion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `condicion`
--

LOCK TABLES `condicion` WRITE;
/*!40000 ALTER TABLE `condicion` DISABLE KEYS */;
/*!40000 ALTER TABLE `condicion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `condicion-sintoma_analisis-procedimiento-banner`
--

DROP TABLE IF EXISTS `condicion-sintoma_analisis-procedimiento-banner`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `condicion-sintoma_analisis-procedimiento-banner` (
  `id_condicion-sintoma_analisis-procedimiento-banner` int(11) NOT NULL AUTO_INCREMENT,
  `banner_id_banner` int(11) NOT NULL,
  `con-sin_has_an-pro_id_condicion-analisis` int(11) NOT NULL,
  `condicion-sintoma_analisis-procedimiento-bannercol` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id_condicion-sintoma_analisis-procedimiento-banner`),
  KEY `fk_condicion-sintoma_analisis-procedimiento-banner_banner1_idx` (`banner_id_banner`),
  KEY `fk_condicion-sintoma_analisis-procedimiento-banner_con-sin__idx` (`con-sin_has_an-pro_id_condicion-analisis`),
  CONSTRAINT `fk_condicion-sintoma_analisis-procedimiento-banner_banner1` FOREIGN KEY (`banner_id_banner`) REFERENCES `banner` (`id_banner`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_condicion-sintoma_analisis-procedimiento-banner_con-sin_ha1` FOREIGN KEY (`con-sin_has_an-pro_id_condicion-analisis`) REFERENCES `con-sin_has_an-pro` (`id_condicion-analisis`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `condicion-sintoma_analisis-procedimiento-banner`
--

LOCK TABLES `condicion-sintoma_analisis-procedimiento-banner` WRITE;
/*!40000 ALTER TABLE `condicion-sintoma_analisis-procedimiento-banner` DISABLE KEYS */;
/*!40000 ALTER TABLE `condicion-sintoma_analisis-procedimiento-banner` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `condicion_sintomas`
--

DROP TABLE IF EXISTS `condicion_sintomas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `condicion_sintomas` (
  `id_condicion_sintomas` int(11) NOT NULL AUTO_INCREMENT,
  `condicion_id_condicion` int(11) NOT NULL,
  `sintomas_id_sintomas` int(11) NOT NULL,
  `biblioteca_salud_id_biblioteca_salud` int(11) NOT NULL,
  PRIMARY KEY (`id_condicion_sintomas`),
  KEY `fk_condicion-sintomas_condicion1_idx` (`condicion_id_condicion`),
  KEY `fk_condicion-sintomas_sintomas1_idx` (`sintomas_id_sintomas`),
  KEY `fk_condicion-sintomas_biblioteca-salud1_idx` (`biblioteca_salud_id_biblioteca_salud`),
  CONSTRAINT `fk_condicion-sintomas_biblioteca-salud1` FOREIGN KEY (`biblioteca_salud_id_biblioteca_salud`) REFERENCES `biblioteca_salud` (`id_biblioteca_salud`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_condicion-sintomas_condicion1` FOREIGN KEY (`condicion_id_condicion`) REFERENCES `condicion` (`id_condicion`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_condicion-sintomas_sintomas1` FOREIGN KEY (`sintomas_id_sintomas`) REFERENCES `sintomas` (`id_sintomas`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `condicion_sintomas`
--

LOCK TABLES `condicion_sintomas` WRITE;
/*!40000 ALTER TABLE `condicion_sintomas` DISABLE KEYS */;
/*!40000 ALTER TABLE `condicion_sintomas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `confirmacion`
--

DROP TABLE IF EXISTS `confirmacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `confirmacion` (
  `id_confirmacion` int(11) NOT NULL AUTO_INCREMENT,
  `confirmacion_paciente` tinyint(1) DEFAULT 0,
  `confirmacion_doctor` tinyint(1) DEFAULT 0,
  `agenda_turno_id_agenda_turno` int(11) NOT NULL,
  PRIMARY KEY (`id_confirmacion`,`agenda_turno_id_agenda_turno`),
  KEY `fk_confirmacion_agenda_turno1_idx` (`agenda_turno_id_agenda_turno`),
  CONSTRAINT `fk_confirmacion_agenda_turno1` FOREIGN KEY (`agenda_turno_id_agenda_turno`) REFERENCES `agenda_turno` (`id_agenda_turno`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `confirmacion`
--

LOCK TABLES `confirmacion` WRITE;
/*!40000 ALTER TABLE `confirmacion` DISABLE KEYS */;
/*!40000 ALTER TABLE `confirmacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `consultas`
--

DROP TABLE IF EXISTS `consultas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `consultas` (
  `id_consultas` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(450) DEFAULT NULL,
  `descripcion` varchar(450) DEFAULT NULL,
  `preguntas_id_preguntas` int(11) NOT NULL,
  PRIMARY KEY (`id_consultas`),
  KEY `fk_consultas_preguntas1_idx` (`preguntas_id_preguntas`),
  CONSTRAINT `fk_consultas_preguntas1` FOREIGN KEY (`preguntas_id_preguntas`) REFERENCES `preguntas` (`id_preguntas`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `consultas`
--

LOCK TABLES `consultas` WRITE;
/*!40000 ALTER TABLE `consultas` DISABLE KEYS */;
/*!40000 ALTER TABLE `consultas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contacto`
--

DROP TABLE IF EXISTS `contacto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contacto` (
  `id_contacto` int(11) NOT NULL AUTO_INCREMENT,
  `tipo_contacto` varchar(100) DEFAULT NULL,
  `descripcion` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_contacto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contacto`
--

LOCK TABLES `contacto` WRITE;
/*!40000 ALTER TABLE `contacto` DISABLE KEYS */;
/*!40000 ALTER TABLE `contacto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contacto_persona`
--

DROP TABLE IF EXISTS `contacto_persona`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contacto_persona` (
  `id_contacto_persona` int(11) NOT NULL AUTO_INCREMENT,
  `detalle` varchar(100) DEFAULT NULL,
  `persona_id_persona` int(11) NOT NULL,
  `contacto_id_contacto` int(11) NOT NULL,
  PRIMARY KEY (`id_contacto_persona`),
  KEY `fk_contacto-persona_persona1_idx` (`persona_id_persona`),
  KEY `fk_contacto-persona_contacto1_idx` (`contacto_id_contacto`),
  CONSTRAINT `fk_contacto-persona_contacto1` FOREIGN KEY (`contacto_id_contacto`) REFERENCES `contacto` (`id_contacto`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_contacto-persona_persona1` FOREIGN KEY (`persona_id_persona`) REFERENCES `persona` (`id_persona`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contacto_persona`
--

LOCK TABLES `contacto_persona` WRITE;
/*!40000 ALTER TABLE `contacto_persona` DISABLE KEYS */;
/*!40000 ALTER TABLE `contacto_persona` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `coordenada-direccion`
--

DROP TABLE IF EXISTS `coordenada-direccion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `coordenada-direccion` (
  `id_coordenada-direccion` int(11) NOT NULL AUTO_INCREMENT,
  `direccion_id_direccion` int(11) NOT NULL,
  `coordenadas_id_coordenadas` int(11) NOT NULL,
  PRIMARY KEY (`id_coordenada-direccion`),
  KEY `fk_coordenada-direccion_direccion1_idx` (`direccion_id_direccion`),
  KEY `fk_coordenada-direccion_coordenadas1_idx` (`coordenadas_id_coordenadas`),
  CONSTRAINT `fk_coordenada-direccion_coordenadas1` FOREIGN KEY (`coordenadas_id_coordenadas`) REFERENCES `coordenadas` (`id_coordenadas`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_coordenada-direccion_direccion1` FOREIGN KEY (`direccion_id_direccion`) REFERENCES `direccion` (`id_direccion`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `coordenada-direccion`
--

LOCK TABLES `coordenada-direccion` WRITE;
/*!40000 ALTER TABLE `coordenada-direccion` DISABLE KEYS */;
/*!40000 ALTER TABLE `coordenada-direccion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `coordenadas`
--

DROP TABLE IF EXISTS `coordenadas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `coordenadas` (
  `id_coordenadas` int(11) NOT NULL AUTO_INCREMENT,
  `latitud` varchar(700) DEFAULT NULL,
  `longitud` varchar(700) DEFAULT NULL,
  PRIMARY KEY (`id_coordenadas`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `coordenadas`
--

LOCK TABLES `coordenadas` WRITE;
/*!40000 ALTER TABLE `coordenadas` DISABLE KEYS */;
/*!40000 ALTER TABLE `coordenadas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `correo-confirmacion`
--

DROP TABLE IF EXISTS `correo-confirmacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `correo-confirmacion` (
  `id_correo-confirmacion` int(11) NOT NULL AUTO_INCREMENT,
  `fecha_envio` datetime DEFAULT NULL,
  `fecha_acceso` datetime DEFAULT NULL,
  `usuario_id_usuario` int(11) NOT NULL,
  PRIMARY KEY (`id_correo-confirmacion`),
  KEY `fk_confirmacion-contacto_usuario1_idx` (`usuario_id_usuario`),
  CONSTRAINT `fk_confirmacion-contacto_usuario1` FOREIGN KEY (`usuario_id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `correo-confirmacion`
--

LOCK TABLES `correo-confirmacion` WRITE;
/*!40000 ALTER TABLE `correo-confirmacion` DISABLE KEYS */;
/*!40000 ALTER TABLE `correo-confirmacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `diagnostico`
--

DROP TABLE IF EXISTS `diagnostico`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `diagnostico` (
  `id_diagnostico` int(11) NOT NULL AUTO_INCREMENT,
  `detalle` varchar(150) DEFAULT NULL,
  `cita_id_cita` int(11) NOT NULL,
  PRIMARY KEY (`id_diagnostico`),
  KEY `fk_diagnostico_cita1_idx` (`cita_id_cita`),
  CONSTRAINT `fk_diagnostico_cita1` FOREIGN KEY (`cita_id_cita`) REFERENCES `cita` (`id_cita`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `diagnostico`
--

LOCK TABLES `diagnostico` WRITE;
/*!40000 ALTER TABLE `diagnostico` DISABLE KEYS */;
/*!40000 ALTER TABLE `diagnostico` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dias`
--

DROP TABLE IF EXISTS `dias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dias` (
  `id_dias` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(45) NOT NULL,
  PRIMARY KEY (`id_dias`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dias`
--

LOCK TABLES `dias` WRITE;
/*!40000 ALTER TABLE `dias` DISABLE KEYS */;
INSERT INTO `dias` VALUES (1,'lunes'),(2,'martes'),(3,'miercoles'),(4,'jueves'),(5,'viernes');
/*!40000 ALTER TABLE `dias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `direccion`
--

DROP TABLE IF EXISTS `direccion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `direccion` (
  `id_direccion` int(11) NOT NULL AUTO_INCREMENT,
  `detalle` varchar(450) DEFAULT NULL,
  PRIMARY KEY (`id_direccion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `direccion`
--

LOCK TABLES `direccion` WRITE;
/*!40000 ALTER TABLE `direccion` DISABLE KEYS */;
/*!40000 ALTER TABLE `direccion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doctor`
--

DROP TABLE IF EXISTS `doctor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `doctor` (
  `id_doctor` int(11) NOT NULL AUTO_INCREMENT,
  `numero_matricula_profesional` int(11) NOT NULL,
  `usuario_id_usuario` int(11) NOT NULL,
  `precio_consulta` float NOT NULL,
  PRIMARY KEY (`id_doctor`),
  KEY `fk_doctor_usuario1_idx` (`usuario_id_usuario`),
  CONSTRAINT `fk_doctor_usuario1` FOREIGN KEY (`usuario_id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctor`
--

LOCK TABLES `doctor` WRITE;
/*!40000 ALTER TABLE `doctor` DISABLE KEYS */;
INSERT INTO `doctor` VALUES (2,73647,4,12312),(3,3748364,5,2191830),(4,2147483647,6,133331000),(5,1238276,9,0),(6,2147483647,16,21232300),(7,2147483647,24,150000);
/*!40000 ALTER TABLE `doctor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doctor_auditoria`
--

DROP TABLE IF EXISTS `doctor_auditoria`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `doctor_auditoria` (
  `id_doctor-auditoria` int(11) NOT NULL AUTO_INCREMENT,
  `salario_anterior` float DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL,
  `fecha_inicio_anterior` date DEFAULT NULL,
  `fecha_fin_anterior` date DEFAULT NULL,
  `horario_inicio_anterior` time DEFAULT NULL,
  `horario_fin` time DEFAULT NULL,
  `doctor_id_doctor` int(11) NOT NULL,
  PRIMARY KEY (`id_doctor-auditoria`),
  KEY `fk_doctor-auditoria_doctor1_idx` (`doctor_id_doctor`),
  CONSTRAINT `fk_doctor-auditoria_doctor1` FOREIGN KEY (`doctor_id_doctor`) REFERENCES `doctor` (`id_doctor`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctor_auditoria`
--

LOCK TABLES `doctor_auditoria` WRITE;
/*!40000 ALTER TABLE `doctor_auditoria` DISABLE KEYS */;
/*!40000 ALTER TABLE `doctor_auditoria` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doctor_dias`
--

DROP TABLE IF EXISTS `doctor_dias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `doctor_dias` (
  `doctor_id_doctor` int(11) NOT NULL,
  `dias_id_dias` int(11) NOT NULL,
  PRIMARY KEY (`doctor_id_doctor`,`dias_id_dias`),
  KEY `fk_doctor_has_dias_dias1_idx` (`dias_id_dias`),
  KEY `fk_doctor_has_dias_doctor1_idx` (`doctor_id_doctor`),
  CONSTRAINT `fk_doctor_has_dias_dias1` FOREIGN KEY (`dias_id_dias`) REFERENCES `dias` (`id_dias`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_doctor_has_dias_doctor1` FOREIGN KEY (`doctor_id_doctor`) REFERENCES `doctor` (`id_doctor`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctor_dias`
--

LOCK TABLES `doctor_dias` WRITE;
/*!40000 ALTER TABLE `doctor_dias` DISABLE KEYS */;
INSERT INTO `doctor_dias` VALUES (6,1),(6,3);
/*!40000 ALTER TABLE `doctor_dias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doctor_lugar_trabajo`
--

DROP TABLE IF EXISTS `doctor_lugar_trabajo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `doctor_lugar_trabajo` (
  `doctor_id_doctor` int(11) NOT NULL,
  `lugar_trabajo_id_lugar_trabajo` int(11) NOT NULL,
  PRIMARY KEY (`doctor_id_doctor`,`lugar_trabajo_id_lugar_trabajo`),
  KEY `fk_doctor_has_lugar_trabajo_lugar_trabajo1_idx` (`lugar_trabajo_id_lugar_trabajo`),
  KEY `fk_doctor_has_lugar_trabajo_doctor1_idx` (`doctor_id_doctor`),
  CONSTRAINT `fk_doctor_has_lugar_trabajo_doctor1` FOREIGN KEY (`doctor_id_doctor`) REFERENCES `doctor` (`id_doctor`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_doctor_has_lugar_trabajo_lugar_trabajo1` FOREIGN KEY (`lugar_trabajo_id_lugar_trabajo`) REFERENCES `lugar_trabajo` (`id_lugar_trabajo`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctor_lugar_trabajo`
--

LOCK TABLES `doctor_lugar_trabajo` WRITE;
/*!40000 ALTER TABLE `doctor_lugar_trabajo` DISABLE KEYS */;
/*!40000 ALTER TABLE `doctor_lugar_trabajo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doctor_obra_social`
--

DROP TABLE IF EXISTS `doctor_obra_social`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `doctor_obra_social` (
  `doctor_id_doctor` int(11) NOT NULL,
  `obra_social_id_obra_social` int(11) NOT NULL,
  PRIMARY KEY (`doctor_id_doctor`,`obra_social_id_obra_social`),
  KEY `fk_doctor_has_obra_social_obra_social1_idx` (`obra_social_id_obra_social`),
  KEY `fk_doctor_has_obra_social_doctor1_idx` (`doctor_id_doctor`),
  CONSTRAINT `fk_doctor_has_obra_social_doctor1` FOREIGN KEY (`doctor_id_doctor`) REFERENCES `doctor` (`id_doctor`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_doctor_has_obra_social_obra_social1` FOREIGN KEY (`obra_social_id_obra_social`) REFERENCES `obra_social` (`id_obra_social`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctor_obra_social`
--

LOCK TABLES `doctor_obra_social` WRITE;
/*!40000 ALTER TABLE `doctor_obra_social` DISABLE KEYS */;
INSERT INTO `doctor_obra_social` VALUES (6,3);
/*!40000 ALTER TABLE `doctor_obra_social` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doctor_prestacion`
--

DROP TABLE IF EXISTS `doctor_prestacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `doctor_prestacion` (
  `id_doctor_prestacion` int(11) NOT NULL AUTO_INCREMENT,
  `costo` float DEFAULT NULL,
  `doctor_id_doctor` int(11) NOT NULL,
  PRIMARY KEY (`id_doctor_prestacion`),
  KEY `fk_doctor-tratamiento_doctor1_idx` (`doctor_id_doctor`),
  CONSTRAINT `fk_doctor-tratamiento_doctor1` FOREIGN KEY (`doctor_id_doctor`) REFERENCES `doctor` (`id_doctor`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctor_prestacion`
--

LOCK TABLES `doctor_prestacion` WRITE;
/*!40000 ALTER TABLE `doctor_prestacion` DISABLE KEYS */;
/*!40000 ALTER TABLE `doctor_prestacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `documento`
--

DROP TABLE IF EXISTS `documento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `documento` (
  `id_documento` int(11) NOT NULL AUTO_INCREMENT,
  `tipo_documento` varchar(150) NOT NULL,
  `descripcion` varchar(150) DEFAULT NULL,
  `obligatorio` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id_documento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documento`
--

LOCK TABLES `documento` WRITE;
/*!40000 ALTER TABLE `documento` DISABLE KEYS */;
/*!40000 ALTER TABLE `documento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `documento_persona`
--

DROP TABLE IF EXISTS `documento_persona`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `documento_persona` (
  `id_documento-persona` int(11) NOT NULL AUTO_INCREMENT,
  `detalle` varchar(150) DEFAULT NULL,
  `documento_id_documento` int(11) NOT NULL,
  `persona_id_persona` int(11) NOT NULL,
  PRIMARY KEY (`id_documento-persona`),
  KEY `fk_documento-persona_documento1_idx` (`documento_id_documento`),
  KEY `fk_documento-persona_persona1_idx` (`persona_id_persona`),
  CONSTRAINT `fk_documento-persona_documento1` FOREIGN KEY (`documento_id_documento`) REFERENCES `documento` (`id_documento`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_documento-persona_persona1` FOREIGN KEY (`persona_id_persona`) REFERENCES `persona` (`id_persona`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documento_persona`
--

LOCK TABLES `documento_persona` WRITE;
/*!40000 ALTER TABLE `documento_persona` DISABLE KEYS */;
/*!40000 ALTER TABLE `documento_persona` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `especialidad`
--

DROP TABLE IF EXISTS `especialidad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `especialidad` (
  `id_especialidad` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_especialidad` varchar(150) NOT NULL,
  PRIMARY KEY (`id_especialidad`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `especialidad`
--

LOCK TABLES `especialidad` WRITE;
/*!40000 ALTER TABLE `especialidad` DISABLE KEYS */;
/*!40000 ALTER TABLE `especialidad` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `especialidad_has_doctor`
--

DROP TABLE IF EXISTS `especialidad_has_doctor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `especialidad_has_doctor` (
  `especialidad_id_especialidad` int(11) NOT NULL,
  `doctor_id_doctor` int(11) NOT NULL,
  PRIMARY KEY (`especialidad_id_especialidad`,`doctor_id_doctor`),
  KEY `fk_especialidad_has_doctor_doctor1_idx` (`doctor_id_doctor`),
  KEY `fk_especialidad_has_doctor_especialidad1_idx` (`especialidad_id_especialidad`),
  CONSTRAINT `fk_especialidad_has_doctor_doctor1` FOREIGN KEY (`doctor_id_doctor`) REFERENCES `doctor` (`id_doctor`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_especialidad_has_doctor_especialidad1` FOREIGN KEY (`especialidad_id_especialidad`) REFERENCES `especialidad` (`id_especialidad`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `especialidad_has_doctor`
--

LOCK TABLES `especialidad_has_doctor` WRITE;
/*!40000 ALTER TABLE `especialidad_has_doctor` DISABLE KEYS */;
/*!40000 ALTER TABLE `especialidad_has_doctor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `estados`
--

DROP TABLE IF EXISTS `estados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `estados` (
  `id_estados` int(11) NOT NULL AUTO_INCREMENT,
  `tipo_estado` varchar(100) DEFAULT NULL,
  `descripcion` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_estados`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estados`
--

LOCK TABLES `estados` WRITE;
/*!40000 ALTER TABLE `estados` DISABLE KEYS */;
INSERT INTO `estados` VALUES (1,'Disponible','aganda disponible'),(2,'inactivo','agenda inactiva'),(3,'pendiente','agenda pendiente'),(4,'vacaciones','doctor en vacaciones');
/*!40000 ALTER TABLE `estados` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `familiar`
--

DROP TABLE IF EXISTS `familiar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `familiar` (
  `id_familiar` int(11) NOT NULL AUTO_INCREMENT,
  `relacion` varchar(500) DEFAULT NULL,
  `descripcion` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id_familiar`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `familiar`
--

LOCK TABLES `familiar` WRITE;
/*!40000 ALTER TABLE `familiar` DISABLE KEYS */;
/*!40000 ALTER TABLE `familiar` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `familiar_persona`
--

DROP TABLE IF EXISTS `familiar_persona`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `familiar_persona` (
  `id_familiar_persona` int(11) NOT NULL AUTO_INCREMENT,
  `familiar_id_familiar` int(11) NOT NULL,
  `persona_id_persona` int(11) NOT NULL,
  PRIMARY KEY (`id_familiar_persona`),
  KEY `fk_familiar-persona_familiar1_idx` (`familiar_id_familiar`),
  KEY `fk_familiar-persona_persona1_idx` (`persona_id_persona`),
  CONSTRAINT `fk_familiar-persona_familiar1` FOREIGN KEY (`familiar_id_familiar`) REFERENCES `familiar` (`id_familiar`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_familiar-persona_persona1` FOREIGN KEY (`persona_id_persona`) REFERENCES `persona` (`id_persona`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `familiar_persona`
--

LOCK TABLES `familiar_persona` WRITE;
/*!40000 ALTER TABLE `familiar_persona` DISABLE KEYS */;
/*!40000 ALTER TABLE `familiar_persona` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fotos`
--

DROP TABLE IF EXISTS `fotos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fotos` (
  `id_fotos` int(11) NOT NULL AUTO_INCREMENT,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `obligatorio` tinyint(1) NOT NULL DEFAULT 1,
  `fecha_alta` date DEFAULT NULL,
  `fecha_baja` date DEFAULT NULL,
  `descripcion` varchar(45) DEFAULT NULL,
  `guia_id_guia` int(11) NOT NULL,
  PRIMARY KEY (`id_fotos`),
  KEY `fk_fotos_guia1_idx` (`guia_id_guia`),
  CONSTRAINT `fk_fotos_guia1` FOREIGN KEY (`guia_id_guia`) REFERENCES `guia` (`id_guia`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fotos`
--

LOCK TABLES `fotos` WRITE;
/*!40000 ALTER TABLE `fotos` DISABLE KEYS */;
/*!40000 ALTER TABLE `fotos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `guia`
--

DROP TABLE IF EXISTS `guia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `guia` (
  `id_guia` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(450) DEFAULT NULL,
  `subtitulo` varchar(450) DEFAULT NULL,
  `descripcion` varchar(450) DEFAULT NULL,
  `clinica_id_clinica` int(11) NOT NULL,
  PRIMARY KEY (`id_guia`),
  KEY `fk_guia_clinica1_idx` (`clinica_id_clinica`),
  CONSTRAINT `fk_guia_clinica1` FOREIGN KEY (`clinica_id_clinica`) REFERENCES `clinica` (`id_clinica`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `guia`
--

LOCK TABLES `guia` WRITE;
/*!40000 ALTER TABLE `guia` DISABLE KEYS */;
/*!40000 ALTER TABLE `guia` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `localidad`
--

DROP TABLE IF EXISTS `localidad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `localidad` (
  `id_localidad` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_localidad` varchar(450) DEFAULT NULL,
  `provincia_id_provincia` int(11) NOT NULL,
  PRIMARY KEY (`id_localidad`),
  KEY `fk_localidad_provincia1_idx` (`provincia_id_provincia`),
  CONSTRAINT `fk_localidad_provincia1` FOREIGN KEY (`provincia_id_provincia`) REFERENCES `provincia` (`id_provincia`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `localidad`
--

LOCK TABLES `localidad` WRITE;
/*!40000 ALTER TABLE `localidad` DISABLE KEYS */;
/*!40000 ALTER TABLE `localidad` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lugar_trabajo`
--

DROP TABLE IF EXISTS `lugar_trabajo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lugar_trabajo` (
  `id_lugar_trabajo` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_lugar` varchar(500) DEFAULT NULL,
  `direccion_id_direccion` int(11) NOT NULL,
  PRIMARY KEY (`id_lugar_trabajo`),
  KEY `fk_lugar_trabajo_direccion1_idx` (`direccion_id_direccion`),
  CONSTRAINT `fk_lugar_trabajo_direccion1` FOREIGN KEY (`direccion_id_direccion`) REFERENCES `direccion` (`id_direccion`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lugar_trabajo`
--

LOCK TABLES `lugar_trabajo` WRITE;
/*!40000 ALTER TABLE `lugar_trabajo` DISABLE KEYS */;
/*!40000 ALTER TABLE `lugar_trabajo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `metodo_pago`
--

DROP TABLE IF EXISTS `metodo_pago`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `metodo_pago` (
  `id_metodo_pago` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_metodo` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id_metodo_pago`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `metodo_pago`
--

LOCK TABLES `metodo_pago` WRITE;
/*!40000 ALTER TABLE `metodo_pago` DISABLE KEYS */;
/*!40000 ALTER TABLE `metodo_pago` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `metodo_pago_has_turno-pago`
--

DROP TABLE IF EXISTS `metodo_pago_has_turno-pago`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `metodo_pago_has_turno-pago` (
  `metodo_pago_id_metodo_pago` int(11) NOT NULL,
  `turno_pago_id_turno_pago` int(11) NOT NULL,
  PRIMARY KEY (`metodo_pago_id_metodo_pago`,`turno_pago_id_turno_pago`),
  KEY `fk_metodo_pago_has_turno-pago_turno-pago1_idx` (`turno_pago_id_turno_pago`),
  KEY `fk_metodo_pago_has_turno-pago_metodo_pago1_idx` (`metodo_pago_id_metodo_pago`),
  CONSTRAINT `fk_metodo_pago_has_turno-pago_metodo_pago1` FOREIGN KEY (`metodo_pago_id_metodo_pago`) REFERENCES `metodo_pago` (`id_metodo_pago`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_metodo_pago_has_turno-pago_turno-pago1` FOREIGN KEY (`turno_pago_id_turno_pago`) REFERENCES `turno_pago` (`id_turno_pago`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `metodo_pago_has_turno-pago`
--

LOCK TABLES `metodo_pago_has_turno-pago` WRITE;
/*!40000 ALTER TABLE `metodo_pago_has_turno-pago` DISABLE KEYS */;
/*!40000 ALTER TABLE `metodo_pago_has_turno-pago` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mision`
--

DROP TABLE IF EXISTS `mision`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mision` (
  `id_mision` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(450) DEFAULT NULL,
  `titulo` varchar(450) DEFAULT NULL,
  `clinica_id_clinica` int(11) NOT NULL,
  PRIMARY KEY (`id_mision`),
  KEY `fk_mision_clinica1_idx` (`clinica_id_clinica`),
  CONSTRAINT `fk_mision_clinica1` FOREIGN KEY (`clinica_id_clinica`) REFERENCES `clinica` (`id_clinica`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mision`
--

LOCK TABLES `mision` WRITE;
/*!40000 ALTER TABLE `mision` DISABLE KEYS */;
/*!40000 ALTER TABLE `mision` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mision-banner`
--

DROP TABLE IF EXISTS `mision-banner`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mision-banner` (
  `id_mision-banner` int(11) NOT NULL AUTO_INCREMENT,
  `mision_id_mision` int(11) NOT NULL,
  `banner_id_banner` int(11) NOT NULL,
  PRIMARY KEY (`id_mision-banner`),
  KEY `fk_mision-banner_mision1_idx` (`mision_id_mision`),
  KEY `fk_mision-banner_banner1_idx` (`banner_id_banner`),
  CONSTRAINT `fk_mision-banner_banner1` FOREIGN KEY (`banner_id_banner`) REFERENCES `banner` (`id_banner`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_mision-banner_mision1` FOREIGN KEY (`mision_id_mision`) REFERENCES `mision` (`id_mision`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mision-banner`
--

LOCK TABLES `mision-banner` WRITE;
/*!40000 ALTER TABLE `mision-banner` DISABLE KEYS */;
/*!40000 ALTER TABLE `mision-banner` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `modulos`
--

DROP TABLE IF EXISTS `modulos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `modulos` (
  `id_modulos` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id_modulos`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `modulos`
--

LOCK TABLES `modulos` WRITE;
/*!40000 ALTER TABLE `modulos` DISABLE KEYS */;
/*!40000 ALTER TABLE `modulos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `modulos_tablas`
--

DROP TABLE IF EXISTS `modulos_tablas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `modulos_tablas` (
  `id_modulos_tablas` int(11) NOT NULL AUTO_INCREMENT,
  `Tablas_id_tablas` int(11) NOT NULL,
  `modulos_id_modulos` int(11) NOT NULL,
  PRIMARY KEY (`id_modulos_tablas`),
  KEY `fk_modulos_tablas_Tablas1_idx` (`Tablas_id_tablas`),
  KEY `fk_modulos_tablas_modulos1_idx` (`modulos_id_modulos`),
  CONSTRAINT `fk_modulos_tablas_Tablas1` FOREIGN KEY (`Tablas_id_tablas`) REFERENCES `tablas` (`id_tablas`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_modulos_tablas_modulos1` FOREIGN KEY (`modulos_id_modulos`) REFERENCES `modulos` (`id_modulos`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `modulos_tablas`
--

LOCK TABLES `modulos_tablas` WRITE;
/*!40000 ALTER TABLE `modulos_tablas` DISABLE KEYS */;
/*!40000 ALTER TABLE `modulos_tablas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `nosotros`
--

DROP TABLE IF EXISTS `nosotros`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `nosotros` (
  `id_nosotros` int(11) NOT NULL AUTO_INCREMENT,
  `clinica_id_clinica` int(11) NOT NULL,
  `descripcion` varchar(450) DEFAULT NULL,
  PRIMARY KEY (`id_nosotros`),
  KEY `fk_nosotros_clinica1_idx` (`clinica_id_clinica`),
  CONSTRAINT `fk_nosotros_clinica1` FOREIGN KEY (`clinica_id_clinica`) REFERENCES `clinica` (`id_clinica`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `nosotros`
--

LOCK TABLES `nosotros` WRITE;
/*!40000 ALTER TABLE `nosotros` DISABLE KEYS */;
/*!40000 ALTER TABLE `nosotros` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `nosotros-banner`
--

DROP TABLE IF EXISTS `nosotros-banner`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `nosotros-banner` (
  `id_nosotros-banner` int(11) NOT NULL AUTO_INCREMENT,
  `banner_id_banner` int(11) NOT NULL,
  `nosotros_id_nosotros` int(11) NOT NULL,
  PRIMARY KEY (`id_nosotros-banner`),
  KEY `fk_nosotros-banner_banner1_idx` (`banner_id_banner`),
  KEY `fk_nosotros-banner_nosotros1_idx` (`nosotros_id_nosotros`),
  CONSTRAINT `fk_nosotros-banner_banner1` FOREIGN KEY (`banner_id_banner`) REFERENCES `banner` (`id_banner`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_nosotros-banner_nosotros1` FOREIGN KEY (`nosotros_id_nosotros`) REFERENCES `nosotros` (`id_nosotros`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `nosotros-banner`
--

LOCK TABLES `nosotros-banner` WRITE;
/*!40000 ALTER TABLE `nosotros-banner` DISABLE KEYS */;
/*!40000 ALTER TABLE `nosotros-banner` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `noticias`
--

DROP TABLE IF EXISTS `noticias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `noticias` (
  `id_noticias` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(450) DEFAULT NULL,
  `descripcion` varchar(450) DEFAULT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `obligatorio` tinyint(1) NOT NULL DEFAULT 1,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `clinica_id_clinica` int(11) NOT NULL,
  PRIMARY KEY (`id_noticias`),
  KEY `fk_noticias_clinica1_idx` (`clinica_id_clinica`),
  CONSTRAINT `fk_noticias_clinica1` FOREIGN KEY (`clinica_id_clinica`) REFERENCES `clinica` (`id_clinica`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `noticias`
--

LOCK TABLES `noticias` WRITE;
/*!40000 ALTER TABLE `noticias` DISABLE KEYS */;
/*!40000 ALTER TABLE `noticias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `obra-social_turno`
--

DROP TABLE IF EXISTS `obra-social_turno`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `obra-social_turno` (
  `idobra-social_turno` int(11) NOT NULL AUTO_INCREMENT,
  `turnos_id_turnos` int(11) NOT NULL,
  `obra-social_id_obra-social` int(11) NOT NULL,
  PRIMARY KEY (`idobra-social_turno`),
  KEY `fk_obra-social_turno_turnos1_idx` (`turnos_id_turnos`),
  KEY `fk_obra-social_turno_obra-social1_idx` (`obra-social_id_obra-social`),
  CONSTRAINT `fk_obra-social_turno_obra-social1` FOREIGN KEY (`obra-social_id_obra-social`) REFERENCES `obra_social` (`id_obra_social`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_obra-social_turno_turnos1` FOREIGN KEY (`turnos_id_turnos`) REFERENCES `turno` (`id_turnos`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `obra-social_turno`
--

LOCK TABLES `obra-social_turno` WRITE;
/*!40000 ALTER TABLE `obra-social_turno` DISABLE KEYS */;
/*!40000 ALTER TABLE `obra-social_turno` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `obra_social`
--

DROP TABLE IF EXISTS `obra_social`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `obra_social` (
  `id_obra_social` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_obra_social` varchar(500) DEFAULT NULL,
  `detalle` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id_obra_social`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `obra_social`
--

LOCK TABLES `obra_social` WRITE;
/*!40000 ALTER TABLE `obra_social` DISABLE KEYS */;
INSERT INTO `obra_social` VALUES (1,'OSDEN','wswxqa'),(2,'ANSES','eesxqw'),(3,'PreMedic','wqsqwa');
/*!40000 ALTER TABLE `obra_social` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `obra_social_prestacion`
--

DROP TABLE IF EXISTS `obra_social_prestacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `obra_social_prestacion` (
  `obra_social_id_obra-social` int(11) NOT NULL,
  `prestacion_id_prestacion` int(11) NOT NULL,
  `codigo` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`obra_social_id_obra-social`,`prestacion_id_prestacion`),
  KEY `fk_obra-social_has_prestacion_prestacion1_idx` (`prestacion_id_prestacion`),
  KEY `fk_obra-social_has_prestacion_obra-social1_idx` (`obra_social_id_obra-social`),
  CONSTRAINT `fk_obra-social_has_prestacion_obra-social1` FOREIGN KEY (`obra_social_id_obra-social`) REFERENCES `obra_social` (`id_obra_social`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_obra-social_has_prestacion_prestacion1` FOREIGN KEY (`prestacion_id_prestacion`) REFERENCES `prestacion` (`id_prestacion`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `obra_social_prestacion`
--

LOCK TABLES `obra_social_prestacion` WRITE;
/*!40000 ALTER TABLE `obra_social_prestacion` DISABLE KEYS */;
/*!40000 ALTER TABLE `obra_social_prestacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ordenes-consulta`
--

DROP TABLE IF EXISTS `ordenes-consulta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ordenes-consulta` (
  `id_ordenes-medicas` int(11) NOT NULL AUTO_INCREMENT,
  `numero_autorizacion` varchar(450) DEFAULT NULL,
  `estado` tinyint(1) DEFAULT 1,
  `fecha_solicitud` date DEFAULT NULL,
  `fecha_vencimiento` date DEFAULT NULL,
  `fecha_prescripcion` date DEFAULT NULL,
  `cita_id_cita` int(11) NOT NULL,
  PRIMARY KEY (`id_ordenes-medicas`,`cita_id_cita`),
  KEY `fk_ordenes-consulta_cita1_idx` (`cita_id_cita`),
  CONSTRAINT `fk_ordenes-consulta_cita1` FOREIGN KEY (`cita_id_cita`) REFERENCES `cita` (`id_cita`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ordenes-consulta`
--

LOCK TABLES `ordenes-consulta` WRITE;
/*!40000 ALTER TABLE `ordenes-consulta` DISABLE KEYS */;
/*!40000 ALTER TABLE `ordenes-consulta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `paciente`
--

DROP TABLE IF EXISTS `paciente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `paciente` (
  `id_paciente` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id_usuario` int(11) NOT NULL,
  PRIMARY KEY (`id_paciente`),
  KEY `fk_paciente_usuario1_idx` (`usuario_id_usuario`),
  CONSTRAINT `fk_paciente_usuario1` FOREIGN KEY (`usuario_id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `paciente`
--

LOCK TABLES `paciente` WRITE;
/*!40000 ALTER TABLE `paciente` DISABLE KEYS */;
INSERT INTO `paciente` VALUES (1,2),(2,6),(5,10),(6,11),(7,21);
/*!40000 ALTER TABLE `paciente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `paciente_obra_social`
--

DROP TABLE IF EXISTS `paciente_obra_social`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `paciente_obra_social` (
  `id_paciente_obra_social` int(11) NOT NULL AUTO_INCREMENT,
  `paciente_id_paciente` int(11) NOT NULL,
  `obra_social_id_obra_social` int(11) NOT NULL,
  PRIMARY KEY (`id_paciente_obra_social`),
  KEY `fk_paciente-obra_social_paciente2_idx` (`paciente_id_paciente`),
  KEY `fk_paciente-obra_social_obra-social2_idx` (`obra_social_id_obra_social`),
  CONSTRAINT `fk_paciente-obra_social_obra-social2` FOREIGN KEY (`obra_social_id_obra_social`) REFERENCES `obra_social` (`id_obra_social`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_paciente-obra_social_paciente2` FOREIGN KEY (`paciente_id_paciente`) REFERENCES `paciente` (`id_paciente`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `paciente_obra_social`
--

LOCK TABLES `paciente_obra_social` WRITE;
/*!40000 ALTER TABLE `paciente_obra_social` DISABLE KEYS */;
/*!40000 ALTER TABLE `paciente_obra_social` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pais`
--

DROP TABLE IF EXISTS `pais`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pais` (
  `id_pais` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_pais` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id_pais`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pais`
--

LOCK TABLES `pais` WRITE;
/*!40000 ALTER TABLE `pais` DISABLE KEYS */;
/*!40000 ALTER TABLE `pais` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `perfil`
--

DROP TABLE IF EXISTS `perfil`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `perfil` (
  `id_perfil` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_perfil` varchar(500) DEFAULT NULL,
  `descripcion` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id_perfil`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `perfil`
--

LOCK TABLES `perfil` WRITE;
/*!40000 ALTER TABLE `perfil` DISABLE KEYS */;
INSERT INTO `perfil` VALUES (1,'administrador','administra'),(2,'doctor','atiende a los paciente'),(3,'paciente','tomo una consulta');
/*!40000 ALTER TABLE `perfil` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `perfiles_modulos`
--

DROP TABLE IF EXISTS `perfiles_modulos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `perfiles_modulos` (
  `id_perlies_modulos` int(11) NOT NULL AUTO_INCREMENT,
  `modulos_id_modulos` int(11) NOT NULL,
  `perfil_id_perfil` int(11) NOT NULL,
  PRIMARY KEY (`id_perlies_modulos`),
  KEY `fk_perfiles-modulos_modulos1_idx` (`modulos_id_modulos`),
  KEY `fk_perfiles-modulos_perfil1_idx` (`perfil_id_perfil`),
  CONSTRAINT `fk_perfiles-modulos_modulos1` FOREIGN KEY (`modulos_id_modulos`) REFERENCES `modulos` (`id_modulos`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_perfiles-modulos_perfil1` FOREIGN KEY (`perfil_id_perfil`) REFERENCES `perfil` (`id_perfil`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `perfiles_modulos`
--

LOCK TABLES `perfiles_modulos` WRITE;
/*!40000 ALTER TABLE `perfiles_modulos` DISABLE KEYS */;
/*!40000 ALTER TABLE `perfiles_modulos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permiso`
--

DROP TABLE IF EXISTS `permiso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permiso` (
  `id_permiso` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_permiso` varchar(500) DEFAULT NULL,
  `detalle` varchar(500) DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_permiso`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permiso`
--

LOCK TABLES `permiso` WRITE;
/*!40000 ALTER TABLE `permiso` DISABLE KEYS */;
/*!40000 ALTER TABLE `permiso` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permiso_perfiles`
--

DROP TABLE IF EXISTS `permiso_perfiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permiso_perfiles` (
  `id_permiso-perfil` int(11) NOT NULL,
  `descripcion` varchar(500) DEFAULT NULL,
  `permiso_id_permiso` int(11) NOT NULL,
  `perfil_id_perfil` int(11) NOT NULL,
  PRIMARY KEY (`id_permiso-perfil`),
  KEY `fk_permiso-perfiles_permiso1_idx` (`permiso_id_permiso`),
  KEY `fk_permiso-perfiles_perfil1_idx` (`perfil_id_perfil`),
  CONSTRAINT `fk_permiso-perfiles_perfil1` FOREIGN KEY (`perfil_id_perfil`) REFERENCES `perfil` (`id_perfil`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_permiso-perfiles_permiso1` FOREIGN KEY (`permiso_id_permiso`) REFERENCES `permiso` (`id_permiso`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permiso_perfiles`
--

LOCK TABLES `permiso_perfiles` WRITE;
/*!40000 ALTER TABLE `permiso_perfiles` DISABLE KEYS */;
/*!40000 ALTER TABLE `permiso_perfiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `persona`
--

DROP TABLE IF EXISTS `persona`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `persona` (
  `id_persona` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(150) NOT NULL,
  `apellido` varchar(150) NOT NULL,
  `sexo` varchar(50) NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  PRIMARY KEY (`id_persona`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `persona`
--

LOCK TABLES `persona` WRITE;
/*!40000 ALTER TABLE `persona` DISABLE KEYS */;
INSERT INTO `persona` VALUES (1,'Maximiliano','Avalos','1','2002-02-13'),(2,'Maximiliano','Avalos','1','1999-04-23'),(3,'Maximilianos','Avalos','1','1999-03-14'),(4,'Pedro','Ayala','1','2014-04-13'),(5,'dante','montiel','1','1392-03-12'),(6,'oliver','peñasco','1','2013-03-21'),(7,'Maximiliano','Avalos','1','1932-04-03'),(8,'Maximiliano','Avalos','1','2002-02-02'),(9,'Maximiliano','Avalos','1','1999-02-14'),(10,'Maximiliano','Avalos','1','1934-03-22'),(11,'Maximiliano','Avalos','1','1843-02-12'),(12,'Maximiliano','Avalos','1','1321-11-21'),(13,'Maximiliano','Avalos','1','1647-01-29'),(14,'Maximiliano','Avalos','1','2014-04-24'),(15,'Maximiliano','Avalos','1','1234-02-17'),(16,'Maximiliano','Avalos','1','2013-03-03'),(17,'Maximiliano','Avalos','1','2001-01-19'),(18,'Maximiliano','Avalos','1','2017-11-05'),(19,'Maximiliano','Avalos','1','2001-02-12'),(20,'Maximiliano','Avalos','1','2009-04-21'),(21,'Marcelo','Gimenez','1','1888-02-12'),(22,'Maximiliano','Avalos','1','2014-05-23'),(23,'Maximiliano','Avalos','1','2004-04-13'),(24,'Sandra','Polidori','2','1999-02-10');
/*!40000 ALTER TABLE `persona` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `persona_condicion`
--

DROP TABLE IF EXISTS `persona_condicion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `persona_condicion` (
  `id_persona_condicion` int(11) NOT NULL AUTO_INCREMENT,
  `persona_id_persona` int(11) NOT NULL,
  `condicion_id_condicion` int(11) NOT NULL,
  `diagnostico_id_diagnostico` int(11) NOT NULL,
  PRIMARY KEY (`id_persona_condicion`),
  KEY `fk_persona-condicion_persona1_idx` (`persona_id_persona`),
  KEY `fk_persona-condicion_condicion1_idx` (`condicion_id_condicion`),
  KEY `fk_persona-condicion_diagnostico1_idx` (`diagnostico_id_diagnostico`),
  CONSTRAINT `fk_persona-condicion_condicion1` FOREIGN KEY (`condicion_id_condicion`) REFERENCES `condicion` (`id_condicion`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_persona-condicion_diagnostico1` FOREIGN KEY (`diagnostico_id_diagnostico`) REFERENCES `diagnostico` (`id_diagnostico`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_persona-condicion_persona1` FOREIGN KEY (`persona_id_persona`) REFERENCES `persona` (`id_persona`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `persona_condicion`
--

LOCK TABLES `persona_condicion` WRITE;
/*!40000 ALTER TABLE `persona_condicion` DISABLE KEYS */;
/*!40000 ALTER TABLE `persona_condicion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `preguntas`
--

DROP TABLE IF EXISTS `preguntas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `preguntas` (
  `id_preguntas` int(11) NOT NULL AUTO_INCREMENT,
  `titulo_pregunta` varchar(450) DEFAULT NULL,
  `descripcion` varchar(450) DEFAULT NULL,
  `estado` tinyint(1) DEFAULT 1,
  `fecha_alta` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `guia_id_guia` int(11) NOT NULL,
  PRIMARY KEY (`id_preguntas`),
  KEY `fk_preguntas_guia1_idx` (`guia_id_guia`),
  CONSTRAINT `fk_preguntas_guia1` FOREIGN KEY (`guia_id_guia`) REFERENCES `guia` (`id_guia`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `preguntas`
--

LOCK TABLES `preguntas` WRITE;
/*!40000 ALTER TABLE `preguntas` DISABLE KEYS */;
/*!40000 ALTER TABLE `preguntas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `preparacion`
--

DROP TABLE IF EXISTS `preparacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `preparacion` (
  `id_preparacion` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(500) DEFAULT NULL,
  `descripcion` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id_preparacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `preparacion`
--

LOCK TABLES `preparacion` WRITE;
/*!40000 ALTER TABLE `preparacion` DISABLE KEYS */;
/*!40000 ALTER TABLE `preparacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prestacion`
--

DROP TABLE IF EXISTS `prestacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `prestacion` (
  `id_prestacion` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_prestacion` varchar(150) DEFAULT NULL,
  `costo_prestacion` float DEFAULT NULL,
  `codigo_prestacion` varchar(450) DEFAULT NULL,
  PRIMARY KEY (`id_prestacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prestacion`
--

LOCK TABLES `prestacion` WRITE;
/*!40000 ALTER TABLE `prestacion` DISABLE KEYS */;
/*!40000 ALTER TABLE `prestacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prestecion_recibo`
--

DROP TABLE IF EXISTS `prestecion_recibo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `prestecion_recibo` (
  `id_prestacion_recibo` int(11) NOT NULL AUTO_INCREMENT,
  `recibo_id_recibo` int(11) NOT NULL,
  `doctor_prestacion_id_doctor_prestacion` int(11) NOT NULL,
  PRIMARY KEY (`id_prestacion_recibo`),
  KEY `fk_tratamiento-recibo_recibo1_idx` (`recibo_id_recibo`),
  KEY `fk_prestecion_recibo_doctor_prestacion1_idx` (`doctor_prestacion_id_doctor_prestacion`),
  CONSTRAINT `fk_prestecion_recibo_doctor_prestacion1` FOREIGN KEY (`doctor_prestacion_id_doctor_prestacion`) REFERENCES `doctor_prestacion` (`id_doctor_prestacion`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_tratamiento-recibo_recibo1` FOREIGN KEY (`recibo_id_recibo`) REFERENCES `recibo` (`id_recibo`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prestecion_recibo`
--

LOCK TABLES `prestecion_recibo` WRITE;
/*!40000 ALTER TABLE `prestecion_recibo` DISABLE KEYS */;
/*!40000 ALTER TABLE `prestecion_recibo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `provincia`
--

DROP TABLE IF EXISTS `provincia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `provincia` (
  `id_provincia` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_provincia` varchar(500) DEFAULT NULL,
  `pais_id_pais` int(11) NOT NULL,
  PRIMARY KEY (`id_provincia`),
  KEY `fk_provincia_pais1_idx` (`pais_id_pais`),
  CONSTRAINT `fk_provincia_pais1` FOREIGN KEY (`pais_id_pais`) REFERENCES `pais` (`id_pais`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `provincia`
--

LOCK TABLES `provincia` WRITE;
/*!40000 ALTER TABLE `provincia` DISABLE KEYS */;
/*!40000 ALTER TABLE `provincia` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `que_esperar`
--

DROP TABLE IF EXISTS `que_esperar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `que_esperar` (
  `id_que_esperar` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(500) DEFAULT NULL,
  `descripcion` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id_que_esperar`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `que_esperar`
--

LOCK TABLES `que_esperar` WRITE;
/*!40000 ALTER TABLE `que_esperar` DISABLE KEYS */;
/*!40000 ALTER TABLE `que_esperar` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `razon`
--

DROP TABLE IF EXISTS `razon`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `razon` (
  `id_razon` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(500) DEFAULT NULL,
  `descripcion` varchar(500) DEFAULT NULL,
  `analisis_procedimiento_id_analisis_procedimiento` int(11) NOT NULL,
  PRIMARY KEY (`id_razon`),
  KEY `fk_razon_analisis-procedimiento1_idx` (`analisis_procedimiento_id_analisis_procedimiento`),
  CONSTRAINT `fk_razon_analisis-procedimiento1` FOREIGN KEY (`analisis_procedimiento_id_analisis_procedimiento`) REFERENCES `analisis_procedimiento` (`id_analisis_procedimiento`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `razon`
--

LOCK TABLES `razon` WRITE;
/*!40000 ALTER TABLE `razon` DISABLE KEYS */;
/*!40000 ALTER TABLE `razon` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `recibo`
--

DROP TABLE IF EXISTS `recibo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `recibo` (
  `id_recibo` int(11) NOT NULL AUTO_INCREMENT,
  `clinica_id_clinica` int(11) NOT NULL,
  `turno-pago_id_turno-pago` int(11) NOT NULL,
  PRIMARY KEY (`id_recibo`),
  KEY `fk_recibo_clinica1_idx` (`clinica_id_clinica`),
  KEY `fk_recibo_turno-pago1_idx` (`turno-pago_id_turno-pago`),
  CONSTRAINT `fk_recibo_clinica1` FOREIGN KEY (`clinica_id_clinica`) REFERENCES `clinica` (`id_clinica`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_recibo_turno-pago1` FOREIGN KEY (`turno-pago_id_turno-pago`) REFERENCES `turno_pago` (`id_turno_pago`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recibo`
--

LOCK TABLES `recibo` WRITE;
/*!40000 ALTER TABLE `recibo` DISABLE KEYS */;
/*!40000 ALTER TABLE `recibo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `recordatorio`
--

DROP TABLE IF EXISTS `recordatorio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `recordatorio` (
  `id_recordatorio` int(11) NOT NULL AUTO_INCREMENT,
  `fecha_inicio` datetime DEFAULT NULL,
  `fecha_fin` datetime DEFAULT NULL,
  `agenda_turno_id_agenda_turno` int(11) NOT NULL,
  PRIMARY KEY (`id_recordatorio`,`agenda_turno_id_agenda_turno`),
  KEY `fk_recordatorio_agenda_turno1_idx` (`agenda_turno_id_agenda_turno`),
  CONSTRAINT `fk_recordatorio_agenda_turno1` FOREIGN KEY (`agenda_turno_id_agenda_turno`) REFERENCES `agenda_turno` (`id_agenda_turno`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recordatorio`
--

LOCK TABLES `recordatorio` WRITE;
/*!40000 ALTER TABLE `recordatorio` DISABLE KEYS */;
/*!40000 ALTER TABLE `recordatorio` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `riesgos`
--

DROP TABLE IF EXISTS `riesgos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `riesgos` (
  `id_riesgos` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(45) DEFAULT NULL,
  `descripcion` varchar(45) DEFAULT NULL,
  `analisis_procedimiento_id_analisis-procedimiento` int(11) NOT NULL,
  PRIMARY KEY (`id_riesgos`),
  KEY `fk_riesgos_analisis-procedimiento1_idx` (`analisis_procedimiento_id_analisis-procedimiento`),
  CONSTRAINT `fk_riesgos_analisis-procedimiento1` FOREIGN KEY (`analisis_procedimiento_id_analisis-procedimiento`) REFERENCES `analisis_procedimiento` (`id_analisis_procedimiento`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `riesgos`
--

LOCK TABLES `riesgos` WRITE;
/*!40000 ALTER TABLE `riesgos` DISABLE KEYS */;
/*!40000 ALTER TABLE `riesgos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sintomas`
--

DROP TABLE IF EXISTS `sintomas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sintomas` (
  `id_sintomas` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_sintomas` varchar(500) DEFAULT NULL,
  `descripcion` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id_sintomas`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sintomas`
--

LOCK TABLES `sintomas` WRITE;
/*!40000 ALTER TABLE `sintomas` DISABLE KEYS */;
/*!40000 ALTER TABLE `sintomas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tablas`
--

DROP TABLE IF EXISTS `tablas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tablas` (
  `id_tablas` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_tabla` varchar(500) NOT NULL,
  PRIMARY KEY (`id_tablas`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tablas`
--

LOCK TABLES `tablas` WRITE;
/*!40000 ALTER TABLE `tablas` DISABLE KEYS */;
/*!40000 ALTER TABLE `tablas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `turno`
--

DROP TABLE IF EXISTS `turno`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `turno` (
  `id_turnos` int(11) NOT NULL AUTO_INCREMENT,
  `minutos_turnos` int(11) NOT NULL,
  `fecha_hora` datetime NOT NULL,
  `disponible` tinyint(1) NOT NULL DEFAULT 1,
  `agenda_id_agenda` int(11) NOT NULL,
  PRIMARY KEY (`id_turnos`),
  KEY `fk_turno_agenda1_idx` (`agenda_id_agenda`),
  CONSTRAINT `fk_turno_agenda1` FOREIGN KEY (`agenda_id_agenda`) REFERENCES `agenda` (`id_agenda`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `turno`
--

LOCK TABLES `turno` WRITE;
/*!40000 ALTER TABLE `turno` DISABLE KEYS */;
/*!40000 ALTER TABLE `turno` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `turno_pago`
--

DROP TABLE IF EXISTS `turno_pago`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `turno_pago` (
  `id_turno_pago` int(11) NOT NULL AUTO_INCREMENT,
  `estado` int(11) DEFAULT NULL,
  `importe` float DEFAULT NULL,
  `fecha_pago` datetime DEFAULT NULL,
  `turnos_id_turnos` int(11) NOT NULL,
  PRIMARY KEY (`id_turno_pago`),
  KEY `fk_turno-pago_turnos1_idx` (`turnos_id_turnos`),
  CONSTRAINT `fk_turno-pago_turnos1` FOREIGN KEY (`turnos_id_turnos`) REFERENCES `turno` (`id_turnos`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `turno_pago`
--

LOCK TABLES `turno_pago` WRITE;
/*!40000 ALTER TABLE `turno_pago` DISABLE KEYS */;
/*!40000 ALTER TABLE `turno_pago` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_usuario` varchar(500) DEFAULT NULL,
  `email` varchar(500) DEFAULT NULL,
  `password` varchar(500) DEFAULT NULL,
  `estado` tinyint(1) NOT NULL,
  `ultima_conexion` datetime DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_baja` datetime DEFAULT NULL,
  `persona_id_persona` int(11) NOT NULL,
  PRIMARY KEY (`id_usuario`),
  KEY `fk_usuario_persona1_idx` (`persona_id_persona`),
  CONSTRAINT `fk_usuario_persona1` FOREIGN KEY (`persona_id_persona`) REFERENCES `persona` (`id_persona`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` VALUES (1,'maxi','maxiavalos2013@gmail.com','$2y$10$0WAXHoN6E8zpmMsKsFN11.fWiHBUBKNwz9hd5wdxZsEx.WwT/Wg1G',0,NULL,NULL,NULL,1),(2,'bri','bri013@gmail.com','$2y$10$2kKcKA4n3NiTyeTKoBUnqepipEFgGxH58d9078JLOTPIXDd6/Rzfi',0,NULL,NULL,NULL,2),(3,'maxxxi','maxxxiavalos2013@gmail.com','$2y$10$Z2ZGsaCF8BlMihAkJD6GFODltVumsjwVGRQdCe9dWxewn5gHur7nK',0,NULL,NULL,NULL,3),(4,'pedro','pedro2013@gmail.com','$2y$10$6VwZAS4K0LSsJov8/s3xTu1ktVR.PRCLT2cxsFbntoCo66sKcKUCm',0,NULL,NULL,NULL,4),(5,'dan','dan2013@gmail.com','$2y$10$oZm01U64b9hwLsb82b3L2ORLEcUbHMETYdCWwdNtbMtS9Ra02DEjK',0,NULL,NULL,NULL,5),(6,'oliver','oliver2013@gmail.com','$2y$10$MB2duK.rEEljUD9/UgpdcOM8tCYb7iZDf8jI695c25ladeSy6jRp6',0,NULL,NULL,NULL,6),(7,'pancho','pancho2013@gmail.com','$2y$10$l5t2MnWM6d7Rg0Wx1jhIv.unkoCBrRXeic0EZT979qe03ReaHXfOK',0,NULL,NULL,NULL,7),(8,'pencho','pencho2013@gmail.com','$2y$10$L6xo7VEFAgutmy0lAbYmSOh/3mFaMPbUCt54HSs5mZ9cjpN2mRjqa',0,NULL,NULL,NULL,8),(9,'gille','gille2013@gmail.com','$2y$10$xC67W1Z43fSrxJim5UESdOvCAN/NFFCUNhjDQj9Uog8xfieDDweEG',0,NULL,NULL,NULL,9),(10,'sandra','sandra2013@gmail.com','$2y$10$HLgbIkxZsa8IDNngGvAmHuKRGyvWQuGLpqM1KtXMSCpFxc7WrfHH.',0,NULL,NULL,NULL,10),(11,'jose','jose2013@gmail.com','$2y$10$R7rugzUG53KuTQXnLe0goOAilCQlfEI9jv7Ekj4nVaotM6ItYoSP.',0,NULL,NULL,NULL,11),(12,'marcelo','marcelo2013@gmail.com','$2y$10$gSPMQHdYflHQORA7Ek4fFOgMX.MEfdhU1gsOGDuB1XonXPf8FHzXa',0,NULL,NULL,NULL,12),(13,'jara','jara2013@gmail.com','$2y$10$q3fPnpd0ZswLzavf./FmT.zMpRUW2ADtzS9LVKAPI6Q9eA8kxrNku',0,NULL,NULL,NULL,13),(14,'cuatro','cuatro2013@gmail.com','$2y$10$pyeZ.MsztSAW1zXR4Q23mOqnwmGGyAq6OLubok1BI/If6qc6A3a/u',0,NULL,NULL,NULL,14),(15,'guero','guero2013@gmail.com','$2y$10$gIXjEVQQXyjppPF1TtKTv.1cK3z5mcz5VMJ4KDf437CoeDZoqYvQC',0,NULL,NULL,NULL,15),(16,'alam','alam2013@gmail.com','$2y$10$t8nkkk6JgA6nru2xVP9Zs.kozGUHPpm/kWz6cgWj7D4VffmAVdDxK',0,NULL,NULL,NULL,16),(17,'sanchos','sanchos2013@gmail.com','$2y$10$WlK1yRP3pPOARDI1m3PEvuxDLStmQhvhNWHf9nNbbw0Bxe6UgOy/K',0,NULL,NULL,NULL,17),(18,'guillermo','guillermo2013@gmail.com','$2y$10$4MoApgKFLB8o0SR7lilhb.mutnboGPOuisWCjjvqz.0Ps824YTVnC',0,NULL,NULL,NULL,18),(19,'julian','julian2013@gmail.com','$2y$10$wu3/ZC/fofStRphXUDuqM.CdqiTXyQ0ZF50VYzf7gaFvwD/h3fnOS',0,NULL,NULL,NULL,19),(20,'josefo','josefo2013@gmail.com','$2y$10$oAqRxtjbznxVAt74YDmISuL5RnYyZ/0lA4ooQXFANEyKxd9okK8dO',0,NULL,NULL,NULL,20),(21,'yolet','yolet2013@gmail.com','$2y$10$LW/sm64QgYITBwsluxVBMeSX4Hwp6YmQGP/KEp1mgUQ2G.U6x8TFq',0,NULL,NULL,NULL,21),(22,'juampii','juampii2013@gmail.com','$2y$10$jgi.zVXTfluUw.8CNTGJp.FYGrpmsUmNW0RmSgy6cUer.o/K/0n36',0,NULL,NULL,NULL,22),(23,'alfredo','alfredo2013@gmail.com','$2y$10$ZruxntlYaqSKSLSBi2hF7OytVw6uQxDNVJH/FeWVAKNWxSRQWxZha',0,NULL,NULL,NULL,23),(24,'sandas','sandas2013@gmail.com','$2y$10$imI/gxIk9vwdPad1QL/hdOtXEWazTy.aGacGjgtMEsp94POL4AYe6',0,NULL,NULL,NULL,24);
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario_has_perfil`
--

DROP TABLE IF EXISTS `usuario_has_perfil`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuario_has_perfil` (
  `usuario_id_usuario` int(11) NOT NULL,
  `perfil_id_perfil` int(11) NOT NULL,
  PRIMARY KEY (`usuario_id_usuario`,`perfil_id_perfil`),
  KEY `fk_usuario_has_perfil_perfil1_idx` (`perfil_id_perfil`),
  KEY `fk_usuario_has_perfil_usuario1_idx` (`usuario_id_usuario`),
  CONSTRAINT `fk_usuario_has_perfil_perfil1` FOREIGN KEY (`perfil_id_perfil`) REFERENCES `perfil` (`id_perfil`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_usuario_has_perfil_usuario1` FOREIGN KEY (`usuario_id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario_has_perfil`
--

LOCK TABLES `usuario_has_perfil` WRITE;
/*!40000 ALTER TABLE `usuario_has_perfil` DISABLE KEYS */;
INSERT INTO `usuario_has_perfil` VALUES (2,1),(3,2),(4,2),(5,2),(6,3),(7,3),(8,3),(9,2),(10,3),(11,3),(12,1),(13,1),(14,1),(15,1),(16,3),(17,1),(18,1),(19,1),(20,1),(21,1),(22,1),(23,1),(24,3);
/*!40000 ALTER TABLE `usuario_has_perfil` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-11-22 22:01:44
