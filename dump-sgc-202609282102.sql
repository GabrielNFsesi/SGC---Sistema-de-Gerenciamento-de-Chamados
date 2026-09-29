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
  KEY `fk_chamado_usuario` (`id_usuario`),
  CONSTRAINT `fk_chamado_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chamado`
--

LOCK TABLES `chamado` WRITE;
/*!40000 ALTER TABLE `chamado` DISABLE KEYS */;
INSERT INTO `chamado` VALUES
(4,1,NULL,NULL,'Crítica','Em atendimento','2026-09-23 20:08:09','fjdyytjkftu6tku6rtiytkjhgkiyuit7urelyrl,ytlmukltmhklmtkgmktlngmtklhmtkmhklmlhltm','teste',1),
(7,1,NULL,NULL,'Média','Em atendimento','2026-09-23 20:16:10','ççlkjhmngbn','tttttttt',2),
(10,1,NULL,NULL,'Baixa','Em atendimento','2026-09-23 20:17:17','z,mfnvm,fnvbjfdkv m,fvjbfjd vmk','wertyu',3),
(13,5,NULL,NULL,'Alta','Aberto','2026-09-23 21:33:20','gfjhrehtyjkumytk','teste do Teste',4),
(14,5,NULL,NULL,'Média','Em atendimento','2026-09-23 21:33:45','sdflkjhgfdsdfghjk','teste',5),
(15,7,'solução',NULL,'Alta','Resolvido','2026-09-28 19:03:35','tgtrtnytn','teste',6),
(16,7,'fvnfdvndkogdmbodjj fkgjduij gkdjgbdn jgkmdriugnjrkdmgiudjnkmrdíognm',NULL,'Alta','Resolvido','2026-09-28 19:57:38','dejghty,hjyu jyktyjyu tityj56uhytjtyhyktyj78','qwertyui',7),
(17,13,'asdfghjkl',NULL,'Média','Aberto','2026-09-28 20:23:46','teste usuario','teste',8);
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
  KEY `fk_historico_chamado` (`id_chamado`),
  KEY `fk_historico_usuario` (`id_usuario`),
  CONSTRAINT `fk_historico_chamado` FOREIGN KEY (`id_chamado`) REFERENCES `chamado` (`id_chamado`),
  CONSTRAINT `fk_historico_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `historico`
--

LOCK TABLES `historico` WRITE;
/*!40000 ALTER TABLE `historico` DISABLE KEYS */;
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
  PRIMARY KEY (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` VALUES
(1,'gabriel@teste.com','Gabriel','123','Solicitante'),
(2,'solicitante@senai.com','João Solicitante','123','Solicitante'),
(3,'atendente@senai.com','Maria Atendente','123','Atendente'),
(4,'admin@senai.com','Carlos Admin','123','Administrador'),
(5,'teste@gmail.com','Teste do Teste','123','Solicitante'),
(6,'gabriel@senai.com','Gabriel','123','Administrador'),
(7,'gabriel@gmail.com','Gabriel','123','Solicitante'),
(8,'teste_a@gmail.com','teste aten','123','Solicitante'),
(9,'teste_s@gmail.com','teste s','123','Solicitante'),
(10,'ismael@gmail.com','Ismael','123','Administrador'),
(11,'etr@gmail.com','etr','123','Atendente'),
(12,'atendente@gmail.com','atendente','123','Solicitante'),
(13,'usuario@gmail.com','usuario','123','Solicitante'),
(14,'atend@gmail.com','Atend','123','Atendente'),
(15,'admin@gmail.com','admin','123','Solicitante'),
(16,'adm@gmail.com','adm','123','Administrador');
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

-- Dump completed on 2026-09-28 21:02:16
