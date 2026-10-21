/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-11.7.2-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: sgc
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `chamado`
--

DROP TABLE IF EXISTS `chamado`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `chamado` (
  `id_chamado` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `solucao` varchar(500) DEFAULT NULL,
  `data_prazo` datetime DEFAULT NULL,
  `prioridade` varchar(20) NOT NULL,
  `status` varchar(30) NOT NULL,
  `data_abertura` datetime NOT NULL,
  `descricao` varchar(500) DEFAULT NULL,
  `titulo` varchar(150) NOT NULL,
  `numero_chamado` int(11) NOT NULL,
  PRIMARY KEY (`id_chamado`),
  UNIQUE KEY `uq_chamado_numero` (`numero_chamado`),
  KEY `idx_chamado_usuario_status` (`id_usuario`,`status`),
  CONSTRAINT `fk_chamado_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chamado`
--

LOCK TABLES `chamado` WRITE;
/*!40000 ALTER TABLE `chamado` DISABLE KEYS */;
INSERT INTO `chamado` VALUES
(1,1,'Verificar o cabo HDMI',NULL,'Média','Resolvido','2026-10-21 18:52:44','Monitor não está funcionando','Teste de chamado',1),
(2,1,'Trocar a lampada',NULL,'Média','Resolvido','2026-10-21 18:58:46','A luz não está mais acendendo mesmo estando recebendo energia','Luz não acende',2),
(3,5,'Colocar uma nova bombona de água',NULL,'Alta','Resolvido','2026-10-21 19:02:35','Não tem mais água no bebedouro','Acabou a água',3);
/*!40000 ALTER TABLE `chamado` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `historico`
--

DROP TABLE IF EXISTS `historico`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `historico` (
  `id_historico` int(11) NOT NULL AUTO_INCREMENT,
  `id_chamado` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `status_anterior` varchar(30) DEFAULT NULL,
  `acao` varchar(255) NOT NULL,
  `status_novo` varchar(30) DEFAULT NULL,
  `observacao` varchar(500) DEFAULT NULL,
  `data_hora` datetime NOT NULL,
  PRIMARY KEY (`id_historico`),
  KEY `fk_historico_usuario` (`id_usuario`),
  KEY `idx_historico_chamado` (`id_chamado`),
  CONSTRAINT `fk_historico_chamado` FOREIGN KEY (`id_chamado`) REFERENCES `chamado` (`id_chamado`),
  CONSTRAINT `fk_historico_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `historico`
--

LOCK TABLES `historico` WRITE;
/*!40000 ALTER TABLE `historico` DISABLE KEYS */;
INSERT INTO `historico` VALUES
(1,1,1,NULL,'Chamado aberto','Aberto',NULL,'2026-10-21 18:52:44'),
(2,1,3,'Aberto','Solução proposta','Resolvido','Verificar o cabo HDMI','2026-10-21 18:53:48'),
(3,1,2,'Resolvido','Status alterado','Em atendimento',NULL,'2026-10-21 18:55:00'),
(4,1,2,'Em atendimento','Status alterado','Resolvido',NULL,'2026-10-21 18:55:05'),
(5,2,1,NULL,'Chamado aberto','Aberto',NULL,'2026-10-21 18:58:46'),
(6,2,3,'Aberto','Solução proposta','Resolvido','Trocar a lampada','2026-10-21 18:59:54'),
(7,3,5,NULL,'Chamado aberto','Aberto',NULL,'2026-10-21 19:02:35'),
(8,3,3,'Aberto','Solução proposta','Resolvido','Colocar uma nova bombona de água','2026-10-21 19:03:38');
/*!40000 ALTER TABLE `historico` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(150) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `perfil` varchar(50) NOT NULL,
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `uq_usuario_email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` VALUES
(1,'gabrielsol@gmail.com','Gabriel','$2y$10$ADB1xF4lWZ1qTIjSGb9hqOGh10sOu/Q8KYs0Z7RSRko7XCnBCoOSu','Solicitante'),
(2,'gabrieladm@gmail.com','Gabriel','$2y$10$//exDGABhJ6dJA9WFsbuXOdlkC8bAVoXZaBgeo3VKu4ugxDeqdiq6','Administrador'),
(3,'gabrielatn@gmail.com','Gabriel','$2y$10$cnKW9.bu0weZhxd8crMJ6ua1vOqbD43V3yRiQusuurRDceLbjm6ei','Atendente'),
(4,'ismaeladm@gmail.com','Ismael','$2y$10$0Fc2i/qPf609CRUqjwZ3zuyEftyfIpFUGtBWE88HEd89msQBL7xc.','Administrador'),
(5,'solicitante@gmail.com','Soliciante','$2y$10$anqUI5mXK.ZWh4qNIbVp4eIPQ3IkYeBpu3Ow3pgzGpOU.MZ2SCFzC','Solicitante');
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'sgc'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2026-10-21 19:07:06
